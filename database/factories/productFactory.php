<?php

namespace Database\Factories;

use App\Models\Product;
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
    protected $model = Product::class;
    public function definition(): array
    {
        return [
            'name' => $this->faker->realText(maxNbChars:30),
            'code' => $this->faker->numberBetween(1000, 9999),
            'defaultBatch' => "def-" . $this->faker->numberBetween(1000,9999),
            'brandID' => $this->faker->numberBetween(1,2),
            'categoryID' => $this->faker->numberBetween(1,2),
            'isExpire' => 1,
            'status' => 1,
            'productUnit' => $this->faker->numberBetween(1,3),
            'grade' => $this->faker->numberBetween(100,100000),
            'ltr' => $this->faker->numberBetween(1 ,10),
            'alertQuantity' => $this->faker->numberBetween(20,100),
            'image' => $this->faker->imageUrl(200,200),
            'createdBy' => "System@email.com",
            /* 'prices' => function () {
                return [
                    [
                        'title' => "Retail",
                        'price' => $this->faker->numberBetween(500, 10000),
                    ],
                ];
            }, */
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Product $product) {
            // Assuming you have a relationship between Product and ProductPrices
            $product->prices()->create([
                'title' => "Retail",
                'price' => $this->faker->numberBetween(500, 10000),
            ]);
        });
    }
}
