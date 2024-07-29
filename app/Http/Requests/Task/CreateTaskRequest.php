<?php

namespace App\Http\Requests\Task;

use App\Enums\PriorityEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Enums\StatusEnum;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CreateTaskRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'priority' => ['sometimes', 'int', 'in:' . implode(',', PriorityEnum::all())],
            'status' => ['sometimes', 'string', 'in:' . implode(',', StatusEnum::all())],
            'parent_id' => [
                'nullable',
                'integer',
                Rule::exists('tasks', 'id')->where(function ($query) {
                    $query->where('user_id', Auth::id());
                })
            ]
        ];
    }
}
