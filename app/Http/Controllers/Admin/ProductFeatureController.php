<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\ProductFeature;
use App\Models\ProductFeatureTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductFeatureController extends Controller
{
    public function index()
    {
        $items = ProductFeature::with('translations')->orderBy('order')->orderBy('id')->get();

        return view('yonetim.urunler.ozellikler.index', compact('items'));
    }

    public function create()
    {
        return view('yonetim.urunler.ozellikler.form', ['item' => null]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $translations = $this->prepareTranslations($request->input('translations', []));
        $defaultLang = Language::default()?->code ?: 'tr';
        $name = $translations[$defaultLang]['name'] ?? (reset($translations)['name'] ?? 'ozellik');

        $item = ProductFeature::create([
            'slug' => $this->uniqueSlug($data['slug'] ?? $name),
            'filter_key' => $data['filter_key'],
            'filter_value' => $data['filter_value'],
            'order' => (int) ($data['order'] ?? 0),
            'status' => ! empty($data['status']),
        ]);

        foreach ($translations as $lang => $row) {
            $item->translations()->create(array_merge($row, ['lang_key' => $lang]));
        }

        return redirect()->route('yonetim.urun-ozellikleri.index')->with('success', 'Özellik eklendi.');
    }

    public function edit($id)
    {
        $item = ProductFeature::with('translations')->findOrFail($id);

        return view('yonetim.urunler.ozellikler.form', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = ProductFeature::findOrFail($id);
        $data = $this->validated($request, $item->id);
        $translations = $this->prepareTranslations($request->input('translations', []));
        $defaultLang = Language::default()?->code ?: 'tr';
        $name = $translations[$defaultLang]['name'] ?? (reset($translations)['name'] ?? $item->slug);

        $item->update([
            'slug' => $this->uniqueSlug($data['slug'] ?? $name, $item->id),
            'filter_key' => $data['filter_key'],
            'filter_value' => $data['filter_value'],
            'order' => (int) ($data['order'] ?? 0),
            'status' => ! empty($data['status']),
        ]);

        foreach ($translations as $lang => $row) {
            ProductFeatureTranslation::updateOrCreate(
                ['product_feature_id' => $item->id, 'lang_key' => $lang],
                $row
            );
        }

        return redirect()->route('yonetim.urun-ozellikleri.index')->with('success', 'Özellik güncellendi.');
    }

    public function destroy($id)
    {
        ProductFeature::findOrFail($id)->delete();

        return redirect()->route('yonetim.urun-ozellikleri.index')->with('success', 'Özellik silindi.');
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'slug' => 'nullable|string|max:64',
            'filter_key' => ['required', 'string', Rule::in(array_keys(ProductFeature::FILTER_KEYS))],
            'filter_value' => 'required|string|max:64',
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
        $base = Str::slug($value) ?: 'ozellik';
        $slug = $base;
        $suffix = 2;
        while (ProductFeature::where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
