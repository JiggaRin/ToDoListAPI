<?php

namespace App\Http\Requests\Task;

use App\Enums\PriorityEnum;
use App\Models\Task;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Enums\StatusEnum;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UpdateTaskRequest extends FormRequest
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
        if ($this->route('task') instanceof Task) {
            $taskId = $this->route('task')->id;
        } else {
            $taskId = null;
        }

        return [
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'priority' => ['sometimes', 'int', 'in:' . implode(',', PriorityEnum::all())],
            'status' => ['nullable', 'string', 'in:' . implode(',', StatusEnum::all())],
            'parent_id' => [
                'nullable',
                'integer',
                Rule::exists('tasks', 'id')->where(function ($query) {
                    $query->where('user_id', Auth::id());
                }),
                function ($attribute, $value, $fail) use ($taskId) {
                    if ($value == $taskId) {
                        $fail('A task cannot be its own parent.');
                    }
                },
            ]
        ];
    }
}
