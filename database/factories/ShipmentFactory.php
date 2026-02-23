<?php

namespace Database\Factories;

use App\Models\Shipment;
use App\Models\Unit;
use App\Models\Dealer;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shipment>
 */
class ShipmentFactory extends Factory
{
    protected $model = Shipment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = $this->faker->randomElement(['pending', 'dispatched', 'on_progress', 'delivered', 'delayed']);

        $dispatchedAt = null;
        $arrivedAt = null;

        if ($status !== 'pending') {
            $dispatchedAt = $this->faker->dateTimeBetween('-1 month', 'now');

            if ($status === 'delivered') {
                $arrivedAt = (clone $dispatchedAt)->modify('+' . $this->faker->numberBetween(1, 7) . ' days');
            }
        }

        return [
            'unit_id' => Unit::factory(),
            'dealer_id' => Dealer::factory(),
            'origin_id' => Warehouse::factory(),
            'status' => $status,
            'dispatched_at' => $dispatchedAt,
            'arrived_at' => $arrivedAt,
        ];
    }
}