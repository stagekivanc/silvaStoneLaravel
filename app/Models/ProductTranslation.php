<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductTranslation extends Model
{
    protected $fillable = [
        'product_id', 'lang_key', 'name', 'slug', 'title',
        'short_description', 'description', 'why_title',
        'certificates_intro', 'advantages_intro', 'cta_title', 'cta_text',
        'features', 'technical_specs', 'certificates', 'advantages',
        'seo_title', 'seo_description',
    ];
    protected $casts = [
        'features' => 'array',
        'technical_specs' => 'array',
        'certificates' => 'array',
        'advantages' => 'array',
    ];
}
