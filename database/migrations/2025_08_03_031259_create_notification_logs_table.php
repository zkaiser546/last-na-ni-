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
        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('borrowing_transaction_id')->nullable()
                ->constrained('borrowing_transactions')->onDelete('set null');
            // Removed hold_reserve_id since holds are excluded

            $table->enum('type', ['due_soon', 'overdue', 'fine_notice']); // Removed hold-related types
            $table->enum('method', ['email', 'sms']);
            $table->enum('status', ['sent', 'failed', 'pending']);

            $table->string('recipient'); // Email or phone number
            $table->string('subject')->nullable();
            $table->text('message');

            $table->datetime('sent_at')->nullable();
            $table->text('error_message')->nullable();
            $table->integer('retry_count')->default(0);

            $table->timestamps();

            $table->index(['user_id', 'type', 'sent_at']);
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_logs');
    }
};
