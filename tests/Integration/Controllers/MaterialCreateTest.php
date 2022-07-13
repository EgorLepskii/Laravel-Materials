<?php

namespace Integration\Controllers;

use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Type;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MaterialCreateTest extends TestCase
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
     * Assert, that page will contain all types and categories in form select options
     *
     * @return void
     */
    public function test_page()
    {
        Type::factory()->create()->toArray();
        Category::factory()->create();

        $response = $this->get(route('material.create'));

        foreach (Type::all() as $type) {
            $response->assertSee($type->getAttribute('name'));
        }

        foreach (Category::all() as $category) {
            $response->assertSee($category->getAttribute('name'));
        }
    }

    public function tearDown(): void
    {
        DB::rollBack();
        parent::tearDown();
    }
}
