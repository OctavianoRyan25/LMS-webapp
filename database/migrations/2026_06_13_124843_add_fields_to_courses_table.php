<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table): void {
            $table->string('category_id')->nullable()->after('description');
            $table->string('level')->nullable()->after('category_id');
            $table->decimal('price', 10, 2)->default(0)->after('level');
            $table->integer('duration_hours')->nullable()->after('price');
            $table->string('instructor_id')->nullable()->after('duration_hours');
            $table->boolean('has_certificate')->default(true)->after('instructor_id');
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table): void {
            $table->dropColumn([
                'category_id',
                'level',
                'price',
                'duration_hours',
                'instructor_id',
                'has_certificate',
            ]);
        });
    }
};
