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
        Schema::create('applicant_next_of_kin', function (Blueprint $table) {
            $table->id();
            $table->string('full_name')->nullable();;
            $table->string('mobile')->nullable();;
            $table->string('telephone')->nullable();
            $table->foreignUuid('applicant_id')->nullable()->constrained('applicants')->onDelete('restrict');
            $table->foreignId('relationship_id')->nullable()->constrained('relationships')->onDelete('restrict');
            $table->string('address')->nullable();
            $table->foreignId('town_id')->nullable()->constrained('towns')->onDelete('restrict');
            $table->foreignId('province_id')->nullable()->constrained('provinces')->onDelete('restrict');
            $table->foreignId('country_id')->nullable()->constrained('countries')->onDelete('restrict');
            $table->timestamps();

            // Add indexes
            $table->index('applicant_id');
            $table->index('full_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicant_next_of_kin');
    }
};
