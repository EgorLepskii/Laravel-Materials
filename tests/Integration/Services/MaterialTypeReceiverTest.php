<?php

namespace Integration\Services;

use App\Exceptions\IncorrectCollectionTypeException;
use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Material;
use App\Models\Tag;
use App\Services\MaterialCategoryReceiverService;
use App\Services\MaterialTypeReceiverService;
use Faker\Factory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MaterialTypeReceiverTest extends TestCase
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
        $service = new MaterialTypeReceiverService();
        $types = $service->receive($materials);

        foreach ($types as $key => $type) {
            $this->assertEquals($materials[$key]['type_id'], $type['id']);
        }
    }

    /**
     * @throws IncorrectCollectionTypeException
     */
    public function test_incorrect_collection_type()
    {
        $this->expectException(IncorrectCollectionTypeException::class);
        $service = new MaterialTypeReceiverService();
        Tag::factory()->create();
        $service->receive(Tag::query()->get());
    }


    public function tearDown(): void
    {
        DB::rollBack();
        parent::tearDown();
    }
}
