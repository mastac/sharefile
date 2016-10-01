@extends('layouts.main')

@section('content')

    <div class="container">

        <h1 class="page-header">Файл</h1>

        <div class="row text-center">

            <div>
                <h2>{{ $file->original_filename }}</h2>
            </div>

            <div class="col-md-6 col-md-offset-3">
                <p class="text-muted small">Дата загрузки: {{ $file->created_at }}</p>
            </div>

        </div>

        <div class="form-group">
            {{ link_to('download/' . $file->shorturl, 'Скачать', ['class' => 'btn btn-primary btn-block btn-lg']) }}
        </div>

    </div>

@endsection