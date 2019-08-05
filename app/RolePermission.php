<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'role_id',
    ];

    /* | -----------------------------------------------------------------------------------
     * | Relationships
     * | -----------------------------------------------------------------------------------
     */

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
