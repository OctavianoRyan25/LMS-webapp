<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateAssignmentRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'title'        => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string'],
            'instructions' => ['required', 'string'],
            'due_date'     => ['nullable', 'date'],
            'max_score'    => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
