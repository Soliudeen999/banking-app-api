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
            $table->string('reference')->unique();
            $table->string('from_acct_number');
            $table->string('from_bank_code');

            $table->string('to_acct_number');
            $table->string('to_bank_code');

            $table->decimal('amount', 15, 2);
            $table->string('status'); // receievd/sent/acknowledge
            $table->text('narration')->nullable();
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
