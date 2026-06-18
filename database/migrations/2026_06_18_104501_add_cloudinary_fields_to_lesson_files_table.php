<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom Cloudinary pada lesson_files.
     * - file_url: full HTTPS URL dari Cloudinary (untuk ditampilkan / diunduh)
     * - cloudinary_public_id: public_id Cloudinary (untuk operasi delete/update)
     */
    public function up(): void
    {
        Schema::table('lesson_files', function (Blueprint $table): void {
            $table->string('file_url')->nullable()->after('file_path');
            $table->string('cloudinary_public_id')->nullable()->after('file_url');
        });
    }

    public function down(): void
    {
        Schema::table('lesson_files', function (Blueprint $table): void {
            $table->dropColumn(['file_url', 'cloudinary_public_id']);
        });
    }
};
