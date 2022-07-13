<?php

namespace Integration\Controllers;

use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Category;
use App\Models\Material;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CategoryEditTest extends TestCase
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
     * Assert, that update page will contain all category data
     *
     * @return void
     */
    public function test_show()
    {
        $category = Category::factory()->create();
        $response = $this->get(route('category.edit', ['category' => $category->getAttribute('id')]));

        $response->assertSee($category->getAttribute('name'));
    }

    public function tearDown(): void
    {
        DB::rollBack();
        parent::tearDown();
    }
}
