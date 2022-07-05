<?php

namespace Integration\Controllers;

use App\Http\Controllers\MaterialController;
use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Category;
use App\Models\Material;
use App\Models\Type;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\MockObject\MockBuilder;
use Tests\TestCase;

class MaterialStoreTest extends TestCase
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
        $this->post(route('material.store'), $data)->assertSessionHasErrors($expectedErrors);
    }

    public function incorrectDataProvider()
    {
        $faker = Factory::create();
        return [
            'empty_name' => [
                [
                    'name' => '',
                    'description' => '',
                    'authors' => '',
                    'type_id' => 0,
                    'category_id' => 0
                ],
                [
                    'name'
                ]

            ],
            'name_over_length' => [
                [
                    'name' => $faker->lexify(str_repeat('?', MaterialController::MAX_NAME_LENGTH + 1)),
                    'description' => '',
                    'authors' => '',
                    'type_id' => 0,
                    'category_id' => 0
                ],
                [
                    'name'
                ]
            ],
            'authors_text_over_length' => [
                [
                    'name' => '',
                    'description' => '',
                    'authors' => $faker->lexify(str_repeat('?', MaterialController::MAX_AUTHORS_TEXT_LENGTH + 1)),
                    'type_id' => 0,
                    'category_id' => 0
                ],
                [
                    'authors'
                ]

            ],
            'description_text_over_length' => [
                [
                    'name' => '',
                    'description' => $faker->lexify(str_repeat('?', MaterialController::MAX_DESCRIPTION_LENGTH + 1)),
                    'authors' => '',
                    'type_id' => 0,
                    'category_id' => 0
                ],
                [
                    'description'
                ]
            ],

            'type_not_exists' => [
                [
                    'name' => '',
                    'description' => '',
                    'authors' => '',
                    'type_id' => -1,
                    'category_id' => -1
                ],
                [
                    'type_id'
                ]
            ],
            'category_not_exists' => [
                [
                    'name' => '',
                    'description' => '',
                    'authors' => '',
                    'type_id' => -1,
                    'category_id' => -1
                ],
                [
                    'category_id'
                ]
            ]

        ];
    }

    /**
     * Test correct store. Assert, that model will be saved to database and that there will be redirect
     * to page with all materials
     *
     * @return void
     */
    public function test_correct_store()
    {
        $typeId = (Type::factory()->create())->getAttribute('id');
        $categoryId = (Category::factory()->create())->getAttribute('id');

        $data = [
            'name' => $this->faker->name,
            'description' => '',
            'authors' => '',
            'type_id' => $typeId,
            'category_id' => $categoryId
        ];

        $this->post(route('material.store'), $data)->assertRedirect(route('material.index'));
        $this->assertNotEmpty(Material::query()->where('name', '=', $data['name'])->first());
    }

    /**
     * Assert error if there will be attempt to create material with name, that already exists
     *
     * @return void
     */
    public function test_store_if_name_already_exists()
    {
        $model = Material::factory()->create();

        $typeId = (Type::factory()->create())->getAttribute('id');
        $categoryId = (Category::factory()->create())->getAttribute('id');

        $data = [
            'name' => $model->getAttribute('name'),
            'description' => '',
            'authors' => '',
            'type_id' => $typeId,
            'category_id' => $categoryId
        ];

        $this->post(route('material.store'), $data)->assertSessionHasErrors('name');
    }

    public function tearDown(): void
    {
        DB::rollBack();
        parent::tearDown();
    }
}
