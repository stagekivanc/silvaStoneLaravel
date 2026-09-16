<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectTranslation extends Model
{
    protected $fillable = [
        'project_id', 'lang_key', 'title', 'slug', 'lead', 'body',
        'feats', 'seo_title', 'seo_description',
    ];

    protected $casts = [
        'feats' => 'array',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
