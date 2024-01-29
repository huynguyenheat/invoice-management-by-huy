<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'invoice_item_id'=>$this->faker->numberBetween(1,15),
            'description'=>$this->faker->text(20),
            'unit_price'=>$this->faker->numberBetween(5,200),
            'item_code'=>$this->faker->text(20)
        ];
    }
}
