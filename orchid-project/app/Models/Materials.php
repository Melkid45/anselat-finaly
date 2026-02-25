<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Screen\AsSource;

class Materials extends Model
{
    use AsSource, Attachable, HasFactory, Translatable;

    protected $table = 'materials';

    public array $translatedAttributes = [
        'name',
        'description',
    ];

    protected $guarded = [];

    protected $casts = [
        'image' => 'array',
    ];
}
