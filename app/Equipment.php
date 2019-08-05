<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipment extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    const ALLOW_COLUMNS_SEARCH = [
        'id',
        'serial_number',
        'inventory_number',
        'type_name',
        'manufacturer_name',
        'model_name',
        'updated_at',
        'created_at',
    ];

    /**
     * @var array
     */
    const ALLOW_COLUMNS_SORT = [
        'id',
        'serial_number',
        'inventory_number',
        'type_name',
        'manufacturer_name',
        'model_name',
        'updated_at',
        'created_at',
    ];

    /**
     * Correctly display ORM request.
     *
     * @var array
     */
    const SEARCH_RELATIONSHIP = [
        'manufacturer_name' => 'equipment_manufacturers.name',
        'model_name' => 'equipment_models.name',
        'type_name' => 'equipment_types.name',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'equipments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'serial_number',
        'inventory_number',
        'type_id',
        'manufacturer_id',
        'model_id',
        'description',
    ];

    /**
     * @return Builder
     */
    public static function querySelectJoins()
    {
        return self::select(
            'equipments.*',
            'equipment_types.name as type_name',
            'equipment_manufacturers.name as manufacturer_name',
            'equipment_models.name as model_name'
        )
            ->leftJoin('equipment_types', 'equipments.type_id', '=', 'equipment_types.id')
            ->leftJoin('equipment_manufacturers', 'equipments.manufacturer_id', '=', 'equipment_manufacturers.id')
            ->leftJoin('equipment_models', 'equipments.model_id', '=', 'equipment_models.id');
    }

    /* | -----------------------------------------------------------------------------------
     * | Relationships
     * | -----------------------------------------------------------------------------------
     */

    public function manufacturer()
    {
        return $this->belongsTo(EquipmentManufacturer::class);
    }

    public function type()
    {
        return $this->belongsTo(EquipmentType::class);
    }

    public function model()
    {
        return $this->belongsTo(EquipmentModel::class);
    }

    public function requests()
    {
        return $this->hasMany(Request::class);
    }

    public function files()
    {
        return $this->belongsToMany(File::class, 'equipment_file')
            ->select(
                'files.*',
                'users.first_name',
                'users.last_name'
            )
            ->leftJoin('users', 'users.id', '=', 'files.user_id')
            ->orderByDesc('id');
    }
}
