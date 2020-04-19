<?php declare(strict_types=1);

namespace App;

use App\Enums\Perm;
use App\Traits\ModelHasDefaultTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use ModelHasDefaultTrait;

    /** @var array */
    const ALLOW_COLUMNS_SEARCH = [
        'id',
        'name',
        'default',
        'updated_at',
        'created_at',
    ];

    /** @var array */
    const ALLOW_COLUMNS_SORT = [
        'id',
        'name',
        'default',
        'updated_at',
        'created_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'color',
        'default',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'default' => 'boolean',
    ];

    /**
     * Convert the model instance to an array.
     *
     * @return array
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
                    $active = in_array($permission, $permissions);

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
            $searchKey = array_search($permission->name, $changePermissionNames);
            if ($searchKey === false) {
                $deleteIds[] = $permission->id;
            } else {
                unset($changePermissionNames[$searchKey]);
            }
        }

        // Delete
        RolePermission::destroy($deleteIds);

        // Insert
        $insertValues = collect($changePermissionNames)->map(function ($permissionName) {
            return ['name' => $permissionName];
        });
        $this->permissions()->createMany($insertValues->toArray());
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function permissions(): HasMany
    {
        return $this->hasMany(RolePermission::class);
    }
}
