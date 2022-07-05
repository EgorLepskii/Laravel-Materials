<?php

namespace Integration\Controllers;


use App\Http\Controllers\TagController;
use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Tag;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TagDestroyControllerTest extends TestCase
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
     * Assert, that tag will be deleted
     *
     * @return void
     */
    public function test_delete()
    {
        $tag = Tag::factory()->create();
        $this->delete(route('tag.destroy', ['tag' => $tag->getAttribute('id')]));
        $this->assertDatabaseMissing('tags', ['name' => $tag->getAttribute('name')]);
    }

    public function tearDown(): void
    {
        DB::rollBack();
        parent::tearDown();
    }
}
