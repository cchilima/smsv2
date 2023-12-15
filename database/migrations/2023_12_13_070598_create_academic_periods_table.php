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
        Schema::create('academic_periods', function (Blueprint $table) {
            $table->id();
            $table->string('code',100)->unique();
            $table->date('registration_date');
            $table->date('late_registration_date');
            $table->date('ac_start_date');
            $table->date('ac_end_date');
            $table->foreignId('period_type_id')->constrained('period_types')->onDelete('restrict');
            $table->foreignId('program_intake_id')->constrained('program_intakes')->onDelete('restrict');
            $table->unsignedBigInteger('type');
            $table->unsignedInteger('study_mode_id_allowed');
            $table->timestamps();

            // Indexes for id, periodID and code columns
            $table->index('id');
            $table->index('period_type_id');
            $table->index('code');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_periods');
    }
};
