<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroTranslation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'locale',
        'first_title',
        'second_title',
        'description',
    ];
}
