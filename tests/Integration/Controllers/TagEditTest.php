<?php

namespace Integration\Controllers;

use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Category;
use App\Models\Material;
use App\Models\Tag;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TagEditTest extends TestCase
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
        $tag = Tag::factory()->create();
        $response = $this->get(route('tag.edit', ['tag' => $tag->getAttribute('id')]));

        $response->assertSee($tag->getAttribute('name'));
    }

    public function tearDown(): void
    {
        DB::rollBack();
        parent::tearDown();
    }
}
