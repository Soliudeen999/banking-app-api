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
        Schema::create('govt_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique()->index();
            $table->string('account_number');
            $table->string('bank_code');
            $table->string('related_account_number', 20)->nullable();
            $table->string('related_bank_code', 100)->nullable();
            $table->string('type'); // e.g., debit, credit
            $table->decimal('amount', 15, 2);
            $table->string('status'); // received/sent/acknowledge
            $table->string('narration')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('govt_transactions');
    }
};
