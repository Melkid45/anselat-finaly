<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class WorkBlock extends Model
{
    use AsSource, HasFactory, Translatable;

    protected $table = 'work_block';

    public array $translatedAttributes = [
        'title',
    ];

    protected $guarded = [];
}
