@extends('layouts.main')

@section('content')

    <div class="container">

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(Session::has('message'))
            <p class="alert alert-success">{{ Session::get('message') }}</p>
        @endif

        @if(Session::has('error'))
            <p class="alert alert-danger">{{ Session::get('error') }}</p>
        @endif

        <h1 class="page-header">Загрузка файла</h1>

        {!! Form::open(['url' => 'store', 'files' => true]) !!}

        <div class="form-group">
            {!! Form::label('sharefile', 'Выбери файл') !!}
            {!! Form::file('sharefile',[]) !!}
        </div>

        <div class="alert alert-info">
            {{--<span class="glyphicon glyphicon-info-sign"></span>--}}
            <p class="help-block small">Загружаемые файлы не должны превышать 10Мб</p>
            <p class="help-block small">Также не разрешена загрузка файлов с расширением .exe, .php</p>
        </div>

        <div class="form-group">
            {!! Form::submit('Загрузить', ['class' => 'btn btn-primary btn-block btn-lg']) !!}
        </div>

        {!! Form::close() !!}

    </div>

@endsection