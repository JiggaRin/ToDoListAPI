<?php

namespace App\Http\Requests\Task;

use App\Enums\PriorityEnum;
use App\Enums\StatusEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TaskFiltersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'status' => ['nullable', 'string', 'in:' . implode(',', StatusEnum::all())],
            'priority' => ['sometimes', 'int', 'in:' . implode(',', PriorityEnum::all())],
            'search' => ['nullable', 'string'],
            'sort' => ['nullable', 'array'],
            'sort.*' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) {
                    $allowedFields = ['created_at', 'completed_at', 'priority'];
                    $allowedDirections = ['asc', 'desc'];
                    $field = str_replace('sort.', '', $attribute);
                    $directions = str_replace('sort.', '', $value);

                    if (!in_array($field, $allowedFields)) {
                        $fail("Invalid sort field: {$field}.");
                    }

                    if (!in_array($directions, $allowedDirections)) {
                        $fail("Invalid sort direction: {$field}.");
                    }
                }
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'status.in' => 'The selected status is invalid.',
            'priority.in' => 'The priority must be between 1 and 5.',
        ];
    }
}
