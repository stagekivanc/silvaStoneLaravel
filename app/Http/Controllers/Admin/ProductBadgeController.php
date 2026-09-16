<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Product;
use App\Models\ProductBadge;
use App\Models\ProductBadgeTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductBadgeController extends Controller
{
    public function index()
    {
        $items = ProductBadge::with('translations')->orderBy('order')->orderBy('id')->get();

        return view('yonetim.urunler.rozetler.index', compact('items'));
    }

    public function create()
    {
        return view('yonetim.urunler.rozetler.form', ['item' => null]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $translations = $this->prepareTranslations($request->input('translations', []));
        $defaultLang = Language::default()?->code ?: 'tr';
        $name = $translations[$defaultLang]['name'] ?? (reset($translations)['name'] ?? 'rozet');

        $item = ProductBadge::create([
            'slug' => $this->uniqueSlug($data['slug'] ?? $name),
            'order' => (int) ($data['order'] ?? 0),
            'status' => ! empty($data['status']),
        ]);

        foreach ($translations as $lang => $row) {
            $item->translations()->create(array_merge($row, ['lang_key' => $lang]));
        }

        return redirect()->route('yonetim.urun-rozetleri.index')->with('success', 'Rozet eklendi.');
    }

    public function edit($id)
    {
        $item = ProductBadge::with('translations')->findOrFail($id);

        return view('yonetim.urunler.rozetler.form', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = ProductBadge::findOrFail($id);
        $data = $this->validated($request);
        $translations = $this->prepareTranslations($request->input('translations', []));
        $defaultLang = Language::default()?->code ?: 'tr';
        $name = $translations[$defaultLang]['name'] ?? (reset($translations)['name'] ?? $item->slug);

        $oldSlug = $item->slug;
        $newSlug = $this->uniqueSlug($data['slug'] ?? $name, $item->id);

        $item->update([
            'slug' => $newSlug,
            'order' => (int) ($data['order'] ?? 0),
            'status' => ! empty($data['status']),
        ]);

        if ($oldSlug !== $newSlug) {
            Product::where('badge', $oldSlug)->update(['badge' => $newSlug]);
        }

        foreach ($translations as $lang => $row) {
            ProductBadgeTranslation::updateOrCreate(
                ['product_badge_id' => $item->id, 'lang_key' => $lang],
                $row
            );
        }

        return redirect()->route('yonetim.urun-rozetleri.index')->with('success', 'Rozet güncellendi.');
    }

    public function destroy($id)
    {
        $item = ProductBadge::findOrFail($id);
        $inUse = $item->products()->count();
        if ($inUse > 0) {
            return redirect()->route('yonetim.urun-rozetleri.index')
                ->with('error', "Bu rozet {$inUse} üründe kullanılıyor.");
        }
        $item->delete();

        return redirect()->route('yonetim.urun-rozetleri.index')->with('success', 'Rozet silindi.');
    }

    private function validated(Request $request): array
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
        $base = Str::slug($value) ?: 'rozet';
        $slug = $base;
        $suffix = 2;
        while (ProductBadge::where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
