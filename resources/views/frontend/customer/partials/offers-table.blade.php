@php
    $lang = app()->getLocale();
    $rows = $compact ?? false
        ? (method_exists($offers, 'take') ? $offers->take(5) : collect($offers)->take(5))
        : $offers;
@endphp

@if(($compact ?? false) === false || (method_exists($offers, 'total') ? $offers->total() > 0 : $offers->count() > 0))
<div class="overflow-x-auto">
    <table class="w-full text-left">
        <thead>
            <tr class="bg-brand-gray/50 border-b border-gray-100">
                <th class="px-6 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ __t('account_col_no', 'No', 'frontend') }}</th>
                <th class="px-6 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ __t('account_col_date', 'Tarih', 'frontend') }}</th>
                <th class="px-6 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ __t('account_col_source', 'Kaynak', 'frontend') }}</th>
                <th class="px-6 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ __t('account_col_status', 'Durum', 'frontend') }}</th>
                <th class="px-6 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right">{{ __t('account_col_action', 'İşlem', 'frontend') }}</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($rows as $offer)
                <tr class="hover:bg-gray-50/80 transition-colors">
                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $offer->application_number }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $offer->created_at?->format('d.m.Y H:i') }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $offer->isCartQuote() ? __t('account_source_cart', 'Sepet', 'frontend') : __t('account_source_form', 'Form', 'frontend') }}</td>
                    <td class="px-6 py-4">
                        @php
                            $statusClass = match ($offer->status) {
                                'quoted' => 'bg-emerald-50 text-emerald-700',
                                'processing' => 'bg-amber-50 text-amber-700',
                                'closed' => 'bg-gray-100 text-gray-500',
                                default => 'bg-blue-50 text-blue-700',
                            };
                        @endphp
                        <span class="inline-flex px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider {{ $statusClass }}">
                            {{ $offer->statusLabel() }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('customer.offers.show', ['lang' => $lang, 'id' => $offer->id]) }}"
                           class="text-[10px] font-bold uppercase tracking-widest text-brand-red hover:text-gray-900">
                            {{ __t('account_detail', 'Detay', 'frontend') }}
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500">
                        {{ __t('account_no_quotes', 'Henüz teklif talebiniz yok.', 'frontend') }}
                        <a href="{{ route('module.dispatcher', ['lang' => $lang, 'module' => 'urunler']) }}" class="text-brand-red font-semibold ml-1">{{ __t('account_browse_products', 'Ürünlere göz atın', 'frontend') }}</a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@else
    <div class="px-6 py-12 text-center text-sm text-gray-500">
        {{ __t('account_no_quotes', 'Henüz teklif talebiniz yok.', 'frontend') }}
        <a href="{{ route('module.dispatcher', ['lang' => $lang, 'module' => 'urunler']) }}" class="text-brand-red font-semibold ml-1">{{ __t('account_browse_products', 'Ürünlere göz atın', 'frontend') }}</a>
    </div>
@endif
