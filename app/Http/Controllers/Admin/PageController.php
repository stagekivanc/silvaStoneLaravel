<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::all();
        return view('yonetim.sayfalar.index', compact('pages'));
    }

    public function edit(Page $page)
    {
        $dynamicFields = \App\Services\PageTemplateService::getFields($page->type);
        return view('yonetim.sayfalar.edit', compact('page', 'dynamicFields'));
    }

    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug,' . $page->id,
            'type' => [
                'required',
                Rule::in([
                    'index',
                    'corporate',
                    'contact',
                    'solution',
                    'sustainability',
                    'application',
                    'kvkk',
                    'cookie-policy',
                    'terms',
                    'products',
                    'privacy-policy',
                    'return-policy',
                    'shipping-policy',
                    'kvkk-law',
                    'personal-data',
                    'contracts',
                    'stores',
                    'projects',
                    'faq',
                    'references',
                    'login',
                    'search',
                ]),
            ],
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'content_text' => 'nullable|string',
            'body_content' => 'nullable|string',
            'extra_content' => 'nullable|string',
            'why_us_main_title' => 'nullable|string|max:255',
            'why_us_title_1' => 'nullable|string|max:255',
            'why_us_desc_1' => 'nullable|string',
            'why_us_title_2' => 'nullable|string|max:255',
            'why_us_desc_2' => 'nullable|string',
            'why_us_title_3' => 'nullable|string|max:255',
            'why_us_desc_3' => 'nullable|string',
            'mission_title' => 'nullable|string|max:255',
            'mission_content' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'extra_images' => 'nullable|array',
            'extra_gallery_files' => 'nullable|array',
            'extra_gallery_files.*' => 'nullable|array',
            'extra_gallery_files.*.*' => 'nullable|array',
            'extra_gallery_files.*.*.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'remove_extra_images' => 'nullable|array',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:255',
            'seo_keywords' => 'nullable|string|max:255',
            'extras' => 'nullable|array',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($page->image && file_exists(public_path('uploads/' . $page->image))) {
                unlink(public_path('uploads/' . $page->image));
            }
            
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $fileName);
            $validated['image'] = $fileName;
        } elseif ($request->boolean('remove_image')) {
            if ($page->image && file_exists(public_path('uploads/' . $page->image))) {
                unlink(public_path('uploads/' . $page->image));
            }
            $validated['image'] = null;
        }

        $page->update([
            'image' => array_key_exists('image', $validated) ? $validated['image'] : $page->image,
            'type' => $validated['type'] ?? $page->type,
        ]);

        foreach ($request->get('translations', []) as $lang => $data) {
            $translation = $page->translations()->where('lang_key', $lang)->first();
            $existingExtras = is_array($translation?->extras) ? $translation->extras : [];
            $data['extras'] = $this->mergeExtraImages(
                $request,
                $lang,
                $data['extras'] ?? [],
                $existingExtras
            );
            $data['extras'] = $this->mergeExtraFiles(
                $request,
                $lang,
                $data['extras'] ?? [],
                $existingExtras
            );
            $data['extras'] = $this->mergeExtraGalleries(
                $request,
                $lang,
                $data['extras'] ?? [],
                $existingExtras,
                $page->type
            );

            $page->translations()->updateOrCreate(
                ['lang_key' => $lang],
                $data
            );
        }

        $activeTab = $request->input('active_tab', 'content');
        $activeLang = $request->input('active_lang', 'tr');

        return redirect()->route('yonetim.sayfalar.edit', [
            'page' => $page->id,
            'tab' => $activeTab,
            'lang' => $activeLang
        ])->with('success', 'Sayfa başarıyla güncellendi.');
    }

    private function mergeExtraImages(Request $request, string $lang, array $extras, array $existingExtras): array
    {
        $removals = $request->input("remove_extra_images.$lang", []);
        if (is_array($removals)) {
            $this->applyExtraImageRemovals($extras, $existingExtras, $removals);
        }

        $uploads = $request->file("extra_images.$lang", []);
        if (is_array($uploads)) {
            $this->applyExtraImageUploads($extras, $existingExtras, $uploads);
        }

        return $extras;
    }

    private function applyExtraImageRemovals(array &$extras, array $existingExtras, array $removals, array $path = []): void
    {
        foreach ($removals as $key => $value) {
            $currentPath = array_merge($path, [$key]);

            if ($value === '1' || $value === 1 || $value === true) {
                $existingFile = data_get($existingExtras, $this->pathToKey($currentPath));
                $this->deleteStoredExtraImage($existingFile);
                data_set($extras, $this->pathToKey($currentPath), '');
                continue;
            }

            if (is_array($value)) {
                $this->applyExtraImageRemovals($extras, $existingExtras, $value, $currentPath);
            }
        }
    }

    private function applyExtraImageUploads(array &$extras, array $existingExtras, array $uploads, array $path = []): void
    {
        foreach ($uploads as $key => $value) {
            $currentPath = array_merge($path, [$key]);

            if ($value instanceof \Illuminate\Http\UploadedFile) {
                $mime = strtolower((string) $value->getMimeType());
                $isVideo = str_starts_with($mime, 'video/');
                $rules = $isVideo
                    ? 'required|file|mimes:mp4,webm,mov,ogg,m4v|max:102400'
                    : 'required|file|mimes:jpeg,png,jpg,gif,svg,webp|max:2048';

                validator(
                    ['file' => $value],
                    ['file' => $rules]
                )->validate();

                $existingFile = data_get($existingExtras, $this->pathToKey($currentPath));
                $this->deleteStoredExtraImage($existingFile);

                $safeName = preg_replace('/[^A-Za-z0-9._-]/', '_', $value->getClientOriginalName()) ?: 'media.bin';
                $fileName = time() . '_page_extra_' . $safeName;
                $value->move(public_path('uploads'), $fileName);
                data_set($extras, $this->pathToKey($currentPath), $fileName);
                continue;
            }

            if (is_array($value)) {
                $this->applyExtraImageUploads($extras, $existingExtras, $value, $currentPath);
            }
        }
    }

    private function deleteStoredExtraImage(?string $file): void
    {
        if (!$file || filter_var($file, FILTER_VALIDATE_URL)) {
            return;
        }

        $file = ltrim($file, '/');
        if (str_starts_with($file, 'uploads/')) {
            $file = substr($file, strlen('uploads/'));
        }

        $fullPath = public_path('uploads/' . $file);
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }

    private function mergeExtraFiles(Request $request, string $lang, array $extras, array $existingExtras): array
    {
        $removals = $request->input("remove_extra_files.$lang", []);
        if (is_array($removals)) {
            $this->applyExtraImageRemovals($extras, $existingExtras, $removals);
        }

        $urls = $request->input("extra_file_urls.$lang", []);
        if (is_array($urls)) {
            $this->applyExtraFileUrls($extras, $urls);
        }

        $uploads = $request->file("extra_files.$lang", []);
        if (is_array($uploads)) {
            $this->applyExtraFileUploads($extras, $existingExtras, $uploads);
        }

        return $extras;
    }

    private function applyExtraFileUrls(array &$extras, array $urls, array $path = []): void
    {
        foreach ($urls as $key => $value) {
            $currentPath = array_merge($path, [$key]);

            if (is_array($value)) {
                $this->applyExtraFileUrls($extras, $value, $currentPath);
                continue;
            }

            $url = trim((string) $value);
            if ($url === '') {
                continue;
            }

            if (filter_var($url, FILTER_VALIDATE_URL)) {
                data_set($extras, $this->pathToKey($currentPath), $url);
            }
        }
    }

    private function applyExtraFileUploads(array &$extras, array $existingExtras, array $uploads, array $path = []): void
    {
        foreach ($uploads as $key => $value) {
            $currentPath = array_merge($path, [$key]);

            if ($value instanceof \Illuminate\Http\UploadedFile) {
                validator(
                    ['file' => $value],
                    ['file' => 'required|file|mimes:jpeg,png,jpg,gif,svg,webp,pdf|max:10240']
                )->validate();

                $existingFile = data_get($existingExtras, $this->pathToKey($currentPath));
                $this->deleteStoredExtraImage($existingFile);

                $safeName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $value->getClientOriginalName()) ?: 'file.bin';
                $fileName = time() . '_page_file_' . $safeName;
                $value->move(public_path('uploads'), $fileName);
                data_set($extras, $this->pathToKey($currentPath), $fileName);
                continue;
            }

            if (is_array($value)) {
                $this->applyExtraFileUploads($extras, $existingExtras, $value, $currentPath);
            }
        }
    }

    private function pathToKey(array $path): string
    {
        return implode('.', $path);
    }

    private function mergeExtraGalleries(Request $request, string $lang, array $extras, array $existingExtras, string $pageType): array
    {
        foreach (\App\Services\PageTemplateService::galleryFieldKeys($pageType) as $galleryKey) {
            $keys = explode('.', $galleryKey);
            $namePath = implode('.', $keys);

            $existingGallery = data_get($existingExtras, $namePath, []);
            if (!is_array($existingGallery)) {
                $existingGallery = [];
            }

            $keptGallery = $request->input("old_extra_gallery.$lang.$namePath", []);
            if (!is_array($keptGallery)) {
                $keptGallery = [];
            }

            $removedGallery = $request->input("remove_extra_gallery.$lang.$namePath", []);
            if (!is_array($removedGallery)) {
                $removedGallery = [];
            }

            $gallery = array_values(array_filter(
                $keptGallery,
                fn ($image) => $image && !in_array($image, $removedGallery, true)
            ));

            foreach ($removedGallery as $image) {
                $this->deleteStoredExtraImage($image);
            }

            $uploadedFiles = data_get($request->file('extra_gallery_files'), "$lang.$namePath", []);
            if (is_array($uploadedFiles)) {
                foreach ($uploadedFiles as $file) {
                    if (!$file instanceof \Illuminate\Http\UploadedFile) {
                        continue;
                    }

                    $fileName = time() . '_page_gallery_' . $file->getClientOriginalName();
                    $file->move(public_path('uploads'), $fileName);
                    $gallery[] = $fileName;
                }
            }

            data_set($extras, $namePath, array_values(array_unique($gallery)) ?: []);
        }

        return $extras;
    }
}
