<?php declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class EquipmentModelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @param  Request  $request
     * @return array
     */
    public function rules(Request $request): array
    {
        $rules = [
            'name' => 'string|between:1,255',
            'description' => 'nullable|string|max:600',
            'type_id' => 'integer|exists:equipment_types,id,deleted_at,NULL',
            'manufacturer_id' => 'integer|exists:equipment_manufacturers,id,deleted_at,NULL',
        ];

        if ($request->method === Request::METHOD_POST) {
            $rules['name'] = 'required|'.$rules['name'];
            $rules['type_id'] = 'required|'.$rules['type_id'];
            $rules['manufacturer_id'] = 'required|'.$rules['manufacturer_id'];
        }

        return $rules;
    }
}
