<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Equipment extends BaseModel
{
    use SoftDeletes;

    /**
     * @var array
     */
    public const ALLOW_COLUMNS_SEARCH = [
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
    public const ALLOW_COLUMNS_SORT = [
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
    public const SEARCH_RELATIONSHIP = [
        'manufacturer_name' => 'equipment_manufacturers.name',
        'model_name' => 'equipment_models.name',
        'type_name' => 'equipment_types.name',
    ];

    /**
     * {@inheritdoc}
     */
    protected $table = 'equipments';

    /**
     * {@inheritdoc}
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
    public static function querySelectJoins(): Builder
    {
        return self::query()->select(
            'equipments.*',
            'equipment_types.name as type_name',
            'equipment_manufacturers.name as manufacturer_name',
            'equipment_models.name as model_name'
        )
            ->leftJoin('equipment_types', 'equipments.type_id', '=', 'equipment_types.id')
            ->leftJoin('equipment_manufacturers', 'equipments.manufacturer_id', '=', 'equipment_manufacturers.id')
            ->leftJoin('equipment_models', 'equipments.model_id', '=', 'equipment_models.id');
    }

    /**
     * @return BelongsTo
     */
    public function manufacturer(): BelongsTo
    {
        return $this->belongsTo(EquipmentManufacturer::class);
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
    public function model(): BelongsTo
    {
        return $this->belongsTo(EquipmentModel::class);
    }

    /**
     * @return HasMany
     */
    public function requests(): HasMany
    {
        return $this->hasMany(Request::class);
    }

    /**
     * @return BelongsToMany
     */
    public function files(): BelongsToMany
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
