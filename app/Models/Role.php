<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Perm;
use App\Models\Concerns\HasDefaultColumn;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends BaseModel
{
    use HasDefaultColumn;

    /**
     * @var array
     */
    public const PERMISSION_TAGS = ['permission'];

    /**
     * @var array
     */
    public const ALLOW_COLUMNS_SEARCH = [
        'id',
        'name',
        'default',
        'updated_at',
        'created_at',
    ];

    /**
     * @var array
     */
    public const ALLOW_COLUMNS_SORT = [
        'id',
        'name',
        'default',
        'updated_at',
        'created_at',
    ];

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'name',
        'color',
        'default',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'default' => 'boolean',
    ];

    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        $permissionsList = (object) [];
        $permissionsActive = [];

        if (! empty($this->permissions)) {
            $permissions = $this->permissions->pluck('name')->toArray();
            $structure = Perm::getStructure();

            foreach ($structure as $key => $arr) {
                $temp = [];
                foreach ($arr as $permission => $action) {
                    $active = in_array($permission, $permissions, true);

                    $temp[] = (object) [
                        'name' => $permission,
                        'action' => $action,
                        'active' => $active,
                    ];

                    if ($active) {
                        $permissionsActive[] = $permission;
                    }
                }
                $permissionsList->{$key} = $temp;
            }
        }

        $this->setAttribute('permissions_list', $permissionsList);
        $this->setAttribute('permissions_active', $permissionsActive);
        $this->makeHidden('permissions');

        return parent::toArray();
    }

    /**
     * @param  array  $changePermissionNames
     * @return void
     */
    public function syncPermissions(array $changePermissionNames): void
    {
        $permissions = $this->permissions()->get();
        $deleteIds = [];

        // Filter
        foreach ($permissions as $permission) {
            // TODO Optimization
            $searchKey = array_search($permission->name, $changePermissionNames, true);
            if ($searchKey === false) {
                $deleteIds[] = $permission->id;
            } else {
                unset($changePermissionNames[$searchKey]);
            }
        }

        // Delete
        RolePermission::destroy($deleteIds);

        // Insert
        $insertValues = collect($changePermissionNames)->map(static function ($permissionName) {
            return ['name' => $permissionName];
        });
        $this->permissions()->createMany($insertValues->toArray());
    }

    /**
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * @return HasMany
     */
    public function permissions(): HasMany
    {
        return $this->hasMany(RolePermission::class);
    }
}
