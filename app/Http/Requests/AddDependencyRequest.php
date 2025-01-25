<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddDependencyRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'dependency_id' => 'required|exists:tasks,id',
        ];
    }
}
