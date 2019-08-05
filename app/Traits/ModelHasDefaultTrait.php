<?php

namespace App\Traits;

trait ModelHasDefaultTrait
{
    /**
     * @return int
     */
    public static function clearDefaultValues()
    {
        return self::where('default', true)->update(['default' => false]);
    }

    public static function getDefaultValues()
    {
        return self::where('default', true)->get();
    }

    public static function getDefaultValue()
    {
        return self::where('default', true)->first();
    }
}
