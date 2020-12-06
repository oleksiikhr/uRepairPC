<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Perm;
use Illuminate\Support\Str;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use App\Models\Concerns\HasPermissions;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends BaseModel implements
    AuthorizableContract,
    AuthenticatableContract,
    CanResetPasswordContract,
    JWTSubject
{
    use Authenticatable, Authorizable, HasFactory, CanResetPassword, MustVerifyEmail, SoftDeletes, HasPermissions;

    /**
     * @var int
     */
    private const RANDOM_PASSWORD_LEN = 10;

    /**
     * @var array
     */
    public const ALLOW_COLUMNS_SEARCH = [
        'id',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'phone',
        'updated_at',
        'created_at',
    ];

    /**
     * @var array
     */
    public const ALLOW_COLUMNS_SORT = [
        'id',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'phone',
        'updated_at',
        'created_at',
    ];

    /**
     * @var string
     */
    protected string $guard_name = 'api';

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'phone',
        'description',
    ];

    /**
     * {@inheritdoc}
     */
    protected $hidden = [
        'password',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @var string|null
     */
    protected ?string $plainPassword;

    /**
     * {@inheritdoc}
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        $user = auth()->user();

        if ($user && $this->id !== $user->id && ! $user->perm(Perm::ROLES_VIEW_ALL)) {
            $this->makeHidden('roles');
            $this->makeHidden('permissions');
        }

        return parent::toArray();
    }

    /**
     * @return string|null
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param  string  $password
     * @return void
     */
    public function setPlainPassword(string $password): void
    {
        $this->plainPassword = $password;
    }

    /**
     * @return string
     */
    public static function generateRandomStrPassword(): string
    {
        return Str::random(self::RANDOM_PASSWORD_LEN);
    }

    /**
     * @return HasMany
     */
    public function request(): HasMany
    {
        return $this->hasMany(Request::class);
    }

    /**
     * @return HasMany
     */
    public function requestComments(): HasMany
    {
        return $this->hasMany(RequestComment::class);
    }

    /**
     * @return HasOne
     */
    public function image(): HasOne
    {
        return $this->hasOne(File::class);
    }

    /**
     * @param  Builder  $query
     * @param  array  $permissions
     * @return Builder
     */
    public function scopeFilterRoles(Builder $query, array $permissions): Builder
    {
        return $query->whereHas('roles.permissions', static function (Builder $query) use ($permissions) {
            $query->whereIn('name', $permissions);
        });
    }
}
