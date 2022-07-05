<?php

namespace Integration\Services;

use App\Exceptions\IncorrectCollectionTypeException;
use App\Models\Material;
use App\Models\Tag;
use App\Services\MaterialCategoryReceiverService;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;
use App\Http\Middleware\VerifyCsrfToken;
use Faker\Factory;
use Illuminate\Support\Facades\DB;

class MaterialCategoryReceiverTest extends TestCase
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
     * @throws IncorrectCollectionTypeException
     */
    public function test_correct()
    {
        $testCount = 10;
        $materials = Material::factory()->count($testCount)->create();
        $service = new MaterialCategoryReceiverService();
        $categories = $service->receive($materials);

        foreach ($categories as $key => $category) {
            $this->assertEquals($materials[$key]['category_id'], $category['id']);
        }
    }

    /**
     * @throws IncorrectCollectionTypeException
     */
    public function test_incorrect_collection_type()
    {
        $this->expectException(IncorrectCollectionTypeException::class);
        $service = new MaterialCategoryReceiverService();
        Tag::factory()->create();
        $service->receive(Tag::query()->get());
    }


    public function tearDown(): void
    {
        DB::rollBack();
        parent::tearDown();
    }
}
