<?php

namespace Integration\Controllers;

use App\Http\Middleware\VerifyCsrfToken;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\DocBlock\Tags\Link;
use Tests\TestCase;

class UpdateLinkControllerTest extends TestCase
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
     * Assert error during updating link if material does not exist
     *
     * @return void
     */
    public function test_if_material_not_exists()
    {
        $link = \App\Models\Link::factory()->create();
        $data = ['sign' => '', 'url' => '', 'material_id' => -1];

        $this->post(
            route('link.update', ['link' => $link->getAttribute('id')]),
            $data
        )->assertSessionHasErrors('materialId');
    }

    /**
     * Assert error, if it will be attempted to update link with the name, that already exists for current material
     *
     * @return void
     */
    public function test_if_name_already_exists()
    {
        $link = \App\Models\Link::factory()->create();
        $duplicateLink = \App\Models\Link::factory()->create(['material_id' => $link->getAttribute('material_id')]);

        $data =
            [
                'signUpdate' => $duplicateLink->getAttribute('sign'),
                'url' => $this->faker->url,
                'materialId' => $link->getAttribute('material_id')
            ];

        $this->post(route('link.update', ['link' => $link->getAttribute('id')]), $data)
            ->assertSessionHasErrors('signUpdate');
    }

    /**
     * Assert, that it is possible to update link with the name, that already exists in database form another material
     *
     * @return void
     */
    public function test_duplicate_name_for_another_material()
    {
        $link = \App\Models\Link::factory()->create();
        $duplicateLink = \App\Models\Link::factory()->create();

        $data =
            [
                'signUpdate' => $duplicateLink->getAttribute('sign'),
                'urlUpdate' => $this->faker->url,
                'materialId' => $link->getAttribute('material_id')
            ];

        $this->post(route('link.update', ['link' => $link->getAttribute('id')]), $data)
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas(
            'links',
            ['sign' => $data['signUpdate'],'url' => $data['urlUpdate']]
        );
    }


    public function tearDown(): void
    {
        DB::rollBack();
        parent::tearDown();
    }
}
