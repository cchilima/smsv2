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
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->foreignId('department_id')->constrained('departments')->onDelete('restrict');
            $table->foreignId('qualification_id')->constrained('qualifications')->onDelete('restrict');
            $table->string('slug');
            $table->mediumText('description')->nullable();
            $table->timestamps();


            // Add indexes
            $table->index('id');
            $table->index('code');
            $table->index('name');
            $table->index('department_id');
            $table->index('qualification_id');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
