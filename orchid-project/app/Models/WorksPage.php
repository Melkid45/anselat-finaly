<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Screen\AsSource;

class WorksPage extends Model
{
    use AsSource, Attachable, HasFactory;

    protected $table = 'work_page';
    protected $fillable = [
        'title',
        'images',
    ];
    protected $casts = [
        'images' => 'array',
    ];
}
