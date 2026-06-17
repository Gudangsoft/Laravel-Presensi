<?php

namespace App\Services;

class CaptchaService
{
    public static function generate(): string
    {
        $a = rand(1, 20);
        $b = rand(1, 20);
        session(['captcha_answer' => $a + $b]);
        return "{$a} + {$b}";
    }

    public static function verify(int $answer): bool
    {
        return session('captcha_answer') !== null
            && (int) session('captcha_answer') === $answer;
    }
}
