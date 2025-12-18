<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class ReCaptcha implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha.secret'),
            'response' => $value,
        ]);

        $res = $response->json();

        if (! $res['success']) {
            $fail('Verifikasi Captcha gagal atau token kedaluwarsa.');

            return;
        }

        if (isset($res['score']) && $res['score'] < 0.5) {
            $fail('Sistem mendeteksi aktivitas mencurigakan. Silakan coba lagi.');
        }
    }
}
