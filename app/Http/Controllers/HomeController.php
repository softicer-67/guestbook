<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Services\CaptchaService;

class HomeController extends Controller
{
    private $captchaService;

    public function __construct(CaptchaService $captchaService)
    {
        $this->captchaService = $captchaService;
    }

    public function captcha()
    {
        return $this->captchaService->generate();
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
