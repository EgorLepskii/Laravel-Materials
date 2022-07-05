<?php

namespace Integration\Controllers;


use App\Http\Controllers\TagController;
use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Category;
use App\Models\Material;
use App\Models\Tag;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CategoryDestroyControllerTest extends TestCase
{
    protected $faker;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(VerifyCsrfToken::class);
        $this->faker = Factory::create();
        DB::beginTransaction();
    }

    /**
     * Assert, that category will be deleted and materials with this category will be deleted
     *
     * @return void
     */
    public function test_delete()
    {
        $material = Material::factory()->create();
        $this->delete(route('category.destroy', ['category' => $material->getAttribute('category_id')]));
        $this->assertDatabaseMissing('categories', ['id' => $material->getAttribute('category_id')]);
        $this->assertDatabaseMissing('materials', ['id' => $material->getAttribute('id')]);
    }

    public function tearDown(): void
    {
        DB::rollBack();
        parent::tearDown();
    }
}
