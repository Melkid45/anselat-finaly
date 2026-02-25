<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialPageTranslation extends Model
{
    protected $fillable = [
        'title',
        'soft_title',
        'description',
    ];
}
