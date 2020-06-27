<?php declare(strict_types=1);

namespace App\Models;

use App\Enums\Perm;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Request extends BaseModel
{
    use SoftDeletes;

    /**
     * @var array
     */
    public const ALLOW_COLUMNS_SEARCH = [
        'id',
        'user_name',
        'assign_name',
        'equipment_serial_number',
        'equipment_inventory_number',
        'equipment_name',
        'title',
        'location',
        'updated_at',
        'created_at',
    ];

    /**
     * @var array
     */
    public const ALLOW_COLUMNS_SORT = [
        'id',
        'title',
        'location',
        'priority_name',
        'updated_at',
        'created_at',
    ];

    /**
     * Correctly display ORM request
     *
     * @var array
     */
    public const SEARCH_RELATIONSHIP = [
        'user_name' => 'users.last_name',
        'assign_name' => 'users_assign.last_name',
        'equipment_serial_number' => 'equipments.serial_number',
        'equipment_inventory_number' => 'equipments.inventory_number',
        'equipment_name' => 'equipment_models.name',
    ];

    /**
     * Correctly display ORM request
     *
     * @var array
     */
    public const SORT_RELATIONSHIP = [
        'priority_name' => 'request_priorities.value',
    ];

    /**
     * @inheritDoc
     */
    protected $fillable = [
        'title',
        'location',
        'description',
    ];

    /**
     * @return Builder
     */
    public static function querySelectJoins(): Builder
    {
        return self::query()->select(
            'requests.*',
            DB::raw("CONCAT(users.last_name,' ',users.first_name) AS user_name"),
            DB::raw("CONCAT(users_assign.last_name,' ',users_assign.first_name) AS assign_name"),
            'users.last_name as user_last_name',
            'users.first_name as user_first_name',
            'users_assign.last_name as assign_last_name',
            'users_assign.first_name as assign_first_name',
            'request_types.name as type_name',
            'request_priorities.name as priority_name',
            'request_priorities.value as priority_value',
            'request_priorities.color as priority_color',
            'request_statuses.name as status_name',
            'request_statuses.color as status_color',
            'equipments.serial_number as equipment_serial_number',
            'equipments.inventory_number as equipment_inventory_number',
            'equipments.model_id as equipment_model_id',
            'equipment_models.name as equipment_name'
        )
            ->leftJoin('users', 'requests.user_id', '=', 'users.id')
            ->leftJoin('users as users_assign', 'requests.assign_id', '=', 'users_assign.id')
            ->leftJoin('request_types', 'requests.type_id', '=', 'request_types.id')
            ->leftJoin('request_priorities', 'requests.priority_id', '=', 'request_priorities.id')
            ->leftJoin('request_statuses', 'requests.status_id', '=', 'request_statuses.id')
            ->leftJoin('equipments', 'requests.equipment_id', '=', 'equipments.id')
            ->leftJoin('equipment_models', 'equipments.model_id', '=', 'equipment_models.id');
    }

    /**
     * @param  Builder  $query
     * @param  User  $user
     * @return Builder
     */
    public function scopeFilterByUser(Builder $query, User $user): Builder
    {
        if ($user->perm(Perm::REQUESTS_VIEW_ALL)) {
            return $query;
        }

        if (! $user->perm(Perm::REQUESTS_VIEW_OWN)) {
            $query->where('requests.assign_id', $user->id);
        } elseif (! $user->perm(Perm::REQUESTS_VIEW_ASSIGN)) {
            $query->where('requests.user_id', $user->id);
        } else {
            $query->where(static function (Builder $query) use ($user) {
                $query->where('requests.user_id', $user->id);
                $query->orWhere('requests.assign_id', $user->id);
            });
        }

        return $query;
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    /**
     * @return BelongsTo
     */
    public function priority(): BelongsTo
    {
        return $this->belongsTo(RequestPriority::class);
    }

    /**
     * @return BelongsTo
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(RequestStatus::class);
    }

    /**
     * @return BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(RequestType::class);
    }

    /**
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(RequestComment::class)
            ->select(
                'request_comments.*',
                'users.first_name',
                'users.last_name'
            )
            ->leftJoin('users', 'users.id', '=', 'request_comments.user_id');
    }

    /**
     * @return BelongsToMany
     */
    public function files(): BelongsToMany
    {
        return $this->belongsToMany(File::class, 'request_file')
            ->select(
                'files.*',
                'users.first_name',
                'users.last_name'
            )
            ->leftJoin('users', 'users.id', '=', 'files.user_id')
            ->orderByDesc('id');
    }
}
