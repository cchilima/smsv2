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
        Schema::create('user_personal_information', function (Blueprint $table) {
            $table->id();
            $table->date('date_of_birth');
            $table->string('street_main');
            $table->integer('post_code')->nullable();
            $table->string('telephone')->nullable();
            $table->string('mobile');
            $table->string('nrc');
            $table->string('passport_number')->nullable();
            $table->string('passport_photo_path')->nullable();
            $table->foreignId('marital_status_id')->constrained('marital_statuses')->onDelete('restrict');
            $table->foreignId('town_id')->constrained('towns')->onDelete('restrict');
            $table->foreignId('province_id')->constrained('provinces')->onDelete('restrict');
            $table->foreignId('country_id')->constrained('countries')->onDelete('restrict');
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->timestamps();

            // Add indexes
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_personal_information');
    }
};
