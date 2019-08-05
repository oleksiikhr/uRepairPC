<?php

namespace App\Http\Controllers;

use App\File;
use App\Role;
use App\User;
use App\Enums\Perm;
use App\Mail\EmailChange;
use App\Mail\UserCreated;
use App\Events\Users\EJoin;
use Illuminate\Http\Request;
use App\Events\Users\EUpdate;
use App\Http\Helpers\FileHelper;
use App\Events\Users\EUpdateRoles;
use App\Http\Requests\UserRequest;
use App\Http\Requests\ImageRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    private const FOLDER_AVATARS = 'users/avatars';

    /**
     * @var User
     */
    private $_user;

    /**
     * Add middleware depends on user permissions.
     *
     * @param  Request  $request
     * @return array
     */
    public function permissions(Request $request): array
    {
        $this->_user = auth()->user();

        if (! $this->_user) {
            $this->middleware('jwt.auth');

            return [];
        }

        $requestId = (int) $request->route('user');
        $isOwnProfile = $requestId === $this->_user->id;
        $editPermissionProfile = $isOwnProfile
            ? [Perm::PROFILE_EDIT, Perm::USERS_EDIT_ALL]
            : Perm::USERS_EDIT_ALL;

        return [
            // CRUD
            'index' => Perm::USERS_VIEW_ALL,
            'show' => [$isOwnProfile, Perm::USERS_VIEW_ALL],
            'update' => $editPermissionProfile,
            'store' => Perm::USERS_CREATE,
            'delete' => $requestId === 1 || $isOwnProfile ? Perm::DISABLE : Perm::USERS_DELETE_ALL,

            // Image
            'showImage' => $isOwnProfile ? true : Perm::USERS_VIEW_ALL,
            'updateImage' => $editPermissionProfile,
            'deleteImage' => $editPermissionProfile,

            // Other
            'updateEmail' => $editPermissionProfile,
            'updatePassword' => $editPermissionProfile,
            'updateRoles' => $requestId === 1 ? Perm::DISABLE : Perm::ROLES_EDIT_ALL,
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @param  UserRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(UserRequest $request)
    {
        $query = User::query();

        if ($this->_user->perm(Perm::ROLES_VIEW_ALL)) {
            $query->with('roles');
        }

        // Search
        if ($request->has('search') && $request->has('columns') && ! empty($request->columns)) {
            foreach ($request->columns as $column) {
                $query->orWhere($column, 'LIKE', '%'.$request->search.'%');
            }
        }

        // Order
        if ($request->has('sortColumn')) {
            $query->orderBy($request->sortColumn, $request->sortOrder === 'descending' ? 'desc' : 'asc');
        }

        // Filter
        if ($request->request_access && $this->_user->perm(Perm::REQUESTS_EDIT_ALL)) {
            $query->whereHas('roles.permissions', function ($query) {
                $query->where('name', Perm::REQUESTS_EDIT_ALL);
                $query->orWhere('name', Perm::REQUESTS_EDIT_ASSIGN);
            });
        }

        $list = $query->paginate(self::PAGINATE_DEFAULT);
        event(new EJoin(...$list->items()));

        return response()->json($list);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  UserRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(UserRequest $request)
    {
        $password = User::generateRandomStrPassword();

        $user = new User;
        $user->fill($request->all());
        $user->email = $request->email;
        $user->password = bcrypt($password);

        if (! $user->save()) {
            return $this->responseDatabaseSaveError();
        }

        $user->assignRolesById(Role::getDefaultValues()->pluck('id'));

        // TODO Disable on APP_DEMO
        Mail::to($user)->send(new UserCreated($password));

        return response()->json([
            'message' => __('app.users.store'),
            'user' => $user,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        $user = User::with('roles')->findOrFail($id);
        $user->permissions = $user->getAllPermNames();

        event(new EJoin($user));

        return response()->json([
            'message' => __('app.users.show'),
            'user' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UserRequest  $request
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UserRequest $request, int $id)
    {
        $user = User::findOrFail($id);
        $user->fill($request->all());

        if (! $user->save()) {
            return $this->responseDatabaseSaveError();
        }

        return response()->json([
            'message' => __('app.users.update'),
            'user' => $user,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  UserRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(UserRequest $request, int $id)
    {
        $user = User::findOrFail($id);

        if (! $user->delete()) {
            return $this->responseDatabaseDestroyError();
        }

        return response()->json([
            'message' => __('app.users.destroy'),
        ]);
    }

    /**
     * Get avatar from user.
     *
     * @param  int  $id - user id
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function showImage(int $id)
    {
        $user = User::with('image')->findOrFail($id);

        if (! $user->image_id) {
            return response(null);
        }

        if (! Storage::exists($user->image->path)) {
            return response(null);
        }

        $file = Storage::path($user->image->path);

        return response()->file($file);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateRoles(Request $request, int $id)
    {
        $request->validate([
            'roles' => 'array',
        ]);

        $user = User::findOrFail($id);
        $user->assignRolesById($request->roles);
        $user->permissions = $user->getAllPermNames();

        event(new EUpdateRoles($id, [
            'roles' => $user->roles,
            'permissions' => $user->permissions,
            'updated_at' => $user->updated_at->toDateTimeString(),
        ]));

        return response()->json([
            'message' => __('app.users.roles_changed'),
            'user' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateEmail(Request $request, int $id)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
        ]);

        $user = User::findOrFail($id);
        Mail::to($user)->send(new EmailChange($request->email)); // TODO Disable on APP_DEMO
        $user->email = $request->email;

        if (! $user->save()) {
            return $this->responseDatabaseSaveError();
        }

        return response()->json([
            'message' => __('app.users.email_changed'),
            'user' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage. If the user edit the same, need password
     * Another - send email to the user with new random password.
     *
     * @param   Request  $request
     * @param   int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePassword(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        // We can change only for own profile.
        if ($this->_user->id === $id) {
            return $this->setPasswordProfile($request, $user);
        }

        return $this->setPasswordEmail($user);
    }

    /**
     * Upload avatar for user.
     *
     * @param  ImageRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function updateImage(ImageRequest $request, int $id)
    {
        $user = User::with('image')->findOrFail($id);

        // Delete old image if exists
        if ($user->image_id && ! File::destroy($user->image_id)) {
            return $this->responseDatabaseDestroyError();
        }

        // Upload new file
        $fileHelper = new FileHelper($request->file('image'));
        $file = $fileHelper->fill();
        $file->path = $fileHelper->store(self::FOLDER_AVATARS);

        if (! $file->path) {
            return response()->json(['message' => __('app.files.file_not_saved')], 422);
        }

        if (! $user->image()->save($file)) {
            return $this->responseDatabaseSaveError();
        }

        $user->image_id = $file->id;

        if (! $user->save()) {
            File::destroy($file->id);

            return $this->responseDatabaseSaveError();
        }

        return response()->json([
            'message' => __('app.files.file_saved'),
            'user' => $user,
        ]);
    }

    /**
     * Delete avatar for user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyImage(int $id)
    {
        $user = User::with('image')->findOrFail($id);

        if (! $user->image_id) {
            return response()->json(['message' => __('app.files.file_not_found')], 422);
        }

        if (! File::destroy($user->image_id)) {
            return response()->json(['message' => __('app.files.file_not_deleted')], 422);
        }

        $user->image_id = null;

        // image_id destroy by onDelete('set null') on DB, so send the event manually
        event(new EUpdate($id, [
            'image_id' => null,
            'updated_at' => $user->updated_at->toDateTimeString(),
        ]));

        return response()->json([
            'message' => __('app.files.file_destroyed'),
            'user' => $user,
        ]);
    }

    /**
     * Generate a new random password and send to email.
     *
     * @param  User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    private function setPasswordEmail(User $user)
    {
        $password = User::generateRandomStrPassword();

        // Change password for another users - send generate random password to email.
        $user->password = bcrypt($password);

        if (! $user->save()) {
            return $this->responseDatabaseSaveError();
        }

        Mail::to($user)->send(new UserCreated($password)); // TODO Disable on APP_DEMO

        return response()->json([
            'message' => __('app.users.password_email_changed'),
        ]);
    }

    /**
     * Set a new password to profile of the current user.
     *
     * @param  Request  $request
     * @param  User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    private function setPasswordProfile(Request $request, User $user)
    {
        $request->validate(['password' => 'required|string']);
        $user->password = bcrypt($request->password);

        if (! $user->save()) {
            return $this->responseDatabaseSaveError();
        }

        return response()->json([
            'message' => __('app.users.password_changed'),
        ]);
    }
}
