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
            $table->Integer('level_id');
            $table->Integer('course_id');
            $table->Integer('program_id');
            $table->integer('active')->default(1);
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('courses')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('program_id')->references('id')->on('programs')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('level_id')->references('id')->on('course_levels')->onDelete('restrict')->onUpdate('restrict');
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
