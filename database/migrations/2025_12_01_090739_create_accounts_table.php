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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('account_number')->unique();
            $table->string('type'); // e.g., savings, checking
            $table->decimal('ledger_balance', 15, 2)->default(0.00);
            $table->decimal('main_balance', 15, 2)->default(0.00);
            $table->decimal('debits', 15, 2)->default(0.00);
            $table->decimal('credits', 15, 2)->default(0.00);
            $table->integer('tier')->default(1);
            $table->string('currency', 3)->default('NGN');
            $table->string('status')->default('active'); // active, inactive, closed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
