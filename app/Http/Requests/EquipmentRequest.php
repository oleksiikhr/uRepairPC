<?php

namespace App\Http\Requests;

use App\Equipment;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class EquipmentRequest extends FormRequest
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
        $method = $request->method;

        // List of all equipments
        if ($request->route()->getName() === 'equipments.index') {
            return [
                'search' => 'string',
                'columns' => 'array',
                'columns.*' => 'string|in:'.implode(',', Equipment::ALLOW_COLUMNS_SEARCH),
                'sortColumn' => 'string|in:'.implode(',', Equipment::ALLOW_COLUMNS_SORT),
                'sortOrder' => 'string|in:ascending,descending',
            ];
        }

        if ($method === Request::METHOD_DELETE) {
            return [
                'files_delete' => 'boolean',
            ];
        }

        $rules = [
            'serial_number' => 'nullable|string|between:1,191',
            'inventory_number' => 'nullable|string|max:600',
            'type_id' => 'integer|exists:equipment_types,id,deleted_at,NULL',
            'manufacturer_id' => 'nullable|integer|exists:equipment_manufacturers,id,deleted_at,NULL',
            'model_id' => 'nullable|integer|exists:equipment_models,id,deleted_at,NULL',
            'description' => 'nullable|string|max:600',
        ];

        // Store
        if ($method === Request::METHOD_POST) {
            $rules['type_id'] = 'required|'.$rules['type_id'];
        }

        return $rules;
    }
}
