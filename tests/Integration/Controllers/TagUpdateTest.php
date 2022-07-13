<?php

namespace Integration\Controllers;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController;
use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Category;
use App\Models\Tag;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TagUpdateTest extends TestCase
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
        $tag = Tag::factory()->create();
        $this->post(
            route('tag.update', ['tag' => $tag->getAttribute('id')]),
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
                    'name' => $faker->lexify(str_repeat('?', TagController::MAX_NAME_LENGTH + 1)),
                ],
                [
                    'name'
                ]
            ],

        ];
    }

    /**
     * Test correct update. Assert, that model will be updated to database and that there will be redirect
     * to page with tags
     *
     * @return void
     */
    public function test_correct_update()
    {
        $tag = Tag::factory()->create();

        $data = [
            'name' => $this->faker->name
        ];

        $this->post(
            route('tag.update', ['tag' => $tag->getAttribute('id')]),
            $data
        );

        $updatedModel = Tag::query()->where('id', '=', $tag->getAttribute('id'))->first();
        $this->assertEquals($data['name'], $updatedModel->getAttribute('name'));
    }


    /**
     * Assert error if selected name already exists and doesn't equal to name of category, that update
     *
     * @return void
     */
    public function test_update_if_name_exists()
    {
        $tag = Tag::factory()->create();
        $existsTag = Tag::factory()->create();

        $data = [
            'name' => $existsTag->getAttribute('name'),
        ];

        $this->post(
            route('tag.update', ['tag' => $tag->getAttribute('id')]),
            $data
        )->assertSessionHasErrors('name');
    }

    public function tearDown(): void
    {
        DB::rollBack();
        parent::tearDown();
    }
}
