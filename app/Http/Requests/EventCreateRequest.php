<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EventCreateRequest extends FormRequest
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
            'name' => 'required|string',
            'content' => 'required|string',
            'description' => 'nullable|string',
            'poster' => 'required|url',
            'date' => 'required|date_format:Y-m-d',
            'time' => 'required|date_format:H:i',
            'category' => 'required|array',
            'category.id' => 'required|integer|' . Rule::exists('categories', 'id'),

            'city' => 'required|array',
            'city.id' => 'required|integer|' . Rule::exists('cities', 'id')
        ];
    }
}
