<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreAssignmentRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'title'        => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string'],
            'instructions' => ['required', 'string'],
            'due_date'     => ['nullable', 'date', 'after:now'],
            'max_score'    => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'        => 'Judul tugas harus diisi.',
            'instructions.required' => 'Petunjuk pengerjaan harus diisi.',
            'due_date.after'        => 'Deadline harus lebih dari waktu sekarang.',
        ];
    }
}
