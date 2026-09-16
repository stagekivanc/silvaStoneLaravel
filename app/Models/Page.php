<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use \App\Traits\Translatable;

    protected $translatable = [
        'name', 'title', 'subtitle', 'content_text', 'body_content', 
        'extra_content', 'extras', 'seo_title', 'seo_description', 'seo_keywords'
    ];

    protected $fillable = [
        'slug',
        'name',
        'type',
        'title',
        'subtitle',
        'content_text',
        'image',
        'body_content',
        'extra_content',
        'why_us_main_title',
        'why_us_title_1', 'why_us_desc_1',
        'why_us_title_2', 'why_us_desc_2',
        'why_us_title_3', 'why_us_desc_3',
        'mission_title', 'mission_content',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'extras',
    ];

    protected $casts = [
        'extras' => 'array',
    ];
}
