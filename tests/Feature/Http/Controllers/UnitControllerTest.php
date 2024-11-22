<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\UnitController
 */
final class UnitControllerTest extends TestCase {
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void {
        $units = Unit::factory()->count(3)->create();

        $response = $this->get(route('units.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function show_behaves_as_expected(): void {
        $unit = Unit::factory()->create();

        $response = $this->get(route('units.show', $unit));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function store_uses_form_request_validation(): void {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\UnitController::class,
            'store',
            \App\Http\Requests\UnitControllerStoreRequest::class,
        );
    }

    #[Test]
    public function store_saves(): void {
        $name_en = $this->faker->word();
        $name_ar = $this->faker->word();
        $description_en = $this->faker->text();
        $description_ar = $this->faker->text();

        $response = $this->post(route('units.store'), [
            'name_en' => $name_en,
            'name_ar' => $name_ar,
            'description_en' => $description_en,
            'description_ar' => $description_ar,
        ]);

        $units = Unit::query()
            ->where('name_en', $name_en)
            ->where('name_ar', $name_ar)
            ->where('description_en', $description_en)
            ->where('description_ar', $description_ar)
            ->get();
        $this->assertCount(1, $units);
        $unit = $units->first();
    }


    #[Test]
    public function update_uses_form_request_validation(): void {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\UnitController::class,
            'update',
            \App\Http\Requests\UnitControllerUpdateRequest::class,
        );
    }

    #[Test]
    public function update_behaves_as_expected(): void {
        $unit = Unit::factory()->create();
        $name_en = $this->faker->word();
        $name_ar = $this->faker->word();
        $description_en = $this->faker->text();
        $description_ar = $this->faker->text();

        $response = $this->put(route('units.update', $unit), [
            'name_en' => $name_en,
            'name_ar' => $name_ar,
            'description_en' => $description_en,
            'description_ar' => $description_ar,
        ]);

        $unit->refresh();

        $this->assertEquals($name_en, $unit->name_en);
        $this->assertEquals($name_ar, $unit->name_ar);
        $this->assertEquals($description_en, $unit->description_en);
        $this->assertEquals($description_ar, $unit->description_ar);
    }


    #[Test]
    public function destroy_deletes(): void {
        $unit = Unit::factory()->create();

        $response = $this->delete(route('units.destroy', $unit));

        $this->assertModelMissing($unit);
    }
}
