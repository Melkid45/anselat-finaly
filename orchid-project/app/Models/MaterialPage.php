<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Screen\AsSource;

class MaterialPage extends Model
{
    use AsSource, Attachable, HasFactory, Translatable;

    protected $table = 'material_page';

    public array $translatedAttributes = [
        'title',
        'soft_title',
        'description',
    ];

    protected $guarded = [];

    protected $casts = [
        'images' => 'array',
    ];
}
