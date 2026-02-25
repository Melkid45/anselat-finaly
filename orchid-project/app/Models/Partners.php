<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Screen\AsSource;

class Partners extends Model
{
    use AsSource, Attachable, HasFactory, Translatable;

    protected $table = 'partners';

    public array $translatedAttributes = [
        'title',
    ];

    protected $guarded = [];

    protected $casts = [
        'logos' => 'array',
    ];
}
