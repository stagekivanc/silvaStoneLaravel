<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectPlaceTranslation extends Model
{
    protected $fillable = ['project_place_id', 'lang_key', 'name'];

    public function place()
    {
        return $this->belongsTo(ProjectPlace::class, 'project_place_id');
    }
}
