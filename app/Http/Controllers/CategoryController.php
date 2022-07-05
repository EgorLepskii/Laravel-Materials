<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\CreateTagRequest;
use App\Models\Category;
use App\Models\Material;
use App\Models\Tag;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Routing\UrlGenerator;

class CategoryController extends Controller
{
    /**
     * @var int
     */
    public const MAX_SHOW_COUNT = 10;

    /**
     * @var int
     */
    public const  MAX_NAME_LENGTH = 255;

    protected Material $material;


    public function __construct
    (
        private Factory $viewFactory,
        private Redirector $redirector,
        private UrlGenerator $urlGenerator,
        private ResponseFactory $responseFactory
    )
    {
    }

    /**
     * Create new category and redirect to page with all category
     *
     * @param  CreateCategoryRequest $request
     * @return Application|RedirectResponse|Redirector
     */
    public function store(CreateCategoryRequest $request): Redirector|RedirectResponse|Application
    {
        (new Category($request->only('name')))->save();

        return $this->redirector->to($this->urlGenerator->route('category.index'));
    }


    /**
     * Delete category and all materials, tags, and links, linked to material
     *
     * @param  Category $category
     * @return Response
     */
    public function destroy(Category $category): Response
    {
        $materials = $category->materials()->get();
        foreach ($materials as $material)
        {
            $this->material = $material;
            $this->material->materialsTags()->delete();
            $this->material->links()->delete();
            $this->material->delete();
        }

        $category->delete();
        return $this->responseFactory->make();
    }

    /**
     * Show all tags
     *
     * @param  int $page
     * @return Application|Factory|\Illuminate\Support\Facades\View
     */
    public function index(int $page = 0): View|Factory|Application
    {
        $categories = Category::query()
            ->skip($page * self::MAX_SHOW_COUNT)
            ->limit(self::MAX_SHOW_COUNT)->orderByDesc('id')->get();

        $pagesCount = ceil(Category::query()->count() / self::MAX_SHOW_COUNT);

        return $this->viewFactory->make('categories', ['categories' => $categories, 'pagesCount' => $pagesCount, 'currentPage' => $page]);
    }

    /**
     * Show page to create new category
     *
     * @return Application|Factory|View
     */
    public function create(): View|Factory|Application
    {
        return $this->viewFactory->make('addCategory');
    }


}
