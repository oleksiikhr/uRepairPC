<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EquipmentModel extends Model
{
    use SoftDeletes;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type_id',
        'manufacturer_id',
        'name',
        'description',
    ];

    /**
     * @return mixed
     */
    public static function querySelectJoins()
    {
        return self::select(
            'equipment_models.*',
            'equipment_types.name as type_name',
            'equipment_manufacturers.name as manufacturer_name'
        )
            ->join('equipment_types', 'equipment_models.type_id', '=', 'equipment_types.id')
            ->join('equipment_manufacturers', 'equipment_models.manufacturer_id', '=', 'equipment_manufacturers.id');
    }

    /* | -----------------------------------------------------------------------------------
     * | Relationships
     * | -----------------------------------------------------------------------------------
     */

    public function equipments()
    {
        return $this->hasMany(Equipment::class);
    }

    public function type()
    {
        return $this->belongsTo(EquipmentType::class);
    }

    public function manufacturer()
    {
        return $this->belongsTo(EquipmentManufacturer::class);
    }
}
