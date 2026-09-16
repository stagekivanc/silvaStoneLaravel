<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartQuoteController;
use App\Http\Controllers\CustomerPanelController;
use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\DashboardController;

Route::get('/front-assets/{path}', function (string $path) {
    $assetRoot = realpath(public_path('assets'));
    $assetPath = $assetRoot ? realpath($assetRoot . DIRECTORY_SEPARATOR . $path) : false;

    abort_unless(
        $assetRoot
        && $assetPath
        && is_file($assetPath)
        && str_starts_with($assetPath, $assetRoot . DIRECTORY_SEPARATOR),
        404
    );

    $contentType = match (strtolower(pathinfo($assetPath, PATHINFO_EXTENSION))) {
        'css' => 'text/css; charset=utf-8',
        'js' => 'application/javascript; charset=utf-8',
        'svg' => 'image/svg+xml',
        'png' => 'image/png',
        'jpg', 'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'webp' => 'image/webp',
        'webm' => 'video/webm',
        'mp4' => 'video/mp4',
        'woff' => 'font/woff',
        'woff2' => 'font/woff2',
        'ttf' => 'font/ttf',
        default => mime_content_type($assetPath) ?: 'application/octet-stream',
    };

    return response()->file($assetPath, [
        'Content-Type' => $contentType,
        'Cache-Control' => 'public, max-age=86400',
    ]);
})->where('path', '.*')->name('front.assets');

Route::get('/', function() {
    $defaultLanguage = \Illuminate\Support\Facades\Schema::hasTable('languages')
        ? \App\Models\Language::default()?->code
        : null;

    return redirect('/' . ($defaultLanguage ?: config('app.locale', 'tr')));
});
Route::get('/login', fn () => redirect()->route('yonetim.login'))->name('login');

Route::group(['prefix' => '{lang}', 'where' => ['lang' => '[a-z]{2}']], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/arama', [HomeController::class, 'search'])->name('search');
    Route::get('/search', [HomeController::class, 'search']);
    Route::get('/poisk', [HomeController::class, 'search']);

    Route::post('/uye-giris', [CustomerAuthController::class, 'login'])->name('customer.login');
    Route::post('/uye-kayit', [CustomerAuthController::class, 'register'])->name('customer.register');
    Route::post('/uye-cikis', [CustomerAuthController::class, 'logout'])->name('customer.logout');
    Route::post('/sepet-teklif', [CartQuoteController::class, 'store'])->name('cart.quote');

    Route::middleware('auth')->group(function () {
        Route::get('/hesabim', [CustomerPanelController::class, 'index'])->name('customer.panel');
        Route::post('/hesabim', [CustomerPanelController::class, 'updateProfile'])->name('customer.panel.update');
        Route::get('/hesabim/teklif/{id}', [CustomerPanelController::class, 'showOffer'])->name('customer.offers.show')->whereNumber('id');
    });

    // Translatable Module Routes
    Route::get('/{module}/{slug?}', [HomeController::class, 'moduleDispatcher'])->name('module.dispatcher');
    Route::post('/iletisim', [HomeController::class, 'storeContact'])->name('contact.store');
    Route::post('/teklif-al', [HomeController::class, 'storeApplication'])->name('quote.store');
    Route::post('/request-a-quote', [HomeController::class, 'storeApplication']);
});

// Sitemap Routes (language-based)
Route::get('/robots.txt', [\App\Http\Controllers\SitemapController::class, 'robots'])->name('robots');
Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap.index');
Route::get('/sitemap-{lang}.xml', [\App\Http\Controllers\SitemapController::class, 'langIndex'])->where('lang', '[a-z]{2}')->name('sitemap.lang');
Route::get('/sitemap-{lang}-main.xml', [\App\Http\Controllers\SitemapController::class, 'main'])->where('lang', '[a-z]{2}')->name('sitemap.main');
Route::get('/sitemap-{lang}-pages.xml', [\App\Http\Controllers\SitemapController::class, 'pages'])->where('lang', '[a-z]{2}')->name('sitemap.pages');
Route::get('/sitemap-{lang}-technologies.xml', [\App\Http\Controllers\SitemapController::class, 'technologies'])->where('lang', '[a-z]{2}')->name('sitemap.technologies');
Route::get('/sitemap-{lang}-technology-categories.xml', [\App\Http\Controllers\SitemapController::class, 'technologyCategories'])->where('lang', '[a-z]{2}')->name('sitemap.technology-categories');
Route::get('/sitemap-{lang}-products.xml', function (string $lang) {
    return redirect()->route('sitemap.technologies', ['lang' => $lang], 301);
})->where('lang', '[a-z]{2}');
Route::get('/sitemap-{lang}-product-categories.xml', function (string $lang) {
    return redirect()->route('sitemap.technology-categories', ['lang' => $lang], 301);
})->where('lang', '[a-z]{2}');


// Yonetim (Admin) Routes

Route::prefix('yonetim')->name('yonetim.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Protected Admin Routes
    Route::middleware(['auth:admin'])->group(function () {
        // Dil Yönetimi
        Route::resource('languages', \App\Http\Controllers\Admin\LanguageController::class)->except(['show']);
        Route::get('translations', [\App\Http\Controllers\Admin\StaticTranslationController::class, 'index'])->name('translations.index');
        Route::post('translations/update', [\App\Http\Controllers\Admin\StaticTranslationController::class, 'update'])->name('translations.update');

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/ayarlar', [SettingController::class, 'index'])->name('ayarlar');
        // Messages
        Route::get('/mesajlar', [\App\Http\Controllers\Admin\MessageController::class, 'index'])->name('mesajlar.index');
        Route::get('/mesajlar/{id}', [\App\Http\Controllers\Admin\MessageController::class, 'show'])->name('mesajlar.show');
        Route::patch('/mesajlar/{id}/toggle-read', [\App\Http\Controllers\Admin\MessageController::class, 'toggleRead'])->name('mesajlar.toggle-read');
        Route::delete('/mesajlar/{id}', [\App\Http\Controllers\Admin\MessageController::class, 'destroy'])->name('mesajlar.destroy');
        
        // Teklif Talepleri
        Route::get('/teklif-talepleri', [\App\Http\Controllers\Admin\OfferRequestController::class, 'index'])->name('teklifler.index');
        Route::get('/teklif-talepleri/{id}', [\App\Http\Controllers\Admin\OfferRequestController::class, 'show'])->name('teklifler.show');
        Route::patch('/teklif-talepleri/{id}/status', [\App\Http\Controllers\Admin\OfferRequestController::class, 'updateStatus'])->name('teklifler.status');
        Route::patch('/teklif-talepleri/{id}/toggle-read', [\App\Http\Controllers\Admin\OfferRequestController::class, 'toggleRead'])->name('teklifler.toggle-read');
        Route::delete('/teklif-talepleri/{id}', [\App\Http\Controllers\Admin\OfferRequestController::class, 'destroy'])->name('teklifler.destroy');

        // Üyeler (müşteri hesapları)
        Route::get('/uyeler', [\App\Http\Controllers\Admin\MemberController::class, 'index'])->name('uyeler.index');
        Route::post('/uyeler/impersonation/leave', [\App\Http\Controllers\Admin\MemberController::class, 'leaveImpersonation'])->name('uyeler.leave-impersonation');
        Route::post('/uyeler/{id}/hesabina-gir', [\App\Http\Controllers\Admin\MemberController::class, 'impersonate'])->name('uyeler.impersonate');
        Route::get('/uyeler/{id}', [\App\Http\Controllers\Admin\MemberController::class, 'show'])->name('uyeler.show');
        Route::patch('/uyeler/{id}/toggle-active', [\App\Http\Controllers\Admin\MemberController::class, 'toggleActive'])->name('uyeler.toggle-active');
        Route::delete('/uyeler/{id}', [\App\Http\Controllers\Admin\MemberController::class, 'destroy'])->name('uyeler.destroy');
        
        // Pages
        Route::get('/sayfalar', [\App\Http\Controllers\Admin\PageController::class, 'index'])->name('sayfalar.index');
        Route::get('/sayfalar/{page}/duzenle', [\App\Http\Controllers\Admin\PageController::class, 'edit'])->name('sayfalar.edit');
        Route::post('/sayfalar/{page}', [\App\Http\Controllers\Admin\PageController::class, 'update'])->name('sayfalar.update');



        // Ürün Yönetimi
        Route::resource('urun-kategorileri', \App\Http\Controllers\Admin\ProductCategoryController::class)->parameters(['urun-kategorileri' => 'urun_kategorisi'])->except(['show']);
        Route::resource('urun-ozellikleri', \App\Http\Controllers\Admin\ProductFeatureController::class)->parameters(['urun-ozellikleri' => 'id'])->except(['show']);
        Route::resource('urunler', \App\Http\Controllers\Admin\ProductController::class)->parameters(['urunler' => 'urun'])->except(['show']);
        Route::resource('urun-renkleri', \App\Http\Controllers\Admin\ProductColorController::class)->parameters(['urun-renkleri' => 'id'])->except(['show']);
        Route::resource('urun-rozetleri', \App\Http\Controllers\Admin\ProductBadgeController::class)->parameters(['urun-rozetleri' => 'id'])->except(['show']);

        // Proje Yönetimi
        Route::resource('projeler', \App\Http\Controllers\Admin\ProjectController::class)->parameters(['projeler' => 'proje'])->except(['show']);
        Route::resource('proje-tipleri', \App\Http\Controllers\Admin\ProjectTypeController::class)->parameters(['proje-tipleri' => 'id'])->except(['show']);
        Route::resource('proje-mekanlari', \App\Http\Controllers\Admin\ProjectPlaceController::class)->parameters(['proje-mekanlari' => 'id'])->except(['show']);
        Route::resource('proje-sehirleri', \App\Http\Controllers\Admin\ProjectCityController::class)->parameters(['proje-sehirleri' => 'id'])->except(['show']);

        Route::post('/ayarlar', [SettingController::class, 'update'])->name('ayarlar.update');

        // Notifications
        Route::post('/bildirimler/hepsini-oku', [\App\Http\Controllers\Admin\NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');


        // Profile
        Route::get('/profil', [\App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('profil');
        Route::post('/profil', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profil.update');

        // Kullanıcılar (Admins)
        Route::resource('kullanicilar', \App\Http\Controllers\Admin\AdminUserController::class)->parameters(['kullanicilar' => 'user'])->except(['show']);
    });
});


