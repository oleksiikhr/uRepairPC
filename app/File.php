<?php

namespace App;

use App\Http\Helpers\FileHelper;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    public static function boot()
    {
        parent::boot();
        self::deleting(function ($file) {
            FileHelper::delete($file->path, $file->disk);
        });
    }

    /* | -----------------------------------------------------------------------------------
     * | Relationships
     * | -----------------------------------------------------------------------------------
     */

    public function equipments()
    {
        return $this->belongsToMany(Equipment::class);
    }

    public function requests()
    {
        return $this->belongsToMany(Request::class);
    }

    public function userImage()
    {
        return $this->belongsTo(User::class);
    }
}
