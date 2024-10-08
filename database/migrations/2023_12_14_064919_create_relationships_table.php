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
        Schema::create('relationships', function (Blueprint $table) {
            $table->id();
            $table->enum('relationship', ['Parent', 'Spouse', 'Sibling', 'Child','Aunt','Uncle',  'Grandparent', 'Partner', 'Cousin','Nephew', 'Niece', 'Other']); 
            $table->mediumText('description')->nullable(); 
            $table->timestamps();

            // Add indexes
            $table->index('id');
            $table->index('relationship');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('relationships');
    }
};
