<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    private const IMAGE_MAX_KB = 10240; // 10 MB

    private function ensureProductDocumentsDir(): string
    {
        $dir = public_path('uploads/product_documents');
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        return $dir;
    }

    private function uniqueRootSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value) ?: 'urun';
        $slug = $base;
        $suffix = 2;

        while (Product::where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }

    private function uniqueTranslationSlug(string $lang, string $value, ?int $ignoreProductId = null): string
    {
        $base = Str::slug($value) ?: 'urun';
        $slug = $base;
        $suffix = 2;

        while (\App\Models\ProductTranslation::where('lang_key', $lang)
            ->where('slug', $slug)
            ->when($ignoreProductId, fn ($query) => $query->where('product_id', '!=', $ignoreProductId))
            ->exists()) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }

    private function productValidationRules(): array
    {
        $imageMax = self::IMAGE_MAX_KB;

        return [
            'category_id' => 'required|exists:product_categories,id',
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'why_title' => 'nullable|string|max:255',
            'certificates_intro' => 'nullable|string',
            'advantages_intro' => 'nullable|string',
            'cta_title' => 'nullable|string|max:255',
            'cta_text' => 'nullable|string',
            'order' => 'nullable|integer',
            'status' => 'nullable|boolean',
            'home_status' => 'nullable|boolean',
            'sku' => 'nullable|string|max:64',
            'badge' => 'nullable|string|max:32',
            'color' => 'nullable|string|exists:product_colors,slug',
            'panel_size' => 'nullable|string|max:64',
            'size_extra' => 'nullable|string|max:120',
            'thick' => 'nullable|string|max:32',
            'indoor' => 'nullable|boolean',
            'outdoor' => 'nullable|boolean',
            'depot' => 'nullable|boolean',
            'source_url' => 'nullable|string|max:500',
            'main_image' => "nullable|image|mimes:jpeg,jpg,png,gif,webp|max:{$imageMax}",
            'hover_image' => "nullable|image|mimes:jpeg,jpg,png,gif,webp|max:{$imageMax}",
            'gallery' => 'nullable|array',
            'gallery.*' => "nullable|image|mimes:jpeg,jpg,png,gif,webp|max:{$imageMax}",
            'documents' => 'nullable|array',
            'document_files' => 'nullable|array',
            'document_files.*' => 'nullable|file|max:20480',
            'features' => 'nullable|array',
            'technical_specs' => 'nullable|array',
            'certificates' => 'nullable|array',
            'advantages' => 'nullable|array',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:255',
            'tech_media' => 'nullable|array',
            'remove_tech_media' => 'nullable|array',
        ];
    }

    private function productValidationMessages(): array
    {
        $maxMb = (int) (self::IMAGE_MAX_KB / 1024);

        return [
            'main_image.image' => 'Ana görsel geçerli bir resim dosyası olmalıdır (JPEG, PNG, GIF, WEBP).',
            'main_image.mimes' => 'Ana görsel JPEG, PNG, GIF veya WEBP formatında olmalıdır.',
            'main_image.max' => "Ana görsel en fazla {$maxMb} MB olabilir.",
            'gallery.*.image' => 'Galeri dosyaları geçerli bir resim olmalıdır.',
            'gallery.*.mimes' => 'Galeri dosyaları JPEG, PNG, GIF veya WEBP formatında olmalıdır.',
            'gallery.*.max' => "Galeri görselleri en fazla {$maxMb} MB olabilir.",
            'document_files.*.max' => 'Belge dosyası en fazla 20 MB olabilir.',
            'name.required' => 'Ürün adı zorunludur.',
            'category_id.required' => 'Kategori seçimi zorunludur.',
            'category_id.exists' => 'Seçilen kategori geçersiz.',
        ];
    }

    private function prepareTranslationData(array $translations, ?int $ignoreProductId = null): array
    {
        $prepared = [];

        foreach ($translations as $lang => $data) {
            if (! is_array($data)) {
                continue;
            }

            $slugSource = $data['slug'] ?? $data['name'] ?? 'urun';
            $data['slug'] = $this->uniqueTranslationSlug((string) $lang, (string) $slugSource, $ignoreProductId);

            if (array_key_exists('features', $data)) {
                $data['features'] = $this->normalizeFeatureList($data['features']);
            }

            $prepared[$lang] = $data;
        }

        return $prepared;
    }

    private function normalizeFeatureList(mixed $features): array
    {
        if (! is_array($features)) {
            return [];
        }

        if (isset($features['items']) && is_array($features['items'])) {
            $features = $features['items'];
        }

        $items = [];
        foreach ($features as $item) {
            if (is_string($item)) {
                $text = trim($item);
            } elseif (is_array($item)) {
                $text = trim((string) ($item['title'] ?? $item['text'] ?? $item['value'] ?? ''));
            } else {
                $text = '';
            }

            if ($text !== '') {
                $items[] = $text;
            }
        }

        return array_values($items);
    }

    private function processProductDocuments(Request $request, ?array $existingDocuments = []): array
    {
        if (! $request->has('documents_present')) {
            return is_array($existingDocuments) ? $existingDocuments : [];
        }

        $existingDocuments = is_array($existingDocuments) ? $existingDocuments : [];
        $existingByFile = collect($existingDocuments)->keyBy('file');
        $documents = [];
        $input = $request->input('documents', []);

        foreach ($input as $index => $doc) {
            $title = trim($doc['title'] ?? '');
            $subtitle = trim($doc['subtitle'] ?? '');
            $file = trim($doc['file'] ?? '');
            $fileExt = strtolower(trim($doc['file_ext'] ?? ''));

            if ($request->hasFile("document_files.$index")) {
                $uploadedFile = $request->file("document_files.$index");
                if ($file && file_exists(public_path('uploads/' . $file))) {
                    unlink(public_path('uploads/' . $file));
                }

                $fileName = time() . '_' . $index . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $uploadedFile->getClientOriginalName());
                $uploadedFile->move($this->ensureProductDocumentsDir(), $fileName);
                $file = 'product_documents/' . $fileName;
                $fileExt = strtolower($uploadedFile->getClientOriginalExtension());
            } elseif ($request->hasFile('document_files') && isset($request->file('document_files')[$index])) {
                $uploadedFile = $request->file('document_files')[$index];
                if ($file && file_exists(public_path('uploads/' . $file))) {
                    unlink(public_path('uploads/' . $file));
                }

                $fileName = time() . '_' . $index . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $uploadedFile->getClientOriginalName());
                $uploadedFile->move($this->ensureProductDocumentsDir(), $fileName);
                $file = 'product_documents/' . $fileName;
                $fileExt = strtolower($uploadedFile->getClientOriginalExtension());
            } elseif ($file && $existingByFile->has($file)) {
                $fileExt = $fileExt ?: ($existingByFile[$file]['file_ext'] ?? '');
            }

            if ($title === '' && $subtitle === '' && $file === '') {
                continue;
            }

            $documents[] = array_filter([
                'title' => $title,
                'subtitle' => $subtitle,
                'file' => $file ?: null,
                'file_ext' => $fileExt ?: null,
            ], fn ($value) => $value !== null && $value !== '');
        }

        $keptFiles = collect($documents)->pluck('file')->filter()->all();
        foreach ($existingDocuments as $oldDoc) {
            $oldFile = $oldDoc['file'] ?? null;
            if ($oldFile && ! in_array($oldFile, $keptFiles, true) && file_exists(public_path('uploads/' . $oldFile))) {
                unlink(public_path('uploads/' . $oldFile));
            }
        }

        return $documents;
    }

    private function applyTechMedia(Request $request, array $translations): array
    {
        foreach ($translations as $lang => &$data) {
            if (! is_array($data)) {
                continue;
            }

            $this->applyTechMediaRemovals($data, $request->input("remove_tech_media.$lang", []));
            $this->applyTechMediaUploads($data, $request->file("tech_media.$lang") ?? []);
        }
        unset($data);

        return $translations;
    }

    private function applyTechMediaRemovals(array &$data, $removals, array $path = []): void
    {
        if (! is_array($removals)) {
            return;
        }

        foreach ($removals as $key => $value) {
            $currentPath = array_merge($path, [$key]);

            if ($value === '1' || $value === 1 || $value === true) {
                $existing = data_get($data, $this->dotPath($currentPath));
                $this->deleteUploadedTechMedia(is_string($existing) ? $existing : null);
                data_set($data, $this->dotPath($currentPath), '');
                continue;
            }

            if (is_array($value)) {
                $this->applyTechMediaRemovals($data, $value, $currentPath);
            }
        }
    }

    private function applyTechMediaUploads(array &$data, $uploads, array $path = []): void
    {
        if (! is_array($uploads)) {
            return;
        }

        foreach ($uploads as $key => $value) {
            $currentPath = array_merge($path, [$key]);

            if ($value instanceof UploadedFile) {
                $maxKb = self::IMAGE_MAX_KB;
                validator(
                    ['file' => $value],
                    ['file' => "required|file|mimes:jpeg,png,jpg,gif,svg,webp|max:{$maxKb}"]
                )->validate();

                $existing = data_get($data, $this->dotPath($currentPath));
                $this->deleteUploadedTechMedia(is_string($existing) ? $existing : null);

                $safeName = preg_replace('/[^A-Za-z0-9._-]/', '_', $value->getClientOriginalName()) ?: 'media.bin';
                $fileName = time() . '_tech_' . $safeName;
                $value->move(public_path('uploads'), $fileName);
                data_set($data, $this->dotPath($currentPath), $fileName);
                continue;
            }

            if (is_array($value)) {
                $this->applyTechMediaUploads($data, $value, $currentPath);
            }
        }
    }

    private function deleteUploadedTechMedia(?string $file): void
    {
        $file = ltrim((string) $file, '/');
        if ($file === '' || filter_var($file, FILTER_VALIDATE_URL) || str_starts_with($file, 'assets/')) {
            return;
        }

        if (str_starts_with($file, 'uploads/')) {
            $file = substr($file, strlen('uploads/'));
        }

        $fullPath = public_path('uploads/' . $file);
        if (is_file($fullPath)) {
            unlink($fullPath);
        }
    }

    private function dotPath(array $path): string
    {
        return implode('.', $path);
    }

    private function applySilvaFields(array &$validated, Request $request): void
    {
        if (\Illuminate\Support\Facades\Schema::hasColumn('products', 'color')) {
            $validated['color'] = $request->input('color') ?: null;
        }
        if (\Illuminate\Support\Facades\Schema::hasColumn('products', 'panel_size')) {
            $validated['panel_size'] = $request->input('panel_size') ?: null;
        }
        if (\Illuminate\Support\Facades\Schema::hasColumn('products', 'size_extra')) {
            $validated['size_extra'] = $request->input('size_extra') ?: null;
        }
        if (\Illuminate\Support\Facades\Schema::hasColumn('products', 'thick')) {
            $validated['thick'] = $request->input('thick') ?: null;
        }
        if (\Illuminate\Support\Facades\Schema::hasColumn('products', 'indoor')) {
            $validated['indoor'] = (bool) $request->input('indoor', 0);
        }
        if (\Illuminate\Support\Facades\Schema::hasColumn('products', 'outdoor')) {
            $validated['outdoor'] = (bool) $request->input('outdoor', 0);
        }
        if (\Illuminate\Support\Facades\Schema::hasColumn('products', 'depot')) {
            $validated['depot'] = (bool) $request->input('depot', 0);
        }
        if (\Illuminate\Support\Facades\Schema::hasColumn('products', 'source_url')) {
            $validated['source_url'] = $request->input('source_url') ?: null;
        }
        if (\Illuminate\Support\Facades\Schema::hasColumn('products', 'hover_image') && $request->hasFile('hover_image')) {
            $file = $request->file('hover_image');
            $fileName = time() . '_hover_' . preg_replace('/[^A-Za-z0-9._-]/', '_', $file->getClientOriginalName());
            $file->move(public_path('uploads'), $fileName);
            $validated['hover_image'] = $fileName;
        }
    }

    private function categoryOptions()
    {
        $query = ProductCategory::query()->orderBy('order');

        if (\Illuminate\Support\Facades\Schema::hasColumn('product_categories', 'parent_id')) {
            return $query->with(['children' => fn ($q) => $q->orderBy('order')])->get();
        }

        return $query->get();
    }

    public function index()
    {
        $products = Product::with('category')->orderBy('order')->get();
        return view('yonetim.urunler.index', compact('products'));
    }

    public function create()
    {
        $categories = $this->categoryOptions();
        $colors = \App\Models\ProductColor::optionsMap(null, false);

        return view('yonetim.urunler.create', compact('categories', 'colors'));
    }

    public function store(Request $request)
    {
        // Pull default language values into root for validation
        $defaultLang = \App\Models\Language::where('is_default', 1)->first()->code ?? 'tr';
        if ($request->has("translations.{$defaultLang}")) {
            $request->merge($request->input("translations.{$defaultLang}"));
        }

        $validated = $request->validate(
            $this->productValidationRules(),
            $this->productValidationMessages()
        );

        $validated['slug'] = $this->uniqueRootSlug($request->input('slug') ?: $request->name);

        if ($request->hasFile('main_image')) {
            $file = $request->file('main_image');
            $fileName = time() . '_prod_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $fileName);
            $validated['main_image'] = $fileName;
        }

        if ($request->hasFile('gallery')) {
            $gallery = [];
            foreach ($request->file('gallery') as $file) {
                $fileName = time() . '_gal_' . $file->getClientOriginalName();
                $file->move(public_path('uploads'), $fileName);
                $gallery[] = $fileName;
            }
            $validated['gallery'] = $gallery;
        }

        $validated['documents'] = $this->processProductDocuments($request);

        if (\Illuminate\Support\Facades\Schema::hasColumn('products', 'sku')) {
            $validated['sku'] = $request->input('sku') ?: null;
        }
        if (\Illuminate\Support\Facades\Schema::hasColumn('products', 'badge')) {
            $validated['badge'] = $request->input('badge') ?: null;
        }
        if (\Illuminate\Support\Facades\Schema::hasColumn('products', 'home_status')) {
            $validated['home_status'] = (bool) $request->input('home_status', 0);
        }
        $this->applySilvaFields($validated, $request);

        $product = Product::create($validated);

        $translationPayload = $this->prepareTranslationData($request->get('translations', []));
        foreach ($this->applyTechMedia($request, $translationPayload) as $lang => $data) {
            if (array_key_exists('features', $data) && $lang === ($defaultLang ?? 'tr')) {
                $product->features = $data['features'];
                $product->save();
            }
            $product->translations()->updateOrCreate(
                ['lang_key' => $lang],
                $data
            );
        }

        return redirect()->route('yonetim.urunler.index')->with('success', 'Ürün başarıyla eklendi.');
    }

    public function edit(Product $urun)
    {
        $product = $urun;
        $categories = $this->categoryOptions();
        $colors = \App\Models\ProductColor::optionsMap(null, false);

        return view('yonetim.urunler.edit', compact('product', 'categories', 'colors'));
    }

    public function update(Request $request, Product $urun)
    {
        // Pull default language values into root for validation
        $defaultLang = \App\Models\Language::where('is_default', 1)->first()->code ?? 'tr';
        if ($request->has("translations.{$defaultLang}")) {
            $request->merge($request->input("translations.{$defaultLang}"));
        }

        $rules = $this->productValidationRules();
        $rules['old_gallery'] = 'nullable|array';

        $validated = $request->validate($rules, $this->productValidationMessages());

        $validated['slug'] = $this->uniqueRootSlug(
            $request->input('slug') ?: $request->name,
            $urun->id
        );

        $mainImage = $urun->main_image;
        if ($request->hasFile('main_image')) {
            if ($mainImage && file_exists(public_path('uploads/' . $mainImage))) {
                unlink(public_path('uploads/' . $mainImage));
            }
            $file = $request->file('main_image');
            $fileName = time() . '_prod_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $fileName);
            $mainImage = $fileName;
        } elseif ($request->boolean('remove_main_image')) {
            if ($mainImage && file_exists(public_path('uploads/' . $mainImage))) {
                unlink(public_path('uploads/' . $mainImage));
            }
            $mainImage = null;
        }

        $gallery = $request->old_gallery ?? [];
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $fileName = time() . '_gal_' . $file->getClientOriginalName();
                $file->move(public_path('uploads'), $fileName);
                $gallery[] = $fileName;
            }
        }

        $urun->category_id = $validated['category_id'];
        $urun->name = $validated['name'];
        $urun->slug = $validated['slug'];
        $urun->title = $validated['title'] ?? null;
        $urun->short_description = $validated['short_description'] ?? null;
        $urun->description = $validated['description'] ?? null;
        $urun->seo_title = $validated['seo_title'] ?? null;
        $urun->seo_description = $validated['seo_description'] ?? null;
        $urun->order = $validated['order'] ?? 0;
        $urun->status = $validated['status'] ?? true;
        if (\Illuminate\Support\Facades\Schema::hasColumn('products', 'sku')) {
            $urun->sku = $request->input('sku') ?: null;
        }
        if (\Illuminate\Support\Facades\Schema::hasColumn('products', 'badge')) {
            $urun->badge = $request->input('badge') ?: null;
        }
        if (\Illuminate\Support\Facades\Schema::hasColumn('products', 'home_status')) {
            $urun->home_status = (bool) $request->input('home_status', 0);
        }
        $silva = [];
        $this->applySilvaFields($silva, $request);
        foreach ($silva as $key => $value) {
            $urun->{$key} = $value;
        }
        $urun->gallery = $gallery;
        $urun->documents = $this->processProductDocuments($request, json_decode($urun->getRawOriginal('documents') ?? '[]', true) ?: []);
        $urun->main_image = $mainImage;
        $urun->save();

        $translationPayload = $this->prepareTranslationData($request->get('translations', []), $urun->id);
        foreach ($this->applyTechMedia($request, $translationPayload) as $lang => $data) {
            if (array_key_exists('features', $data)) {
                $urun->features = $data['features'];
            }
            $urun->translations()->updateOrCreate(
                ['lang_key' => $lang],
                $data
            );
        }

        return redirect()->route('yonetim.urunler.index')->with('success', 'Ürün başarıyla güncellendi.');
    }

    public function destroy(Product $urun)
    {
        if ($urun->main_image && file_exists(public_path('uploads/' . $urun->main_image))) {
            unlink(public_path('uploads/' . $urun->main_image));
        }
        if ($urun->gallery) {
            foreach ($urun->gallery as $img) {
                if (file_exists(public_path('uploads/' . $img))) {
                    unlink(public_path('uploads/' . $img));
                }
            }
        }
        if (is_array($urun->documents)) {
            foreach ($urun->documents as $doc) {
                $file = $doc['file'] ?? null;
                if ($file && file_exists(public_path('uploads/' . $file))) {
                    unlink(public_path('uploads/' . $file));
                }
            }
        }
        $urun->delete();
        return redirect()->route('yonetim.urunler.index')->with('success', 'Ürün başarıyla silindi.');
    }
}
