<?php

namespace App\Http\Controllers\admin;

use Request;
use App\Http\Controllers\Controller;
use View;
use Illuminate\Support\Facades\Request as Input;
use File;
use Config;

class AdminSlidersController extends Controller
{

	public function deleteFile()
	{
		if (!empty(request("pathImg"))) {
			unlink(request("pathImg"));
		}
	}

	public function uploadFile()
	{


		if (!Request::ajax()) {
			die("Error");
		}

		$upload = array();

		if (!Input::hasFile('file') || !Input::file('file')->isValid()) {
			$upload['status'] = "error";
			$upload['msg'] = trans(\Config::get('app.theme') . '-admin.slider.error_upload_img');
		}

		try {

			if (empty($_POST['url_img'])) {
				//$relative_dest_path_temp = 'resources';
				$relative_dest_path = '/img/resources';
			} elseif ($_POST['url_img']) {
				//$relative_dest_path_temp = $_POST['url_img'];
				$relative_dest_path = '/img/' . $_POST['url_img'];
			}

			//$relative_dest_path = '/themes/'.\Config::get('app.theme').'/img/'.$relative_dest_path_temp;

			$destination_path = public_path($relative_dest_path);

			if (!File::exists($destination_path)) {
				File::makeDirectory($destination_path, 0775, true);
			}

			$file = Input::file('file');
			$filename = $file->getClientOriginalName();

			$cont = 0;

			while (File::exists($destination_path . '/' . $filename) && $cont < 10) {
				$filename = rand() . "_" . $file->getClientOriginalName();
				$cont++;
			}

			if (File::exists($filename) && $cont == 10) {
				$upload['status'] = "error";
				$upload['msg'] = trans(\Config::get('app.theme') . '-admin.slider.error_upload_img');
			}

			if ($file->move($destination_path, $filename)) {
				$upload['status'] = "success";
				$upload['file'] = $relative_dest_path . '/' . $filename;
			}
		} catch (\Exception $e) {
			\Log::info($e->getMessage());
			$upload['status'] = "error";
			$upload['msg'] = trans(\Config::get('app.theme') . '-admin.slider.error_upload_img');
		}

		die(json_encode($upload));
	}

	public function save()
	{
		switch (Input::get('save')) {
			case 'new':
				$this->saveNewSlider();
				break;

			case 'settings':
				$this->saveSettings();
				break;
		}
	}

	private function saveNewSlider()
	{
		$data = array();
		$file = Input::get('file_url');
		$text = Input::get('summernote');
		$name = Input::get('name');
		$dest_path = Config::get('app.sliders_upload_folder');
		$absolute_dest_path = public_path($dest_path);
		$absolute_file_path = public_path($file);
		$absolute_dest_path_file = $absolute_dest_path . '/' . basename($file);
		$data['file'] = str_replace(Config::get('app.tmp_upload_folder'), $dest_path, $file);
		//Si no existe la carpeta de destino, la crea
		if (!File::exists($absolute_dest_path)) {
			File::makeDirectory($absolute_dest_path, 0775, true);
		}

		if (!File::exists($absolute_file_path, $absolute_dest_path_file)) {
			die("error");
		}

		$date = new \DateTime();
		$now = $date->format('d-m-Y H:i:s');
		print_r($now);
		print_r($file);
		print_r($text);
		print_r($name);
		die();
	}

	private function saveSettings()
	{
		$order = Input::get('order');
		print_r($order);
		die;
	}
}
