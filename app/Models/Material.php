<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

/**
 * @property HasOne $type
 * @property HasOne $category
 * @property BelongsToMany $tags
 * @property HasMany $links;
 */
class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'authors', 'description', 'type_id', 'category_id'
    ];

    /**
     * @return HasOne
     */
    public function category(): HasOne
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    /**
     * @return HasOne
     */
    public function type(): HasOne
    {
        return $this->hasOne(Type::class, 'id', 'type_id');
    }

    /**
     * Receive tags, that have been linked to current material
     *
     * @return BelongsToMany
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'materials_tags', 'material_id');
    }

    /**
     * Receive entries from materials_tags table for current material
     *
     * @return HasMany
     */
    public function materialsTags(): HasMany
    {
        return $this->hasMany(MaterialTag::class, 'material_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function links(): HasMany
    {
        return $this->hasMany(Link::class, 'material_id', 'id');
    }

    /**
     * Link new tag to material
     *
     * @param int $tagId
     * @return void
     */
    public function addTag(int $tagId): void
    {
        DB::table('materials_tags')->insert(['material_id' => $this->getAttribute('id'), 'tag_id' => $tagId]);
    }
}
