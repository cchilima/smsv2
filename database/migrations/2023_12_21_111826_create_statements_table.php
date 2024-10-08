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
        Schema::create('statements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_method_id')->constrained('payment_methods');
            $table->foreignId('invoice_id')->nullable()->constrained('invoices');
            $table->foreignId('collected_by')->constrained('users')->onDelete('restrict');
            $table->foreignId('collected_from')->constrained('students')->onDelete('restrict');
            $table->decimal('amount', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statements');
    }
};
