<?php
namespace App\Services;

use App\Exceptions\IncorrectCollectionTypeException;
use App\Models\Category;
use App\Models\Material;
use Illuminate\Database\Eloquent\Collection;

class MaterialCategoryReceiverService
{
    protected Material $material;

    /**
     * Create collection of material categories
     *
     * @param  Collection<int, Material> $materials
     * @return Collection<int, Category>
     * @throws IncorrectCollectionTypeException
     */
    public function receive(Collection $materials): Collection
    {
        if(Material::class != $materials->getQueueableClass() && $materials->getQueueableClass()) {
            throw new IncorrectCollectionTypeException('Incorrect collection type(expected Material)');
        }

        $collection = new Collection();

        foreach ($materials as $material)
        {
            $this->material = $material;
            $collection->add($this->material->category()->getQuery()->first());
        }

        return $collection;
    }

}
