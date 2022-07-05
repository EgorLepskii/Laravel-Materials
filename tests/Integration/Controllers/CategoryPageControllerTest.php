<?php

namespace Integration\Controllers;


use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CategoryPageController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TagPageController;
use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Category;
use App\Models\Tag;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Symfony\Component\DomCrawler\Crawler;
use Tests\TestCase;

class CategoryPageControllerTest extends TestCase
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
     * Assert, that in page with categories will be displayed categories in the amount of the maximum allowed value
     *
     * @return void
     */
    public function test_index_pagination()
    {
        $data = Category::factory()->count(CategoryController::MAX_SHOW_COUNT + 1)->create()->toArray();
        $response = $this->get(route('category.index'));
        $firstPageContent = $response->getContent();
        $crawler = new Crawler($firstPageContent);
        $itemsCount = $crawler->filter('.me-3')->count();

        $this->assertEquals(CategoryController::MAX_SHOW_COUNT, $itemsCount);

        $lastElem = end($data);
        // Categories are displayed in reverse order in page. lastElem - last elem, that have been added to database
        $response->assertSee($lastElem['name']);

        // $data[0] - first element, saved to database with account index, that more per unit, than
        // maximum allowed count to show. Assert, that this element will not be displayed on first page
        $response = $this->get(route('category.index', ['page' => 1]));
        $response->assertSee($data[0]['name']);
    }

    /**
     * @return void
     */
    public function test_create_page()
    {
        $this->get(route('category.create'))->assertSee('Добавить категорию');
    }

    public function tearDown(): void
    {
        DB::rollBack();
        parent::tearDown();

    }
}
