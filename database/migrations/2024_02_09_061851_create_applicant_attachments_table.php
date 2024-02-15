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
        Schema::create('applicant_attachments', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['NRC / Passport', 'Results', 'Photo']);
            $table->string('attachment');
            $table->foreignId('applicant_id')->contrained('applicants')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicant_attachments');
    }
};
