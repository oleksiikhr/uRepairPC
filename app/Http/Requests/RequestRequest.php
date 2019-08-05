<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use App\Request as RequestModel;
use Illuminate\Foundation\Http\FormRequest;

class RequestRequest extends FormRequest
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
        if ($request->route()->getName() === 'requests.index') {
            return [
                'search' => 'string',
                'columns' => 'array',
                'columns.*' => 'string|in:'.implode(',', RequestModel::ALLOW_COLUMNS_SEARCH),
                'sortColumn' => 'string|in:'.implode(',', RequestModel::ALLOW_COLUMNS_SORT),
                'sortOrder' => 'string|in:ascending,descending',
                'priority_id' => 'integer|min:1',
                'status_id' => 'integer|min:1',
                'type_id' => 'integer|min:1',
            ];
        }

        if ($method === Request::METHOD_DELETE) {
            return [
                'files_delete' => 'boolean',
            ];
        }

        $rules = [
            'title' => 'string|between:1,191',
            'location' => 'nullable|string|between:1,191',
            'description' => 'nullable|string|max:1200',
            'assign_id' => 'nullable|integer|exists:users,id,deleted_at,NULL',
            'type_id' => 'integer|exists:request_types,id,deleted_at,NULL',
            'priority_id' => 'integer|exists:request_priorities,id,deleted_at,NULL',
            'status_id' => 'integer|exists:request_statuses,id,deleted_at,NULL',
            'equipment_id' => 'nullable|integer|exists:equipments,id,deleted_at,NULL',
        ];

        // Store
        if ($method === Request::METHOD_POST) {
            $rules['title'] = 'required|'.$rules['title'];
        }

        return $rules;
    }
}
