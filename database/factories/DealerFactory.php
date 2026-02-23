<?php

namespace Database\Factories;

use App\Models\Dealer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Dealer>
 */
class DealerFactory extends Factory
{
    protected $model = Dealer::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $regions = ['Jabodetabek', 'Jawa Barat', 'Jawa Tengah', 'Jawa Timur', 'Sumatera', 'Sulawesi', 'Kalimantan'];

        return [
            'name' => 'Toyota ' . $this->faker->city() . ' ' . $this->faker->streetName(),
            'address' => $this->faker->address(),
            'region' => $this->faker->randomElement($regions),
        ];
    }
}