<?php

namespace App\Http\Requests;

use App\Models\Category;
use App\Models\City;
use App\Models\EventType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EventIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'per_page' => 'nullable|integer|min:1',
            'sort' => 'nullable|in:asc,desc',
            'start_date' => 'nullable|date|date_format:Y-m-d',
            'end_date' => 'nullable|date|date_format:Y-m-d|after_or_equal:start_date',
            'search' => 'nullable|string',
            'category' => [
                'nullable',
                'string',
                Rule::in(Category::pluck('name')->map(fn($name) => strtolower($name))->toArray()),
            ],
            'city' => [
                'nullable',
                'string',
                Rule::in(City::pluck('name')->map(fn($name) => strtolower($name))->toArray()),
            ],
        ];
    }
}
