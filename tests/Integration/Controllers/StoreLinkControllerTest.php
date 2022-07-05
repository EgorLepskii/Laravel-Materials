<?php

namespace Integration\Controllers;

use App\Http\Middleware\VerifyCsrfToken;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\DocBlock\Tags\Link;
use Tests\TestCase;

class StoreLinkControllerTest extends TestCase
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
     * Assert error during creating link if material does not exist
     *
     * @return void
     */
    public function test_if_material_not_exists()
    {
        $data = ['sign' => '', 'url' => '', 'material_id' => -1];

        $this->post(route('link.store', $data))->assertSessionHasErrors('material_id');
    }

    /**
     * Assert error, if it will be attempted to create link with the name, that already exists for current material
     *
     * @return void
     */
    public function test_if_name_already_exists()
    {
        $link = \App\Models\Link::factory()->create();
        $duplicateLink = \App\Models\Link::factory()->create(['material_id' => $link->getAttribute('material_id')]);

        $data =
            [
                'sign' => $duplicateLink->getAttribute('sign'),
                'url' => $this->faker->url,
                'material_id' => $link->getAttribute('material_id')
            ];

        $this->post(route('link.store', $data))->assertSessionHasErrors('sign');
    }

    /**
     * Assert, that it is possible to create link with the name, that already exists in database form another material
     *
     * @return void
     */
    public function test_duplicate_name_for_another_material()
    {
        $link = \App\Models\Link::factory()->create();
        $duplicateLink = \App\Models\Link::factory()->create();

        $data =
            [
                'sign' => $duplicateLink->getAttribute('sign'),
                'url' => $this->faker->url,
                'material_id' => $link->getAttribute('material_id')
            ];

        $this->post(route('link.store', $data))->assertSessionHasNoErrors();
        $this->assertDatabaseHas('links', ['id' => $link->getAttribute('id')]);
    }


    public function tearDown(): void
    {
        DB::rollBack();
        parent::tearDown();
    }
}
