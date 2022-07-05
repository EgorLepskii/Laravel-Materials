<?php

namespace Integration\Controllers;

use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Material;
use App\Models\Tag;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MaterialIndexTest extends TestCase
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
     * @return void
     */
    public function test_index()
    {
        $material = Material::factory()->create();
        $this->get(route('material.index'))->assertSee($material->getAttribute('name'));
    }

    /**
     * Assert see only materials with tag if it will be received tag param in uri
     *
     * @return void
     */
    public function test_index_with_tag()
    {
        $material = Material::factory()->create();
        $materialWithoutTag = Material::factory()->create();
        $tag = Tag::factory()->create();
        $material->addTag($tag->getAttribute('id'));
        $response = $this->get(route('material.index', ['tag' => $tag->getAttribute('name')]));

        $response->assertSee($material->getAttribute('name'));
        $response->assertDontSee($materialWithoutTag->getAttribute('name'));
    }


    public function tearDown(): void
    {
        DB::rollBack();
        parent::tearDown();
    }
}
