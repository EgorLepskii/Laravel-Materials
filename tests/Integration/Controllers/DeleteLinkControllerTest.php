<?php

namespace Integration\Controllers;

use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Link;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DeleteLinkControllerTest extends TestCase
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
     * Assert, that link will be deleted
     *
     * @return void
     */
    public function test_delete()
    {
        $link = Link::factory()->create();
        $this->delete(route('link.destroy', ['link' => $link->getAttribute('id')]));
        $this->assertDatabaseMissing('materials', ['id' => $link->getAttribute('id')]);
    }

    public function tearDown(): void
    {
        DB::rollBack();
        parent::tearDown();
    }
}
