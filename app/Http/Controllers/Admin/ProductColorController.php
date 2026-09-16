<?php

namespace App\Http\Controllers\Admin;

use App\Models\Language;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductColorTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;

class ProductColorController extends Controller
{
    public function index()
    {
        $items = ProductColor::with('translations')->orderBy('order')->orderBy('id')->get();

        return view('yonetim.urunler.renkler.index', compact('items'));
    }

    public function create()
    {
        return view('yonetim.urunler.renkler.form', ['item' => null]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $translations = $this->prepareTranslations($request->input('translations', []));
        $defaultLang = Language::default()?->code ?: 'tr';
        $name = $translations[$defaultLang]['name'] ?? (reset($translations)['name'] ?? 'renk');

        $item = ProductColor::create([
            'slug' => $this->uniqueSlug($data['slug'] ?? $name),
            'hex' => $data['hex'] ?? '#cccccc',
            'order' => (int) ($data['order'] ?? 0),
            'status' => ! empty($data['status']),
        ]);

        foreach ($translations as $lang => $row) {
            $item->translations()->create(array_merge($row, ['lang_key' => $lang]));
        }

        return redirect()->route('yonetim.urun-renkleri.index')->with('success', 'Renk eklendi.');
    }

    public function edit($id)
    {
        $item = ProductColor::with('translations')->findOrFail($id);

        return view('yonetim.urunler.renkler.form', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = ProductColor::findOrFail($id);
        $data = $this->validated($request);
        $translations = $this->prepareTranslations($request->input('translations', []));
        $defaultLang = Language::default()?->code ?: 'tr';
        $name = $translations[$defaultLang]['name'] ?? (reset($translations)['name'] ?? $item->slug);

        $oldSlug = $item->slug;
        $newSlug = $this->uniqueSlug($data['slug'] ?? $name, $item->id);

        $item->update([
            'slug' => $newSlug,
            'hex' => $data['hex'] ?? $item->hex,
            'order' => (int) ($data['order'] ?? 0),
            'status' => ! empty($data['status']),
        ]);

        if ($oldSlug !== $newSlug) {
            Product::where('color', $oldSlug)->update(['color' => $newSlug]);
        }

        foreach ($translations as $lang => $row) {
            ProductColorTranslation::updateOrCreate(
                ['product_color_id' => $item->id, 'lang_key' => $lang],
                $row
            );
        }

        return redirect()->route('yonetim.urun-renkleri.index')->with('success', 'Renk güncellendi.');
    }

    public function destroy($id)
    {
        $item = ProductColor::findOrFail($id);
        $inUse = $item->products()->count();
        if ($inUse > 0) {
            return redirect()->route('yonetim.urun-renkleri.index')
                ->with('error', "Bu renk {$inUse} üründe kullanılıyor.");
        }
        $item->delete();

        return redirect()->route('yonetim.urun-renkleri.index')->with('success', 'Renk silindi.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'slug' => 'nullable|string|max:64',
            'hex' => 'nullable|string|max:32',
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
        $base = Str::slug($value) ?: 'renk';
        $slug = $base;
        $suffix = 2;
        while (ProductColor::where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
