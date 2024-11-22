<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Unit;

class ProductFactory extends Factory {
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array {
        return [
            'title_en' => $this->faker->word(),
            'title_ar' => $this->faker->word(),
            'description_en' => $this->faker->text(),
            'description_ar' => $this->faker->text(),
            'price' => $this->faker->randomFloat(2, 0, 999999.99),
            'discount' => $this->faker->randomFloat(2, 0, 999999.99),
            'current_stock_quantity' => $this->faker->numberBetween(-10000, 10000),
            'category_id' => Category::factory(),
            'brand_id' => Brand::factory(),
            'unit_id' => Unit::factory(),
            'min_order_quantity' => $this->faker->numberBetween(-10000, 10000),
            'max_order_quantity' => $this->faker->numberBetween(-10000, 10000),
            'min_storage_quantity' => $this->faker->numberBetween(-10000, 10000),
            'max_storage_quantity' => $this->faker->numberBetween(-10000, 10000),
        ];
    }
}
