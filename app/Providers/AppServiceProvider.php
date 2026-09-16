<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Carbon::setLocale(config('app.locale'));

        // Ayarları tüm görünümlerde paylaş
        if (!app()->runningInConsole()) {
            if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                $settings = \App\Models\Setting::all()->pluck('value', 'key');
                \Illuminate\Support\Facades\View::share('settings', $settings);
            }
            // Share pages by type
            if (\Illuminate\Support\Facades\Schema::hasTable('pages')) {
                $pages = \App\Models\Page::all()->mapWithKeys(function ($item) {
                    return [$item->type => $item->slug];
                })->toArray();
                \Illuminate\Support\Facades\View::share('pages', $pages);
            }

            // Share categories
            if (\Illuminate\Support\Facades\Schema::hasTable('product_categories')) {
                $headerQuery = \App\Models\ProductCategory::query()
                    ->where('status', 1)
                    ->orderBy('order');

                if (\Illuminate\Support\Facades\Schema::hasColumn('product_categories', 'parent_id')) {
                    $headerQuery->whereNull('parent_id')
                        ->with(['children' => fn ($query) => $query->where('status', 1)->orderBy('order')]);
                }

                \Illuminate\Support\Facades\View::share('header_categories', $headerQuery->get());
            }

            // Admin panel sayıları ve bildirimler
            \Illuminate\Support\Facades\View::composer('yonetim.*', function ($view) {
                // Her kategoriden son 10 okunmamış kaydı çek
                $mList = \App\Models\ContactMessage::where('is_read', false)->latest()->limit(10)->get();
                $oList = \App\Models\OfferRequest::where('is_read', false)->latest()->limit(10)->get();

                // Map and combine
                $unreadMessages = $mList->map(function($item) {
                    $item->notif_type = 'message';
                    $item->notif_icon = 'mail';
                    $item->notif_label = 'Yeni Mesaj: ' . $item->name . ' ' . $item->surname;
                    $item->notif_route = route('yonetim.mesajlar.show', $item->id);
                    return $item;
                });
                $unreadOffers = $oList->map(function($item) {
                    $item->notif_type = 'offer';
                    $item->notif_icon = 'file-text';
                    $item->notif_label = 'Yeni Teklif: ' . $item->application_number;
                    $item->notif_route = route('yonetim.teklifler.show', $item->id);
                    return $item;
                });

                $notifications = $unreadMessages->concat($unreadOffers)
                    ->sortByDesc('created_at')
                    ->take(10);

                // Gerçek sayıları ayrı ayrı çek (verimlilik için count() kullanıyoruz)
                $countMessages = \App\Models\ContactMessage::where('is_read', false)->count();
                $countOffers = \App\Models\OfferRequest::where('is_read', false)->count();

                $view->with([
                    'unreadMessagesCount' => $countMessages,
                    'unreadOffersCount' => $countOffers,
                    'totalUnreadCount' => $countMessages + $countOffers,
                    'latestNotifications' => $notifications
                ]);
            });
        }
    }
}
