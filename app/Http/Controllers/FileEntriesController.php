<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

use Auth;
use App\FileEntry;
use Symfony\Component\HttpFoundation\Response;
use Hashids;
/**
 *  Class FileEntriesController
 * @package App\Http\Controllers
 */
class FileEntriesController extends Controller
{

    /**
     * Create new controller instance
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['download', 'downloadfile']]);
    }


    /**
     * Upload page
     * @param Request $request
     * @return string
     */
    public function upload(Request $request)
    {
        return view('upload');
    }


    /**
     * Download page
     *
     * @param Request $request
     * @return string
     */
    public function download(Request $request, $shorturl)
    {

        $file = FileEntry::where('shorturl', $shorturl)->firstOrFail();
        return view('download', compact('file'));
    }


    /**
     * Download file
     *
     * @param Request $request
     * @return string
     */
    public function downloadfile(Request $request, $shorturl)
    {
        $entry = FileEntry::where('shorturl', $shorturl)->first();

        $file_path = $entry->user_id . DIRECTORY_SEPARATOR . $entry->shorturl . '.' . $entry->ext;

        if (Storage::disk('local')->has($file_path) === false)
        {
            abort(404);
        }

        return response()->download(
            public_path() . '/files/' . $file_path,
            $entry->original_filename,
            ['Content-Type' => $entry->mime]
        );
    }


    /**
     * Store uploading files
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'sharefile' => 'required|max:10000|not_ext:php,exe', //add your validation
        ]);

        if ($request->hasFile('sharefile') === true) {
            $file = $request->file('sharefile');

            $file_ext = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);

            $user_id = Auth::user()->id;

            $timestamp = \Carbon\Carbon::now()->timestamp;

            $entry = new FileEntry();

            /*
             * Генерация короткой ссылки             *
             * Hashids::encode генерирует некий хэш который используется для короткой ссылки
             * хэш получается уникальным
             * Хэш от $timestamp выдает пустой результат, поэтому делаем его меньше на 1000000000
             *
             * Поэтому на уникальность не проверю
             */
            $entry->shorturl = Hashids::encode($user_id, $timestamp - 1000000000);

            /*
             * Для каждого пользователя создаем отдельную директорию
             * в которой храним оригинальные загруженые файлы
             */
            Storage::disk('local')->put(
                $user_id . DIRECTORY_SEPARATOR . $entry->shorturl . '.' . $file_ext,
                File::get($file)
            );

            $entry->user_id = $user_id;
            $entry->ext = $file_ext;
            $entry->mime = $file->getClientMimeType();
            $entry->original_filename = $file->getClientOriginalName();
            $entry->save();

            return redirect('myfiles')->with('message', 'Upload successfully');

        } else {
            return redirect('myfiles')->with('error', 'Some error is uploading file');
        }
    }


    /**
     * Display all uploading files
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function myfiles()
    {
        $last = Auth::user()->files()->orderby('created_at', 'desc')->first();

        if (!is_null($last)) {
            $files = Auth::user()->files()->where('id', '<>', $last->id)->orderby('created_at', 'desc')->get();
        } else {
            $file = null;
        }

        return view('myfiles', compact('files', 'last'));
    }


    /**
     * Delete file from database and filesystem
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function delete(Request $request, $id)
    {
        $entry = FileEntry::find($id);

        Storage::disk('local')->delete(
            Auth::user()->id . DIRECTORY_SEPARATOR . $entry->shorturl . '.' . $entry->ext
        );

        FileEntry::destroy($id);

        return redirect('myfiles');
    }
}
