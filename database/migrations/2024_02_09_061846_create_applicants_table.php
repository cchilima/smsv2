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
        Schema::create('applicants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('applicant_code')->unique();
            $table->string('nrc')->nullable();
            $table->string('passport')->nullable();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['Male', 'Female'])->nullable();
            $table->string('address')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            $table->year('year_applying_for')->nullable();
            $table->enum('status', ['incomplete', 'pending', 'complete', 'accepted', 'rejected'])->default('incomplete');
            $table->foreignId('marital_status_id')->nullable()->constrained('marital_statuses')->onDelete('restrict');
            $table->foreignId('town_id')->nullable()->constrained('towns')->onDelete('restrict');
            $table->foreignId('province_id')->nullable()->constrained('provinces')->onDelete('restrict');
            $table->foreignId('country_id')->nullable()->constrained('countries')->onDelete('restrict');
            $table->foreignId('program_id')->nullable()->constrained('programs')->onDelete('restrict');
            $table->foreignId('study_mode_id')->nullable()->constrained('study_modes')->onDelete('restrict');
            $table->foreignId('academic_period_intake_id')->nullable()->constrained('academic_period_intakes')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicants');
    }
};
