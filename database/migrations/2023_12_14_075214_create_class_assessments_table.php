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
        Schema::create('class_assessments', function (Blueprint $table) {
            $table->id();
            $table->integer('total');
            $table->date('end_date');
            $table->foreignId('assessment_type_id')->constrained('assessment_types')->onDelete('restrict');
            $table->foreignId('academic_period_class_id')->constrained('academic_period_classes')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_assessments');
    }
};
