<?php

namespace Database\Seeders;

use App\Models\BloodBag;
use App\Models\BloodBank;
use App\Models\Refrigerator;
use App\Models\TemperatureLog;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'role' => 'admin',
                'password' => bcrypt('password'),
            ]
        );

        $staff = User::firstOrCreate(
            ['email' => 'staff@example.com'],
            [
                'name' => 'Staff User',
                'role' => 'staff',
                'password' => bcrypt('password'),
            ]
        );

        $bank1 = BloodBank::create([
            'name' => 'Central Blood Bank',
            'location' => 'Nairobi',
        ]);

        $bank2 = BloodBank::create([
            'name' => 'Coast Blood Bank',
            'location' => 'Mombasa',
        ]);

        $staff->bloodBanks()->attach([$bank1->id, $bank2->id]);
        $admin->bloodBanks()->attach([$bank1->id]);

        $fridge1 = Refrigerator::create([
            'identifier' => 'REF-001',
            'blood_bank_id' => $bank1->id,
            'status' => 'active',
        ]);

        $fridge2 = Refrigerator::create([
            'identifier' => 'REF-002',
            'blood_bank_id' => $bank2->id,
            'status' => 'active',
        ]);

        BloodBag::create([
            'bag_number' => 'BAG-1001',
            'blood_group' => 'A+',
            'donor_name' => 'Jane Doe',
            'collection_date' => Carbon::now()->subDays(10),
            'expiry_date' => Carbon::now()->addDays(20),
            'quantity' => 450,
            'status' => BloodBag::STATUS_AVAILABLE,
            'refrigerator_id' => $fridge1->id,
        ]);

        BloodBag::create([
            'bag_number' => 'BAG-1002',
            'blood_group' => 'O-',
            'donor_name' => 'John Smith',
            'collection_date' => Carbon::now()->subDays(15),
            'expiry_date' => Carbon::now()->subDays(2),
            'quantity' => 300,
            'status' => BloodBag::STATUS_EXPIRED,
            'refrigerator_id' => $fridge1->id,
        ]);

        BloodBag::create([
            'bag_number' => 'BAG-1003',
            'blood_group' => 'B+',
            'donor_name' => 'Alicia Kim',
            'collection_date' => Carbon::now()->subDays(5),
            'expiry_date' => Carbon::now()->addHours(12),
            'quantity' => 250,
            'status' => BloodBag::STATUS_RESERVED,
            'refrigerator_id' => $fridge2->id,
        ]);

        TemperatureLog::create([
            'refrigerator_id' => $fridge1->id,
            'temperature' => 2.8,
            'recorded_at' => now(),
        ]);

        TemperatureLog::create([
            'refrigerator_id' => $fridge2->id,
            'temperature' => 7.4,
            'recorded_at' => now()->subHour(),
        ]);
    }
}
