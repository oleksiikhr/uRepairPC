<?php

namespace App\Traits;

use App\Role;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

trait ModelHasPermissionsTrait
{
    /**
     * @var int seconds
     * @default 3 days
     */
    protected $permissionCacheTime = 60 * 60 * 24 * 3;

    /**
     * @var string
     */
    protected $permissionCacheKey = 'permissions';

    /**
     * @var Collection
     */
    private $_permissionNames;

    /**
     * @param  {string|array}  $name
     * @return array
     */
    public function assignRolesByName($names): array
    {
        $names = is_array($names) ? $names : [$names];

        $query = Role::query();
        foreach ($names as $name) {
            $query->orWhere('name', $name);
        }
        $roles = $query->get();

        Cache::forget($this->getCacheKey());

        return $this->roles()->sync($roles->pluck('id')->toArray());
    }

    /**
     * @param  {string|array|Collection}  $ids
     * @return array
     */
    public function assignRolesById($ids): array
    {
        if ($ids instanceof Collection) {
            $ids = $ids->toArray();
        } elseif (! is_array($ids)) {
            $ids = [$ids];
        }

        Cache::forget($this->getCacheKey());

        return $this->roles()->sync($ids);
    }

    /**
     * Return all the permissions the model has via roles.
     *
     * @return Collection
     */
    public function getAllPerm(): Collection
    {
        return Cache::remember($this->getCacheKey(), $this->permissionCacheTime, function () {
            $this->loadMissing(['roles', 'roles.permissions']);

            return $this->roles->flatMap(function ($role) {
                return $role->permissions;
            })->sort()->values();
        });
    }

    /**
     * Get names of permissions.
     * @return array
     */
    public function getAllPermNames(): array
    {
        if (! $this->_permissionNames) {
            $this->_permissionNames = $this->getAllPerm()
                ->pluck('name')
                ->toArray();
        }

        return $this->_permissionNames;
    }

    /**
     * @param  {array|string}  $permissions
     * @return bool
     */
    public function perm($permissions): bool
    {
        $permissions = is_array($permissions) ? $permissions : [$permissions];
        $userPermissions = $this->getAllPermNames();

        foreach ($permissions as $permission) {
            // Check boolean
            if (in_array($permission, ['1', 1, true], true)) {
                return true;
            }

            if (in_array($permission, $userPermissions)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get key for Cache permissions.
     * @return string
     */
    private function getCacheKey(): string
    {
        return $this->permissionCacheKey.'.'.$this->getTable().'.'.$this->id;
    }
}
