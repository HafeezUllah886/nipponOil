<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\product>
 */
class productFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->realText(maxNbChars:30),
            'code' => $this->faker->numberBetween(1000, 9999),
            'defaultBatch' => "def-" . $this->faker->numberBetween(1000,9999),
            'brandID' => $this->faker->numberBetween(1,2),
            'categoryID' => $this->faker->numberBetween(1,2),
            'isExpire' => 1,
            'productUnit' => $this->faker->numberBetween(1,3),
            'purchasePrice' => $this->faker->numberBetween(100,1000),
            'salePrice' => $this->faker->numberBetween(100,1000),
            'wholeSalePrice' => $this->faker->numberBetween(100,1000),
            'alertQuantity' => $this->faker->numberBetween(20,100),
            'commission' => $this->faker->numberBetween(0,30),
            'description' => $this->faker->sentence(),
            'image' => $this->faker->imageUrl(200,200),
            'createdBy' => "System@email.com",
        ];
    }
}
