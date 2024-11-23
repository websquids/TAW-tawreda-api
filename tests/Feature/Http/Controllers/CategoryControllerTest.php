<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\CategoryController
 */
final class CategoryControllerTest extends TestCase {
  use AdditionalAssertions, RefreshDatabase, WithFaker;

  #[Test]
  public function index_behaves_as_expected(): void {
    $categories = Category::factory()->count(3)->create();

    $response = $this->get(route('categories.index'));

    $response->assertOk();
    $response->assertJsonStructure([]);
  }


  #[Test]
  public function show_behaves_as_expected(): void {
    $category = Category::factory()->create();

    $response = $this->get(route('categories.show', $category));

    $response->assertOk();
    $response->assertJsonStructure([]);
  }


  #[Test]
  public function store_uses_form_request_validation(): void {
    $this->assertActionUsesFormRequest(
      \App\Http\Controllers\CategoryController::class,
      'store',
      \App\Http\Requests\CategoryStoreRequest::class,
    );
  }

  #[Test]
  public function store_saves(): void {
    $name_en = $this->faker->word();
    $name_ar = $this->faker->word();
    $description_en = $this->faker->text();
    $description_ar = $this->faker->text();
    $parent_id = $this->faker->word();

    $response = $this->post(route('categories.store'), [
        'name_en' => $name_en,
        'name_ar' => $name_ar,
        'description_en' => $description_en,
        'description_ar' => $description_ar,
        'parent_id' => $parent_id,
    ]);

    $categories = Category::query()
        ->where('name_en', $name_en)
        ->where('name_ar', $name_ar)
        ->where('description_en', $description_en)
        ->where('description_ar', $description_ar)
        ->where('parent_id', $parent_id)
        ->get();
    $this->assertCount(1, $categories);
    $category = $categories->first();
  }


  #[Test]
  public function update_uses_form_request_validation(): void {
    $this->assertActionUsesFormRequest(
      \App\Http\Controllers\CategoryController::class,
      'update',
      \App\Http\Requests\CategoryUpdateRequest::class,
    );
  }

  #[Test]
  public function update_behaves_as_expected(): void {
    $category = Category::factory()->create();
    $name_en = $this->faker->word();
    $name_ar = $this->faker->word();
    $description_en = $this->faker->text();
    $description_ar = $this->faker->text();
    $parent_id = $this->faker->word();

    $response = $this->put(route('categories.update', $category), [
        'name_en' => $name_en,
        'name_ar' => $name_ar,
        'description_en' => $description_en,
        'description_ar' => $description_ar,
        'parent_id' => $parent_id,
    ]);

    $category->refresh();

    $this->assertEquals($name_en, $category->name_en);
    $this->assertEquals($name_ar, $category->name_ar);
    $this->assertEquals($description_en, $category->description_en);
    $this->assertEquals($description_ar, $category->description_ar);
    $this->assertEquals($parent_id, $category->parent_id);
  }


  #[Test]
  public function destroy_deletes(): void {
    $category = Category::factory()->create();

    $response = $this->delete(route('categories.destroy', $category));

    $this->assertModelMissing($category);
  }
}
