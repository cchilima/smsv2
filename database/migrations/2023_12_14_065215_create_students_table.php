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
        Schema::create('students', function (Blueprint $table) {
            $table->unsignedBiginteger('id')->primary();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('program_id')->constrained('programs')->onDelete('restrict');
            $table->foreignId('academic_period_intake_id')->constrained('academic_period_intakes')->onDelete('restrict');
            $table->foreignId('study_mode_id')->constrained('study_modes')->onDelete('restrict');
            $table->foreignId('course_level_id')->constrained('course_levels')->onDelete('restrict');
            $table->foreignId('period_type_id')->constrained('period_types')->onDelete('restrict');
            $table->enum('admission_status', ['active', 'inactive']);
            $table->year('admission_year');
            $table->boolean('graduated')->default(0);
            $table->timestamps();


            // Add indexes
            $table->index('id');
            $table->index('user_id');
            $table->index('program_id');
            $table->index('academic_period_intake_id');
            $table->index('study_mode_id');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
