<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class RequestPriorityRequest extends FormRequest
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
            'name' => 'string|between:1,255|unique:request_priorities,name',
            'value' => 'integer|max:100',
            'color' => 'nullable|string|regex:/^#([a-zA-Z0-9]{6})$/i',
            'description' => 'nullable|string|max:600',
            'default' => 'boolean',
        ];

        if ($request->priority) {
            $rules['name'] = $rules['name'].','.$request->priority;
        }

        if ($request->method === Request::METHOD_POST) {
            $rules['name'] = 'required|'.$rules['name'];
        }

        return $rules;
    }
}
