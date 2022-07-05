<?php

namespace Integration\Controllers;

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

class MaterialUpdateTest extends TestCase
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
        $material = Material::factory()->create();
        $this->post(
            route('material.update', ['material' => $material->getAttribute('id')]),
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
     * Test correct update. Assert, that model will be updated to database and that there will be redirect
     * to page with material
     *
     * @return void
     */
    public function test_correct_update()
    {
        $typeId = (Type::factory()->create())->getAttribute('id');
        $categoryId = (Category::factory()->create())->getAttribute('id');
        $material = Material::factory()->create();

        $data = [
            'name' => $material->getAttribute('name'),
            'description' => '',
            'authors' => '',
            'type_id' => $typeId,
            'category_id' => $categoryId,
            'id' => $material->getAttribute('id')
        ];

        $this->post(
            route('material.update', ['material' => $material->getAttribute('id')]),
            $data
        );

        $updatedModel = Material::query()->where('id', '=', $material->getAttribute('id'))->first();

        $this->assertEquals($data['name'], $updatedModel->getAttribute('name'));
        $this->assertEquals($data['description'], $updatedModel->getAttribute('description'));
        $this->assertEquals($data['authors'], $updatedModel->getAttribute('authors'));
        $this->assertEquals($data['type_id'], $updatedModel->getAttribute('type_id'));
        $this->assertEquals($data['category_id'], $updatedModel->getAttribute('category_id'));
    }


    /**
     * Assert error if selected name already exists and doesn't equal to name of material, that update
     *
     * @return void
     */
    public function test_update_if_name_exists()
    {
        $typeId = (Type::factory()->create())->getAttribute('id');
        $categoryId = (Category::factory()->create())->getAttribute('id');
        $material = Material::factory()->create();
        $existsMaterial = Material::factory()->create();

        $data = [
            'name' => $existsMaterial->getAttribute('name'),
            'description' => '',
            'authors' => '',
            'type_id' => $typeId,
            'category_id' => $categoryId,
            'id' => $material->getAttribute('id')
        ];

        $this->post(
            route('material.update', ['material' => $material->getAttribute('id')]),
            $data
        )->assertSessionHasErrors('name');

    }

    public function tearDown(): void
    {
        DB::rollBack();
        parent::tearDown();
    }
}
