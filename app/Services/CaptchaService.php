<?php

namespace App\Services;

class CaptchaService
{
    public function generate()
    {
    // Генерация CAPTCHA и сохранение значения в сессии
    $randomString = substr(str_shuffle("ABCDEFGHJKLMNPRSTUVWXYZ123456789"), 0, 4);
    session(['captcha' => $randomString]);

    // Создание изображения CAPTCHA и вывод его
    $image = imagecreatetruecolor(85, 30);

    // Определение цветов
    $bgColor = imagecolorallocate($image, 255, 255, 198);
    $textColor = imagecolorallocate($image, 0, 0, 0); // Чёрный цвет для текста

    // Заполнение фона изображения
    imagefill($image, 0, 0, $bgColor);

    // Нанесение текста CAPTCHA с случайными цветами
    for ($i = 0; $i < 4; $i++) {
    // Выбор цвета символа, который будет контрастным к фону
    $randomColor = imagecolorallocate($image, rand(0, 255), rand(0, 255), rand(0, 255));
    $colorDiff = abs($randomColor - $bgColor);
    $threshold = 200; // Порог для определения контрастности
    if ($colorDiff < $threshold) {
        $randomColor = $textColor; // Заменяем цвет символа на черный
    }

    // Нанесение символа
    imagettftext($image, 16, rand(-15, 15), 10 + ($i * 15), 24, $randomColor, public_path('fonts/comicz.ttf'), $randomString[$i]);
    }

    // Установка заголовка для вывода изображения
    header('Content-Type: image/png');

    // Вывод изображения в формате PNG
    imagepng($image);

    // Удаление изображения из памяти
    imagedestroy($image);
    }
}
