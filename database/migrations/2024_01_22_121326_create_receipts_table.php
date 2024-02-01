<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->nullable();
            $table->decimal('amount', 10, 2);
            $table->text('transaction_id')->nullable();
            $table->foreignId('collected_by')->constrained('users');
            $table->foreignId('student_id')->constrained('students');
            $table->timestamps();

            // Indexes
            $table->index('invoices');
            $table->index('student_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipts');
    }
}
