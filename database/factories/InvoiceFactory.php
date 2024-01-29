<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id'=>$this->faker->numberBetween(1,10),
            'date'=>$this->faker->dateTimeBetween('2020-01-01', 'now')->format('Y-m-d'),
            'due_date'=>$this->faker->dateTimeBetween('now', '2025-01-01')->format('Y-m-d'),
            'number'=>$this->faker->numberBetween(1,10),
            'reference'=>$this->faker->text(20),
            'terms'=>$this->faker->text(20),
            'sub_total'=>$this->faker->numberBetween(20,1000),
            'discount'=>$this->faker->numberBetween(1,10),
            'total'=>$this->faker->numberBetween(20,2000)
        ];
    }
}
