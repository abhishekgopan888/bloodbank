<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('temperature_logs', function (Blueprint $table) {
            $table->index(['refrigerator_id']);
            $table->index(['recorded_at']);
            $table->index(['temperature']);
        });

        Schema::table('blood_bags', function (Blueprint $table) {
            $table->index('expiry_date');
            $table->index('bag_number');
        });
    }

    public function down(): void
    {
        Schema::table('temperature_logs', function (Blueprint $table) {
            $table->dropIndex(['refrigerator_id']);
            $table->dropIndex(['recorded_at']);
            $table->dropIndex(['temperature']);
        });

        Schema::table('blood_bags', function (Blueprint $table) {
            $table->dropIndex(['expiry_date']);
            $table->dropIndex(['bag_number']);
        });
    }
};
