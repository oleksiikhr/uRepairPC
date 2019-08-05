<?php

namespace App\Http\Requests;

use App\Role;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
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
        // List of all users
        if ($request->route()->getName() === 'roles.index') {
            return [
                'search' => 'string',
                'columns' => 'array',
                'columns.*' => 'string|in:'.implode(',', Role::ALLOW_COLUMNS_SEARCH),
                'sortColumn' => 'string|in:'.implode(',', Role::ALLOW_COLUMNS_SORT),
                'sortOrder' => 'string|in:ascending,descending',
                'permissions' => 'boolean',
                'count' => 'int',
            ];
        }

        $rules = [
            'name' => 'string|between:1,191',
            'color' => 'nullable|string|regex:/^#([a-zA-Z0-9]{6})$/i',
            'default' => 'boolean',
        ];

        // Store
        if ($request->method === Request::METHOD_POST) {
            $rules['name'] = 'required|'.$rules['name'].'|unique:roles,name';
        }

        return $rules;
    }
}
