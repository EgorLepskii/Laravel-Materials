<?php

namespace App\Services;

use App\Exceptions\IncorrectCollectionTypeException;
use App\Models\Material;
use App\Models\Tag;
use App\Models\Type;
use Illuminate\Database\Eloquent\Collection;

class TagsReceiverService
{
    protected Material $material;

    /**
     * Create collection of tags, that are not linked to material
     *
     * @param  Material $material
     * @return Collection
     */
    public function receive(Material $material): Collection
    {
        $materialTags = $material->tags()->get();
        $materialTagsIds = [];

        foreach ($materialTags as $materialTag) {
            $materialTagsIds[] = $materialTag->getAttribute('id');
        }

        return Tag::query()->whereNotIn('id', $materialTagsIds)->get();
    }
}
