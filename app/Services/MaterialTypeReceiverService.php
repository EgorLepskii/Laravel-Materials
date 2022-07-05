<?php
namespace App\Services;

use App\Exceptions\IncorrectCollectionTypeException;
use App\Models\Material;
use App\Models\Type;
use Illuminate\Database\Eloquent\Collection;

class MaterialTypeReceiverService
{
    protected Material $material;

    /**
     * Create collection of material types
     *
     * @param  Collection<int, Material> $materials
     * @return Collection<int, Type>
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
            $collection->add($this->material->type()->getQuery()->first());
        }

        return $collection;
    }

}
