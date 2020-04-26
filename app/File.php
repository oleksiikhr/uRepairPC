<?php declare(strict_types=1);

namespace App;

use App\Http\Helpers\FileHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class File extends Model
{
    public static function boot()
    {
        parent::boot();

        self::deleting(function ($file) {
            FileHelper::delete($file->path, $file->disk);
        });
    }

    public function equipments(): BelongsToMany
    {
        return $this->belongsToMany(Equipment::class);
    }

    public function requests(): BelongsToMany
    {
        return $this->belongsToMany(Request::class);
    }

    public function userImage(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
