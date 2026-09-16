<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        return view('yonetim.ayarlar');
    }

    public function update(Request $request)
    {
        if ($request->hasFile('site_logo')) {
            $this->storeUploadedFile($request->file('site_logo'), 'site_logo', 'logo_');
        }

        if ($request->hasFile('site_favicon')) {
            $this->storeUploadedFile($request->file('site_favicon'), 'site_favicon', 'favicon_');
        }

        $this->handlePdfSetting(
            $request,
            'catalog_pdf',
            'remove_catalog_pdf'
        );

        $this->handlePdfSetting(
            $request,
            'legal_cookie_policy_pdf',
            'remove_legal_cookie_policy_pdf'
        );

        $this->handlePdfSetting(
            $request,
            'legal_terms_pdf',
            'remove_legal_terms_pdf'
        );

        $settings = $request->except([
            '_token',
            'site_logo',
            'site_favicon',
            'catalog_pdf',
            'legal_cookie_policy_pdf',
            'legal_terms_pdf',
            'remove_catalog_pdf',
            'remove_legal_cookie_policy_pdf',
            'remove_legal_terms_pdf',
            'active_tab',
            'product_faqs',
            'shoes_note',
            'shoes_footer',
            'shoes_headers',
            'shoes_rows',
            'apparel_note',
            'apparel_footer',
            'apparel_headers',
            'apparel_rows',
        ]);

        $active_tab = $request->input('active_tab');

        if ($active_tab == 'recaptcha' && !$request->has('recaptcha_status')) {
            \App\Models\Setting::set('recaptcha_status', 0);
        }

        if ($active_tab == 'mailjet' && !$request->has('mailjet_status')) {
            \App\Models\Setting::set('mailjet_status', 0);
        }

        foreach ($settings as $key => $value) {
            \App\Models\Setting::set($key, $value);
        }

        if ($active_tab === 'urun-detay') {
            $this->saveProductDetailSettings($request);
        }

        return redirect()->route('yonetim.ayarlar', ['tab' => $active_tab])->with('success', 'Ayarlar başarıyla güncellendi.');
    }

    private function saveProductDetailSettings(Request $request): void
    {
        $faqs = [];
        foreach ((array) $request->input('product_faqs', []) as $row) {
            if (! is_array($row)) {
                continue;
            }
            $question = trim((string) ($row['question'] ?? ''));
            $answer = trim((string) ($row['answer'] ?? ''));
            if ($question === '' && $answer === '') {
                continue;
            }
            $faqs[] = [
                'question' => $question,
                'answer' => $answer,
            ];
        }
        \App\Models\Setting::set('product_detail_faqs', json_encode($faqs, JSON_UNESCAPED_UNICODE));

        foreach (['shoes' => 'product_size_chart_shoes', 'apparel' => 'product_size_chart_apparel'] as $prefix => $settingKey) {
            $headers = array_values(array_filter(array_map('trim', (array) $request->input("{$prefix}_headers", [])), fn ($v) => $v !== ''));
            $rows = [];
            foreach ((array) $request->input("{$prefix}_rows", []) as $row) {
                if (! is_array($row)) {
                    continue;
                }
                $cells = array_map(fn ($c) => trim((string) $c), array_values($row));
                if (collect($cells)->filter(fn ($c) => $c !== '')->isEmpty()) {
                    continue;
                }
                $rows[] = $cells;
            }
            \App\Models\Setting::set($settingKey, json_encode([
                'note' => trim((string) $request->input("{$prefix}_note", '')),
                'headers' => $headers,
                'rows' => $rows,
                'footer' => trim((string) $request->input("{$prefix}_footer", '')),
            ], JSON_UNESCAPED_UNICODE));
        }
    }

    private function handlePdfSetting(Request $request, string $field, string $removeField): void
    {
        if ($request->boolean($removeField)) {
            $this->deleteStoredFile(\App\Models\Setting::get($field));
            \App\Models\Setting::set($field, '');
        }

        if ($request->hasFile($field)) {
            $request->validate([
                $field => 'file|mimes:pdf|max:20480',
            ]);

            $this->deleteStoredFile(\App\Models\Setting::get($field));
            $this->storeUploadedFile($request->file($field), $field, str_replace('_pdf', '_', $field));
        }
    }

    private function storeUploadedFile($file, string $settingKey, string $prefix): void
    {
        $this->deleteStoredFile(\App\Models\Setting::get($settingKey));

        $extension = strtolower($file->getClientOriginalExtension() ?: 'pdf');
        $filename = $prefix . time() . '.' . $extension;
        $file->move(public_path('uploads'), $filename);
        \App\Models\Setting::set($settingKey, $filename);
    }

    private function deleteStoredFile(?string $filename): void
    {
        $filename = trim((string) $filename);

        if ($filename === '' || str_contains($filename, '://')) {
            return;
        }

        $path = public_path('uploads/' . ltrim($filename, '/'));

        if (is_file($path)) {
            unlink($path);
        }
    }
}
