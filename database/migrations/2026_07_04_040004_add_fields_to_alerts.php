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
        Schema::table('alerts', function (Blueprint $table) {
            if (!Schema::hasColumn('alerts', 'refrigerator_id')) {
                $table->foreignId('refrigerator_id')->nullable()->constrained()->cascadeOnDelete();
            }
            if (!Schema::hasColumn('alerts', 'type')) {
                $table->string('type')->nullable();
            }
            if (!Schema::hasColumn('alerts', 'message')) {
                $table->text('message')->nullable();
            }
            if (!Schema::hasColumn('alerts', 'metadata')) {
                $table->json('metadata')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alerts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('refrigerator_id');
            $table->dropColumn(['type', 'message', 'metadata']);
        });
    }
};
