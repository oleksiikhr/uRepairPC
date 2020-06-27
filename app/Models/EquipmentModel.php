<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EquipmentModel extends BaseModel
{
    use SoftDeletes;

    /**
     * @inheritDoc
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

    /**
     * @return HasMany
     */
    public function equipments(): HasMany
    {
        return $this->hasMany(Equipment::class);
    }

    /**
     * @return BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(EquipmentType::class);
    }

    /**
     * @return BelongsTo
     */
    public function manufacturer(): BelongsTo
    {
        return $this->belongsTo(EquipmentManufacturer::class);
    }
}
