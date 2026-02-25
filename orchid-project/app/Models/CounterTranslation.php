<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CounterTranslation extends Model
{
    protected $fillable = [
        'items',
    ];

    protected $casts = [
        'items' => 'array',
    ];
}
