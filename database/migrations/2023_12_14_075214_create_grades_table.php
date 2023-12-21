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
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->string('course_code');
            $table->string('course_title');
            $table->integer('total');
            $table->boolean('publication_status')->default(0);
            $table->foreignId('student_id')->constrained('students')->onDelete('restrict');
            $table->foreignId('academic_period_id')->constrained('academic_periods')->onDelete('restrict');
            $table->foreignId('assessment_type_id')->constrained('assessment_types')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
