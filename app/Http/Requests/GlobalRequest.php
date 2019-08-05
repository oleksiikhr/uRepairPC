<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GlobalRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'meta_title' => 'nullable|string',
            'app_name' => 'nullable|string',
            'logo_auth' => 'nullable|file|mimes:jpeg,jpg,png|max:2000',
            'logo_header' => 'nullable|file|mimes:jpeg,jpg,png|max:2000',
            'favicon' => 'nullable|file|mimes:ico,png|max:200',
            'name_and_logo' => 'boolean',
        ];
    }
}
