<?php

namespace Database\Factories;

use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Warehouse>
 */
class WarehouseFactory extends Factory
{
    protected $model = Warehouse::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $warehouses = [
            ['name' => 'Main Gateway Warehouse - Jakarta', 'location' => 'Marunda, Jakarta Utara'],
            ['name' => 'Transit Hub West - Tangerang', 'location' => 'Batuceper, Tangerang'],
            ['name' => 'Distribution Center Central - Semarang', 'location' => 'Kawasan Industri Candi, Semarang'],
            ['name' => 'East Java Logistics Park - Surabaya', 'location' => 'Gresik, Jawa Timur'],
        ];

        $warehouse = $this->faker->randomElement($warehouses);

        return [
            'name' => $warehouse['name'],
            'location' => $warehouse['location'],
        ];
    }
}