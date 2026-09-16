<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectCityTranslation extends Model
{
    protected $fillable = ['project_city_id', 'lang_key', 'name'];

    public function city()
    {
        return $this->belongsTo(ProjectCity::class, 'project_city_id');
    }
}
