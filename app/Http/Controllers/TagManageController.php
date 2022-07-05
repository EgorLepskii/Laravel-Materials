<?php

namespace App\Http\Controllers;

use App\Http\Requests\LinkTagRequest;
use App\Models\Material;
use App\Models\MaterialTag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\LaravelIgnition\Solutions\SolutionProviders\DefaultDbNameSolutionProvider;

class TagManageController extends Controller
{
    protected Material $material;
    public function __construct
    (
        private \Illuminate\Routing\Redirector $redirector,
        private \Illuminate\Database\DatabaseManager $databaseManager
    ) {}

    /**
     * Link tag to material
     *
     * @param  LinkTagRequest $request
     * @return RedirectResponse
     */
    public function store(LinkTagRequest $request): RedirectResponse
    {
        $this->material = Material::query()->where('id', '=', $request->input('materialId'))->first() ?? new Material();

        $tagId = $request->input('tag');
        $this->material->addTag($tagId);

        return $this->redirector->back();
    }

    /**
     * Delete linked tag
     *
     * @param  Request $request
     * @return void
     */
    public function destroy(Request $request): void
    {
        $data = $request->input();
        $this->databaseManager->table('materials_tags')->delete($data['entryid']);
    }
}
