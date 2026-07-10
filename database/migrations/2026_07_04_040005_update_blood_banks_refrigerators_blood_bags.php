<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blood_banks', function (Blueprint $table) {
            if (!Schema::hasColumn('blood_banks', 'name')) {
                $table->string('name')->nullable();
            }
            if (!Schema::hasColumn('blood_banks', 'location')) {
                $table->string('location')->nullable();
            }
        });

        Schema::table('refrigerators', function (Blueprint $table) {
            if (!Schema::hasColumn('refrigerators', 'identifier')) {
                $table->string('identifier')->nullable();
            }
            if (!Schema::hasColumn('refrigerators', 'blood_bank_id')) {
                $table->foreignId('blood_bank_id')->nullable()->constrained()->cascadeOnDelete();
            }
            if (!Schema::hasColumn('refrigerators', 'status')) {
                $table->string('status')->default('active');
            }
        });

        Schema::table('blood_bags', function (Blueprint $table) {
            if (!Schema::hasColumn('blood_bags', 'bag_number')) {
                $table->string('bag_number')->nullable();
            }
            if (!Schema::hasColumn('blood_bags', 'blood_group')) {
                $table->string('blood_group')->nullable();
            }
            if (!Schema::hasColumn('blood_bags', 'donor_name')) {
                $table->string('donor_name')->nullable();
            }
            if (!Schema::hasColumn('blood_bags', 'collection_date')) {
                $table->date('collection_date')->nullable();
            }
            if (!Schema::hasColumn('blood_bags', 'expiry_date')) {
                $table->date('expiry_date')->nullable();
            }
            if (!Schema::hasColumn('blood_bags', 'quantity')) {
                $table->integer('quantity')->default(0);
            }
            if (!Schema::hasColumn('blood_bags', 'status')) {
                $table->string('status')->default('Available');
            }
            if (!Schema::hasColumn('blood_bags', 'refrigerator_id')) {
                $table->foreignId('refrigerator_id')->nullable()->constrained()->cascadeOnDelete();
            }
        });
    }

    public function down(): void
    {
        // no down operations for safety in assessment
    }
};
