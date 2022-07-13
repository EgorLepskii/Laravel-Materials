<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    /**
     * Receive entries from materials_tags table for current tag
     *
     * @return HasMany
     */
    public function materialsTags(): HasMany
    {
        return $this->hasMany(MaterialTag::class, 'tag_id', 'id');
    }
}
