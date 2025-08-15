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
        Schema::table('library_visits', function (Blueprint $table) {
            $table->dropForeign(['visit_purpose_id']);
            $table->dropColumn(['visit_purpose_id', 'other_visit_purpose']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('library_visits', function (Blueprint $table) {
            $table->foreignId('visit_purpose_id')->nullable()->constrained('visit_purposes')->onDelete('cascade');
            $table->string('other_visit_purpose')->nullable();
        });
    }
};
