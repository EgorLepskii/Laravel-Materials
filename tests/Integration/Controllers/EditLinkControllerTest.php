<?php

namespace Integration\Controllers;

use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Link;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class EditLinkControllerTest extends TestCase
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
     * Assert see in the edit page data with link sign and url
     *
     * @return void
     */
    public function test_edit()
    {
        $link = Link::factory()->create();
        $response = $this->get(route('link.edit', ['link' => $link->getAttribute('id')]));
        $response->assertSee($link->getAttribute('sign'));
        $response->assertSee($link->getAttribute('url'));
    }

    public function tearDown(): void
    {
        DB::rollBack();
        parent::tearDown();
    }
}
