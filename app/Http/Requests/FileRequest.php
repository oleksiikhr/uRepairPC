<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class FileRequest extends FormRequest
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
        switch ($request->method) {
            case Request::METHOD_POST:
                return [
                    'files' => 'required|array',
                    'files.*' => 'file|max:20000',
                ];
            default:
                return [
                    'name' => 'required|string',
                ];
        }
    }
}
