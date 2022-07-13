<?php

namespace Integration\Controllers;

use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Material;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\DocBlock\Tag;
use Tests\TestCase;
use Faker\Factory;

class TagManageStoreTest extends TestCase
{
    protected \Faker\Generator $faker;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(VerifyCsrfToken::class);
        $this->faker = Factory::create();
        DB::beginTransaction();
    }


    /**
     * Assert error, if it will be attempt try to add tag, that does not exist
     *
     * @return void
     */
    public function test_if_tag_not_exists()
    {
        $material = Material::factory()->create();

        $this->post(route('tagManage.store'), ['tag' => -1, 'materialId' => $material->getAttribute('id')])
            ->assertSessionHasErrors('tag');
    }

    /**
     * Assert error, if it will be attempted to add tag to material that already exists
     *
     * @return void
     */
    public function test_if_tag_and_material_already_exists()
    {
        $material = Material::factory()->create();
        $tag = \App\Models\Tag::factory()->create();
        DB::table('materials_tags')
            ->insert(['material_id' => $material->getAttribute('id'), 'tag_id' => $tag->getAttribute('id')]);

        $this->post(
            route('tagManage.store'),
            ['tag' => $tag->getAttribute('id'), 'materialId' => $material->getAttribute('id')]
        )->assertSessionHasErrors('tag');
    }

    /**
     * @return void
     */
    public function test_correct_store()
    {
        $material = Material::factory()->create();
        $tag = \App\Models\Tag::factory()->create();

        $this->post(
            route('tagManage.store'),
            ['tag' => $tag->getAttribute('id'), 'materialId' => $material->getAttribute('id')]
        );


        $this->assertNotEmpty(
            DB::table('materials_tags')
                ->select('*')
                ->where('tag_id', '=', $tag->getAttribute('id'))
                ->where('material_id', '=', $material->getAttribute('id'))
                ->first()
        );
    }


    /**
     * @return void
     */
    public function test_create_page()
    {
        $this->get(route('tag.create'))->assertSee('Добавить тег');
    }

    public function tearDown(): void
    {
        DB::rollBack();
        parent::tearDown();
    }
}
