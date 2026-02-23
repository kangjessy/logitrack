<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Warehouse;
use App\Models\Dealer;
use App\Models\Unit;
use App\Models\DistributionPlan;
use App\Models\Shipment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Admin User
        User::factory()->create([
            'name' => 'Admin LogiTrack',
            'email' => 'admin@logitrack.com',
            'password' => Hash::make('password'),
        ]);

        // 2. Create Warehouses
        $warehouses = Warehouse::factory(4)->create();

        // 3. Create Dealers across regions
        $dealers = Dealer::factory(10)->create();

        // 4. Create Distribution Plans for current month
        foreach ($dealers as $dealer) {
            DistributionPlan::factory()->create([
                'dealer_id' => $dealer->id,
                'month' => now()->format('Y-m'),
                'target_quantity' => rand(15, 30),
            ]);
        }

        // 5. Create units and shipments
        // Available Units in Warehouses
        Unit::factory(20)->create([
            'status' => 'available',
            'warehouse_id' => fn() => $warehouses->random()->id,
        ]);

        // Shipments: Delivered (Standard)
        for ($i = 0; $i < 40; $i++) {
            $origin = $warehouses->random();
            $dealer = $dealers->random();
            $unit = Unit::factory()->create([
                'warehouse_id' => $origin->id,
                'status' => 'delivered'
            ]);

            $dispatchedAt = now()->subDays(rand(5, 15));
            $arrivedAt = (clone $dispatchedAt)->addDays(rand(1, 3));

            Shipment::create([
                'unit_id' => $unit->id,
                'dealer_id' => $dealer->id,
                'origin_id' => $origin->id,
                'status' => 'delivered',
                'dispatched_at' => $dispatchedAt,
                'arrived_at' => $arrivedAt,
            ]);
        }

        // Shipments: Delivered (Bottleneck - Slow Lead Time > 3 Days)
        for ($i = 0; $i < 5; $i++) {
            $origin = $warehouses->random();
            $dealer = $dealers->random();
            $unit = Unit::factory()->create([
                'warehouse_id' => $origin->id,
                'status' => 'delivered'
            ]);

            $dispatchedAt = now()->subDays(rand(10, 20));
            $arrivedAt = (clone $dispatchedAt)->addDays(rand(5, 8)); // 5-8 days (Bottleneck)

            Shipment::create([
                'unit_id' => $unit->id,
                'dealer_id' => $dealer->id,
                'origin_id' => $origin->id,
                'status' => 'delivered',
                'dispatched_at' => $dispatchedAt,
                'arrived_at' => $arrivedAt,
            ]);
        }

        // Shipments: On Progress / In Transit
        for ($i = 0; $i < 12; $i++) {
            $origin = $warehouses->random();
            $dealer = $dealers->random();
            $unit = Unit::factory()->create([
                'warehouse_id' => $origin->id,
                'status' => 'in_transit'
            ]);

            Shipment::create([
                'unit_id' => $unit->id,
                'dealer_id' => $dealer->id,
                'origin_id' => $origin->id,
                'status' => 'on_progress',
                'dispatched_at' => now()->subDays(rand(1, 2)),
            ]);
        }

        // Shipments: Delayed (Explicit status)
        for ($i = 0; $i < 3; $i++) {
            $origin = $warehouses->random();
            $dealer = $dealers->random();
            $unit = Unit::factory()->create([
                'warehouse_id' => $origin->id,
                'status' => 'in_transit'
            ]);

            Shipment::create([
                'unit_id' => $unit->id,
                'dealer_id' => $dealer->id,
                'origin_id' => $origin->id,
                'status' => 'delayed',
                'dispatched_at' => now()->subDays(4),
            ]);
        }

        // Shipments: Pending / Booked
        for ($i = 0; $i < 8; $i++) {
            $origin = $warehouses->random();
            $dealer = $dealers->random();
            $unit = Unit::factory()->create([
                'warehouse_id' => $origin->id,
                'status' => 'booked'
            ]);

            Shipment::create([
                'unit_id' => $unit->id,
                'dealer_id' => $dealer->id,
                'origin_id' => $origin->id,
                'status' => 'pending',
            ]);
        }
    }
}