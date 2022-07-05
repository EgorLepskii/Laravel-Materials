<?php

namespace Integration\Model;


use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Category;
use App\Models\MaterialTag;
use App\Models\Material;
use App\Models\Tag;
use App\Models\Type;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MaterialTest extends TestCase
{
    protected \Faker\Generator $faker;
    protected Material $material;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(VerifyCsrfToken::class);
        $this->faker = Factory::create();
        DB::beginTransaction();
    }


    /**
     * Test material and category hasOne relation
     *
     * @return void
     */
    public function test_category_relation()
    {
        $material = Material::factory()->create();
        $category = Category::query()->where('id', '=', $material->getAttribute('category_id'))->first();

        $this->assertTrue($category->is($material->category()->first()));
    }

    /**
     * Test material and type hasOne relation
     *
     * @return void
     */
    public function test_type_relation()
    {
        $material = Material::factory()->create();
        $type = Type::query()->where('id', '=', $material->getAttribute('type_id'))->first();

        $this->assertTrue($type->is($material->type()->first()));
    }

    /**
     * Test material and tag belongsToMany relation
     *
     * @return void
     */
    public function test_tag_relation()
    {
        $tagsTestCount = 10;
        $tags = Tag::factory()->count($tagsTestCount)->create();
        $materials = Material::factory()->create();

        foreach ($tags as $tag) {
            DB::table('materials_tags')
                ->insert(
                    ['material_id' => $materials->getAttribute('id'),
                    'tag_id' => $tag->getAttribute('id')]
                );
        }

        foreach ($materials->tags()->get() as $materialTag) {
            $this->assertTrue($tags->contains('name', $materialTag->getAttribute('name')));
        }

    }

    /**
     * Assert, that will be created entry in materials_tags table for manyToMany relation
     *
     * @return void
     */
    public function test_add_tag()
    {
        $material = Material::factory()->create();
        $tag = Tag::factory()->create();

        $material->addTag($tag->getAttribute('id'));

        $data = DB::table('materials_tags')
            ->select('*')
            ->where('tag_id', '=', $tag->getAttribute('id'))
            ->where('material_id', '=', $material->getAttribute('id'))
            ->first();

        $this->assertEquals($data->tag_id, $tag->getAttribute('id'));
        $this->assertEquals($data->material_id, $material->getAttribute('id'));
    }

    public function test_linked_tags_receive()
    {
        $linkedTag = MaterialTag::factory()->create();
        $this->material = Material::query()
            ->where('id', '=', $linkedTag->getAttribute('material_id'))
            ->first() ?? new Material();

        $tags  = $this->material->materialsTags()->get();
        $this->assertTrue($tags->contains('id', $linkedTag->getAttribute('id')));

    }

    public function tearDown(): void
    {
        DB::rollBack();
        parent::tearDown();
    }
}
