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
        Schema::create('applicant_grades', function (Blueprint $table) {
            $table->id();
            $table->text('secondary_school');
            $table->text('subject');
            $table->integer('grade');
            $table->foreignUuid('applicant_id')->constrained('applicants')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicant_grades');
    }
};
