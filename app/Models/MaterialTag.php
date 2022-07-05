<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model for materials_tags table
 */
class MaterialTag extends Model
{
    use HasFactory;

    protected $fillable = ['tag_id', 'material_id'];
    protected $table  = 'materials_tags';
}
