<?php declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
     * @return Builder
     */
    public static function querySelectJoins(): Builder
    {
        return self::query()->select(
            'equipment_models.*',
            'equipment_types.name as type_name',
            'equipment_manufacturers.name as manufacturer_name'
        )
            ->join('equipment_types', 'equipment_models.type_id', '=', 'equipment_types.id')
            ->join('equipment_manufacturers', 'equipment_models.manufacturer_id', '=', 'equipment_manufacturers.id');
    }

    public function equipments(): HasMany
    {
        return $this->hasMany(Equipment::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(EquipmentType::class);
    }

    public function manufacturer(): BelongsTo
    {
        return $this->belongsTo(EquipmentManufacturer::class);
    }
}
