<?php

namespace App\Http\Controllers\Admin;

use App\Models\Project;
use App\Models\ProjectPlace;

class ProjectPlaceController extends ProjectTaxonomyController
{
    protected function modelClass(): string
    {
        return ProjectPlace::class;
    }

    protected function translationForeignKey(): string
    {
        return 'project_place_id';
    }

    protected function routePrefix(): string
    {
        return 'yonetim.proje-mekanlari';
    }

    protected function pageTitle(): string
    {
        return 'Proje Mekânları';
    }

    protected function singularLabel(): string
    {
        return 'Mekân';
    }

    protected function syncProjectSlugs(string $oldSlug, string $newSlug): void
    {
        Project::where('place', $oldSlug)->update(['place' => $newSlug]);
    }
}
