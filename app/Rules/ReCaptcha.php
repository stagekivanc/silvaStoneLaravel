<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class ReCaptcha implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! recaptcha_enabled()) {
            return;
        }

        $secretKey = recaptcha_secret_key();
        $token = trim((string) $value);

        if ($token === '') {
            $fail(form_t('form_recaptcha_required', 'Lütfen robot olmadığınızı doğrulayın.'));

            return;
        }

        $response = Http::asForm()->timeout(10)->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $secretKey,
            'response' => $token,
            'remoteip' => request()->ip(),
        ]);

        if (! $response->successful() || ! $response->json('success')) {
            $fail(form_t('form_recaptcha_failed', 'reCAPTCHA doğrulaması başarısız oldu. Lütfen tekrar deneyin.'));
        }
    }
}
