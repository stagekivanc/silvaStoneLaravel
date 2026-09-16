<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategoryTranslation extends Model
{
    protected $fillable = [
        'product_category_id', 'lang_key', 'name', 'slug', 'description', 'home_description', 'seo_title', 'seo_description'
    ];
}
