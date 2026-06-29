<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreLessonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'            => ['required', 'string', 'max:255'],
            'content'          => ['nullable', 'string'],
            'order'            => ['nullable', 'integer', 'min:0'],
            'duration_minutes' => ['nullable', 'integer', 'min:1'],
            'is_free_preview'  => ['nullable', 'boolean'],
            'video'            => ['nullable', 'file', 'mimes:mp4,mov,avi,webm', 'max:204800'], // 200MB
            'attachments'      => ['nullable', 'array'],
            'attachments.*'    => ['file', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,txt', 'max:20480'], // 20MB
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'       => 'Judul lesson harus diisi.',
            'video.mimes'          => 'Format video harus mp4, mov, avi, atau webm.',
            'video.max'            => 'Ukuran video maksimal 200MB.',
            'attachments.*.mimes'  => 'Format lampiran tidak didukung.',
            'attachments.*.max'    => 'Ukuran setiap lampiran maksimal 20MB.',
        ];
    }
}
