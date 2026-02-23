<?php

namespace Database\Factories;

use App\Models\Unit;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Unit>
 */
class UnitFactory extends Factory
{
    protected $model = Unit::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $models = ['Avanza 1.5 G', 'Veloz Q TSS', 'Innova Zenix G', 'Fortuner 2.8 GR', 'Rush 1.5 S', 'Raize 1.0T GR'];
        $colors = ['Super White', 'Silver Metallic', 'Gray Metallic', 'Attitude Black', 'Dark Red Mica', 'Platinum White Pearl'];

        return [
            'model_name' => $this->faker->randomElement($models),
            'vin_number' => strtoupper($this->faker->bothify('MHF1??###########')),
            'engine_number' => strtoupper($this->faker->bothify('2NR#######')),
            'color' => $this->faker->randomElement($colors),
            'production_year' => $this->faker->numberBetween(2023, 2024),
            'status' => 'available',
            'warehouse_id' => Warehouse::factory(),
        ];
    }
}