@extends('index')

@section('content')

    @include('_common._form')
    <hr>

    <div class="btn-container">
        <form method="get" action="{{ url()->current() }}">
            <button type="submit" name="sort_by" value="name">
                Сортировать по имени
                @if ($sort_by == 'name')
                    @if ($sort_order == 'asc')
                        &uarr;
                @else
                    &darr;
                @endif
                @endif
            </button>
            <input type="hidden" name="sort_order" value="{{ $sort_by == 'name' && $sort_order == 'asc' ? 'desc' : 'asc' }}">
        </form>

        <form method="get" action="{{ url()->current() }}">
            <button type="submit" name="sort_by" value="email">
                Сортировать по электронной почте
                @if ($sort_by == 'email')
                    @if ($sort_order == 'asc')
                        &uarr;
                @else
                    &darr;
                @endif
                @endif
            </button>
            <input type="hidden" name="sort_order" value="{{ $sort_by == 'email' && $sort_order == 'asc' ? 'desc' : 'asc' }}">
        </form>

        <form method="get" action="{{ url()->current() }}">
            <button type="submit" name="sort_by" value="created_at">
                Сортировать по времени
                @if ($sort_by == 'created_at')
                    @if ($sort_order == 'asc')
                        &uarr;
                @else
                    &darr;
                @endif
                @endif
            </button>
            <input type="hidden" name="sort_order" value="{{ $sort_by == 'created_at' && $sort_order == 'asc' ? 'desc' : 'asc' }}">
        </form><hr>
        <div class="text-right"><b>Всего сообщений:</b> <i class="badge">{{ $count }}</i></div><br/>
    </div>
    @include('pages.messages._items')
@stop
