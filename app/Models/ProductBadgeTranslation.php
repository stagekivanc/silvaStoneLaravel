<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductBadgeTranslation extends Model
{
    protected $fillable = ['product_badge_id', 'lang_key', 'name'];
}
