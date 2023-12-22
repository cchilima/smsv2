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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->enum('gender', ['Male', 'Female']);
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('active')->default(0);
            $table->integer('force_password_reset')->nullable();
            $table->unsignedBiginteger('last_login_ip')->nullable();
            $table->integer('last_login_at')->nullable();
            $table->foreignId('user_type_id')->constrained('user_types')->onDelete('restrict');
            $table->rememberToken();
            $table->timestamps();

            // Add indexes
            $table->index('first_name');
            $table->index('middle_name');
            $table->index('last_name');
            $table->index('email');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
