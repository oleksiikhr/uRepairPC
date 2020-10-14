<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Job;
use Illuminate\Foundation\Http\FormRequest;

class JobRequest extends FormRequest
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
     * @return array
     */
    public function rules(): array
    {
        return [
            'search' => 'string',
            'columns' => 'array',
            'columns.*' => 'string|in:'.implode(',', Job::ALLOW_COLUMNS_SEARCH),
            'sortColumn' => 'string|in:'.implode(',', Job::ALLOW_COLUMNS_SORT),
            'sortOrder' => 'string|in:ascending,descending',
        ];
    }
}
