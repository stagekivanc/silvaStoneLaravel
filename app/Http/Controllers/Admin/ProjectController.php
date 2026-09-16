<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Project;
use App\Models\ProjectTranslation;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    private const IMAGE_MAX_KB = 10240;

    public function index()
    {
        $projects = Project::with('translations')->orderBy('order')->orderBy('id')->get();

        return view('yonetim.projeler.index', compact('projects'));
    }

    public function create()
    {
        return view('yonetim.projeler.form', [
            'types' => \App\Models\ProjectType::optionsMap(null, false),
            'places' => \App\Models\ProjectPlace::optionsMap(null, false),
            'cities' => \App\Models\ProjectCity::optionsMap(null, false),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $translations = $this->prepareTranslations($request->input('translations', []));
        $defaultLang = Language::default()?->code ?: config('app.locale', 'tr');
        $title = $translations[$defaultLang]['title'] ?? (reset($translations)['title'] ?? 'proje');

        $project = new Project();
        $project->fill($this->projectAttributes($data, $title));
        $project->main_image = $this->storeUploadedImage($request->file('main_image'));
        $project->gallery = $this->storeGalleryUploads($request->file('gallery', []));
        $project->save();

        foreach ($translations as $lang => $row) {
            $project->translations()->create(array_merge($row, ['lang_key' => $lang]));
        }

        return redirect()
            ->route('yonetim.projeler.index')
            ->with('success', 'Proje başarıyla eklendi.');
    }

    public function edit(Project $proje)
    {
        $proje->load('translations');

        return view('yonetim.projeler.form', [
            'project' => $proje,
            'types' => \App\Models\ProjectType::optionsMap(null, false),
            'places' => \App\Models\ProjectPlace::optionsMap(null, false),
            'cities' => \App\Models\ProjectCity::optionsMap(null, false),
        ]);
    }

    public function update(Request $request, Project $proje)
    {
        $data = $this->validated($request, $proje->id);
        $translations = $this->prepareTranslations($request->input('translations', []), $proje->id);
        $defaultLang = Language::default()?->code ?: config('app.locale', 'tr');
        $title = $translations[$defaultLang]['title'] ?? (reset($translations)['title'] ?? $proje->title);

        $proje->fill($this->projectAttributes($data, $title, $proje->id));

        if ($request->boolean('remove_main_image')) {
            $this->deleteUpload($proje->main_image);
            $proje->main_image = null;
        }

        if ($request->hasFile('main_image')) {
            $this->deleteUpload($proje->main_image);
            $proje->main_image = $this->storeUploadedImage($request->file('main_image'));
        }

        $gallery = collect((array) ($proje->gallery ?? []))
            ->map(fn ($item) => is_array($item) ? (string) ($item['path'] ?? '') : (string) $item)
            ->filter()
            ->values()
            ->all();

        $removeGallery = array_map('intval', (array) $request->input('remove_gallery', []));
        if ($removeGallery !== []) {
            $kept = [];
            foreach ($gallery as $index => $path) {
                if (in_array($index, $removeGallery, true)) {
                    $this->deleteUpload($path);
                    continue;
                }
                $kept[] = $path;
            }
            $gallery = $kept;
        }

        $newGallery = $this->storeGalleryUploads($request->file('gallery', []));
        $proje->gallery = array_values(array_merge($gallery, $newGallery));
        $proje->save();

        foreach ($translations as $lang => $row) {
            ProjectTranslation::updateOrCreate(
                ['project_id' => $proje->id, 'lang_key' => $lang],
                $row
            );
        }

        return redirect()
            ->route('yonetim.projeler.index')
            ->with('success', 'Proje güncellendi.');
    }

    public function destroy(Project $proje)
    {
        $this->deleteUpload($proje->main_image);
        foreach ((array) ($proje->gallery ?? []) as $item) {
            $path = is_array($item) ? (string) ($item['path'] ?? '') : (string) $item;
            $this->deleteUpload($path);
        }
        $proje->delete();

        return redirect()
            ->route('yonetim.projeler.index')
            ->with('success', 'Proje silindi.');
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        $imageMax = self::IMAGE_MAX_KB;

        return $request->validate([
            'place' => 'required|string|exists:project_places,slug',
            'type' => 'required|string|exists:project_types,slug',
            'city' => 'required|string|exists:project_cities,slug',
            'product_name' => 'nullable|string|max:255',
            'year' => 'nullable|string|max:16',
            'area' => 'nullable|string|max:64',
            'order' => 'nullable|integer|min:0',
            'status' => 'nullable|boolean',
            'home_status' => 'nullable|boolean',
            'main_image' => "nullable|image|mimes:jpeg,jpg,png,gif,webp|max:{$imageMax}",
            'gallery' => 'nullable|array',
            'gallery.*' => "nullable|image|mimes:jpeg,jpg,png,gif,webp|max:{$imageMax}",
            'remove_main_image' => 'nullable|boolean',
            'remove_gallery' => 'nullable|array',
            'translations' => 'required|array',
            'translations.*.title' => 'nullable|string|max:255',
            'translations.*.slug' => 'nullable|string|max:255',
            'translations.*.lead' => 'nullable|string',
            'translations.*.body' => 'nullable|string',
            'translations.*.feats_text' => 'nullable|string',
            'translations.*.seo_title' => 'nullable|string|max:255',
            'translations.*.seo_description' => 'nullable|string',
        ], [
            'place.required' => 'Mekân seçimi zorunludur.',
            'type.required' => 'Proje tipi zorunludur.',
        ]);
    }

    private function projectAttributes(array $data, string $title, ?int $ignoreId = null): array
    {
        $citySlug = trim((string) ($data['city'] ?? ''));
        $cityLabel = \App\Models\ProjectCity::optionsMap(null, false)[$citySlug]
            ?? \App\Models\ProjectCity::where('slug', $citySlug)->value('slug');

        return [
            'slug' => $this->uniqueRootSlug($title, $ignoreId),
            'place' => $data['place'],
            'type' => $data['type'],
            'city' => $citySlug !== '' ? $citySlug : null,
            'city_label' => $cityLabel ?: null,
            'product_name' => trim((string) ($data['product_name'] ?? '')) ?: null,
            'year' => trim((string) ($data['year'] ?? '')) ?: null,
            'area' => trim((string) ($data['area'] ?? '')) ?: null,
            'order' => (int) ($data['order'] ?? 0),
            'status' => ! empty($data['status']),
            'home_status' => ! empty($data['home_status']),
        ];
    }

    private function prepareTranslations(array $translations, ?int $ignoreId = null): array
    {
        $prepared = [];

        foreach ($translations as $lang => $row) {
            if (! is_array($row)) {
                continue;
            }

            $title = trim((string) ($row['title'] ?? ''));
            $slugInput = trim((string) ($row['slug'] ?? ''));
            $featsText = (string) ($row['feats_text'] ?? '');
            $feats = collect(preg_split('/\r\n|\r|\n/', $featsText) ?: [])
                ->map(fn ($line) => trim((string) $line))
                ->filter()
                ->values()
                ->all();

            if ($title === '' && $slugInput === '' && trim((string) ($row['lead'] ?? '')) === '' && trim((string) ($row['body'] ?? '')) === '' && $feats === []) {
                continue;
            }

            $prepared[$lang] = [
                'title' => $title !== '' ? $title : null,
                'slug' => $this->uniqueTranslationSlug($lang, $slugInput !== '' ? $slugInput : $title, $ignoreId),
                'lead' => trim((string) ($row['lead'] ?? '')) ?: null,
                'body' => trim((string) ($row['body'] ?? '')) ?: null,
                'feats' => $feats,
                'seo_title' => trim((string) ($row['seo_title'] ?? '')) ?: null,
                'seo_description' => trim((string) ($row['seo_description'] ?? '')) ?: null,
            ];
        }

        return $prepared;
    }

    private function uniqueRootSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value) ?: 'proje';
        $slug = $base;
        $suffix = 2;

        while (Project::where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }

    private function uniqueTranslationSlug(string $lang, string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value) ?: 'proje';
        $slug = $base;
        $suffix = 2;

        while (ProjectTranslation::where('lang_key', $lang)
            ->where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('project_id', '!=', $ignoreId))
            ->exists()) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }

    private function storeUploadedImage(?UploadedFile $file): ?string
    {
        if (! $file) {
            return null;
        }

        $dir = public_path('uploads/projects');
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $safeName = preg_replace('/[^A-Za-z0-9._-]/', '_', $file->getClientOriginalName()) ?: 'image.jpg';
        $fileName = time() . '_' . $safeName;
        $file->move($dir, $fileName);

        return 'projects/' . $fileName;
    }

    private function storeGalleryUploads($files): array
    {
        $paths = [];
        foreach ((array) $files as $file) {
            if ($file instanceof UploadedFile) {
                $stored = $this->storeUploadedImage($file);
                if ($stored) {
                    $paths[] = $stored;
                }
            }
        }

        return $paths;
    }

    private function deleteUpload(?string $path): void
    {
        $path = ltrim((string) $path, '/');
        if ($path === '' || filter_var($path, FILTER_VALIDATE_URL)) {
            return;
        }

        if (str_starts_with($path, 'silvastone/') || str_starts_with($path, 'assets/')) {
            return;
        }

        if (str_starts_with($path, 'uploads/')) {
            $path = substr($path, strlen('uploads/'));
        }

        $full = public_path('uploads/' . $path);
        if (is_file($full)) {
            unlink($full);
        }
    }
}
