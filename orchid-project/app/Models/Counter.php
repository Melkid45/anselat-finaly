<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Counter extends Model
{
    use AsSource, HasFactory, Translatable;

    protected $table = 'counter';

    public array $translatedAttributes = [
        'items',
    ];

    protected $guarded = [];
}
