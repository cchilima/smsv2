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
        Schema::create('academic_period_classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('course_id')->constrained('courses')->onDelete('restrict');
            $table->foreignId('academic_period_id')->constrained('academic_periods')->onDelete('restrict');
            $table->timestamps();

            // Add indexes
            $table->index('instructor_id');
            $table->index('course_id');
            $table->index('academic_period_id');
            $table->index('key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_period_classes');
    }
};
