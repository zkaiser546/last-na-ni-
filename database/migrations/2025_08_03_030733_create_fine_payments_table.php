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
        Schema::create('fine_payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_number')->unique(); // e.g., 'P-2024-000001'
            $table->foreignId('fine_fee_id')->constrained('fines_fees')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->decimal('amount', 10, 2);
            $table->enum('method', ['cash', 'check', 'card', 'bank_transfer', 'waiver']);
            $table->string('reference_number')->nullable();
            $table->date('payment_date');
            $table->foreignId('received_by')->constrained('users');
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['fine_fee_id', 'payment_date']);
            $table->index('payment_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fine_payments');
    }
};
