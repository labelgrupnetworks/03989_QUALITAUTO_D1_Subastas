<?php

namespace App\Http\Controllers\admin\contenido;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AdminUploadsController extends Controller
{
	const STORE_NAME = 'public_uploads';

	public function __construct()
	{
		view()->share(['menu' => 'contenido']);
	}

	public function index(Request $request)
	{
		$storage = Storage::disk(self::STORE_NAME);
		$files = $storage->files();
		$fileDetails = array_map(function ($file) use ($storage) {
            return [
                'name' => basename($file),
                'url' => $storage->url($file),
                'lastModified' => Carbon::createFromTimestamp($storage->lastModified($file))->toDateTimeString(),
				'size' => $this->unitConvert($storage->size($file)),
				'isImage' => in_array(pathinfo($file, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']),
            ];
        }, $files);

		return view('admin::pages.contenido.uploads.index', ['files' => $fileDetails]);
	}

	public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,gif,mp4,pdf,doc,docx,xls,xlsx,ppt,pptx,txt|max:100000'
        ]);

		$file = $request->file('file');
		$filename = $file->getClientOriginalName();
        $path = $file->storeAs('', $filename, self::STORE_NAME);

        return redirect()->back()->with('success', ['File uploaded successfully'])->with('file_path', $path);
    }

	public function delete($fileName)
    {
        Storage::disk(self::STORE_NAME)->delete($fileName);
        return redirect()->back()->with('success', 'File deleted successfully');
    }

    public function update(Request $request, $fileName)
    {
        $request->validate([
            'new_name' => 'required|string'
        ]);

        $newName = $request->input('new_name') . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
        Storage::disk(self::STORE_NAME)->move($fileName, $newName);

        return response()->json(['success' => true, 'message' => 'File renamed successfully', 'new_name' => $newName]);
    }

	private function unitConvert($size)
	{
		return match (true) {
			$size >= 1073741824 => number_format($size / 1073741824, 2) . ' GB',
			$size >= 1048576 => number_format($size / 1048576, 2) . ' MB',
			$size >= 1024 => number_format($size / 1024, 2) . ' KB',
			default => $size . ' bytes'
		};
	}
}
