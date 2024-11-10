<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\ProductController
 */
final class ProductControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $products = Product::factory()->count(3)->create();

        $response = $this->get(route('products.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function show_responds_with(): void
    {
        $product = Product::factory()->create();

        $response = $this->get(route('products.show', $product));

        $response->assertOk();
        $response->assertJson($response()->json($product));
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ProductController::class,
            'store',
            \App\Http\Requests\ProductControllerStoreRequest::class
        );
    }

    #[Test]
    public function store_saves(): void
    {
        $title_en = $this->faker->word();
        $title_ar = $this->faker->word();
        $description_en = $this->faker->text();
        $description_ar = $this->faker->text();
        $price = $this->faker->randomFloat(/** decimal_attributes **/);
        $discount = $this->faker->randomFloat(/** decimal_attributes **/);
        $current_stock_quantity = $this->faker->numberBetween(-10000, 10000);
        $category = Category::factory()->create();
        $brand = Brand::factory()->create();
        $unit = Unit::factory()->create();
        $min_order_quantity = $this->faker->numberBetween(-10000, 10000);
        $max_order_quantity = $this->faker->numberBetween(-10000, 10000);
        $min_storage_quantity = $this->faker->numberBetween(-10000, 10000);
        $max_storage_quantity = $this->faker->numberBetween(-10000, 10000);

        $response = $this->post(route('products.store'), [
            'title_en' => $title_en,
            'title_ar' => $title_ar,
            'description_en' => $description_en,
            'description_ar' => $description_ar,
            'price' => $price,
            'discount' => $discount,
            'current_stock_quantity' => $current_stock_quantity,
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'unit_id' => $unit->id,
            'min_order_quantity' => $min_order_quantity,
            'max_order_quantity' => $max_order_quantity,
            'min_storage_quantity' => $min_storage_quantity,
            'max_storage_quantity' => $max_storage_quantity,
        ]);

        $products = Product::query()
            ->where('title_en', $title_en)
            ->where('title_ar', $title_ar)
            ->where('description_en', $description_en)
            ->where('description_ar', $description_ar)
            ->where('price', $price)
            ->where('discount', $discount)
            ->where('current_stock_quantity', $current_stock_quantity)
            ->where('category_id', $category->id)
            ->where('brand_id', $brand->id)
            ->where('unit_id', $unit->id)
            ->where('min_order_quantity', $min_order_quantity)
            ->where('max_order_quantity', $max_order_quantity)
            ->where('min_storage_quantity', $min_storage_quantity)
            ->where('max_storage_quantity', $max_storage_quantity)
            ->get();
        $this->assertCount(1, $products);
        $product = $products->first();
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ProductController::class,
            'update',
            \App\Http\Requests\ProductControllerUpdateRequest::class
        );
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $product = Product::factory()->create();
        $title_en = $this->faker->word();
        $title_ar = $this->faker->word();
        $description_en = $this->faker->text();
        $description_ar = $this->faker->text();
        $price = $this->faker->randomFloat(/** decimal_attributes **/);
        $discount = $this->faker->randomFloat(/** decimal_attributes **/);
        $current_stock_quantity = $this->faker->numberBetween(-10000, 10000);
        $category = Category::factory()->create();
        $brand = Brand::factory()->create();
        $unit = Unit::factory()->create();
        $min_order_quantity = $this->faker->numberBetween(-10000, 10000);
        $max_order_quantity = $this->faker->numberBetween(-10000, 10000);
        $min_storage_quantity = $this->faker->numberBetween(-10000, 10000);
        $max_storage_quantity = $this->faker->numberBetween(-10000, 10000);

        $response = $this->put(route('products.update', $product), [
            'title_en' => $title_en,
            'title_ar' => $title_ar,
            'description_en' => $description_en,
            'description_ar' => $description_ar,
            'price' => $price,
            'discount' => $discount,
            'current_stock_quantity' => $current_stock_quantity,
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'unit_id' => $unit->id,
            'min_order_quantity' => $min_order_quantity,
            'max_order_quantity' => $max_order_quantity,
            'min_storage_quantity' => $min_storage_quantity,
            'max_storage_quantity' => $max_storage_quantity,
        ]);

        $product->refresh();

        $this->assertEquals($title_en, $product->title_en);
        $this->assertEquals($title_ar, $product->title_ar);
        $this->assertEquals($description_en, $product->description_en);
        $this->assertEquals($description_ar, $product->description_ar);
        $this->assertEquals($price, $product->price);
        $this->assertEquals($discount, $product->discount);
        $this->assertEquals($current_stock_quantity, $product->current_stock_quantity);
        $this->assertEquals($category->id, $product->category_id);
        $this->assertEquals($brand->id, $product->brand_id);
        $this->assertEquals($unit->id, $product->unit_id);
        $this->assertEquals($min_order_quantity, $product->min_order_quantity);
        $this->assertEquals($max_order_quantity, $product->max_order_quantity);
        $this->assertEquals($min_storage_quantity, $product->min_storage_quantity);
        $this->assertEquals($max_storage_quantity, $product->max_storage_quantity);
    }


    #[Test]
    public function destroy_deletes(): void
    {
        $product = Product::factory()->create();

        $response = $this->delete(route('products.destroy', $product));

        $this->assertModelMissing($product);
    }
}
