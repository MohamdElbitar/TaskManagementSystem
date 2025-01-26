<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'sometimes|date|after_or_equal:today',
            'status' => 'sometimes|in:pending,completed,canceled',
            'assignee_id' => 'sometimes|exists:users,id',
        ];
    }
}
