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
        Schema::create('user_next_of_kin', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('mobile');
            $table->string('telephone')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('relationship_id')->constrained('relationships')->onDelete('restrict');
            $table->string('address');
            $table->foreignId('town_id')->constrained('towns')->onDelete('restrict');
            $table->foreignId('province_id')->constrained('provinces')->onDelete('restrict');
            $table->foreignId('country_id')->constrained('countries')->onDelete('restrict');
            $table->timestamps();

            // Add indexes
            $table->index('user_id');
            $table->index('full_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_next_of_kin');
    }
};
