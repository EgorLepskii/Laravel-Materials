<?php

namespace Integration\Controllers;

use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Material;
use App\Models\Tag;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Monolog\Test\TestCase;

class TagManageDeleteTest extends \Tests\TestCase
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
     * @return void
     */
    public function test_delete()
    {
        $material = Material::factory()->create();
        $tag = Tag::factory()->create();
        $material->addTag($tag->getAttribute('id'));

        $entryId = DB::table('materials_tags')->select('id')
            ->where('tag_id', '=', $tag->getAttribute('id'))
            ->where('material_id', '=', $material->getAttribute('id'))
            ->first();

        $this->delete(route('tagManage.destroy', ['entryid' => $entryId]));


        $this->assertEmpty(
            DB::table('materials_tags')->select('id')
                ->where('tag_id', '=', $tag->getAttribute('id'))
                ->where('material_id', '=', $material->getAttribute('id'))
                ->first()
        );
    }

    public function tearDown(): void
    {
        DB::rollBack();
        parent::tearDown();
    }
}
