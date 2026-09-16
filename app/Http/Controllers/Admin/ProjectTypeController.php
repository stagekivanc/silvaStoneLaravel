<?php

namespace App\Http\Controllers\Admin;

use App\Models\Project;
use App\Models\ProjectType;

class ProjectTypeController extends ProjectTaxonomyController
{
    protected function modelClass(): string
    {
        return ProjectType::class;
    }

    protected function translationForeignKey(): string
    {
        return 'project_type_id';
    }

    protected function routePrefix(): string
    {
        return 'yonetim.proje-tipleri';
    }

    protected function pageTitle(): string
    {
        return 'Proje Tipleri';
    }

    protected function singularLabel(): string
    {
        return 'Proje Tipi';
    }

    protected function syncProjectSlugs(string $oldSlug, string $newSlug): void
    {
        Project::where('type', $oldSlug)->update(['type' => $newSlug]);
    }
}
