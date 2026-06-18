<?php

namespace App\Http\Requests;

use App\Models\Course;
use Illuminate\Foundation\Http\FormRequest;

final class StoreCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:100'],
            'thumbnail' => ['nullable', 'image', 'max:2048'],
            'status' => ['required', 'string', 'in:draft,published,archived'],
            'category_id' => ['nullable', 'string'],
            'level' => ['nullable', 'string', 'in:beginner,intermediate,advanced'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'duration_hours' => ['nullable', 'integer', 'min:1'],
            'instructor_id' => ['nullable', 'string'],
            'has_certificate' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Nama kursus harus diisi.',
            'description.required' => 'Deskripsi kursus harus diisi.',
            'description.min' => 'Deskripsi minimal 100 karakter.',
            'thumbnail.image' => 'File harus berupa gambar.',
            'thumbnail.max' => 'Ukuran gambar maksimal 2MB.',
            'status.required' => 'Status publikasi harus dipilih.',
        ];
    }
}
