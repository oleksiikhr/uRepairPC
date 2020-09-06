<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\Perm;
use App\Models\User;
use App\Models\File;
use App\Models\Role;
use App\Mail\EmailChange;
use App\Mail\UserCreated;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Realtime\Users\EJoin;
use App\Realtime\Users\EUpdate;
use App\Http\Helpers\FileHelper;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\UserRequest;
use App\Http\Requests\ImageRequest;
use Illuminate\Support\Facades\Mail;
use App\Realtime\Users\EUpdateRoles;
use Illuminate\Support\Facades\Storage;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class UserController extends Controller
{
    private const FOLDER_AVATARS = 'users/avatars';

    /**
     * @inheritDoc
     */
    public function permissions(): array
    {
        return [
            // CRUD
            'index' => Perm::USERS_VIEW_ALL,
            'show' => Perm::USERS_VIEW_ALL,
            'update' => [Perm::PROFILE_EDIT, Perm::USERS_EDIT_ALL],
            'store' => Perm::USERS_CREATE,
            'delete' => Perm::USERS_DELETE_ALL,

            // Image
            'showImage' => Perm::USERS_VIEW_ALL,
            'updateImage' => [Perm::PROFILE_EDIT, Perm::USERS_EDIT_ALL],
            'deleteImage' => [Perm::PROFILE_EDIT, Perm::USERS_EDIT_ALL],

            // Other
            'updateEmail' => [Perm::PROFILE_EDIT, Perm::USERS_EDIT_ALL],
            'updatePassword' => [Perm::PROFILE_EDIT, Perm::USERS_EDIT_ALL],
            'updateRoles' => Perm::ROLES_EDIT_ALL,
        ];
    }

    /**
     * Display a listing of the resource
     *
     * @param  UserRequest  $request
     * @return JsonResponse
     */
    public function index(UserRequest $request): JsonResponse
    {
        $query = User::query();

        if (perm(Perm::ROLES_VIEW_ALL)) {
            $query->with('roles');
        }

        // Search
        if ($request->has('search') && $request->exists('columns')) {
            foreach ($request->columns as $column) {
                $query->orWhere($column, 'LIKE', $request->search.'%');
            }
        }

        // Order
        if ($request->has('sortColumn')) {
            $query->orderBy($request->sortColumn, $request->sortOrder === 'descending' ? 'desc' : 'asc');
        }

        // Filter
        if ($request->has('request_access') && perm(Perm::REQUESTS_EDIT_ALL)) {
            $query->whereHas('roles.permissions', static function ($query) {
                $query->where('name', Perm::REQUESTS_EDIT_ALL);
                $query->orWhere('name', Perm::REQUESTS_EDIT_ASSIGN);
            });
        }

        $list = $query->paginate();
        EJoin::dispatchAfterResponse(...$list->items());

        return response()->json($list);
    }

    /**
     * Store a newly created resource in storage
     *
     * @param  UserRequest  $request
     * @return JsonResponse
     */
    public function store(UserRequest $request): JsonResponse
    {
        $user = User::create($request->validated());

        $ids = Role::getDefaultValues()->pluck('id');
        $user->assignRolesById($ids);

        return response()->json([
            'message' => __('app.users.store'),
            'user' => $user,
        ]);
    }

    /**
     * Display the specified resource
     *
     * @param  User  $user
     * @return JsonResponse
     */
    public function show(User $user): JsonResponse
    {
        $user->load('roles');
        $user->permissions = $user->getAllPermNames();

        EJoin::dispatchAfterResponse($user);

        return response()->json([
            'message' => __('app.users.show'),
            'user' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage
     *
     * @param  UserRequest  $request
     * @param  User  $user
     * @return JsonResponse
     */
    public function update(UserRequest $request, User $user): JsonResponse
    {
        $user->fill($request->validated());
        $user->save();

        return response()->json([
            'message' => __('app.users.update'),
            'user' => $user,
        ]);
    }

    /**
     * Remove the specified resource from storage
     *
     * @param  User  $user
     * @return JsonResponse
     * @throws AuthorizationException
     * @throws \Exception
     */
    public function destroy(User $user): JsonResponse
    {
        $this->authorize('delete', $user);

        $user->delete();

        return response()->json([
            'message' => __('app.users.destroy'),
        ]);
    }

    /**
     * Get avatar for specified user
     *
     * @param  User  $user
     * @return Response|BinaryFileResponse
     */
    public function showImage(User $user)
    {
        if (! $user->image_id) {
            return response(null);
        }

        $user->load('image');

        if (! Storage::exists($user->image->path)) {
            return response(null);
        }

        $file = Storage::path($user->image->path);

        return response()->file($file);
    }

    /**
     * Update the specified resource in storage
     *
     * @param  Request  $request
     * @param  User  $user
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function updateRoles(Request $request, User $user): JsonResponse
    {
        $this->authorize('updateRoles', $user);

        // TODO Request class
        $request->validate([
            'roles' => 'array',
        ]);

        $user->assignRolesById($request->roles);
        $user->permissions = $user->getAllPermNames();

        EUpdateRoles::dispatchAfterResponse($user->id, [
            'roles' => $user->roles,
            'permissions' => $user->permissions,
            'updated_at' => $user->updated_at->toDateTimeString(),
        ]);

        return response()->json([
            'message' => __('app.users.roles_changed'),
            'user' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage
     *
     * @param  Request  $request
     * @param  User  $user
     * @return JsonResponse
     */
    public function updateEmail(Request $request, User $user): JsonResponse
    {
        // TODO Request class
        $request->validate([
            'email' => 'required|email|unique:users,email',
        ]);

        Mail::to($user)->send(new EmailChange($request->email));
        $user->email = $request->email;
        $user->save();

        return response()->json([
            'message' => __('app.users.email_changed'),
            'user' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage. If the user edit the same, need password
     * Another - send email to the user with new random password
     *
     * @param   Request  $request
     * @param   User  $user
     * @return JsonResponse
     */
    public function updatePassword(Request $request, User $user): JsonResponse
    {
        // We can change only for own profile
        if (auth()->id() === $user->id) {
            return $this->setPasswordProfile($request, $user);
        }

        return $this->setPasswordEmail($user);
    }

    /**
     * Upload avatar for user
     *
     * @param  ImageRequest  $request
     * @param  User  $user
     * @return JsonResponse
     * @throws \Exception
     */
    public function updateImage(ImageRequest $request, User $user): JsonResponse
    {
        // Delete old image if exists
        if ($user->image_id) {
            File::destroy($user->image_id);
        }

        // Upload new file
        $fileHelper = new FileHelper($request->file('image'));
        $file = $fileHelper->fill();
        $file->path = $fileHelper->store(self::FOLDER_AVATARS);

        if (! $file->path) {
            return response()->json(['message' => __('app.files.file_not_saved')], 422);
        }

        $user->image()->save($file);
        $user->image_id = $file->id;
        $user->save();

        return response()->json([
            'message' => __('app.files.file_saved'),
            'user' => $user,
        ]);
    }

    /**
     * Delete avatar for user
     *
     * @param  User  $user
     * @return JsonResponse
     */
    public function destroyImage(User $user): JsonResponse
    {
        if (! $user->image_id) {
            return response()->json(['message' => __('app.files.file_not_found')], 422);
        }

        File::destroy($user->image_id);

        $user->image_id = null;

        // image_id destroy by onDelete('set null') on DB, so send the event manually
        EUpdate::dispatchAfterResponse($user->id, [
            'image_id' => null,
            'updated_at' => $user->updated_at->toDateTimeString(),
        ]);

        return response()->json([
            'message' => __('app.files.file_destroyed'),
            'user' => $user,
        ]);
    }

    /**
     * Generate a new random password and send to email
     *
     * @param  User  $user
     * @return JsonResponse
     */
    protected function setPasswordEmail(User $user): JsonResponse
    {
        $password = User::generateRandomStrPassword();

        // Change password for another users - send generate random password to email
        $user->password = bcrypt($password);
        $user->save();

        Mail::to($user)->send(new UserCreated($password));

        return response()->json([
            'message' => __('app.users.password_email_changed'),
        ]);
    }

    /**
     * Set a new password to profile of the current user
     *
     * @param  Request  $request
     * @param  User  $user
     * @return JsonResponse
     */
    protected function setPasswordProfile(Request $request, User $user): JsonResponse
    {
        $request->validate(['password' => 'required|string']);
        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json([
            'message' => __('app.users.password_changed'),
        ]);
    }
}
