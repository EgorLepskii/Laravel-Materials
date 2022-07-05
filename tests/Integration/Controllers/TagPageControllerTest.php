<?php

namespace Integration\Controllers;


use App\Http\Controllers\TagController;
use App\Http\Controllers\TagPageController;
use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Tag;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Symfony\Component\DomCrawler\Crawler;
use Tests\TestCase;

class TagPageControllerTest extends TestCase
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
     * Assert, that in page with tags will be displayed tags in the amount of the maximum allowed value
     *
     * @return void
     */
    public function test_index_pagination()
    {
        $data = Tag::factory()->count(TagController::MAX_SHOW_COUNT + 1)->create()->toArray();
        $response = $this->get(route('tag.index'));
        $firstPageContent = $response->getContent();
        $crawler = new Crawler($firstPageContent);
        $itemsCount = $crawler->filter('.me-3')->count();

        $this->assertEquals(TagController::MAX_SHOW_COUNT, $itemsCount);
        $lastElem = end($data);

        // Tags are displayed in reverse order in page. lastElem - last elem, that have been added to database
        $response->assertSee($lastElem['name']);

        $response = $this->get(route('tag.index', ['page' => 1]));

        // $data[0] - first element, saved to database with account index, that more per unit, than
        // maximum allowed count to show. Assert, that this element will not be displayed on first page
        $response->assertSee($data[0]['name']);


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
