<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Screen\AsSource;

class Works extends Model implements TranslatableContract
{
    use AsSource, Attachable, HasFactory, Translatable;

    protected $table = 'works';
    public array $translatedAttributes = [
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
    protected $with = ['translations'];

    protected $fillable = [
        'view_on_home_page',
        'category_id',
        'preview',
        'gallery',
        'og_image',
    ];
    protected $casts = [
        'view_on_home_page' => 'boolean',
        'preview' => 'array',
        'gallery' => 'array',
        'top_gallery' => 'array',
        'bottom_gallery' => 'array',
        'og_image' => 'array',
    ];

    public function workCategory(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
