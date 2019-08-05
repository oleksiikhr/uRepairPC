<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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

        // List of all users
        if ($request->route()->getName() === 'users.index') {
            return [
                'search' => 'string',
                'columns' => 'array',
                'columns.*' => 'string|in:'.implode(',', User::ALLOW_COLUMNS_SEARCH),
                'sortColumn' => 'string|in:'.implode(',', User::ALLOW_COLUMNS_SORT),
                'sortOrder' => 'string|in:ascending,descending',
                'request_access' => 'boolean',
            ];
        }

        if ($method === Request::METHOD_DELETE) {
            return [];
        }

        $rules = [
            'first_name' => 'string|between:1,191',
            'middle_name' => 'nullable|string|max:191',
            'last_name' => 'string|between:1,191',
            'phone' => 'nullable|string|max:191',
            'description' => 'nullable|string|max:600',
        ];

        // Store
        if ($method === Request::METHOD_POST) {
            $rules['email'] = 'required|email|unique:users,email';
            $rules['first_name'] = 'required|'.$rules['first_name'];
            $rules['last_name'] = 'required|'.$rules['last_name'];
        }

        return $rules;
    }
}
