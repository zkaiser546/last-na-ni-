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
        Schema::create('borrowing_histories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('record_id')->constrained('records')->onDelete('cascade');
            $table->foreignId('borrowing_transaction_id')
                ->constrained('borrowing_transactions')->onDelete('cascade');

            $table->string('action'); // 'checkout', 'return', 'renew', 'overdue', 'lost', etc.
            $table->datetime('action_date');
            $table->foreignId('performed_by')->constrained('users'); // Staff who performed action

            $table->json('metadata')->nullable(); // Additional data (due dates, notes, etc.)
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'action_date']);
            $table->index(['record_id', 'action_date']);
            $table->index(['action', 'action_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrowing_histories');
    }
};
