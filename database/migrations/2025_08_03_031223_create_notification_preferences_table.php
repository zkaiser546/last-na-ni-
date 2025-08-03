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
        Schema::create('notification_preferences', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Notification types
            $table->boolean('due_soon_email')->default(true);
            $table->boolean('due_soon_sms')->default(false);
            $table->boolean('overdue_email')->default(true);
            $table->boolean('overdue_sms')->default(false);
            // Removed hold-related notifications since holds are excluded

            // Timing preferences
            $table->integer('due_soon_days')->default(3); // Days before due date
            $table->integer('overdue_reminder_frequency')->default(7); // Days between reminders

            $table->string('sms_number')->nullable();
            $table->boolean('sms_verified')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_preferences');
    }
};
