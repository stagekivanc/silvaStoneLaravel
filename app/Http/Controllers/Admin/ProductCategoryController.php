<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductCategoryController extends Controller
{
    protected function defaultLang(): string
    {
        return \App\Models\Language::where('is_default', 1)->value('code') ?? 'tr';
    }

    protected function mergeDefaultTranslation(Request $request): void
    {
        $defaultLang = $this->defaultLang();
        if ($request->has("translations.{$defaultLang}")) {
            $request->merge($request->input("translations.{$defaultLang}"));
        }
    }

    public function index()
    {
        $categories = ProductCategory::orderBy('order')->get();
        return view('yonetim.urunler.kategoriler.index', compact('categories'));
    }

    public function create()
    {
        return view('yonetim.urunler.kategoriler.create');
    }

    public function store(Request $request)
    {
        $this->mergeDefaultTranslation($request);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
            'status' => 'nullable|in:0,1',
            'home_status' => 'nullable|in:0,1',
            'image' => 'nullable|image|max:2048',
            'icon' => 'nullable|file|mimes:png,jpg,jpeg,webp,svg|max:1024',
            'icon_home' => 'nullable|file|mimes:png,jpg,jpeg,webp,svg|max:2048',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:255',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['status'] = $request->input('status', 1);
        $validated['home_status'] = $request->input('home_status', 1);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_cat_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $fileName);
            $validated['image'] = $fileName;
        }

        if ($request->hasFile('icon')) {
            $file = $request->file('icon');
            $fileName = time() . '_icon_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $fileName);
            $validated['icon'] = $fileName;
        }

        if ($request->hasFile('icon_home')) {
            $file = $request->file('icon_home');
            $fileName = time() . '_icon_home_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $fileName);
            $validated['icon_home'] = $fileName;
        }

        $category = ProductCategory::create($validated);

        foreach ($request->get('translations', []) as $lang => $data) {
            if (! empty($data['slug'])) {
                $data['slug'] = Str::slug($data['slug']);
            } elseif (! empty($data['name'])) {
                $data['slug'] = Str::slug($data['name']);
            }
            $category->translations()->updateOrCreate(
                ['lang_key' => $lang],
                $data
            );
        }

        return redirect()->route('yonetim.urun-kategorileri.index')->with('success', 'Kategori başarıyla eklendi.');
    }

    public function edit(ProductCategory $urun_kategorisi)
    {
        $urun_kategorisi->load('translations');

        return view('yonetim.urunler.kategoriler.edit', ['category' => $urun_kategorisi]);
    }

    public function update(Request $request, ProductCategory $urun_kategorisi)
    {
        $this->mergeDefaultTranslation($request);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
            'status' => 'nullable|in:0,1',
            'home_status' => 'nullable|in:0,1',
            'image' => 'nullable|image|max:2048',
            'icon' => 'nullable|file|mimes:png,jpg,jpeg,webp,svg|max:1024',
            'icon_home' => 'nullable|file|mimes:png,jpg,jpeg,webp,svg|max:2048',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:255',
        ]);

        $image = $urun_kategorisi->image;
        $previousImage = $image;
        if ($request->hasFile('image')) {
            $this->deleteUploadedMedia($image);
            $image = $this->storeUploadedMedia($request->file('image'), 'cat');
        } elseif ($request->boolean('remove_image')) {
            $this->deleteUploadedMedia($image);
            $image = null;
        }

        $icon = $urun_kategorisi->icon;
        if ($request->hasFile('icon')) {
            $this->deleteUploadedMedia($icon);
            $icon = $this->storeUploadedMedia($request->file('icon'), 'icon');
        } elseif ($request->boolean('remove_icon')) {
            $this->deleteUploadedMedia($icon);
            $icon = null;
        }

        $iconHome = $urun_kategorisi->icon_home;
        if ($request->hasFile('icon_home')) {
            $this->deleteUploadedMedia($iconHome);
            $iconHome = $this->storeUploadedMedia($request->file('icon_home'), 'icon_home');
        } elseif ($request->boolean('remove_icon_home')) {
            $this->deleteUploadedMedia($iconHome);
            $iconHome = null;
        } elseif ($request->hasFile('image') && ($iconHome === null || $iconHome === $previousImage || str_starts_with((string) $iconHome, 'front-assets/'))) {
            // Keep homepage/urunler cards in sync when only the main category image is replaced.
            $iconHome = $image;
        }

        $urun_kategorisi->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'seo_title' => $validated['seo_title'] ?? null,
            'seo_description' => $validated['seo_description'] ?? null,
            'order' => $validated['order'] ?? $urun_kategorisi->order,
            'status' => $request->input('status', $urun_kategorisi->status),
            'home_status' => $request->input('home_status', $urun_kategorisi->home_status),
            'image' => $image,
            'icon' => $icon,
            'icon_home' => $iconHome,
        ]);

        foreach ($request->get('translations', []) as $lang => $data) {
            if (! empty($data['slug'])) {
                $data['slug'] = Str::slug($data['slug']);
            } elseif (! empty($data['name'])) {
                $data['slug'] = Str::slug($data['name']);
            }
            $urun_kategorisi->translations()->updateOrCreate(
                ['lang_key' => $lang],
                $data
            );
        }

        return redirect()->route('yonetim.urun-kategorileri.index')->with('success', 'Kategori başarıyla güncellendi.');
    }

    public function destroy(ProductCategory $urun_kategorisi)
    {
        $this->deleteUploadedMedia($urun_kategorisi->image);
        $this->deleteUploadedMedia($urun_kategorisi->icon);
        $this->deleteUploadedMedia($urun_kategorisi->icon_home);
        $urun_kategorisi->delete();

        return redirect()->route('yonetim.urun-kategorileri.index')->with('success', 'Kategori başarıyla silindi.');
    }

    private function storeUploadedMedia(\Illuminate\Http\UploadedFile $file, string $prefix): string
    {
        $safeName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName()) ?: 'media.bin';
        $fileName = time() . '_' . uniqid($prefix . '_', true) . '_' . $safeName;
        $file->move(public_path('uploads'), $fileName);

        return $fileName;
    }

    private function deleteUploadedMedia(?string $path): void
    {
        $path = trim((string) $path);
        if ($path === '' || filter_var($path, FILTER_VALIDATE_URL) || str_starts_with($path, 'front-assets/')) {
            return;
        }

        $normalized = ltrim($path, '/');
        if (str_starts_with($normalized, 'uploads/')) {
            $normalized = substr($normalized, strlen('uploads/'));
        }

        $fullPath = public_path('uploads/' . $normalized);
        if (is_file($fullPath)) {
            unlink($fullPath);
        }
    }
}
