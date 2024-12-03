<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Brand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\BrandController
 */
final class BrandControllerTest extends TestCase {
  use AdditionalAssertions, RefreshDatabase, WithFaker;

  #[Test]
  public function index_responds_with(): void {
    $brands = Brand::factory()->count(3)->create();

    $response = $this->get(route('brands.index'));

    $response->assertOk();
    $response->assertJson($json);
    $response->assertOk();
    $response->assertJsonStructure([]);
  }


  #[Test]
  public function show_responds_with(): void {
    $brand = Brand::factory()->create();

    $response = $this->get(route('brands.show', $brand));

    $response->assertOk();
    $response->assertJson($json);
    $response->assertOk();
    $response->assertJsonStructure([]);
  }


  #[Test]
  public function store_uses_form_request_validation(): void {
    $this->assertActionUsesFormRequest(
      \App\Http\Controllers\BrandController::class,
      'store',
      \App\Http\Requests\BrandStoreRequest::class,
    );
  }

  #[Test]
  public function store_saves_and_responds_with(): void {
    $name_en = $this->faker->word();
    $name_ar = $this->faker->word();
    $description_en = $this->faker->text();
    $description_ar = $this->faker->text();

    $response = $this->post(route('brands.store'), [
      'name_en' => $name_en,
      'name_ar' => $name_ar,
      'description_en' => $description_en,
      'description_ar' => $description_ar,
    ]);

    $brands = Brand::query()
        ->where('name_en', $name_en)
        ->where('name_ar', $name_ar)
        ->where('description_en', $description_en)
        ->where('description_ar', $description_ar)
        ->get();
    $this->assertCount(1, $brands);
    $brand = $brands->first();

    $response->assertOk();
    $response->assertJson($json);
  }


  #[Test]
  public function update_uses_form_request_validation(): void {
    $this->assertActionUsesFormRequest(
      \App\Http\Controllers\BrandController::class,
      'update',
      \App\Http\Requests\BrandUpdateRequest::class,
    );
  }

  #[Test]
  public function update_responds_with(): void {
    $brand = Brand::factory()->create();
    $name_en = $this->faker->word();
    $name_ar = $this->faker->word();
    $description_en = $this->faker->text();
    $description_ar = $this->faker->text();

    $response = $this->put(route('brands.update', $brand), [
      'name_en' => $name_en,
      'name_ar' => $name_ar,
      'description_en' => $description_en,
      'description_ar' => $description_ar,
    ]);

    $brand->refresh();

    $response->assertOk();
    $response->assertJson($json);

    $this->assertEquals($name_en, $brand->name_en);
    $this->assertEquals($name_ar, $brand->name_ar);
    $this->assertEquals($description_en, $brand->description_en);
    $this->assertEquals($description_ar, $brand->description_ar);
  }


  #[Test]
  public function destroy_deletes_and_responds_with(): void {
    $brand = Brand::factory()->create();

    $response = $this->delete(route('brands.destroy', $brand));

    $response->assertOk();
    $response->assertJson($json);

    $this->assertModelMissing($brand);
  }
}
