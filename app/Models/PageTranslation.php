<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageTranslation extends Model
{
    protected $fillable = [
        'page_id', 'lang_key', 'slug', 'name', 'title', 'subtitle', 
        'content_text', 'body_content', 'extra_content', 'extras',
        'seo_title', 'seo_description', 'seo_keywords',
        'why_us_main_title', 'why_us_title_1', 'why_us_desc_1', 
        'why_us_title_2', 'why_us_desc_2', 'why_us_title_3', 'why_us_desc_3',
        'mission_title', 'mission_content'
    ];
    protected $casts = ['extras' => 'array'];
}
