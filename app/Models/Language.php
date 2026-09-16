<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $fillable = ['name', 'code', 'is_default', 'status', 'order'];

    public static function default()
    {
        return self::where('is_default', true)->first() ?? self::first();
    }

    public static function active()
    {
        return self::where('status', true)->orderBy('order')->get();
    }
}
