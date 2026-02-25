<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryTranslation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'locale',
        'name',
        'slug',
        'description',
        'meta_title',
        'meta_description',
        'og_description',
        'twitter_description',
        'meta_keywords',
    ];
}
