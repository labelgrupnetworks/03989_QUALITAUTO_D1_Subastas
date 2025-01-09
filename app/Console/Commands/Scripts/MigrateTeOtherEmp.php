<?php

namespace App\Console\Commands\Scripts;

use App\Models\V5\FsEmail;
use App\Models\V5\FsEmail_Lang;
use App\Models\V5\FsEmailTemplate;
use App\Models\V5\Web_Page;
use App\Models\WebNewbannerModel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateTeOtherEmp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'label:duplicate-to-emp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Duplicate data from one database to another';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
		$from = $this->ask('Desde que empresa?');
		$to = $this->ask('Hacia que empresa?');

		$options = [
			'webpages',
			'translates',
			'emails'
		];

		$option = $this->choice('Que sección quieres duplicar?', $options);

		$isOk = $this->confirm('Se van a duplicar los datos la seccion ' . $option . ' de la empresa ' . $from . ' a la empresa ' . $to . '. ¿Estás seguro?');

		if (!$isOk) {
			$this->error('Proceso cancelado');
			return;
		}

		/* match ($option) {
			'webpages' => $this->duplicateWebPagesTables($from, $to),
			'translates' => $this->duplicateTranslatesTables($from, $to),
			'emails' => $this->duplicateEmailsTables($from, $to),
			default => $this->error('Opción no válida'),
		}; */

		//specialists: fgespecial0, fgespecial0_lang, fgespecial1, fgespecial1_lang
		//Son pocos, duplicados directamente en la base de datos.

		//images_sizes ok
		// seo_routes ok. En la empresa 006 no tienen las rutas traducidas, no se porque.
		// al pasar cms a la main 002, la rutas en ingles se modificarán. tenerlo en cuanta.

		// key_keywords_search -> no se usa
		// web_calendar -> no se usa
		// web_calendar_event -> no se usa

		//faqs -> ya estaban.

		//banners
		//db y archivos
		//creo será más fácil duplicar los banners directamente en la base de datos.
		//$this->duplicateBanners($origin, $destination);

		$this->info('Proceso finalizado');
    }

	private function duplicateBanners($origin, $destination)
	{
		$banners = WebNewbannerModel::withoutGlobalScopes()
			->with('items', function ($query) {
				$query->active();
			})
			->activo()
			->where('empresa', $origin)
			->get();

		dd($banners);
	}

	private function duplicateWebPagesTables($origin, $destination)
	{
		$maxId = Web_Page::withoutGlobalScopes()
			->max('id_web_page');

		$pages = Web_Page::withoutGlobalScopes()
			//withou appends. need add select
			->where('emp_web_page', $origin)
			->get()
			->each(function ($page) use ($destination, &$maxId) {
				$page->emp_web_page = $destination;
				//necesitamos nuevos ids, a partir del más grande de la tabla
				$page->id_web_page = ++$maxId;

				//los campos clob opupan demasiado y neceitamos crearlos uno a unp
				Web_Page::create($page->toArrayWithoutAppends());
			});
	}

	private function duplicateTranslatesTables($origin, $destination)
	{
		//las traducciones están relacionadas por id's entre las distintas tablas.
		//En vez de duplicarla, será más fácil actualizar la empresa.

		//tablas: web_translate_headers, web_translate_key, web_translate
		DB::table('web_translate_headers')
			->where('id_emp,', $origin)
			->update(['id_emp' => $destination]);

		DB::table('web_translate_key')
			->where('id_emp', $origin)
			->update(['id_emp' => $destination]);

		DB::table('web_translate')
			->where('id_emp', $origin)
			->update(['id_emp' => $destination]);

	}

	private function duplicateEmailsTables($origin, $destination)
	{
		$mails = FsEmail::withoutGlobalScopes()
			->where('emp_email', $origin)
			->get()
			->each(function ($mail) use ($destination) {
				$mail->emp_email = $destination;
			});

		FsEmail::insert($mails->toArray());

		$mailsLang = FsEmail_Lang::where('emp_lang', $origin)
			->get()
			->each(function ($mail) use ($destination) {
				$mail->emp_lang = $destination;
			});

		FsEmail_Lang::insert($mailsLang->toArray());

		$emailsTemplates = FsEmailTemplate::withoutGlobalScopes()
			->where('emp_template', $origin)
			->get()
			->each(function ($email) use ($destination) {
				$email->emp_template = $destination;
			});

		FsEmailTemplate::insert($emailsTemplates->toArray());
	}
}
