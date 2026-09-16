@if(recaptcha_enabled())
    <div class="formRecaptcha">
        <div class="g-recaptcha" data-sitekey="{{ recaptcha_site_key() }}"></div>
        @error('g-recaptcha-response')
            <p class="formRecaptchaError">{{ $message }}</p>
        @enderror
    </div>
@endif
