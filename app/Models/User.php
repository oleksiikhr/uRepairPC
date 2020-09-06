<?php declare(strict_types=1);

namespace App\Models;

use App\Enums\Perm;
use Illuminate\Support\Str;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Models\Concerns\HasPermissions;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends BaseModel implements AuthorizableContract, AuthenticatableContract, CanResetPasswordContract,
    JWTSubject
{
    use Authenticatable, Authorizable, CanResetPassword, MustVerifyEmail, SoftDeletes, HasPermissions;

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
     * @inheritDoc
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
     * @inheritDoc
     */
    protected $hidden = [
        'password',
    ];

    /**
     * @inheritDoc
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @inheritDoc
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * @inheritDoc
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    /**
     * @inheritDoc
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
}
