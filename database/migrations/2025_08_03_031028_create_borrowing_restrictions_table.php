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
        Schema::create('borrowing_restrictions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['suspension', 'fine_limit', 'overdue_limit', 'manual_block']);
            $table->string('reason');

            $table->date('start_date');
            $table->date('end_date')->nullable(); // null for indefinite

            $table->boolean('blocks_borrowing')->default(true);
            $table->boolean('blocks_renewals')->default(true);
            $table->boolean('blocks_holds')->default(false); // Changed to false since holds are excluded

            $table->enum('status', ['active', 'expired', 'lifted']);

            $table->foreignId('created_by')->constrained('users'); // Staff who created restriction
            $table->foreignId('lifted_by')->nullable()->constrained('users'); // Staff who lifted restriction
            $table->datetime('lifted_date')->nullable();
            $table->text('lift_reason')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'status']);
            $table->index(['start_date', 'end_date', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrowing_restrictions');
    }
};
