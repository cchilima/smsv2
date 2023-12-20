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
        Schema::create('academic_period_information', function (Blueprint $table) {
            $table->id();
            $table->date('registration_date');
            $table->date('late_registration_date');
            $table->date('late_registration_end_date');
            $table->decimal('registration_threshold');
            $table->decimal('exam_slip_threshold');
            $table->decimal('view_results_threshold');
            $table->foreignId('study_mode_id')->constrained('study_modes')->onDelete('restrict');
            $table->foreignId('academic_period_intake_id')->constrained('academic_period_intakes')->onDelete('restrict');
            $table->timestamps();

            // Add indexes
            $table->index('id');
            $table->index('study_mode_id');
            $table->index('academic_period_intake_id');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_period_information');
    }
};
