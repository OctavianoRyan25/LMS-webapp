<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateExamRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'title'            => ['required', 'string', 'max:255'],
            'description'      => ['nullable', 'string'],
            'duration_minutes' => ['required', 'integer', 'min:1'],
            'passing_score'    => ['nullable', 'integer', 'min:1', 'max:100'],
            'start_date'       => ['nullable', 'date'],
            'end_date'         => ['nullable', 'date'],
            'q_text'           => ['required', 'array', 'min:1'],
            'q_text.*'         => ['required', 'string'],
            'q_options'        => ['required', 'array'],
            'q_options.*'      => ['required', 'array', 'min:2'],
            'q_correct'        => ['required', 'array'],
            'q_correct.*'      => ['required', 'integer', 'min:0'],
        ];
    }
}
