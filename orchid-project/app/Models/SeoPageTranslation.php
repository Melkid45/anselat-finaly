<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class SeoPageTranslation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'locale',
        'slug',
        'meta_title',
        'meta_description',
        'og_description',
        'twitter_description',
        'meta_keywords',
    ];

    public function seoPage(): BelongsTo
    {
        return $this->belongsTo(SeoPage::class);
    }
}
