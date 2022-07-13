<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Material;
use App\Models\MaterialTag;
use Illuminate\Contracts\Database\Query\Builder;

class SearchService
{
    protected Builder $builder;

    /**
     * Return builder for search materials by materials names, categories names, tags names and authors names
     *
     * @param  string $searchString
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function search(string $searchString): \Illuminate\Database\Eloquent\Builder
    {
        return ($this->searchByMaterial($searchString))
            ->union($this->searchByAuthors($searchString))
            ->union($this->searchByTags($searchString))
            ->union($this->searchByCategories($searchString));
    }

    /**
     * Return builder for search by material name in materials
     *
     * @param  string $searchString
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function searchByMaterial(string $searchString): \Illuminate\Database\Eloquent\Builder
    {
        return Material::query()->where('name', 'LIKE', "%$searchString%");
    }

    /**
     * Return builder for search by authors in materials
     *
     * @param  string $searchString
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function searchByAuthors(string $searchString): \Illuminate\Database\Eloquent\Builder
    {
        return Material::query()->where('authors', 'LIKE', "%$searchString%");
    }

    /**
     * Return builder for receive materials, that have tags, which name contains search string
     *
     * @param  string $searchString
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function searchByTags(string $searchString): \Illuminate\Database\Eloquent\Builder
    {
        return Material::query()
            ->join('materials_tags', 'materials.id', '=', 'materials_tags.material_id')
            ->join('tags', 'tags.id', '=', 'materials_tags.tag_id')
            ->where('tags.name', 'LIKE', "%$searchString%")
            ->select('materials.*')->distinct();
    }

    /**
     * Return builder  for receive materials with categories, that contains search string
     *
     * @param  string $searchString
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function searchByCategories(string $searchString): \Illuminate\Database\Eloquent\Builder
    {
        return Category::query()
            ->join('materials', 'materials.category_id', '=', 'categories.id')
            ->where('categories.name', 'LIKE', "%$searchString%")
            ->select('materials.*')->distinct();
    }
}
