<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\TransactionStatusEnum;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->uuid('token')->unique();
            $table->enum('status', TransactionStatusEnum::all());
            $table->decimal('amount', 11, 2);

            $table->unsignedBigInteger('payer_id')
                ->foreign('payer_id')
                ->references('id')
                ->on('users');

            $table->unsignedBigInteger('payee_id')
                ->foreign('payee_id')
                ->references('id')
                ->on('users');

            $table->timestamps();
            $table->softDeletes();
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
