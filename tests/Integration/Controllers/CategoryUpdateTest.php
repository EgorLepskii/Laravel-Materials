<?php

namespace Integration\Controllers;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MaterialController;
use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Category;
use App\Models\Material;
use App\Models\Type;
use Faker\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\MockObject\MockBuilder;
use Tests\TestCase;

class CategoryUpdateTest extends TestCase
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
     * Test request validation
     *
     * @dataProvider incorrectDataProvider
     * @return       void
     */
    public function test_incorrect_data(array $data, array $expectedErrors)
    {
        $category = Category::factory()->create();
        $this->post(
            route('category.update', ['category' => $category->getAttribute('id')]),
            $data
        )->assertSessionHasErrors($expectedErrors);
    }

    public function incorrectDataProvider()
    {
        $faker = Factory::create();
        return [
            'empty_name' => [
                [
                    'name' => '',
                ],
                [
                    'name'
                ]

            ],
            'name_over_length' => [
                [
                    'name' => $faker->lexify(str_repeat('?', CategoryController::MAX_NAME_LENGTH + 1)),
                ],
                [
                    'name'
                ]
            ],

        ];
    }

    /**
     * Test correct update. Assert, that model will be updated to database and that there will be redirect
     * to page with categories
     *
     * @return void
     */
    public function test_correct_update()
    {

        $category = Category::factory()->create();

        $data = [
            'name' => $this->faker->name
        ];

        $this->post(
            route('category.update', ['category' => $category->getAttribute('id')]),
            $data
        );

        $updatedModel = Category::query()->where('id', '=', $category->getAttribute('id'))->first();
        $this->assertEquals($data['name'], $updatedModel->getAttribute('name'));
    }


    /**
     * Assert error if selected name already exists and doesn't equal to name of category, that update
     *
     * @return void
     */
    public function test_update_if_name_exists()
    {
        $category = Category::factory()->create();
        $existsCategory = Category::factory()->create();

        $data = [
            'name' => $existsCategory->getAttribute('name'),
        ];

        $this->post(
            route('category.update', ['category' => $category->getAttribute('id')]),
            $data
        )->assertSessionHasErrors('name');
    }

    public function tearDown(): void
    {
        DB::rollBack();
        parent::tearDown();
    }
}
