<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyTranslation extends Model
{
    protected $fillable = [
        'title',
        'items',
    ];

    protected $casts = [
        'items' => 'array',
    ];
}
