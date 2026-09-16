<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function index()
    {
        $languages = Language::orderBy('order')->get();
        return view('yonetim.languages.index', compact('languages'));
    }

    public function create()
    {
        return view('yonetim.languages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required|unique:languages,code',
        ]);

        Language::create($request->all());

        return redirect()->route('yonetim.languages.index')->with('success', 'Dil başarıyla eklendi.');
    }

    public function edit(Language $language)
    {
        return view('yonetim.languages.edit', compact('language'));
    }

    public function update(Request $request, Language $language)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required|unique:languages,code,' . $language->id,
        ]);

        $data = $request->all();
        $data['status'] = $request->get('status', $language->status);

        if ($request->has('is_default') && $request->is_default == 1) {
            Language::where('id', '!=', $language->id)->update(['is_default' => 0]);
            $data['is_default'] = 1;
            $data['status'] = 1; // Default dil pasif olamaz
        } else {
            $data['is_default'] = $language->is_default;
        }

        $language->update($data);

        return redirect()->route('yonetim.languages.index')->with('success', 'Dil başarıyla güncellendi.');
    }

    public function destroy(Language $language)
    {
        if ($language->is_default) {
            return back()->with('error', 'Varsayılan dil silinemez.');
        }

        $language->delete();

        return redirect()->route('yonetim.languages.index')->with('success', 'Dil başarıyla silindi.');
    }
}
