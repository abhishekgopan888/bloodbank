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
        Schema::table('temperature_logs', function (Blueprint $table) {
            if (!Schema::hasColumn('temperature_logs', 'refrigerator_id')) {
                $table->foreignId('refrigerator_id')->nullable()->constrained()->cascadeOnDelete();
            }
            if (!Schema::hasColumn('temperature_logs', 'temperature')) {
                $table->float('temperature')->nullable();
            }
            if (!Schema::hasColumn('temperature_logs', 'recorded_at')) {
                $table->dateTime('recorded_at')->nullable()->index();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('temperature_logs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('refrigerator_id');
            $table->dropColumn(['temperature', 'recorded_at']);
        });
    }
};
