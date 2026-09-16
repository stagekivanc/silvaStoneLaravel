@extends('frontend.layouts.app')

@section('title', data_get($page, 'seo_title') ?: 'İletişim | Silva Stone')
@section('meta_description', data_get($page, 'seo_description') ?: 'Silva Stone proje, numune ve showroom talepleri için Acarkon ekibiyle iletişime geçin.')
@section('body_attrs', 'data-page="contact"')

@php
  $lang = app()->getLocale();
  $intro = data_get($contact, 'intro', []);
  $channels = collect(data_get($contact, 'channels', []))->values();
  $form = data_get($contact, 'form', []);
  $interests = collect(data_get($form, 'interests', []))->values();
  $map = data_get($contact, 'map', []);
  $settingsPhone = \App\Models\Setting::get('phone', '+90 850 346 02 26');
  $settingsPhoneRaw = \App\Models\Setting::get('phone_raw', '+908503460226');
  $settingsEmail = \App\Models\Setting::get('email', 'bilgi@acarkon.com');
  $settingsAddress = \App\Models\Setting::get('address', 'Horozluhan Mahallesi Hotamış Sk. No:49 Selçuklu / Konya');
  $wa = preg_replace('/\D+/', '', \App\Models\Setting::get('whatsapp', '908503460226'));
@endphp

@section('content')
  <section class="page-intro page-intro--plain page-intro--white">
    <div class="mx-auto max-w-site px-5 md:px-8">
      <div class="page-intro-top">
        <div>
          <p class="page-intro-kicker font-display italic">{{ data_get($intro, 'kicker', 'İletişim') }}</p>
          <h1>{{ data_get($intro, 'title', 'Projenizi konuşalım') }}</h1>
        </div>
        <div class="page-intro-aside">
          <p>{{ data_get($intro, 'text') }}</p>
          <p class="page-intro-meta">{{ data_get($intro, 'hours') }}</p>
        </div>
      </div>
    </div>
  </section>

  <section class="bg-white py-16 md:py-24">
    <div class="mx-auto grid max-w-site gap-10 px-5 md:px-8 lg:grid-cols-12 lg:items-start lg:gap-12">
      <div class="space-y-3 lg:col-span-5">
        @forelse ($channels as $channel)
          @php
            $type = data_get($channel, 'type');
            $url = trim((string) data_get($channel, 'url', ''));
            $value = data_get($channel, 'value');
            $icon = data_get($channel, 'icon', 'bx-phone');

            if ($type === 'phone' && ($url === '' || $value === '')) {
              $value = $value ?: $settingsPhone;
              $url = $url ?: 'tel:' . $settingsPhoneRaw;
            } elseif ($type === 'email' && ($url === '' || $value === '')) {
              $value = $value ?: $settingsEmail;
              $url = $url ?: 'mailto:' . $settingsEmail;
            } elseif ($type === 'whatsapp' && ($url === '' || $value === '')) {
              $value = $value ?: $settingsPhone;
              $url = $url ?: 'https://wa.me/' . $wa;
            } elseif ($type === 'address' && $value === '') {
              $value = $settingsAddress;
            }

            $isLink = $url !== '';
            $tag = $isLink ? 'a' : 'div';
            $attrs = $isLink
              ? 'href="' . e($url) . '"' . (str_starts_with($url, 'http') ? ' target="_blank" rel="noopener"' : '') . ' class="contact-card group"'
              : 'class="contact-card"';
          @endphp
          <{{ $tag }} {!! $attrs !!}>
            <span class="contact-channel-icon"><i class="bx {{ $icon }}"></i></span>
            <span>
              <span class="contact-channel-label">{{ data_get($channel, 'label') }}</span>
              <span class="contact-channel-value">{{ $value }}</span>
            </span>
          </{{ $tag }}>
        @empty
          <a href="tel:{{ $settingsPhoneRaw }}" class="contact-card group">
            <span class="contact-channel-icon"><i class="bx bx-phone"></i></span>
            <span>
              <span class="contact-channel-label">Telefon</span>
              <span class="contact-channel-value">{{ $settingsPhone }}</span>
            </span>
          </a>
        @endforelse
      </div>

      <form
        id="contact-form"
        class="contact-form contact-form--page lg:col-span-7"
        method="POST"
        action="{{ route('contact.store', ['lang' => $lang]) }}"
        novalidate
      >
        @csrf
        <input type="text" name="website" value="" tabindex="-1" autocomplete="off" class="hidden" aria-hidden="true" />

        @if (session('success'))
          <p class="contact-success mb-6" role="status">{{ session('success') }}</p>
        @endif
        @if (session('error'))
          <p class="mb-6 text-sm text-red-600" role="alert">{{ session('error') }}</p>
        @endif
        @if ($errors->any())
          <div class="mb-6 space-y-1 text-sm text-red-600" role="alert">
            @foreach ($errors->all() as $error)
              <p>{{ $error }}</p>
            @endforeach
          </div>
        @endif

        <div class="contact-form-grid">
          <div class="contact-field">
            <label for="cf-name">{{ data_get($form, 'name_label', 'Ad Soyad') }}</label>
            <input id="cf-name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" placeholder="{{ data_get($form, 'name_placeholder') }}" />
          </div>
          <div class="contact-field">
            <label for="cf-phone">{{ data_get($form, 'phone_label', 'Telefon') }}</label>
            <input id="cf-phone" type="tel" name="phone" value="{{ old('phone') }}" required autocomplete="tel" placeholder="{{ data_get($form, 'phone_placeholder') }}" />
          </div>
          <div class="contact-field">
            <label for="cf-email">{{ data_get($form, 'email_label', 'E-posta') }}</label>
            <input id="cf-email" type="email" name="email" value="{{ old('email') }}" autocomplete="email" placeholder="{{ data_get($form, 'email_placeholder') }}" />
          </div>
          <div class="contact-field">
            <label for="cf-city">{{ data_get($form, 'city_label', 'Şehir') }}</label>
            <input id="cf-city" type="text" name="city" value="{{ old('city') }}" placeholder="{{ data_get($form, 'city_placeholder') }}" />
          </div>
          <div class="contact-field contact-field--full">
            <label for="cf-interest">{{ data_get($form, 'interest_label', 'İlgilendiğiniz konu') }}</label>
            <div class="contact-select">
              <select id="cf-interest" name="interest">
                @foreach ($interests as $interest)
                  <option value="{{ data_get($interest, 'value') }}" @selected(old('interest', 'catalog') === data_get($interest, 'value'))>
                    {{ data_get($interest, 'label') }}
                  </option>
                @endforeach
              </select>
              <i class="bx bx-chevron-down"></i>
            </div>
          </div>
          <div class="contact-field contact-field--full">
            <label for="cf-message">{{ data_get($form, 'message_label', 'Proje notu') }}</label>
            <textarea id="cf-message" name="message" rows="4" placeholder="{{ data_get($form, 'message_placeholder') }}">{{ old('message') }}</textarea>
          </div>
        </div>

        <div class="contact-form-foot">
          <label class="contact-consent">
            <input type="checkbox" name="consent" value="1" required @checked(old('consent')) />
            <span>{!! \App\Support\SilvaLegalDefaults::resolveBodyHtml((string) data_get($form, 'consent_html')) !!}</span>
          </label>
          <button type="submit" class="contact-submit">
            <span>{{ data_get($form, 'submit_label', 'Talebi gönder') }}</span>
            <i class="bx bx-right-arrow-alt"></i>
          </button>
        </div>
      </form>
    </div>
  </section>

  <section class="bg-white">
    <div class="mx-auto max-w-site px-5 py-10 md:px-8 md:py-12">
      <h2 class="text-[clamp(1.4rem,2.5vw,1.85rem)] font-light tracking-[-0.03em]">{{ data_get($map, 'title', 'Genel Merkez Konumu') }}</h2>
    </div>
    <div class="contact-map">
      <iframe
        title="{{ data_get($map, 'iframe_title', 'Acarkon Showroom Konya') }}"
        src="{{ data_get($map, 'embed_url') }}"
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade"
      ></iframe>
    </div>
  </section>
@endsection

@push('scripts')
  <script src="{{ silva_asset('js/cart.js') }}"></script>
  <script src="{{ silva_asset('js/search.js') }}"></script>
  <script src="{{ silva_asset('js/main.js') }}"></script>
@endpush
