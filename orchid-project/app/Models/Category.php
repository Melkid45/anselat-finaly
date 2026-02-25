<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Category extends Model implements TranslatableContract
{
    use AsSource, HasFactory, Translatable;

    protected $table = 'category';
    public array $translatedAttributes = [
        'name',
        'slug',
        'description',
        'meta_title',
        'meta_description',
        'og_description',
        'twitter_description',
        'meta_keywords',
    ];
    protected $with = ['translations'];

    protected $fillable = [];

    public function works(): HasMany
    {
        return $this->hasMany(Works::class, 'category_id');
    }
}
