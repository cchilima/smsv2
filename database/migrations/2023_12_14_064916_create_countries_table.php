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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->char('alpha_2_code', 2)->unique();
            $table->char('alpha_3_code', 3)->unique();
            $table->string('dialing_code', 4)->unique();
            $table->string('country')->unique();
            $table->string('nationality')->unique();
            $table->timestamps();

            // Add indexes
            $table->index('id');
            $table->index('country');
            $table->index('nationality');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
