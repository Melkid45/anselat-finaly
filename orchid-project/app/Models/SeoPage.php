<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Screen\AsSource;

class SeoPage extends Model implements TranslatableContract
{
    use AsSource, Attachable, HasFactory, Translatable;

    public array $translatedAttributes = [
        'slug',
        'meta_title',
        'meta_description',
        'og_description',
        'twitter_description',
        'meta_keywords',
    ];

    protected $fillable = [
        'page_key',
        'og_image',
    ];

    protected $casts = [
        'og_image' => 'array',
    ];

    protected $with = ['translations'];
}
