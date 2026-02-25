<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Screen\AsSource;

class About extends Model
{
    use AsSource, Attachable, HasFactory, Translatable;

    protected $table = 'about';

    public array $translatedAttributes = [
        'title',
        'description',
    ];

    protected $guarded = [];

    protected $casts = [
        'images' => 'array',
    ];
}
