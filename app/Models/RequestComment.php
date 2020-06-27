<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Request as RequestModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequestComment extends BaseModel
{
    use SoftDeletes;

    /**
     * @inheritDoc
     */
    protected $fillable = [
        'message',
    ];

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
    public function request(): BelongsTo
    {
        return $this->belongsTo(RequestModel::class);
    }
}
