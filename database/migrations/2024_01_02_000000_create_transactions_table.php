<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['credit', 'debit']);
            $table->string('purpose'); // funding, airtime, transfer, withdrawal
            $table->decimal('amount', 12, 2);
            $table->decimal('balance_before', 12, 2);
            $table->decimal('balance_after', 12, 2);
            $table->string('reference')->unique();
            $table->enum('status', ['successful', 'pending', 'failed'])->default('successful');
            $table->text('description')->nullable();
            $table->json('meta')->nullable(); // store extra data like network, phone
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
