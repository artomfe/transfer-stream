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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->decimal('value', 10, 2);
            $table->unsignedBigInteger('payer_id');
            $table->foreign('payer_id')->references('id')->on('wallets');
            $table->unsignedBigInteger('payee_id');
            $table->foreign('payee_id')->references('id')->on('wallets');
            $table->tinyInteger('status');
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
