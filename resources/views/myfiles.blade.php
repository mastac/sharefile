@extends('layouts.main')

@section('content')

    @if(Session::has('message'))
        <p class="alert alert-success">{{ Session::get('message') }}</p>
    @endif

    @if(Session::has('error'))
        <p class="alert alert-danger">{{ Session::get('error') }}</p>
    @endif

    <h1 class="page-header">Мои загруженые файлы</h1>

    @if (!is_null($last))

    <div class="form-group">
         {!! Form::label('shorturl' . $last->id, 'Последний загруженый файл') !!}

        <div>
         {{ \Carbon\Carbon::parse($last->created_at)->format("Y-m-d H:i:s") }}
         - {{ link_to($last->shorturl, $last->original_filename ) }}
         - {{ link_to('delete/' . $last->id, 'Удалить') }}
        </div>

         {!! Form::text(
            'shorturl' . $last->id,
            URL::to('/') . '/' . $last->shorturl,
            [
                'readonly',
                'class' => 'shorturl lead form-control',
                'onfocus' => "this.select();",
                'onmouseup' => "return false;"
            ])
            !!}
          <p class="help-block small">Скопируйте ссылку и отправьте другу</p>
    </div>

    @if (count($files) > 0)
        <h2>Предыдущие загруженые файлы</h2>

        <table class="table table-hover">
            <tr>
                <th ></th>
                <th>Дата загрузки</th>
                <th width="450">Имя загруженого файла</th>
                <th></th>
            </tr>
            <?php $pos=2 ?>
            @foreach($files as $file)
            <tr>
                <td><?php print $pos++ ?></td>
                <td>{{ \Carbon\Carbon::parse($file->created_at)->format("Y-m-d H:i:s") }}</td>
                <td>
                    <p>{{ link_to($file->shorturl, $file->original_filename ) }}</p>
                    <div>{!! Form::text('shorturl' . $file->id, URL::to('/') . '/' . $file->shorturl,
                            [
                                'readonly',
                                'class' => 'shorturl form-control',
                                'onfocus' => "this.select();",
                                'onmouseup' => "return false;"
                            ])!!}
                    </div>
                </td>
                <td>
                    {{ link_to('delete/' . $file->id, 'Удалить') }}
                </td>
            </tr>
            @endforeach
        </table>
    @endif

    @else

        <div class="text-center alert alert-warning">
            У вас пока нет загруженых файлов
        </div>

        <div class="form-group">
            <p><a class="btn btn-lg btn-primary btn-block" href="{{ url('/upload') }}" role="button">Загрузить файлы</a></p>
        </div>

    @endif

@endsection