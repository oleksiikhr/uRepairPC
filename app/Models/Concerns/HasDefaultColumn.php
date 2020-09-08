<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Collection;

trait HasDefaultColumn
{
    /**
     * @return int
     */
    public static function clearDefaultValues(): int
    {
        return self::query()->where('default', true)->update(['default' => false]);
    }

    /**
     * @return Collection
     */
    public static function getDefaultValues(): Collection
    {
        return self::query()->where('default', true)->get();
    }

    /**
     * @return self
     */
    public static function getDefaultValue(): self
    {
        return self::query()->where('default', true)->first();
    }
}
