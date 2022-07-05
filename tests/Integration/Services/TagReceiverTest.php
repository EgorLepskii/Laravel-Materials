<?php

namespace Integration\Services;

use App\Exceptions\IncorrectCollectionTypeException;
use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Material;
use App\Models\Tag;
use App\Services\MaterialTypeReceiverService;
use App\Services\TagsReceiverService;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TagReceiverTest extends TestCase
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
     * Assert, that function will return only tags, that are not linked to material
     *
     * @return void
     */
    public function test_tags()
    {
        $testCount = 2;
        $material = Material::factory()->create();
        $tags = Tag::factory()->count($testCount)->create();

        $tagForLink = Tag::factory()->create();
        $material->addTag($tagForLink->getAttribute('id'));

        $service = new TagsReceiverService();
        $output = $service->receive($material);

        foreach ($tags as $tag)
        {
            $this->assertTrue($output->contains('id', $tag->getAttribute('id')));
        }

        $this->assertFalse($output->contains('id', $tagForLink->getAttribute('id')));

    }


    public function tearDown(): void
    {
        DB::rollBack();
        parent::tearDown();
    }
}
