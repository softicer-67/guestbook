<form method="POST" action="{{ route('add-message') }}" id="guestbook-form">
    @csrf <!-- Вставляет CSRF-токен для защиты от атак межсайтовой подделки запроса -->

    <div class="form-group">
        <label for="name">User Name:</label>
        <input class="form-control" placeholder="User Name" name="name" type="text" id="name" required pattern="[a-zA-Z0-9]+" title="Только буквы и цифры">
    </div>

    <div class="form-group">
        <label for="email">E-mail:</label>
        <input class="form-control" placeholder="E-mail" name="email" type="email" id="email" required>
    </div>

    <div class="form-group">
        <label for="captcha">CAPTCHA: <img src="{{ route('captcha-image') }}" height="30" alt="CAPTCHA Image"></label>
        <input class="form-control" placeholder="CAPTCHA" name="captcha" type="text" id="captcha" required pattern="[a-zA-Z0-9]+" title="Только буквы и цифры">
    </div>


    <div class="form-group">
        <label for="message">Text:</label>
        <textarea class="form-control" rows="3" placeholder="Text" name="message" id="message" required></textarea>
    </div>

    <div class="form-group">
        <input class="btn btn-primary" type="submit" value="Add">
    </div>
    <!-- Вывод сообщений об успешном и неуспешном добавлении сообщения -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</form>
