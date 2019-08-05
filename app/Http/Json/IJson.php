<?php

namespace App\Http\Json;

interface IJson
{
    /**
     * Get data from file or uses default.
     *
     * @return object
     */
    public function getData();

    /**
     * Get default data.
     *
     * @return array
     */
    public function getDefaultData();

    /**
     * Get attribute and type.
     *
     * @return mixed
     * @example
     *  ['attr' => 'string']
     */
    public function getAttributes();

    /**
     * Save data to file.
     *
     * @param  array  $arr
     * @return object
     */
    public function mergeAndSaveToFile($arr);
}
