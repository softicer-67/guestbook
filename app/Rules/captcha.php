<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Captcha implements Rule
{
    public function passes($attribute, $value)
    {
        // Здесь вы должны проверить, совпадает ли значение CAPTCHA с оригинальным
        return $value === $_SESSION['captcha']; // Предполагается, что значение CAPTCHA сохранено в сессии
    }

    public function message()
    {
        return 'CAPTCHA введена неправильно.';
    }
}
