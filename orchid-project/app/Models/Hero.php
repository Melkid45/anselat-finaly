<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Screen\AsSource;

class Hero extends Model implements TranslatableContract
{
    use AsSource, Attachable, HasFactory, Translatable;

    protected $table = 'hero';
    public array $translatedAttributes = [
        'first_title',
        'second_title',
        'description',
    ];
    protected $with = ['translations'];
    protected $fillable = [
        'image',
    ];
    public $translationModel = HeroTranslation::class;
    protected $casts = [
        'image' => 'array',
    ];
}
