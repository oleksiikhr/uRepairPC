<?php

declare(strict_types=1);

namespace App\Models;

use App\Http\Helpers\FileHelper;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class File extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function boot(): void
    {
        parent::boot();

        self::deleting(static function ($file) {
            FileHelper::delete($file->path, $file->disk);
        });
    }

    /**
     * @return BelongsToMany
     */
    public function equipments(): BelongsToMany
    {
        return $this->belongsToMany(Equipment::class);
    }

    /**
     * @return BelongsToMany
     */
    public function requests(): BelongsToMany
    {
        return $this->belongsToMany(Request::class);
    }

    /**
     * @return BelongsTo
     */
    public function userImage(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
