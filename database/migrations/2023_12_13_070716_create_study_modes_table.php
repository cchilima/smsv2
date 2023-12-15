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
        Schema::create('study_modes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->mediumText('description')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('id');
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_modes');
    }
};
