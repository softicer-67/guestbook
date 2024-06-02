<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CaptchaRule implements Rule
{
    public function passes($attribute, $value)
    {
        // Проверка CAPTCHA. Здесь вы можете проверить, совпадает ли введенное значение с сохраненным в сессии
        return $value === session('captcha');
    }

    public function message()
    {
        return 'CAPTCHA введена неправильно.';
    }
}
