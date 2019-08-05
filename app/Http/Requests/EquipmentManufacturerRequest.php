<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class EquipmentManufacturerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @param  Request  $request
     * @return array
     */
    public function rules(Request $request)
    {
        $rules = [
            'name' => 'string|between:1,191|unique:equipment_manufacturers,name',
            'description' => 'nullable|string|max:600',
        ];

        if ($request->manufacturer) {
            $rules['name'] = $rules['name'].','.$request->manufacturer;
        }

        if ($request->method === Request::METHOD_POST) {
            $rules['name'] = 'required|'.$rules['name'];
        }

        return $rules;
    }
}
