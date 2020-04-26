<?php declare(strict_types=1);

namespace App;

use App\Traits\ModelHasDefaultTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RequestPriority extends Model
{
    use ModelHasDefaultTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'value',
        'color',
        'description',
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

    public function requests(): HasMany
    {
        return $this->hasMany(Request::class);
    }
}
