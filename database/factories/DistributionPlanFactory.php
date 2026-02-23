<?php

namespace Database\Factories;

use App\Models\DistributionPlan;
use App\Models\Dealer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DistributionPlan>
 */
class DistributionPlanFactory extends Factory
{
    protected $model = DistributionPlan::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'month' => now()->format('Y-m'),
            'dealer_id' => Dealer::factory(),
            'target_quantity' => $this->faker->numberBetween(10, 50),
        ];
    }
}