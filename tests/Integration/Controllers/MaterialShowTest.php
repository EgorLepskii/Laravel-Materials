<?php

namespace Integration\Controllers;

use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Material;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MaterialShowTest extends TestCase
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
     * Assert see material data in the material page
     *
     * @return void
     */
    public function test_show()
    {
        $material = Material::factory()->create();
        $response = $this->get(route('material.show', ['material' => $material->getAttribute('id')]));

        $response->assertSee($material->getAttribute('name'));
        $response->assertSee($material->getAttribute('authors'));
        $response->assertSee($material->getAttribute('description'));
        $response->assertSee($material->type()->first()->getAttribute('name'));
        $response->assertSee($material->category()->first()->getAttribute('name'));
    }


    public function tearDown(): void
    {
        DB::rollBack();
        parent::tearDown();
    }
}
