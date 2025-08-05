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
        Schema::create('library_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('description')->nullable();
            $table->enum('type', ['string', 'integer', 'boolean', 'json'])->default('string');
            $table->timestamps();
        });

        // Insert default settings
        DB::table('library_settings')->insert([
            [
                'key' => 'library_name',
                'value' => 'University Library',
                'description' => 'Name of the library',
                'type' => 'string',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'max_visit_hours',
                'value' => '12',
                'description' => 'Maximum hours a patron can stay (0 for unlimited)',
                'type' => 'integer',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'auto_logout_enabled',
                'value' => 'true',
                'description' => 'Automatically log out patrons after max hours',
                'type' => 'boolean',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'operating_hours',
                'value' => '{"monday":{"open":"08:00","close":"22:00"},"tuesday":{"open":"08:00","close":"22:00"},"wednesday":{"open":"08:00","close":"22:00"},"thursday":{"open":"08:00","close":"22:00"},"friday":{"open":"08:00","close":"22:00"},"saturday":{"open":"09:00","close":"18:00"},"sunday":{"open":"10:00","close":"18:00"}}',
                'description' => 'Library operating hours by day',
                'type' => 'json',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('library_settings');
    }
};
