<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StaticTranslation;
use App\Models\Language;
use Illuminate\Http\Request;

class StaticTranslationController extends Controller
{
    public function index(Request $request)
    {
        $languages = Language::active();
        $group = $request->get('group', 'frontend');
        $defaultLang = Language::default()->code;

        $keys = StaticTranslation::where('group', $group)
            ->distinct()
            ->orderBy('key')
            ->pluck('key');

        $translations = $keys->map(function ($key) use ($group, $defaultLang) {
            return StaticTranslation::firstOrCreate(
                ['lang_key' => $defaultLang, 'group' => $group, 'key' => $key],
                ['value' => '']
            );
        });

        return view('yonetim.translations.index', compact('languages', 'translations', 'group'));
    }

    public function update(Request $request)
    {
        $data = $request->get('translations', []);
        $group = $request->get('group', 'general');

        foreach ($data as $langKey => $keys) {
            foreach ($keys as $key => $value) {
                StaticTranslation::updateOrCreate(
                    [
                        'lang_key' => $langKey,
                        'group' => $group,
                        'key' => $key,
                    ],
                    ['value' => $value]
                );
            }
        }

        return redirect()
            ->route('yonetim.translations.index', ['group' => $group])
            ->with('success', 'Çeviriler başarıyla güncellendi.');
    }
}
