<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rule;

class HomeController extends Controller
{
    public function index()
    {
        $page = \App\Models\Page::where('type', 'index')->first();
        $homepage = page_extras($page, \App\Support\SilvaHomepageDefaults::data());

        $hasHomeStatus = \Illuminate\Support\Facades\Schema::hasColumn('products', 'home_status');
        $hasBadge      = \Illuminate\Support\Facades\Schema::hasColumn('products', 'badge');

        $featuredProducts = \App\Models\Product::where('status', 1)
            ->when($hasHomeStatus, fn($q) => $q->where('home_status', 1))
            ->when($hasBadge, fn($q) => $q->orderByRaw("CASE WHEN badge = 'cok-satan' THEN 0 ELSE 1 END"))
            ->with('category')
            ->orderBy('order')
            ->take(8)
            ->get();

        $newProducts = \App\Models\Product::where('status', 1)
            ->when($hasBadge, fn($q) => $q->orderByRaw("CASE WHEN badge = 'yeni' THEN 0 ELSE 1 END"))
            ->with('category')
            ->latest()
            ->take(9)
            ->get();

        return view('frontend.index', compact('page', 'homepage', 'featuredProducts', 'newProducts'));
    }
    public function contact()
    {
        $page = \App\Models\Page::where('type', 'contact')->firstOrFail();
        $contact = page_extras($page, \App\Support\SilvaContactDefaults::data());

        return view('frontend.contact', compact('page', 'contact'));
    }

    public function storeContact(Request $request)
    {
        $throttleKey = 'contact_submit|' . $request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            return back()->with('error', form_t('form_contact_rate_limit', 'Çok fazla mesaj gönderdiniz. Lütfen biraz bekleyin.'));
        }
        RateLimiter::hit($throttleKey, 60);

        if ($request->filled('website')) {
            return back()->with('success', form_t('form_contact_success_short', 'Mesajınız başarıyla iletildi.'));
        }

        $contactDefaults = \App\Support\SilvaContactDefaults::data();
        $interestValues = collect($contactDefaults['form']['interests'] ?? [])->pluck('value')->filter()->all();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'email' => 'nullable|email|max:255',
            'city' => 'nullable|string|max:255',
            'interest' => ['nullable', 'string', 'max:255', Rule::in($interestValues)],
            'message' => 'nullable|string|max:5000',
            'consent' => 'accepted',
            'g-recaptcha-response' => [recaptcha_enabled() ? 'required' : 'nullable', new \App\Rules\ReCaptcha],
        ], [
            'name.required' => form_t('form_contact_name_required', 'Ad soyad alanı zorunludur.'),
            'phone.required' => form_t('form_contact_phone_required', 'Telefon alanı zorunludur.'),
            'email.email' => form_t('form_contact_email_invalid', 'Geçerli bir e-posta adresi giriniz.'),
            'consent.accepted' => form_t('form_contact_consent_required', 'Devam etmek için aydınlatma metnini kabul etmelisiniz.'),
            'g-recaptcha-response.required' => form_t('form_recaptcha_required', 'Lütfen robot olmadığınızı doğrulayın.'),
        ]);

        $labelOf = static function (array $options, ?string $value): string {
            if ($value === null || $value === '') {
                return '';
            }

            foreach ($options as $option) {
                if (($option['value'] ?? null) === $value) {
                    return (string) ($option['label'] ?? $value);
                }
            }

            return $value;
        };

        $interestLabel = $labelOf($contactDefaults['form']['interests'] ?? [], $validated['interest'] ?? null);
        $nameParts = preg_split('/\s+/u', trim($validated['name']), 2, PREG_SPLIT_NO_EMPTY) ?: [];
        $firstName = $nameParts[0] ?? $validated['name'];
        $surname = $nameParts[1] ?? '-';

        $subject = $interestLabel !== '' ? $interestLabel : form_t('form_contact_default_subject', 'İletişim Formu');
        $messageLines = array_values(array_filter([
            $interestLabel !== '' ? ('Konu: ' . $interestLabel) : null,
            !empty($validated['city']) ? ('Şehir: ' . $validated['city']) : null,
            !empty($validated['message']) ? trim($validated['message']) : null,
        ]));
        $message = $messageLines !== []
            ? implode("\n", $messageLines)
            : form_t('form_contact_default_message', 'İletişim formu üzerinden yeni bir talep iletildi.');

        $email = trim((string) ($validated['email'] ?? ''));

        \App\Models\ContactMessage::create([
            'name' => $firstName,
            'surname' => $surname,
            'company' => $validated['city'] ?? null,
            'phone' => $validated['phone'],
            'email' => $email !== '' ? $email : '-',
            'subject' => $subject,
            'message' => $message,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        try {
            $siteName = \App\Models\Setting::get('site_name', 'Silva Stone');

            $details = array_filter([
                'Ad Soyad' => $validated['name'],
                'E-posta' => $email !== '' ? $email : null,
                'Telefon' => $validated['phone'],
                'Şehir' => $validated['city'] ?? null,
                'Konu' => $interestLabel !== '' ? $interestLabel : null,
                'Mesaj' => $validated['message'] ?? null,
            ], static fn ($value) => $value !== null && $value !== '');

            \App\Services\MailjetService::sendToAdmins(
                'Yeni İletişim Mesajı: ' . $subject,
                view('emails.generic', [
                    'subject' => 'Yeni İletişim Mesajı',
                    'title' => 'İletişim formundan yeni bir mesaj geldi.',
                    'message_text' => 'Mesaj detayları aşağıdadır:',
                    'details' => $details,
                ])->render()
            );

            if ($email !== '') {
                \App\Services\MailjetService::send(
                    $email,
                    $firstName,
                    'Mesajınız Alındı - ' . $siteName,
                    view('emails.generic', [
                        'subject' => 'Mesajınız Alındı',
                        'title' => 'Merhaba ' . $firstName . ',',
                        'message_text' => 'İletişim formumuz üzerinden gönderdiğiniz mesaj başarıyla tarafımıza ulaşmıştır. En kısa sürede size dönüş sağlayacağız.',
                        'details' => [
                            'Konu' => $subject,
                            'Tarih' => date('d.m.Y H:i'),
                        ],
                    ])->render()
                );
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Mail sending error in storeContact: ' . $e->getMessage());
        }

        $success = data_get($contactDefaults, 'form.success_message')
            ?: form_t('form_contact_success', 'Mesajınız başarıyla iletildi. En kısa sürede size dönüş yapacağız.');

        return back()->with('success', $success);
    }

    public function corporate()
    {
        $page = \App\Models\Page::where('type', 'corporate')->firstOrFail();
        $about = page_extras($page, []);

        return view('frontend.corporate', compact('page', 'about'));
    }
    public function notFound()
    {
        return response()->view('errors.404', [], 404);
    }

    public function dynamicPage($lang, $slug)
    {

        $page = \App\Models\Page::whereTranslation('slug', $slug)->first();
        if (!$page) {
            return $this->notFound();
        }

        // Sayfa tipine göre doğru blade şablonuna yönlendir
        $legalTypes = [
            'kvkk' => true,
            'cookie-policy' => true,
            'terms' => true,
            'privacy-policy' => true,
            'kvkk-law' => true,
            'personal-data' => true,
        ];

        $typeViewMap = [
            'contact' => 'frontend.contact',
            'corporate' => 'frontend.corporate',
            'solution' => 'frontend.solution',
            'sustainability' => 'frontend.sustainability',
            'application' => 'frontend.application',
            'kvkk' => 'frontend.legal',
            'cookie-policy' => 'frontend.legal',
            'terms' => 'frontend.legal',
            'privacy-policy' => 'frontend.legal',
            'kvkk-law' => 'frontend.legal',
            'personal-data' => 'frontend.legal',
            'contracts' => 'frontend.contracts',
            'stores' => 'frontend.stores',
        ];

        $viewName = $typeViewMap[$page->type] ?? null;
        if (!$viewName) {
            return $this->notFound();
        }

        // Bazı sayfalar ekstra veri gerektirir
        $extraData = [];
        if ($page->type == 'corporate') {
            $extraData['about'] = page_extras($page, []);
        }
        if ($page->type == 'contact') {
            $extraData['contact'] = page_extras($page, \App\Support\SilvaContactDefaults::data());
        }
        if ($page->type == 'contracts') {
            $extraData['contracts'] = page_extras($page, \App\Support\SilvaContractsDefaults::data());
        }
        if ($page->type == 'stores') {
            $extraData['stores'] = page_extras($page, \App\Support\SilvaStoresDefaults::data());
        }
        if ($page->type == 'solution') {
            $extraData['solution'] = page_extras($page, []);
        }
        if ($page->type == 'sustainability') {
            $extraData['sustainability'] = page_extras($page, []);
        }
        if ($page->type == 'application') {
            $extraData['quote'] = page_extras($page, []);
        }
        if (isset($legalTypes[$page->type])) {
            $extraData['legal'] = page_extras($page, \App\Support\SilvaLegalDefaults::data($page->type));
        }

        return view($viewName, array_merge(compact('page'), $extraData));
    }

    public function application()
    {
        $page = \App\Models\Page::where('type', 'application')->first();
        if (!$page) {
            abort(404, 'Teklif al sayfası bulunamadı.');
        }
        $quote = page_extras($page, []);

        return view('frontend.application', compact('page', 'quote'));
    }

    public function privacyPolicy()
    {
        return $this->renderPolicyPage('privacy-policy');
    }

    public function returnPolicy()
    {
        return $this->renderPolicyPage('return-policy');
    }

    public function shippingPolicy()
    {
        return $this->renderPolicyPage('shipping-policy');
    }

    public function kvkkPolicy()
    {
        return $this->renderPolicyPage('kvkk');
    }

    public function cookiePolicy()
    {
        return $this->renderPolicyPage('cookie-policy');
    }

    public function termsPolicy()
    {
        return $this->renderPolicyPage('terms');
    }

    public function kvkkLawPolicy()
    {
        return $this->renderPolicyPage('kvkk-law');
    }

    public function personalDataPolicy()
    {
        return $this->renderPolicyPage('personal-data');
    }

    public function contracts()
    {
        $page = \App\Models\Page::where('type', 'contracts')->firstOrFail();
        $contracts = page_extras($page, \App\Support\SilvaContractsDefaults::data());

        return view('frontend.contracts', compact('page', 'contracts'));
    }

    public function stores()
    {
        $page = \App\Models\Page::where('type', 'stores')->firstOrFail();
        $stores = page_extras($page, \App\Support\SilvaStoresDefaults::data());

        return view('frontend.stores', compact('page', 'stores'));
    }

    public function projects()
    {
        $page = \App\Models\Page::where('type', 'projects')->firstOrFail();
        $projectsPage = page_extras($page, \App\Support\SilvaProjectsDefaults::data());
        $projectItems = \App\Models\Project::query()
            ->where('status', true)
            ->with('translations')
            ->orderBy('order')
            ->orderBy('id')
            ->get()
            ->map(fn ($project) => $project->toFrontendArray())
            ->values();

        return view('frontend.projects', compact('page', 'projectsPage', 'projectItems'));
    }

    public function projectDetail($slug)
    {
        $translation = \App\Models\ProjectTranslation::where('slug', $slug)
            ->where('lang_key', app()->getLocale())
            ->first();

        if (! $translation) {
            $translation = \App\Models\ProjectTranslation::where('slug', $slug)->first();
        }

        $projectModel = $translation
            ? \App\Models\Project::with('translations')->where('status', true)->find($translation->project_id)
            : \App\Models\Project::with('translations')->where('status', true)->where('slug', $slug)->first();

        if (! $projectModel) {
            return $this->notFound();
        }

        $page = \App\Models\Page::where('type', 'projects')->first();
        $projectsPage = page_extras($page, \App\Support\SilvaProjectsDefaults::data());
        $project = $projectModel->toFrontendArray();
        $project['seo_title'] = $projectModel->seo_title;
        $project['seo_description'] = $projectModel->seo_description;

        $related = \App\Models\Project::query()
            ->where('status', true)
            ->where('id', '!=', $projectModel->id)
            ->where(function ($q) use ($projectModel) {
                $q->where('type', $projectModel->type)->orWhere('place', $projectModel->place);
            })
            ->with('translations')
            ->orderBy('order')
            ->limit(3)
            ->get();

        if ($related->isEmpty()) {
            $related = \App\Models\Project::query()
                ->where('status', true)
                ->where('id', '!=', $projectModel->id)
                ->with('translations')
                ->orderBy('order')
                ->limit(3)
                ->get();
        }

        $related = $related->map(fn ($item) => $item->toFrontendArray())->values();

        return view('frontend.project', compact('page', 'projectsPage', 'project', 'projectModel', 'related'));
    }

    public function references()
    {
        return $this->notFound();
    }

    public function faq()
    {
        $page = \App\Models\Page::where('type', 'faq')->first();
        $content = page_extras($page, []);
        $faqCategories = collect($content['categories'] ?? [])->map(function ($category) {
            return [
                'id' => $category['id'] ?? '',
                'label' => $category['label'] ?? '',
                'icon' => $category['icon'] ?? 'bx-help-circle',
                'sorular' => collect($category['items'] ?? [])->map(fn ($item) => [
                    'soru' => $item['question'] ?? ($item['soru'] ?? ''),
                    'cevap' => $item['answer'] ?? ($item['cevap'] ?? ''),
                ])->all(),
            ];
        })->all();

        return view('frontend.faq', compact('page', 'content', 'faqCategories'));
    }

    public function login()
    {
        if (auth('web')->check()) {
            return redirect()->route('customer.panel', ['lang' => app()->getLocale() ?: 'tr']);
        }

        $page = \App\Models\Page::where('type', 'login')->first();
        $content = page_extras($page, []);

        return view('frontend.login', compact('page', 'content'));
    }

    private function renderPolicyPage(string $type, string $activePolicyTab = 'terms')
    {
        $page = \App\Models\Page::where('type', $type)->firstOrFail();
        $legal = page_extras($page, \App\Support\SilvaLegalDefaults::data($type));

        return view('frontend.legal', compact('page', 'legal', 'activePolicyTab'));
    }

    public function storeApplication(Request $request)
    {
        // 1. Rate Limiting Check
        $throttleKey = 'offer_submit|' . $request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            if (!$request->expectsJson()) {
                return back()->withErrors([
                    'form' => form_t('form_quote_rate_limit', 'Çok fazla istek gönderdiniz. Lütfen :seconds saniye bekleyin.', ['seconds' => $seconds]),
                ])->withInput();
            }

            return response()->json([
                'status' => 'error',
                'message' => form_t('form_quote_rate_limit', 'Çok fazla istek gönderdiniz. Lütfen :seconds saniye bekleyin.', ['seconds' => $seconds]),
            ], 429);
        }
        RateLimiter::hit($throttleKey, 60);

        // 2. Honeypot Check
        if ($request->filled('website')) {
            if (!$request->expectsJson()) {
                return back()->with('quote_success', form_t('form_quote_success_short', 'Talebiniz alınmıştır.'));
            }

            return response()->json(['status' => 'success', 'message' => form_t('form_quote_success_short', 'Talebiniz alındı.')]);
        }

        // Aynı mail ile tek başvuru kontrolü
        $existing = \App\Models\OfferRequest::where('email', $request->email)->first();
        if ($existing) {
            if (!$request->expectsJson()) {
                return back()->withErrors([
                    'email' => form_t('form_quote_duplicate', 'Bu e-posta adresi ile daha önce bir teklif talebi oluşturulmuş.'),
                ])->withInput();
            }

            return response()->json([
                'status' => 'duplicate',
                'message' => form_t('form_quote_duplicate_long', 'Bu e-posta adresi ile daha önce bir teklif talebi oluşturulmuş. Talebiniz tarafımıza ulaşmıştır, uzmanlarımız sizinle en kısa sürede iletişime geçecektir.'),
            ], 422);
        }

        $quotePage = \App\Models\Page::where('type', 'application')->first();
        $quoteDefaults = page_extras($quotePage, []);
        $sectorValues = collect($quoteDefaults['form']['sectors'] ?? [])->pluck('value')->filter()->all();
        $materialValues = collect($quoteDefaults['form']['materials'] ?? [])->pluck('value')->filter()->all();
        $requestValues = collect($quoteDefaults['form']['requests'] ?? [])->pluck('value')->filter()->all();

        $validated = $request->validate([
            'company' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'sector' => empty($sectorValues)
                ? ['nullable', 'string', 'max:100']
                : ['nullable', 'string', 'max:100', Rule::in($sectorValues)],
            'material' => empty($materialValues)
                ? ['nullable', 'string', 'max:100']
                : ['nullable', 'string', 'max:100', Rule::in($materialValues)],
            'request' => empty($requestValues)
                ? ['nullable', 'string', 'max:100']
                : ['nullable', 'string', 'max:100', Rule::in($requestValues)],
            'g-recaptcha-response' => [recaptcha_enabled() ? 'required' : 'nullable', new \App\Rules\ReCaptcha],
        ], [
            'company.required' => form_t('form_quote_company_required', 'Firma adı zorunludur.'),
            'email.required' => form_t('form_quote_email_required', 'E-posta adresi zorunludur.'),
            'email.email' => form_t('form_quote_email_invalid', 'Geçerli bir e-posta adresi giriniz.'),
            'g-recaptcha-response.required' => form_t('form_recaptcha_required', 'Lütfen robot olmadığınızı doğrulayın.'),
        ]);

        $labelOf = static function (array $options, ?string $value): string {
            if ($value === null || $value === '') {
                return '';
            }

            foreach ($options as $option) {
                if (($option['value'] ?? null) === $value) {
                    return (string) ($option['label'] ?? $value);
                }
            }

            return $value;
        };

        $sectorLabel = $labelOf($quoteDefaults['form']['sectors'] ?? [], $validated['sector'] ?? null);
        $materialLabel = $labelOf($quoteDefaults['form']['materials'] ?? [], $validated['material'] ?? null);
        $requestLabel = $labelOf($quoteDefaults['form']['requests'] ?? [], $validated['request'] ?? null);

        $offer = \App\Models\OfferRequest::create([
            'name' => $validated['company'],
            'company' => $validated['company'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? '',
            'areas' => array_values(array_filter([$sectorLabel !== '' ? $sectorLabel : null])),
            'budget' => $materialLabel !== '' ? $materialLabel : null,
            'employees' => $requestLabel !== '' ? $requestLabel : null,
            'configuration' => array_filter([
                'sector' => $sectorLabel !== '' ? $sectorLabel : null,
                'material' => $materialLabel !== '' ? $materialLabel : null,
                'request' => $requestLabel !== '' ? $requestLabel : null,
            ], static fn ($value) => $value !== null && $value !== ''),
            'source' => 'form',
            'status' => 'pending',
            'message' => null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Send Emails via Mailjet
        try {
            $siteName = \App\Models\Setting::get('site_name', 'Silva Stone');

            $details = array_filter([
                'Başvuru No' => $offer->application_number,
                'Şirket' => $offer->company,
                'E-posta' => $offer->email,
                'Telefon' => $offer->phone ?: null,
                'Sektör' => $sectorLabel !== '' ? $sectorLabel : null,
                'Malzeme' => $materialLabel !== '' ? $materialLabel : null,
                'Talep Türü' => $requestLabel !== '' ? $requestLabel : null,
            ], static fn ($value) => $value !== null && $value !== '');

            // Send to All Admins
            \App\Services\MailjetService::sendToAdmins(
                'Yeni Teklif Talebi: ' . $offer->application_number,
                view('emails.generic', [
                    'subject' => 'Yeni Teklif Talebi',
                    'title' => 'Sistem üzerinden yeni bir teklif talebi alındı.',
                    'message_text' => 'Talep detayları aşağıdadır:',
                    'details' => $details
                ])->render()
            );

            // Send to User
            \App\Services\MailjetService::send(
                $offer->email,
                $offer->company,
                'Teklif Talebiniz Alındı - ' . $siteName,
                view('emails.generic', [
                    'subject' => 'Teklif Talebiniz Alındı',
                    'title' => 'Sayın ' . $offer->company . ',',
                    'message_text' => 'Teklif talebiniz başarıyla alınmıştır. Talebinizle ilgili uzmanlarımız en kısa sürede sizinle iletişime geçecektir.',
                    'details' => [
                        'Takip Numarası' => $offer->application_number,
                        'Tarih' => date('d.m.Y H:i')
                    ]
                ])->render()
            );
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Mail sending error in storeApplication: ' . $e->getMessage());
        }

        if (!$request->expectsJson()) {
            return back()->with('quote_success', form_t('form_quote_success', 'Teklif talebiniz başarıyla alındı. Takip numaranız: :number', ['number' => $offer->application_number]));
        }

        return response()->json([
            'status' => 'success',
            'message' => form_t('form_quote_success_json', 'Teklif talebiniz başarıyla alındı.'),
            'application_number' => $offer->application_number
        ]);
    }


    private function getStats()
    {
        return [
            'stat1' => [
                'value' => \App\Models\Setting::get('homepage_stat_1_value', '500'),
                'label' => \App\Models\Setting::get('homepage_stat_1_label', 'Tamamlanan<br>Proje'),
            ],
            'stat2' => [
                'value' => \App\Models\Setting::get('homepage_stat_2_value', '100'),
                'label' => \App\Models\Setting::get('homepage_stat_2_label', 'Aktif Danışmanlık<br>Verilen Firma'),
            ],
            'stat3' => [
                'value' => \App\Models\Setting::get('homepage_stat_3_value', '98'),
                'label' => \App\Models\Setting::get('homepage_stat_3_label', 'Proje Onay<br>Başarısı'),
            ],
            'stat4' => [
                'value' => \App\Models\Setting::get('homepage_stat_4_value', '10'),
                'label' => \App\Models\Setting::get('homepage_stat_4_label', 'Yıllık Sektörel<br>Deneyim'),
            ],
        ];
    }

    public function products($slug = null)
    {
        if ($slug && ! is_products_all_slug($slug) && ! in_array($slug, ['tum-urunler', 'all-products', 'all'], true)) {
            $isCategory = \App\Models\ProductCategory::where('status', 1)
                ->where(function ($q) use ($slug) {
                    $q->where('slug', $slug)
                        ->orWhereHas('translations', fn ($t) => $t->where('slug', $slug));
                })
                ->exists();

            if (! $isCategory) {
                return $this->productDetail($slug);
            }

            // Category slug: show list; client filter via ?cat=
            request()->merge(['cat' => $slug]);
        }

        $page = \App\Models\Page::where('type', 'products')->firstOrFail();
        $productsPage = page_extras($page, \App\Support\SilvaProductsDefaults::data());
        $productItems = \App\Models\Product::query()
            ->where('status', true)
            ->with(['translations', 'category.translations'])
            ->whereHas('category', fn ($q) => $q->where('status', true))
            ->orderBy('order')
            ->orderBy('id')
            ->get()
            ->map(fn ($product) => $product->toFrontendArray())
            ->values();

        $categoryMap = \App\Models\ProductCategory::query()
            ->where('status', true)
            ->with('translations')
            ->orderBy('order')
            ->get()
            ->mapWithKeys(fn ($cat) => [$cat->slug => $cat->name])
            ->all();

        $colorMap = \App\Models\ProductColor::optionsMap();
        $colorHex = \App\Models\ProductColor::hexMap();
        $productFeatures = \App\Models\ProductFeature::activeOrdered();

        return view('frontend.products', compact(
            'page',
            'productsPage',
            'productItems',
            'categoryMap',
            'colorMap',
            'colorHex',
            'productFeatures'
        ));
    }

    public function productDetail($slug)
    {
        $productModel = \App\Models\Product::with(['translations', 'category.translations'])
            ->where('status', true)
            ->where(function ($q) use ($slug) {
                $q->where('slug', $slug)
                    ->orWhere('sku', $slug)
                    ->orWhereHas('translations', fn ($t) => $t->where('slug', $slug));
            })
            ->whereHas('category', fn ($q) => $q->where('status', true))
            ->first();

        if (! $productModel) {
            return $this->notFound();
        }

        $page = \App\Models\Page::where('type', 'products')->first();
        $productsPage = page_extras($page, \App\Support\SilvaProductsDefaults::data());
        $product = $productModel->toFrontendArray();
        if (! $product['lead']) {
            $product['lead'] = null;
        }

        $related = \App\Models\Product::query()
            ->where('status', true)
            ->where('id', '!=', $productModel->id)
            ->where('category_id', $productModel->category_id)
            ->with(['translations', 'category.translations'])
            ->orderBy('order')
            ->limit(4)
            ->get()
            ->map(fn ($item) => $item->toFrontendArray())
            ->values();

        $categoryMap = \App\Models\ProductCategory::query()
            ->where('status', true)
            ->with('translations')
            ->orderBy('order')
            ->get()
            ->mapWithKeys(fn ($cat) => [$cat->slug => $cat->name])
            ->all();

        $colorMap = \App\Models\ProductColor::optionsMap();
        $featureLabels = \App\Models\ProductFeature::labelMap();

        return view('frontend.product', compact(
            'page',
            'productsPage',
            'product',
            'productModel',
            'related',
            'categoryMap',
            'colorMap',
            'featureLabels'
        ));
    }

    public function search(Request $request)
    {
        $query = trim((string) $request->query('q', ''));
        $page = \App\Models\Page::where('type', 'search')->first()
            ?? \App\Models\Page::where('type', 'products')->first();
        $catalog = page_extras(
            \App\Models\Page::where('type', 'products')->first(),
            \App\Support\SilvaProductsDefaults::data()
        );
        $searchContent = page_extras($page, []);
        $results = collect();

        if ($query !== '') {
            $term = '%' . addcslashes($query, '%_\\') . '%';
            $products = \App\Models\Product::where('status', 1)
                ->whereHas('category', fn ($builder) => $builder->where('status', 1))
                ->where(function ($builder) use ($term) {
                    $builder->where('name', 'like', $term)
                        ->orWhere('title', 'like', $term)
                        ->orWhere('short_description', 'like', $term)
                        ->orWhereHas('translations', function ($translation) use ($term) {
                            $translation->where('lang_key', app()->getLocale())
                                ->where(function ($fields) use ($term) {
                                    $fields->where('name', 'like', $term)
                                        ->orWhere('title', 'like', $term)
                                        ->orWhere('short_description', 'like', $term)
                                        ->orWhere('description', 'like', $term);
                                });
                        });
                })
                ->with('category')
                ->orderBy('order')
                ->limit(30)
                ->get();

            foreach ($products as $product) {
                $results->push([
                    'type' => __t('search_type_product', 'Ürün', 'frontend'),
                    'title' => trim($product->name . ' ' . $product->title),
                    'description' => $product->short_description ?: $product->description,
                    'url' => m_url('product', $product->slug),
                ]);
            }

            $categories = \App\Models\ProductCategory::where('status', 1)
                ->where(function ($builder) use ($term) {
                    $builder->where('name', 'like', $term)
                        ->orWhere('description', 'like', $term)
                        ->orWhereHas('translations', function ($translation) use ($term) {
                            $translation->where('lang_key', app()->getLocale())
                                ->where(fn ($fields) => $fields->where('name', 'like', $term)->orWhere('description', 'like', $term));
                        });
                })
                ->orderBy('order')
                ->limit(10)
                ->get();

            foreach ($categories as $category) {
                $results->push([
                    'type' => __t('search_type_category', 'Koleksiyon', 'frontend'),
                    'title' => $category->name,
                    'description' => $category->description,
                    'url' => m_url('products', $category->slug),
                ]);
            }
        }

        return view('frontend.search', compact('page', 'catalog', 'searchContent', 'query', 'results'));
    }

    public function moduleDispatcher($lang, $module, $slug = null)
    {
        // 1. Check if this module segment is actually a Page slug
        $page = \App\Models\Page::whereTranslation('slug', trim($module))->first();
        if ($page) {
            if ($page->type === 'products') {
                return $this->products($slug);
            }
            if ($page->type === 'application') {
                return $this->application();
            }
            if ($page->type === 'faq') {
                return $this->faq();
            }
            if ($page->type === 'references') {
                return $this->references();
            }
            if ($page->type === 'privacy-policy') {
                return $this->privacyPolicy();
            }
            if ($page->type === 'kvkk') {
                return $this->kvkkPolicy();
            }
            if ($page->type === 'cookie-policy') {
                return $this->cookiePolicy();
            }
            if ($page->type === 'terms') {
                return $this->termsPolicy();
            }
            if ($page->type === 'kvkk-law') {
                return $this->kvkkLawPolicy();
            }
            if ($page->type === 'personal-data') {
                return $this->personalDataPolicy();
            }
            if ($page->type === 'contracts') {
                return $this->contracts();
            }
            if ($page->type === 'stores') {
                return $this->stores();
            }
            if ($page->type === 'projects') {
                return $slug ? $this->projectDetail($slug) : $this->projects();
            }
            if ($page->type === 'return-policy') {
                return $this->returnPolicy();
            }
            if ($page->type === 'shipping-policy') {
                return $this->shippingPolicy();
            }
            if ($page->type === 'login') {
                return $this->login();
            }
            if ($page->type === 'search') {
                return $this->search(request());
            }
            if ($page->type === 'contact') {
                return $this->contact();
            }
            return $this->dynamicPage($lang, trim($module));
        }

        // 2. Try to find the module key from database translations (group: routes)
        $matchedTranslation = \App\Models\StaticTranslation::where('lang_key', $lang)
            ->where('group', 'routes')
            ->where('value', trim($module))
            ->first();

        $moduleKey = null;
        if ($matchedTranslation) {
            // key is 'route_products', we need 'products'
            $moduleKey = str_replace('route_', '', $matchedTranslation->key);
        } else {
            // 2. Fallback to hardcoded defaults if not found in DB
            $defaults = [
                // TR
                'urunler' => 'products', 'urun' => 'product', 'teknolojiler' => 'products', 'teknoloji' => 'product',
                'iletisim' => 'contact', 'teklif-al' => 'get_quote', 'b2b-toptan' => 'get_quote', 'kurumsal' => 'corporate', 'hakkimizda' => 'corporate',
                'gizlilik-politikasi' => 'privacy_policy', 'iade-kosullari' => 'return_policy', 'kargo-teslimat' => 'shipping_policy',
                'referanslar' => 'references',
                'projeler' => 'projects',
                'proje' => 'project',
                'sss' => 'faq',
                'giris' => 'login',
                'arama' => 'search',
                // EN
                'products' => 'products', 'product' => 'product', 'technologies' => 'products', 'technology' => 'product',
                'contact' => 'contact', 'get-quote' => 'get_quote', 'b2b-wholesale' => 'get_quote', 'corporate' => 'corporate', 'about' => 'corporate', 'about-us' => 'corporate',
                'privacy-policy' => 'privacy_policy', 'return-policy' => 'return_policy', 'shipping-delivery' => 'shipping_policy',
                'references' => 'references',
                'projects' => 'projects',
                'project' => 'project',
                'faq' => 'faq',
                'login' => 'login',
                'search' => 'search',
            ];
            $moduleKey = $defaults[$module] ?? null;
        }

        // 3. Map module key to controller method
        $methodMap = [
            'products'      => 'products',
            'product'       => 'productDetail',
            'projects'      => 'projects',
            'project'       => 'projectDetail',
            'contact'       => 'contact',
            'get_quote'     => 'application',
            'corporate'     => 'corporate',
            'about'         => 'corporate',
            'privacy_policy' => 'privacyPolicy',
            'return_policy' => 'returnPolicy',
            'shipping_policy' => 'shippingPolicy',
            'references'    => 'references',
            'faq' => 'faq',
            'login' => 'login',
            'search' => 'search',
        ];

        if ($moduleKey && isset($methodMap[$moduleKey])) {
            $method = $methodMap[$moduleKey];

            try {
                if ($method === 'products') {
                    return $this->products($slug);
                }

                if ($method === 'projects') {
                    return $slug ? $this->projectDetail($slug) : $this->projects();
                }

                if ($method === 'search') {
                    return $this->search(request());
                }

                if (!$slug) {
                    if ($method === 'productDetail' || $method === 'projectDetail') {
                        return $method === 'productDetail' ? $this->products(null) : $this->projects();
                    }

                    $noSlugMethods = ['contact', 'corporate', 'application', 'privacyPolicy', 'returnPolicy', 'shippingPolicy', 'references', 'faq', 'login'];
                    if (in_array($method, $noSlugMethods, true)) {
                        return $this->$method();
                    }

                    return $this->notFound();
                }

                return $this->$method($slug);
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                return $this->notFound();
            }
        }

        // 4. Fallback to dynamic page
        try {
            return $this->dynamicPage($lang, $module);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFound();
        }
    }

    private function workwearCatalogContext(): array
    {
        $catalogQuery = \App\Models\ProductCategory::query()
            ->where('status', 1)
            ->orderBy('order');

        if (\Illuminate\Support\Facades\Schema::hasColumn('product_categories', 'parent_id')) {
            $catalogQuery->whereNull('parent_id')
                ->with(['children' => fn ($query) => $query->where('status', 1)->orderBy('order')]);
        }

        $catalogCategories = $catalogQuery->get();

        $rawCounts = \App\Models\Product::query()
            ->where('status', 1)
            ->selectRaw('category_id, COUNT(*) as aggregate')
            ->groupBy('category_id')
            ->pluck('aggregate', 'category_id');

        $catalogCounts = [];
        foreach ($catalogCategories as $parent) {
            $parentCount = (int) ($rawCounts[$parent->id] ?? 0);
            foreach ($parent->children ?? [] as $child) {
                $childCount = (int) ($rawCounts[$child->id] ?? 0);
                $catalogCounts[$child->id] = $childCount;
                $parentCount += $childCount;
            }
            $catalogCounts[$parent->id] = $parentCount;
        }

        $catalogCounts['all'] = \App\Models\Product::where('status', 1)
            ->whereHas('category', fn ($query) => $query->where('status', 1))
            ->count();

        return compact('catalogCategories', 'catalogCounts');
    }

}
