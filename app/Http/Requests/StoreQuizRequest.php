<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreQuizRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'title'            => ['required', 'string', 'max:255'],
            'description'      => ['nullable', 'string'],
            'duration_minutes' => ['nullable', 'integer', 'min:1'],
            'passing_score'    => ['nullable', 'integer', 'min:1', 'max:100'],
            'q_text'           => ['required', 'array', 'min:1'],
            'q_text.*'         => ['required', 'string'],
            'q_options'        => ['required', 'array'],
            'q_options.*'      => ['required', 'array', 'min:2'],
            'q_correct'        => ['required', 'array'],
            'q_correct.*'      => ['required', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'       => 'Judul kuis harus diisi.',
            'q_text.required'      => 'Minimal 1 pertanyaan harus ditambahkan.',
            'q_text.min'           => 'Minimal 1 pertanyaan harus ditambahkan.',
            'q_text.*.required'    => 'Teks pertanyaan tidak boleh kosong.',
            'q_options.*.min'      => 'Setiap pertanyaan minimal memiliki 2 pilihan jawaban.',
        ];
    }
}
