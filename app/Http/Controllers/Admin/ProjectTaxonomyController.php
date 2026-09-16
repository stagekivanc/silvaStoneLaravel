<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class ProjectTaxonomyController extends Controller
{
    abstract protected function modelClass(): string;

    abstract protected function translationForeignKey(): string;

    abstract protected function routePrefix(): string;

    abstract protected function pageTitle(): string;

    abstract protected function singularLabel(): string;

    public function index()
    {
        $items = $this->modelClass()::with('translations')->orderBy('order')->orderBy('id')->get();

        return view('yonetim.projeler.taxonomies.index', [
            'items' => $items,
            'pageTitle' => $this->pageTitle(),
            'routePrefix' => $this->routePrefix(),
            'singularLabel' => $this->singularLabel(),
        ]);
    }

    public function create()
    {
        return view('yonetim.projeler.taxonomies.form', [
            'item' => null,
            'pageTitle' => 'Yeni ' . $this->singularLabel(),
            'routePrefix' => $this->routePrefix(),
            'singularLabel' => $this->singularLabel(),
            'storeUrl' => route($this->routePrefix() . '.store'),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $translations = $this->prepareTranslations($request->input('translations', []));
        $defaultLang = Language::default()?->code ?: config('app.locale', 'tr');
        $name = $translations[$defaultLang]['name'] ?? (reset($translations)['name'] ?? 'item');

        /** @var Model $item */
        $item = new ($this->modelClass())();
        $item->fill([
            'slug' => $this->uniqueSlug($data['slug'] ?? $name),
            'order' => (int) ($data['order'] ?? 0),
            'status' => ! empty($data['status']),
        ]);
        $item->save();

        foreach ($translations as $lang => $row) {
            $item->translations()->create(array_merge($row, ['lang_key' => $lang]));
        }

        return redirect()
            ->route($this->routePrefix() . '.index')
            ->with('success', $this->singularLabel() . ' eklendi.');
    }

    public function edit($id)
    {
        $item = $this->modelClass()::with('translations')->findOrFail($id);

        return view('yonetim.projeler.taxonomies.form', [
            'item' => $item,
            'pageTitle' => $this->singularLabel() . ' Düzenle',
            'routePrefix' => $this->routePrefix(),
            'singularLabel' => $this->singularLabel(),
            'storeUrl' => route($this->routePrefix() . '.update', $item->id),
        ]);
    }

    public function update(Request $request, $id)
    {
        $item = $this->modelClass()::findOrFail($id);
        $data = $this->validated($request, $item->id);
        $translations = $this->prepareTranslations($request->input('translations', []));
        $defaultLang = Language::default()?->code ?: config('app.locale', 'tr');
        $name = $translations[$defaultLang]['name'] ?? (reset($translations)['name'] ?? $item->slug);

        $oldSlug = $item->slug;
        $newSlug = $this->uniqueSlug($data['slug'] ?? $name, $item->id);

        $item->fill([
            'slug' => $newSlug,
            'order' => (int) ($data['order'] ?? 0),
            'status' => ! empty($data['status']),
        ]);
        $item->save();

        if ($oldSlug !== $newSlug) {
            $this->syncProjectSlugs($oldSlug, $newSlug);
        }

        foreach ($translations as $lang => $row) {
            $item->translations()->updateOrCreate(
                ['lang_key' => $lang],
                $row
            );
        }

        return redirect()
            ->route($this->routePrefix() . '.index')
            ->with('success', $this->singularLabel() . ' güncellendi.');
    }

    public function destroy($id)
    {
        $item = $this->modelClass()::findOrFail($id);
        $inUse = $item->projects()->count();
        if ($inUse > 0) {
            return redirect()
                ->route($this->routePrefix() . '.index')
                ->with('error', "Bu {$this->singularLabel()} {$inUse} projede kullanılıyor. Önce projeleri güncelleyin.");
        }

        $item->delete();

        return redirect()
            ->route($this->routePrefix() . '.index')
            ->with('success', $this->singularLabel() . ' silindi.');
    }

    abstract protected function syncProjectSlugs(string $oldSlug, string $newSlug): void;

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'slug' => 'nullable|string|max:64',
            'order' => 'nullable|integer|min:0',
            'status' => 'nullable|boolean',
            'translations' => 'required|array',
            'translations.*.name' => 'nullable|string|max:255',
        ]);
    }

    private function prepareTranslations(array $translations): array
    {
        $prepared = [];
        foreach ($translations as $lang => $row) {
            if (! is_array($row)) {
                continue;
            }
            $name = trim((string) ($row['name'] ?? ''));
            if ($name === '') {
                continue;
            }
            $prepared[$lang] = ['name' => $name];
        }

        return $prepared;
    }

    private function uniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value) ?: 'item';
        $slug = $base;
        $suffix = 2;
        $model = $this->modelClass();

        while ($model::where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
