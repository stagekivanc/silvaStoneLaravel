<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductFeatureTranslation extends Model
{
    protected $fillable = ['product_feature_id', 'lang_key', 'name'];
}
