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
        Schema::create('cash_deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('depositor_name');
            $table->string('branch')->nullable();
            $table->string('depositor_phone');
            $table->float('amount');
            $table->string('account_number')->index();
            $table->string('account_name');
            $table->string('reference')->unique()->index();
            $table->text('narration')->nullable();
            $table->string('status')->default('pending'); // pending, completed, failed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_deposits');
    }
};
