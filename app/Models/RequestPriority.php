<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasDefaultColumn;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RequestPriority extends BaseModel
{
    use HasDefaultColumn, SoftDeletes;

    /**
     * @inheritDoc
     */
    protected $fillable = [
        'name',
        'value',
        'color',
        'description',
        'default',
    ];

    /**
     * @inheritDoc
     */
    protected $casts = [
        'default' => 'boolean',
    ];

    /**
     * @return HasMany
     */
    public function requests(): HasMany
    {
        return $this->hasMany(Request::class);
    }
}
