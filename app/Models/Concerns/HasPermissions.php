<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Models\Role;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasPermissions
{
    /**
     * @var int seconds
     */
    protected int $permissionCacheTime = 60 * 60 * 24 * 3; // 3 days

    /**
     * @var string
     */
    protected string $permissionCacheKey = 'permissions';

    /**
     * @param  array|string  $names
     * @return array
     */
    public function assignRolesByName($names): array
    {
        $query = Role::query();

        foreach (Arr::wrap($names) as $name) {
            $query->orWhere('name', $name);
        }

        $roles = $query->get();

        Cache::forget($this->getCacheKey());

        return $this->roles()->sync($roles->pluck('id')->toArray());
    }

    /**
     * @param  Collection|array|string  $ids
     * @return array
     */
    public function assignRolesById($ids): array
    {
        if ($ids instanceof Collection) {
            $ids = $ids->toArray();
        }

        Cache::forget($this->getCacheKey());

        return $this->roles()->sync(Arr::wrap($ids));
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

            return $this->roles
                ->flatMap(static fn (Role $role) => $role->permissions)
                ->sort()
                ->values();
        });
    }

    /**
     * Get names of permissions.
     *
     * @return array
     */
    public function getAllPermNames(): array
    {
        return $this->getAllPerm()
            ->pluck('name')
            ->toArray();
    }

    /**
     * @param  array|string  $permissions
     * @return bool
     */
    public function perm($permissions): bool
    {
        $userPermissions = $this->getAllPermNames();

        foreach (Arr::wrap($permissions) as $permission) {
            // Check boolean
            if (in_array($permission, ['1', 1, true], true)) {
                return true;
            }

            if (in_array($permission, $userPermissions, true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Get key for Cache permissions.
     *
     * @return string
     */
    protected function getCacheKey(): string
    {
        return $this->permissionCacheKey.'.'.$this->getTable().'.'.$this->id;
    }
}
