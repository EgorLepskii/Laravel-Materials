<?php

namespace Integration\Services;

use App\Exceptions\IncorrectCollectionTypeException;
use App\Http\Controllers\MaterialController;
use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Category;
use App\Models\Material;
use App\Models\Tag;
use App\Services\MaterialCategoryReceiverService;
use App\Services\MaterialTypeReceiverService;
use App\Services\SearchService;
use Faker\Factory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SearchServiceTest extends TestCase
{
    protected \Faker\Generator $faker;

    // string, that the search object will contain
    protected string $name = "";

    // string for search
    protected string $searchSubstring = "";

    // Material, that will not be in search result
    protected ?Material $notSuitableMaterial;

    // Material, that will be in search result
    protected Material $suitableMaterial;


    protected ?Collection $searchResult = null;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(VerifyCsrfToken::class);
        $this->faker = Factory::create();
        $minNameLength = 10;
        $this->name = $this->faker->lexify(str_repeat('?', mt_rand($minNameLength, MaterialController::MAX_NAME_LENGTH)));
        $this->searchSubstring = substr($this->name, mt_rand($minNameLength, mb_strlen($this->name)), mt_rand($minNameLength, strlen($this->name)));

        DB::beginTransaction();
        $this->notSuitableMaterial = Material::factory()->create();
    }

    /**
     * @return void
     */
    public function test_search_by_name()
    {
        $this->suitableMaterial = Material::factory()->create(['name' => $this->name]);
        $service = new SearchService();
        $this->searchResult = $service->searchByMaterial($this->searchSubstring)->get();

        $this->assertTrue($this->searchResult->contains('id', $this->suitableMaterial->getAttribute('id')));
        $this->assertFalse($this->searchResult->contains('id', $this->notSuitableMaterial->getAttribute('id')));
    }

    /**
     * @return void
     */
    public function test_search_by_authors()
    {
        $this->suitableMaterial = Material::factory()->create(['authors' => $this->name]);
        $service = new SearchService();
        $this->searchResult = $service->searchByAuthors($this->searchSubstring)->get();

        $this->assertTrue($this->searchResult->contains('id', $this->suitableMaterial->getAttribute('id')));
        $this->assertFalse($this->searchResult->contains('id', $this->notSuitableMaterial->getAttribute('id')));
    }

    /**
     * @return void
     */
    public function test_search_by_tags()
    {
        $this->suitableMaterial = Material::factory()->create();
        $tag = Tag::factory()->create(['name' => $this->name]);
        $this->suitableMaterial->addTag($tag->getAttribute('id'));

        $service = new SearchService();
        $this->searchResult = $service->searchByTags($this->searchSubstring)->get();

        $this->assertTrue($this->searchResult->contains('id', $this->suitableMaterial->getAttribute('id')));
        $this->assertFalse($this->searchResult->contains('id', $this->notSuitableMaterial->getAttribute('id')));
    }

    /**
     * @return void
     */
    public function test_search_by_category()
    {
        $category = Category::factory()->create(['name' => $this->name]);
        $this->suitableMaterial = Material::factory()->create(['category_id' => $category->getAttribute('id')]);

        $service = new SearchService();
        $this->searchResult = $service->searchByCategories($this->searchSubstring)->get();

        $this->assertTrue($this->searchResult->contains('id', $this->suitableMaterial->getAttribute('id')));
        $this->assertFalse($this->searchResult->contains('id', $this->notSuitableMaterial->getAttribute('id')));
    }

    /**
     * @return void
     */
    public function test_search()
    {
        $materialWithSuitableName = Material::factory()->create(['name' => $this->name]);
        $materialWithSuitableAuthors = Material::factory()->create(['authors' => $this->name]);

        $category = Category::factory()->create(['name' => $this->name]);
        $materialWithSuitableCategory = Material::factory()->create(['category_id' => $category->getAttribute('id')]);

        $materialWithSuitableTag = Material::factory()->create();
        $tag = Tag::factory()->create(['name' => $this->name]);
        $materialWithSuitableTag->addTag($tag->getAttribute('id'));

        $service = new SearchService();

        $result = $service->search($this->searchSubstring)->get();

        $this->assertTrue($result->contains('id', $materialWithSuitableName->getAttribute('id')));
        $this->assertTrue($result->contains('id', $materialWithSuitableAuthors->getAttribute('id')));
        $this->assertTrue($result->contains('id', $materialWithSuitableCategory->getAttribute('id')));
        $this->assertTrue($result->contains('id', $materialWithSuitableTag->getAttribute('id')));
        $this->assertFalse($result->contains('id', $this->notSuitableMaterial->getAttribute('id')));
    }

    public function tearDown(): void
    {
        DB::rollBack();
        parent::tearDown();
    }
}
