<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom thumbnail_url ke tabel courses.
     * Kolom thumbnail menyimpan Cloudinary public_id (untuk delete),
     * sedangkan thumbnail_url menyimpan full HTTPS URL untuk ditampilkan.
     */
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table): void {
            $table->string('thumbnail_url')->nullable()->after('thumbnail');
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table): void {
            $table->dropColumn('thumbnail_url');
        });
    }
};
