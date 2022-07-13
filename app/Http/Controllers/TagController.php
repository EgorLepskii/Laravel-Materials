<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Models\Tag;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Routing\UrlGenerator;

class TagController extends Controller
{
    /**
     * @var int
     */
    public const  MAX_NAME_LENGTH = 255;

    /**
     * @var int
     */
    public const MAX_SHOW_COUNT = 10;

    public function __construct(
        private Redirector $redirector,
        private UrlGenerator $urlGenerator,
        private ResponseFactory $responseFactory,
        private Factory $viewFactory
    ) {
    }

    /**
     * Create new tag and redirect to page with all tags
     *
     * @param CreateTagRequest $request
     * @return Application|RedirectResponse|Redirector
     */
    public function store(CreateTagRequest $request): Redirector|RedirectResponse|Application
    {
        (new Tag($request->only('name')))->save();

        return $this->redirector->to($this->urlGenerator->route('tag.index'));
    }


    /**
     * Delete tag
     *
     * @param Tag $tag
     * @return Response
     */
    public function destroy(Tag $tag): Response
    {
        $tag->materialsTags()->delete();
        $tag->delete();

        return $this->responseFactory->make('', 200);
    }

    /**
     * Show all tags
     *
     * @param int $page
     * @return Application|Factory|\Illuminate\Support\Facades\View
     */
    public function index(int $page = 0): View|Factory|Application
    {
        $tags = Tag::query()
            ->skip($page * self::MAX_SHOW_COUNT)
            ->limit(self::MAX_SHOW_COUNT)->orderByDesc('id')->get();

        $pagesCount = ceil(Tag::query()->count() / self::MAX_SHOW_COUNT);

        return $this->viewFactory->make('tags', ['tags' => $tags, 'pagesCount' => $pagesCount, 'currentPage' => $page]);
    }

    /**
     * Show page to create new tag
     *
     * @return Application|Factory|View
     */
    public function create(): View|Factory|Application
    {
        return $this->viewFactory->make('addTag');
    }

    /**
     * Show page to update tag
     *
     * @param Tag $tag
     * @return View
     */
    public function edit(Tag $tag): View
    {
        return $this->viewFactory->make('updateTag', ['tag' => $tag]);
    }

    /**
     * Update tag
     *
     * @param UpdateTagRequest $request
     * @param Tag $tag
     * @return RedirectResponse
     */
    public function update(UpdateTagRequest $request, Tag $tag): RedirectResponse
    {
        $tag->update(['name' => $request->input('name')]);
        return $this->redirector->to($this->urlGenerator->route('tag.index'));
    }
}
