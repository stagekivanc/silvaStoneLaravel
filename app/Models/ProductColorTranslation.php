<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductColorTranslation extends Model
{
    protected $fillable = ['product_color_id', 'lang_key', 'name'];
}
