<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

class CategoryFactory extends Factory {
  /**
   * The name of the factory's corresponding model.
   *
   * @var string
   */
  protected $model = Category::class;

  /**
   * Define the model's default state.
   */
  public function definition(): array {
    return [
      'name_en' => $this->faker->word(),
      'name_ar' => $this->faker->word(),
      'description_en' => $this->faker->text(),
      'description_ar' => $this->faker->text(),
      'parent_id' => $this->faker->word(),
    ];
  }
}
