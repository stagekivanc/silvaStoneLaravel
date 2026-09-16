<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectTypeTranslation extends Model
{
    protected $fillable = ['project_type_id', 'lang_key', 'name'];

    public function type()
    {
        return $this->belongsTo(ProjectType::class, 'project_type_id');
    }
}
