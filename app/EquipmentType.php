<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EquipmentType extends Model
{
    use SoftDeletes;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /* | ---------------------------------------------------------------
     * | Relationships
     * | ---------------------------------------------------------------
     */

    public function models()
    {
        return $this->hasMany(EquipmentModel::class);
    }

    public function equipments()
    {
        return $this->hasMany(Equipment::class);
    }
}
