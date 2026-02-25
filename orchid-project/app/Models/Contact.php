<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Contact extends Model
{
    use AsSource, HasFactory;
    protected $table = 'contacts';
    protected $fillable = [
        'address',
        'address_link',
        'address_iframe',
        'email',
        'phone',
        'time',
        'facebook',
        'instagram',
    ];

}
