<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ManifestRequest extends FormRequest
{
    /**
     * Defines the default orientation for all the website's top level browsing contexts.
     * @see https://developer.mozilla.org/en-US/docs/Web/Manifest#orientation
     */
    private const ORIENTATION = [
        'any', 'natural', 'landscape', 'landscape-primary', 'landscape-secondary', 'portrait', 'portrait-primary',
        'portrait-secondary',
    ];

    /**
     * Defines the developers’ preferred display mode for the website.
     * @see https://developer.mozilla.org/en-US/docs/Web/Manifest#display
     */
    private const DISPLAY = [
        'fullscreen', 'standalone', 'minimal-ui', 'browser',
    ];

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
            'name' => 'required|string',
            'short_name' => 'required|string',
            'orientation' => 'required|string|in:'.implode(',', self::ORIENTATION),
            'display' => 'required|string|in:'.implode(',', self::DISPLAY),
            'background_color' => 'required|string|regex:/^#([a-zA-Z0-9]{6})$/i',
            'theme_color' => 'required|string|regex:/^#([a-zA-Z0-9]{6})$/i',
            'upload_icons' => 'array',
            'upload_icons.*' => 'required|file|mimes:jpeg,jpg,png|max:1000',
            'remove_icons' => 'array',
            'remove_icons.*' => 'required|string', // url's
        ];
    }
}
