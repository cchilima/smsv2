<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('program_courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_level_id')->constrained('course_levels')->onDelete('restrict')->onUpdate('restrict');
            $table->foreignId('course_id')->constrained('courses')->onDelete('restrict')->onUpdate('restrict');
            $table->foreignId('program_id')->constrained('programs')->onDelete('restrict')->onUpdate('restrict');
            $table->integer('active')->default(1);
            $table->timestamps();

            // Add indexes
            $table->index('course_id');
            $table->index('program_id');
            $table->index('course_level_id');
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_courses');
    }
};
