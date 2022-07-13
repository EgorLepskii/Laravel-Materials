<?php

namespace App\Http\Controllers;

use App\Exceptions\IncorrectCollectionTypeException;
use App\Http\Requests\CreateMaterialRequest;
use App\Http\Requests\UpdateMaterialRequest;
use App\Models\Category;
use App\Models\Material;
use App\Models\Type;
use App\Services\MaterialCategoryReceiverService;
use App\Services\MaterialTypeReceiverService;
use App\Services\SearchService;
use App\Services\TagsReceiverService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Routing\UrlGenerator;

class MaterialController extends Controller
{
    /**
     * @var int
     */
    public const MAX_NAME_LENGTH = 255;

    /**
     * @var int
     */
    public const MAX_AUTHORS_TEXT_LENGTH = 255;

    /**
     * @var int
     */
    public const MAX_DESCRIPTION_LENGTH = 255;

    public function __construct(
        private Factory $viewFactory,
        private Redirector $redirector,
        private UrlGenerator $urlGenerator,
        private ResponseFactory $responseFactory
    ) {
    }


    /**
     * Show all material
     *
     * @param Request $request
     * @return Application|Factory|\Illuminate\Support\Facades\View
     * @throws IncorrectCollectionTypeException
     */
    public function index(Request $request): View|Factory|Application
    {
        $tag = $request->input('tag');
        $searchString = $request->input('search') ?? "";
        $searchService = new SearchService();

        $materials = empty($tag) ? $searchService->search($searchString)->orderByDesc('id')->get() :
            $searchService->searchByTags($tag)->orderByDesc('id')->get();

        $types = (new MaterialTypeReceiverService())->receive($materials);
        $categories = (new MaterialCategoryReceiverService())->receive($materials);

        return $this->viewFactory->make(
            'materials',
            [
                'materials' => $materials,
                'types' => $types,
                'categories' => $categories,
                'search' => $searchString
            ]
        );
    }

    /**
     * Show page to create material
     *
     * @return Application|Factory|View
     */
    public function create(): View|Factory|Application
    {
        $types = Type::query()->get();
        $categories = Category::query()->get();
        return $this->viewFactory->make('addMaterial', ['types' => $types, 'categories' => $categories]);
    }

    /**
     * Create new material
     *
     * @param CreateMaterialRequest $request
     * @return Application|RedirectResponse|Redirector
     */
    public function store(CreateMaterialRequest $request): Redirector|RedirectResponse|Application
    {
        $input = $request->only(['type_id', 'category_id', 'name', 'authors', 'description']);
        (new Material($input))->save();

        return $this->redirector->to($this->urlGenerator->route('material.index'));
    }

    /**
     * Show page to update material
     *
     * @param Material $material
     * @return Application|Factory|View
     */
    public function edit(Material $material): View|Factory|Application
    {
        $currentType = $material->type();
        $currentCategory = $material->category();

        $types = Type::query()->get();

        $categories = Category::query()->get();

        return $this->viewFactory->make(
            'updateMaterial',
            [
                'types' => $types,
                'categories' => $categories,
                'currentTypeId' => $currentType->first()->getAttribute('id'),
                'currentCategoryId' => $currentCategory->first()->getAttribute('id'),
                'material' => $material
            ]
        );
    }

    /**
     * Update material data
     *
     * @param UpdateMaterialRequest $request
     * @param Material $material
     * @return Application|RedirectResponse|Redirector
     */
    public function update(UpdateMaterialRequest $request, Material $material): Redirector|RedirectResponse|Application
    {
        $input = $request->only(['name', 'authors', 'description', 'type_id', 'category_id']);
        $material->update($input);

        return $this->redirector->to($this->urlGenerator->route('material.show', ['material' => $material->getAttribute('id')]));
    }

    /**
     * Show page with material
     *
     * @param Material $material
     * @return Application|Factory|View
     */
    public function show(Material $material): View|Factory|Application
    {
        $materialCategoryName = $material->category()->first()->getAttribute('name');
        $materialTypeName = $material->type()->first()->getAttribute('name');
        $materialTags = $material->tags()->getQuery()->get();

        $links = $material->links()->get();
        $tags = (new TagsReceiverService())->receive($material);

        return $this->viewFactory->make(
            'material',
            [
                'material' => $material,
                'typeName' => $materialTypeName,
                'categoryName' => $materialCategoryName,
                'materialTags' => $materialTags,
                'tags' => $tags,
                'links' => $links
            ]
        );
    }

    /**
     * Delete material and all tags and links, linked to material
     *
     * @param Material $material
     * @return Application|ResponseFactory|Response
     */
    public function destroy(Material $material): Response|Application|ResponseFactory
    {
        $material->materialsTags()->delete();
        $material->links()->delete();
        $material->delete();
        return $this->responseFactory->make('', 200);
    }
}
