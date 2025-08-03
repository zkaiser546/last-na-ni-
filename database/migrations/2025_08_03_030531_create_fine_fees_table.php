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
        Schema::create('fine_fees', function (Blueprint $table) {
            $table->id();
            $table->string('fine_number')->unique(); // e.g., 'F-2024-000001'

            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('borrowing_transaction_id')->nullable()->constrained('borrowing_transactions')->onDelete('set null');

            $table->enum('type', ['overdue', 'lost', 'damaged', 'processing', 'replacement', 'other']);
            $table->string('description');
            $table->decimal('amount', 10, 2);
            $table->decimal('amount_outstanding', 10, 2);
            $table->decimal('amount_paid', 10, 2)->default(0.00);
            $table->enum('status', ['pending', 'paid', 'partially_paid', 'waived', 'cancelled']);

            $table->date('fine_date');
            $table->date('due_date')->nullable();
            $table->date('paid_date')->nullable();
            $table->date('waived_date')->nullable();

            $table->foreignId('waived_by')->nullable()->constrained('users');
            $table->text('waiver_reason')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'status']);
            $table->index(['fine_date', 'status']);
            $table->index('fine_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fine_fees');
    }
};
