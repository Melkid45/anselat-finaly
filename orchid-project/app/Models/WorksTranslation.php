<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorksTranslation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'locale',
        'name',
        'slug',
        'description',
        'client',
        'date',
        'place',
        'info',
        'about_title',
        'meta_title',
        'meta_description',
        'og_description',
        'twitter_description',
        'meta_keywords',
    ];

    protected $casts = [
        'info' => 'array',
    ];
}
