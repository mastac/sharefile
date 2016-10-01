@extends('layouts.main')

@section('content')
<div class="container">

    <div class="jumbotron">
        <h1>Поделись файлом</h1>
        <p class="lead">Поделись файлом с другом! — способ делиться изображениями и
            фотографиями, музыкой и видео, документами, да и всеми остальными типами файлов. </p>

        @if (Auth::guest())
            <p>Регистрируйся и делись своими файлами.</p>
            <p><a class="btn btn-lg btn-success" href="{{ url('/upload') }}" role="button">Зарегистрируйся</a></p>
        @else
            <p>Начинай делитьсь своими файлами.</p>
            <p><a class="btn btn-lg btn-success" href="{{ url('/upload') }}" role="button">Загрузить файлы</a></p>
        @endif
    </div>

</div>
@endsection
