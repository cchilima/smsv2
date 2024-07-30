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
            $table->foreignId('invoice_id')->nullable()->constrained('invoices');
            $table->decimal('amount', 10, 2);
            $table->text('transaction_id')->nullable();
            $table->foreignId('collected_by')->constrained('users');
            $table->foreignId('student_id')->constrained('students');
            $table->foreignId('payment_method_id')->constrained('payment_methods')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('invoice_id');
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
