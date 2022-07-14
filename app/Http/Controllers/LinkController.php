<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLinkRequest;
use App\Http\Requests\UpdateLinkRequest;
use App\Models\Link;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;

class LinkController extends Controller
{
    /**
     * @var int
     */
    public const MAX_LINK_SIGN = 255;

    /**
     * @var int
     */
    public const MAX_LINK_URL = 255;

    public function __construct(
        private \Illuminate\Routing\Redirector $redirector,
        private \Illuminate\Contracts\Routing\ResponseFactory $responseFactory,
        private \Illuminate\Contracts\View\Factory $viewFactory,
        private \Illuminate\Routing\UrlGenerator $urlGenerator
    ) {
    }


    /**
     * Create link for material
     *
     * @param  CreateLinkRequest $request
     * @return RedirectResponse
     */
    public function store(CreateLinkRequest $request): RedirectResponse
    {
        (new Link($request->only(['sign','url','material_id'])))->save();
        return $this->redirector->back();
    }

    /**
     * Delete link
     *
     * @param  Link $link
     * @return Application|ResponseFactory|Response
     */
    public function destroy(Link $link): Response|Application|ResponseFactory
    {
        $link->delete();
        return $this->responseFactory->make('');
    }

    /**
     * Show page to update link
     *
     * @param  Request $request
     * @param  Link    $link
     * @return Application|Factory|View
     */
    public function edit(Request $request, Link $link): View|Factory|Application
    {
        $materialId = $request->input('material');

        return $this->viewFactory->make('updateLink', ['link' => $link, 'material' => $materialId]);
    }

    /**
     * Update link
     *
     * @param  UpdateLinkRequest $request
     * @param  Link              $link
     * @return Application|RedirectResponse|Redirector
     */
    public function update(UpdateLinkRequest $request, Link $link): Redirector|RedirectResponse|Application
    {
        $url  = $request->input('urlUpdate');
        $sign = $request->input('signUpdate');
        $link->update(['sign' => $sign, 'url' => $url]);
        $materialId = $request->input('materialId');

        return $this->redirector->to($this->urlGenerator->route('material.show', ['material' => $materialId]));
    }
}
