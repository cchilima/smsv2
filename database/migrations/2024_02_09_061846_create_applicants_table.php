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
            $table->id();
            $table->string('nrc')->nullable();
            $table->string('passport')->nullable();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['Male', 'Female'])->nullable();
            $table->string('address')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            $table->date('application_date')->nullable();
            $table->enum('status', ['incomplete','pending', 'accepted', 'rejected'])->default('incomplete');
            $table->foreignId('town_id')->constrained('towns')->onDelete('restrict')->nullable();
            $table->foreignId('province_id')->constrained('provinces')->onDelete('restrict')->nullable();
            $table->foreignId('country_id')->constrained('countries')->onDelete('restrict')->nullable();
            $table->foreignId('program_id')->constrained('programs')->onDelete('restrict')->nullable();
            $table->foreignId('period_type_id')->constrained('period_types')->onDelete('restrict')->nullable();
            $table->foreignId('study_mode_id')->constrained('study_modes')->onDelete('restrict')->nullable();
            $table->foreignId('academic_period_intake_id')->constrained('academic_period_intakes')->onDelete('restrict')->nullable();
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
