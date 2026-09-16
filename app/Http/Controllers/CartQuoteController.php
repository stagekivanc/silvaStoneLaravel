<?php

namespace App\Http\Controllers;

use App\Models\OfferRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class CartQuoteController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::guard('web')->user();

        if (! $user) {
            $lang = app()->getLocale() ?: 'tr';

            return response()->json([
                'status' => 'auth_required',
                'message' => 'Teklif göndermek için giriş yapmalısınız.',
                'login_url' => url('/' . $lang . '/giris'),
            ], 401);
        }

        if (! $user->is_active) {
            return response()->json([
                'status' => 'error',
                'message' => 'Hesabınız pasif durumda. Teklif gönderemezsiniz.',
            ], 403);
        }

        $throttleKey = 'cart_quote|' . $user->id . '|' . $request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Çok fazla istek gönderdiniz. Lütfen biraz bekleyin.',
            ], 429);
        }
        RateLimiter::hit($throttleKey, 60);

        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.id' => ['required', 'integer', 'exists:products,id'],
            'items.*.name' => ['nullable', 'string', 'max:255'],
            'items.*.sku' => ['nullable', 'string', 'max:100'],
            'items.*.size' => ['nullable', 'string', 'max:50'],
            'items.*.color' => ['nullable', 'string', 'max:50'],
            'items.*.qty' => ['required', 'integer', 'min:1', 'max:99999'],
            'items.*.image' => ['nullable', 'string', 'max:500'],
            'message' => ['nullable', 'string', 'max:2000'],
        ], [
            'items.required' => 'Sepetiniz boş.',
            'items.min' => 'Sepetinizde en az bir ürün olmalıdır.',
        ]);

        $normalizedItems = [];
        foreach ($validated['items'] as $row) {
            $product = Product::find($row['id']);
            if (! $product || ! $product->status) {
                continue;
            }

            $normalizedItems[] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'sku' => $product->skuCode(),
                'size' => (string) ($row['size'] ?? ''),
                'color' => (string) ($row['color'] ?? ''),
                'qty' => (int) $row['qty'],
                'image' => $product->image_url ?: ($row['image'] ?? null),
            ];
        }

        if ($normalizedItems === []) {
            return response()->json([
                'status' => 'error',
                'message' => 'Geçerli ürün bulunamadı. Sepetinizi kontrol edin.',
            ], 422);
        }

        $firstProductId = $normalizedItems[0]['product_id'] ?? null;
        $itemSummary = collect($normalizedItems)
            ->map(fn ($i) => $i['name'] . ' ×' . $i['qty'])
            ->implode(', ');

        $offer = OfferRequest::create([
            'user_id' => $user->id,
            'product_id' => $firstProductId,
            'source' => 'cart',
            'status' => 'pending',
            'name' => $user->name,
            'company' => $user->company ?: $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'areas' => ['Sepet Teklifi'],
            'budget' => count($normalizedItems) . ' kalem',
            'employees' => 'Üye sepet teklifi',
            'configuration' => [
                'type' => 'cart',
                'item_count' => count($normalizedItems),
                'total_qty' => collect($normalizedItems)->sum('qty'),
            ],
            'items' => $normalizedItems,
            'message' => $validated['message'] ?? ('Sepet teklif talebi: ' . $itemSummary),
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 500),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Teklif talebiniz alındı. Uzmanlarımız en kısa sürede sizinle iletişime geçecek.',
            'application_number' => $offer->application_number,
            'offer_id' => $offer->id,
        ]);
    }
}
