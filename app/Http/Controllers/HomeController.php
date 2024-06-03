<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;


class HomeController extends Controller
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

    public function index()
    {
        // Получаем параметры сортировки и количество сообщений на странице из запроса
        $sortBy = request()->input('sort_by', 'created_at');
        $sortOrder = request()->input('sort_order', 'desc');
        $showAll = request()->input('show_all', false); // По умолчанию не показываем все сообщения

        // Получаем отсортированные сообщения из базы данных
        $messagesQuery = Message::orderBy($sortBy, $sortOrder);

        // Если пользователь хочет показать все сообщения
        if ($showAll) {
            $messages = $messagesQuery->get(); // Получаем все сообщения без пагинации
        } else {
            $messages = $messagesQuery->paginate(5); // Иначе показываем сообщения с пагинацией
        }

        // Передаем данные в представление
        $data = [
            'title' => 'Гостевая книга на Laravel',
            'pagetitle' => 'Гостевая книга',
            'messages' => $messages,
            'count' => Message::count(),
            'sort_by' => $sortBy,
            'sort_order' => $sortOrder,
        ];

        // Возвращаем представление
        return view('pages.messages.index', $data);
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|regex:/^[a-zA-Z0-9]+$/',
            'email' => 'required|email',
            'captcha' => [new \App\Rules\CaptchaRule],
            'message' => 'required',
        ]);

        // Если валидация прошла успешно, сохраняем сообщение в базе данных
        $entry = new Message;
        $entry->name = $request->name;
        $entry->email = $request->email;
        $entry->message = $request->message;
        $entry->ip_address = $request->ip();
        $entry->user_agent = $request->header('User-Agent');
        $entry->save();

        // Проверяем, прошла ли валидация CAPTCHA
        if ($validatedData['captcha'] === false) {
            // Если ввод капчи неверен, перенаправляем назад с сообщением об ошибке
            return redirect()->back()->withInput()->withErrors(['captcha' => 'Капча введена неверно.']);
        }

        // Получаем критерий сортировки из запроса
        $sortBy = $request->input('sort_by', 'created_at');
        $orderDirection = $request->input('sort_order', 'desc');

        // Выполняем сортировку сообщений
        $messages = Message::orderBy($sortBy, $orderDirection)->get();

        // Перенаправляем пользователя с сообщением об успехе
        return redirect()->back()->with('success', 'Сообщение успешно добавлено!');

    }
}
