<?php

namespace App\Http\Controllers\Admin;

use App\Models\Project;
use App\Models\ProjectCity;

class ProjectCityController extends ProjectTaxonomyController
{
    protected function modelClass(): string
    {
        return ProjectCity::class;
    }

    protected function translationForeignKey(): string
    {
        return 'project_city_id';
    }

    protected function routePrefix(): string
    {
        return 'yonetim.proje-sehirleri';
    }

    protected function pageTitle(): string
    {
        return 'Proje Şehirleri';
    }

    protected function singularLabel(): string
    {
        return 'Şehir';
    }

    protected function syncProjectSlugs(string $oldSlug, string $newSlug): void
    {
        $name = ProjectCity::where('slug', $newSlug)->first()?->name;
        Project::where('city', $oldSlug)->update([
            'city' => $newSlug,
            'city_label' => $name ?: $newSlug,
        ]);
    }
}
