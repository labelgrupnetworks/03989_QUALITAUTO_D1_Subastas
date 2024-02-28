<?php

namespace Tests\Feature;

use App\Http\Controllers\ClientTest\DemoTests;
use App\Models\articles\FgArt0;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FgFamart;
use App\Models\V5\FgOrtsec0;
use App\Models\V5\FgOrtsec1;
use App\Models\V5\FgSub;
use App\Models\V5\FxSec;
use App\Models\V5\FxSubSec;
use App\Models\V5\Web_Artist;
use App\Models\V5\Web_Blog;
use App\Models\V5\Web_Page;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class PagesTest extends TestCase
{
	#TODO: Hacer en cada test un match con cada uno de los clientes y lógica de cada cliente.

	#region Helper methods

	private function setHTTP_HOST($route)
	{
		$url = explode('/',url()->current());
		$http_host = end($url);
		$_SERVER['HTTP_HOST'] = $http_host;
		$_SERVER['REQUEST_URI'] = $route;
	}

	private function getMostRecientAucSession()
	{
		return FgSub::joinSessionSub()->orderBy('"start"', 'desc')->first();
	}

	protected function getAnArtist()
	{
		return Web_Artist::select("NAME_ARTIST, ID_ARTIST")
		->where("ACTIVE_ARTIST",1)
		->orderby("ID_ARTIST", "asc")
		->first();
	}

	/**
	 * Get the values from the database.
	 * @param mixed $dataTable
	 * @param array $whereCases
	 * @param array $whereIsNotNullCases
	 * @param string $orderBy
	 * @param array $joins [table, first, operator, second]
	 * @param array $scopes
	 * @return mixed
	 */
	protected function getDatabaseSingleValues(
		$dataTable,
		$whereCases = [],
		$whereIsNotNullCases = [],
		$orderBy = '',
		$joins = [],
		$scopes = []
		)
	{
		if (count($whereCases) > 0) {
			$dataTable = $dataTable->where($whereCases);
		}
		if (count($whereIsNotNullCases) > 0) {
			$dataTable = $dataTable->whereNotNull($whereIsNotNullCases);
		}
		if ($orderBy != '') {
			$dataTable = $dataTable->orderBy($orderBy, 'asc');
		}
		if (count($joins) > 0) {
			foreach ($joins as $join) {
				$dataTable = $dataTable->join($join['table'], $join['first'], $join['operator'], $join['second']);
			}
		}
		if (count($scopes) > 0) {
			foreach ($scopes as $scope) {
				$dataTable = $dataTable->$scope();
			}
		}
		return $dataTable->first();
	}

	protected function getLotData(array $whereCasesToAdd = [])
	{
		$whereCases = [];
		$whereCases['subc_sub'] = 'S';
		$whereCases['cerrado_asigl0'] = 'N';
		$whereCases['retirado_asigl0'] = 'N';
		$whereCases['oculto_asigl0'] = 'N';

		if (count($whereCasesToAdd) > 0) {
			foreach ($whereCasesToAdd as $field => $value) {
				$whereCases[$field] = $value;
			}
		}

		return self::getDatabaseSingleValues(
			new FgAsigl0(),
			$whereCases,
			['TITULO_HCES1'],
			'fini_asigl0',
			[],
			['joinFghces1Asigl0', 'joinSessionAsigl0', 'joinSubastaAsigl0']
		);
	}

	private function markThisTestAsSkippedIfHasThisThemes(array $themes)
	{
		if (in_array(Config::get('app.theme'), $themes)) {
			$this->markTestSkipped('The theme is ' . Config::get('app.theme') . '.');
		}
	}

	#endregion

    /**
     * A home page test.
     * @return void
     */
    public function test_home_page_is_successful()
    {
		$this->markThisTestAsSkippedIfHasThisThemes(['tauleryfau']);

		self::setHTTP_HOST(route('home'));

		match (Config::get('app.theme')) {
			'demo' => (new DemoTests($this))->testHomePageIsSuccessful(),
			default => $this->markTestIncomplete('The theme is not defined.')
		};


        $response = $this->get(route('home'));

		if ($response->baseResponse->getStatusCode() == 200) {
			$response->assertSuccessful();
		} else {
			$response->assertRedirect(route('home'));
		}
    }

	/**
	 * A test for the user registered page.
	 * @return void
	 */
	public function test_user_registered_is_successful()
	{
		self::setHTTP_HOST(route('user.registered'));

		$response = $this->get(route('user.registered'));

		$response->assertSuccessful();
	}

	/**
	 * A test for the actual auction page.
	 * @return void
	 */
	public function test_subasta_actual_is_succesful()
	{
		$this->markThisTestAsSkippedIfHasThisThemes(['tauleryfau']);

		self::setHTTP_HOST(route('subasta.actual'));

		$response = $this->get(route('subasta.actual'));

		$response->assertSuccessful();
	}

	/**
	 * A test for the actual online auctions page.
	 * @return void
	 */
	public function test_subasta_actual_online_is_succesful()
	{
		$this->markThisTestAsSkippedIfHasThisThemes(['tauleryfau']);

		self::setHTTP_HOST(route('subasta.actual-online'));

		$response = $this->get(route('subasta.actual-online'));

		if ($response->baseResponse->getStatusCode() == 200) {
			$response->assertSuccessful();
		} else {
			$response->assertRedirect(route('subasta.actual-online'));
		}

	}

	/**
	 * A test for the presencial auctions page.
	 * @return void
	 */
	public function test_subastas_presenciales_is_succesful()
	{
		$this->markThisTestAsSkippedIfHasThisThemes(['tauleryfau']);

		self::setHTTP_HOST(route('subastas.presenciales'));

		$response = $this->get(route('subastas.presenciales'));

		$response->assertSuccessful();

	}

	/**
	 * A test for the historic auctions page.
	 * @return void
	 */
	public function test_subastas_historicas_is_succesful()
	{
		$this->markThisTestAsSkippedIfHasThisThemes(['tauleryfau']);

		self::setHTTP_HOST(route('subastas.historicas'));

		$response = $this->get(route('subastas.historicas'));

		$response->assertSuccessful();
	}

	/**
	 * A test for the historic presencial auctions page.
	 * @return void
	 */
	public function test_subastas_historicas_presenciales_is_succesful()
	{
		$this->markThisTestAsSkippedIfHasThisThemes(['tauleryfau']);

		self::setHTTP_HOST(route('subastas.historicas_presenciales'));

		$response = $this->get(route('subastas.historicas_presenciales'));

		$response->assertSuccessful();
	}

	/**
	 * A test for the historic online auctions page.
	 * @return void
	 */
	public function test_subastas_historicas_online_is_succesful()
	{
		$this->markThisTestAsSkippedIfHasThisThemes(['tauleryfau']);

		self::setHTTP_HOST(route('subastas.historicas_online'));

		$response = $this->get(route('subastas.historicas_online'));

		$response->assertSuccessful();
	}

	/**
	 * A test for the online auctions page.
	 * @return void
	 */
	public function test_subastas_online_is_succesful()
	{
		$this->markThisTestAsSkippedIfHasThisThemes(['tauleryfau']);

		self::setHTTP_HOST(route('subastas.online'));

		$response = $this->get(route('subastas.online'));

		$response->assertSuccessful();
	}

	/**
	 * A test for the permanent auctions page.
	 * @return void
	 */
	public function test_subastas_permanentes_is_succesful()
	{
		$this->markThisTestAsSkippedIfHasThisThemes(['tauleryfau']);

		self::setHTTP_HOST(route('subastas.permanentes'));

		$response = $this->get(route('subastas.permanentes'));

		$response->assertSuccessful();
	}

	/**
	 * A test for the direct sale auctions page.
	 * @return void
	 */
	public function test_subastas_venta_directa_is_succesful()
	{
		$this->markThisTestAsSkippedIfHasThisThemes(['tauleryfau']);

		self::setHTTP_HOST(route('subastas.venta_directa'));

		$response = $this->get(route('subastas.venta_directa'));

		$response->assertSuccessful();
	}

	/**
	 * A test for all auctions page.
	 * @return void
	 */
	public function test_subastas_todas_is_succesful()
	{
		$this->markThisTestAsSkippedIfHasThisThemes(['tauleryfau']);

		self::setHTTP_HOST(route('subastas.all'));

		$response = $this->get(route('subastas.all'));

		$response->assertSuccessful();
	}

	/**
	 * A test for the active auctions page.
	 * @return void
	 */
	public function test_subastas_activas_is_succesful()
	{
		$this->markThisTestAsSkippedIfHasThisThemes(['tauleryfau']);

		$response = $this->get(route('subastas.activas'));

		$response->assertSuccessful();
	}

	/**
	 * A test for the special auctions page.
	 * @return void
	 */
	public function test_subastas_especiales_is_succesful()
	{
		$this->markThisTestAsSkippedIfHasThisThemes(['tauleryfau']);

		self::setHTTP_HOST(route('subastas.haz_oferta'));

		$response = $this->get(route('subastas.especiales'));

		$response->assertSuccessful();
	}

	/**
	 * A test for "make a bid" auctions page.
	 * @return void
	 */
	public function test_subastas_haz_oferta_is_succesful()
	{
		$this->markThisTestAsSkippedIfHasThisThemes(['tauleryfau']);

		self::setHTTP_HOST(route('subastas.haz_oferta'));

		$response = $this->get(route('subastas.haz_oferta'));

		$response->assertSuccessful();
	}

	/**
	 * A test for the reverse auctions page.
	 * @return void
	 */
	public function test_subastas_inversas_is_succesful()
	{
		$this->markThisTestAsSkippedIfHasThisThemes(['tauleryfau']);

		self::setHTTP_HOST(route('subastas.subasta_inversa'));

		$response = $this->get(route('subastas.subasta_inversa'));

		$response->assertSuccessful();
	}

	/**
	 * A test for the grid lot page by category and friendly text.
	 * @return void
	 */
	public function test_grid_lot_by_category_with_cod_text_friendly_is_succesful()
	{
		$this->markThisTestAsSkippedIfHasThisThemes(['tauleryfau']);

		$category = self::getDatabaseSingleValues(
			new FgOrtsec0(),
			['sub_ortsec0' => 0],
			['key_ortsec0'],
			'lin_ortsec0');

		if ($category == null) {
			$this->markTestIncomplete('The category is empty.');
		}

		$response = $this->get(route('categoryTexFriendly', ['keycategory' => $category->key_ortsec0, 'texto' => \Str::slug($category->des_ortsec0)]));

		$response->assertSuccessful();
	}

	/**
	 * A test for the grid lot page by category.
	 * @return void
	 */
	public function test_grid_lot_by_category_is_succesful()
	{
		$this->markThisTestAsSkippedIfHasThisThemes(['tauleryfau']);

		$category = self::getDatabaseSingleValues(
			new FgOrtsec0(),
			['sub_ortsec0' => 0],
			['key_ortsec0'],
			'lin_ortsec0'
		);

		if ($category == null) {
			$this->markTestIncomplete('The category is empty.');
		}

		$response = $this->get(route('category', ['keycategory' => $category->key_ortsec0]));

		$response->assertSuccessful();
	}

	/**
	 * A test for the grid lot page by category and section.
	 * @return void
	 */
	public function test_grid_lot_by_category_and_section_is_succesful()
	{
		$this->markThisTestAsSkippedIfHasThisThemes(['tauleryfau']);

		$category = self::getDatabaseSingleValues(
			new FgOrtsec0(),
			['sub_ortsec0' => 0],
			['key_ortsec0'],
			'lin_ortsec0'
		);

		if ($category == null) {
			$this->markTestIncomplete('The category is empty.');
		}

		$subcategory = self::getDatabaseSingleValues(
			new FgOrtsec1(),
			['lin_ortsec1' => $category->lin_ortsec0, 'sub_ortsec1' => 0],
			[],
			'lin_ortsec1'
		);

		if ($subcategory == null) {
			$this->markTestIncomplete('The subcategory is empty.');
		}

		$section = self::getDatabaseSingleValues(
			new FxSec(),
			['cod_sec' => $subcategory->sec_ortsec1],
			['key_sec'],
			'cod_sec'
		);

		if ($section == null) {
			$this->markTestIncomplete('The section is empty.');
		}

		$response = $this->get(route('section', ['keycategory' => $category->key_ortsec0, 'keysubcategory' => $section->key_sec]));

		$response->assertSuccessful();
	}

	/**
	 * A test for the old lot ficha page.
	 * @return void
	 */
	public function test_old_ficha_lot_page_id_succesful()
	{
		Config::set("app.newUrlLot", 0);

		$lot = self::getLotData();

		$titleLog = "\n\nOld ficha lot\n";

		Log::info($titleLog);
		echo $titleLog;

		$this->testLotFicha($lot);
	}

	/**
	 * A test for the old lot type presential ficha page.
	 * @return void
	 */
	public function test_old_ficha_lot_type_presencial_page_id_succesful()
	{
		Config::set("app.newUrlLot", 0);

		$lot = self::getLotData(['tipo_sub' => FgSub::TIPO_SUB_PRESENCIAL]);

		$titleLog = "\n\nOld ficha lot type presencial\n";

		Log::info($titleLog);
		echo $titleLog;

		$this->testLotFicha($lot);
	}

	/**
	 * A test for the old lot type online ficha page.
	 * @return void
	 */
	public function test_old_ficha_lot_type_online_page_id_succesful()
	{
		Config::set("app.newUrlLot", 0);

		$lot = self::getLotData(['tipo_sub' => FgSub::TIPO_SUB_ONLINE]);

		$titleLog = "\n\nOld ficha lot type online\n";

		Log::info($titleLog);
		echo $titleLog;

		$this->testLotFicha($lot);
	}

	/**
	 * A test for the old lot type venta directa ficha page.
	 * @return void
	 */
	public function test_old_ficha_lot_type_venta_directa_page_id_succesful()
	{
		Config::set("app.newUrlLot", 0);

		$lot = self::getLotData(['tipo_sub' => FgSub::TIPO_SUB_VENTA_DIRECTA]);

		$titleLog = "\n\nOld ficha lot type venta directa\n";

		Log::info($titleLog);
		echo $titleLog;

		$this->testLotFicha($lot);
	}

	/**
	 * A test for the old lot type permanente ficha page.
	 * @return void
	 */
	public function test_old_ficha_lot_type_permanente_page_id_succesful()
	{
		Config::set("app.newUrlLot", 0);

		$lot = self::getLotData(['tipo_sub' => FgSub::TIPO_SUB_PERMANENTE]);

		$titleLog = "\n\nOld ficha lot type permanente\n";

		Log::info($titleLog);
		echo $titleLog;

		$this->testLotFicha($lot);

	}

	/**
	 * A test for the old lot type especial ficha page.
	 * @return void
	 */
	public function test_old_ficha_lot_type_especial_page_id_succesful()
	{
		Config::set("app.newUrlLot", 0);

		$lot = self::getLotData(['tipo_sub' => FgSub::TIPO_SUB_ESPECIAL]);

		$titleLog = "\n\nOld ficha lot type especial\n";

		Log::info($titleLog);
		echo $titleLog;

		$this->testLotFicha($lot);
	}

	/**
	 * A test for the old lot type make offer ficha page.
	 * @return void
	 */
	public function test_old_ficha_lot_type_make_offer_page_id_succesful()
	{
		Config::set("app.newUrlLot", 0);

		$lot = self::getLotData(['tipo_sub' => FgSub::TIPO_SUB_ESPECIAL]);

		$titleLog = "\n\nOld ficha lot type make offer\n";

		Log::info($titleLog);
		echo $titleLog;

		$this->testLotFicha($lot);
	}

	/**
	 * A test for the old lot ficha page with state closed.
	 * @return void
	 */
	public function test_old_ficha_lot_state_closed_page_id_succesful()
	{
		Config::set("app.newUrlLot", 0);

		$lot = self::getLotData(['cerrado_asigl0' => 'S']);

		$titleLog = "\n\nOld ficha lot state closed\n";

		Log::info($titleLog);
		echo $titleLog;

		$this->testLotFicha($lot);
	}

	/**
	 * A test for the new lot ficha page.
	 * @return void
	 */
	public function test_new_ficha_lot_page_id_succesful()
	{
		Config::set("app.newUrlLot", 1);

		$lot = self::getLotData();

		$titleLog = "\n\nNew ficha lot\n";

		Log::info($titleLog);
		echo $titleLog;

		$this->testLotFicha($lot);
	}

	/**
	 * A test for the new lot type presential ficha page.
	 * @return void
	 */
	public function test_new_ficha_lot_type_presencial_page_id_succesful()
	{
		Config::set("app.newUrlLot", 1);

		$lot = self::getLotData(['tipo_sub' => FgSub::TIPO_SUB_PRESENCIAL]);

		$titleLog = "\n\nNew ficha lot type presencial\n";

		Log::info($titleLog);
		echo $titleLog;

		$this->testLotFicha($lot);
	}

	/**
	 * A test for the new lot type online ficha page.
	 * @return void
	 */
	public function test_new_ficha_lot_type_online_page_id_succesful()
	{
		Config::set("app.newUrlLot", 1);

		$lot = self::getLotData(['tipo_sub' => FgSub::TIPO_SUB_ONLINE]);

		$titleLog = "\n\nNew ficha lot type online\n";

		Log::info($titleLog);
		echo $titleLog;

		$this->testLotFicha($lot);
	}

	/**
	 * A test for the new lot type venta directa ficha page.
	 * @return void
	 */
	public function test_new_ficha_lot_type_venta_directa_page_id_succesful()
	{
		Config::set("app.newUrlLot", 1);

		$lot = self::getLotData(['tipo_sub' => FgSub::TIPO_SUB_VENTA_DIRECTA]);

		$titleLog = "\n\nNew ficha lot type venta directa\n";

		Log::info($titleLog);
		echo $titleLog;

		$this->testLotFicha($lot);
	}

	/**
	 * A test for the new lot type permanente ficha page.
	 * @return void
	 */
	public function test_new_ficha_lot_type_permanente_page_id_succesful()
	{
		Config::set("app.newUrlLot", 1);

		$lot = self::getLotData(['tipo_sub' => FgSub::TIPO_SUB_PERMANENTE]);

		$titleLog = "\n\nNew ficha lot type permanente\n";

		Log::info($titleLog);
		echo $titleLog;

		$this->testLotFicha($lot);
	}

	/**
	 * A test for the new lot type especial ficha page.
	 * @return void
	 */
	public function test_new_ficha_lot_type_especial_page_id_succesful()
	{
		Config::set("app.newUrlLot", 1);

		$lot = self::getLotData(['tipo_sub' => FgSub::TIPO_SUB_ESPECIAL]);

		$titleLog = "\n\nNew ficha lot type especial\n";

		Log::info($titleLog);
		echo $titleLog;

		$this->testLotFicha($lot);
	}

	/**
	 * A test for the new lot type make offer ficha page.
	 * @return void
	 */
	public function test_new_ficha_lot_type_make_offer_page_id_succesful()
	{
		Config::set("app.newUrlLot", 1);

		$lot = self::getLotData(['tipo_sub' => FgSub::TIPO_SUB_ESPECIAL]);

		$titleLog = "\n\nNew ficha lot type make offer\n";

		Log::info($titleLog);
		echo $titleLog;

		$this->testLotFicha($lot);
	}

	/**
	 * A test for the new lot ficha page with state closed.
	 * @return void
	 */
	public function test_new_ficha_lot_state_closed_page_id_succesful()
	{
		Config::set("app.newUrlLot", 1);

		$lot = self::getLotData(['cerrado_asigl0' => 'S']);

		$titleLog = "\n\nNew ficha lot state closed\n";

		Log::info($titleLog);
		echo $titleLog;

		$this->testLotFicha($lot);
	}

	private function testLotFicha($lot)
	{
		if ($lot == null) {
			$messageInfo = "\nNo hay lote.\n";
			Log::info($messageInfo);
			echo $messageInfo;
			$this->markTestIncomplete('The lot is empty.');
		}

		$url = \Tools::url_lot($lot->sub_asigl0, $lot->id_auc_sessions, $lot->name, $lot->ref_asigl0, $lot->num_hces1, $lot->webfriend_hces1, $lot->title_hces1 ?? $lot->descweb_hces1);

		$request_uri = str_replace(Config::get('app.url'),"",$url);

		self::setHTTP_HOST($request_uri);

		$datos_lot = "\nLot cod and ref: " . $lot->cod_sub . " - " . $lot->ref_asigl0 . "\nLot tipo_sub: " . $lot->tipo_sub . "\nLot subc_sub: " . $lot->subc_sub . "\nLot cerrado_asigl0: " . $lot->cerrado_asigl0 . "\nLot retirado_asigl0: " . $lot->retirado_asigl0 . "\nLot oculto_asigl0: " . $lot->oculto_asigl0 . "\n";

		Log::info($datos_lot);
		echo $datos_lot;

		$response = $this->get($url);

		$response->assertSuccessful();
	}


	/**
	 * A test for the success evaluation page.
	 * @return void
	 */
	public function test_valoracion_articulos_success_is_succesful()
	{
		self::setHTTP_HOST(route('valoracion-success'));

		$response = $this->get(route('valoracion-success'));

		$response->assertSuccessful();
	}

	/**
	 * A test for the spetialists page.
	 * @return void
	 */
	public function test_especialistas_is_succesful()
	{
		self::setHTTP_HOST(route('especialistas'));

		$response = $this->get(route('especialistas'));

		$response->assertSuccessful();
	}

	/**
	 * A test for the V5 contact page.
	 * @return void
	 */
	public function test_contacto_is_succesful()
	{
		self::setHTTP_HOST(route('contact_page'));

		$response = $this->get(route('contact_page'));

		$response->assertSuccessful();
	}

	/**
	 * A test for the V5 register page.
	 * @return void
	 */
	public function test_register_is_succesful()
	{
		self::setHTTP_HOST(route('register', ['lang' => Config::get('app.locale')]));

		$response = $this->get(route('register', ['lang' => Config::get('app.locale')]));

		$response->assertSuccessful();
	}

	/**
	 * A test for the old and new lot grid page.
	 * @return void
	 */
	public function test_lot_grid_is_succesful()
	{
		$this->markThisTestAsSkippedIfHasThisThemes(['tauleryfau']);

		$aucSession = self::getMostRecientAucSession();

		$url = \Tools::url_auction($aucSession->cod_sub, $aucSession->name, $aucSession->id_auc_sessions, $aucSession->reference);

		$response = $this->get($url);

		$response->assertSuccessful();
	}

	/**
	 * A test for the lot grid page with all categories.
	 * @return void
	 */
	public function test_lot_grid_with_all_categories_is_succesful()
	{
		$this->markThisTestAsSkippedIfHasThisThemes(['tauleryfau']);

		self::setHTTP_HOST(route('allCategories'));

		$response = $this->get(route('allCategories'));

		$response->assertSuccessful();
	}

	/**
	 * A test for the artists page.
	 * @return void
	 */
	public function test_artists_page_is_successful()
	{
		$existsView = view()->exists('front::pages.artists');

		if (!$existsView) {
			$this->markTestIncomplete('The view does not exist.');
		} else {
			self::setHTTP_HOST(route('artists'));

			$response = $this->get(route('artists'));

			$response->assertSuccessful();
		}
	}

	/**
	 * A test for the artist page.
	 * @return void
	 */
	public function test_artist_page_is_successful()
	{
		$existsView = view()->exists('front::pages.artist');

		if (!$existsView) {
			$this->markTestIncomplete('The view does not exist.');
		} else {
			$artist = self::getAnArtist();

			$response = $this->get(route('artist', ['name' => \Str::slug($artist->name_artist), 'idArtist' => $artist->id_artist]));

			$response->assertSuccessful();
		}
	}

	/**
	 * A test for the articles page.
	 * @return void
	 */
	public function test_articles_page_is_successful()
	{
		$response = $this->get(route('articles'));

		$response->assertSuccessful();
	}

	/**
	 * A test for the articles page with family.
	 * @return void
	 */
	public function test_articles_page_with_family_is_succesful()
	{
		$family = self::getDatabaseSingleValues(
			new FgFamart(),
			[],
			['cod_famart'],
			'id_famart'
		);

		if ($family == null) {
			$this->markTestIncomplete('The family is empty.');
		}

		$response = $this->get(route('articles_family', ['family' => $family->cod_famart]));

		$response->assertSuccessful();

	}

	/**
	 * A test for the articles page with category.
	 * @return void
	 */
	public function test_articles_page_with_category_is_succesful()
	{
		$category = self::getDatabaseSingleValues(
			new FgOrtsec0(),
			['sub_ortsec0' => 0],
			['key_ortsec0'],
			'lin_ortsec0'
		);

		if ($category == null) {
			$this->markTestIncomplete('The category is empty.');
		}

		$response = $this->get(route('articles-category', ['category' => $category->key_ortsec0]));

		$response->assertSuccessful();
	}

	/**
	 * A test for the articles page with category and subcategory.
	 * @return void
	 */
	public function test_articles_page_with_category_and_subcategory()
	{
		$category = self::getDatabaseSingleValues(
			new FgOrtsec0(),
			['sub_ortsec0' => 0],
			['key_ortsec0'],
			'lin_ortsec0'
		);

		if	 ($category == null) {
			$this->markTestIncomplete('The category is empty.');
		}

		$subcategory = self::getDatabaseSingleValues(
			new FgOrtsec1(),
			['lin_ortsec1' => $category->lin_ortsec0, 'sub_ortsec1' => 0],
			[],
			'lin_ortsec1'
		);

		if ($subcategory == null) {
			$this->markTestIncomplete('The subcategory is empty.');
		}

		$section = self::getDatabaseSingleValues(
			new FxSec(),
			['cod_sec' => $subcategory->sec_ortsec1],
			['key_sec'],
			'cod_sec'
		);

		if ($section == null) {
			$this->markTestIncomplete('The section is empty.');
		}

		$response = $this->get(route('articles-subcategory', ['category' => $category->key_ortsec0, 'subcategory' => $section->key_sec]));

		$response->assertSuccessful();
	}

	/**
	 * A test for the article page.
	 * @return void
	 */
	public function test_article_page_is_succesful()
	{
		$article = self::getDatabaseSingleValues(
			new FgArt0(),
			[],
			['des_art0'],
			'id_art0'
		);

		if ($article == null) {
			$this->markTestIncomplete('The article is empty.');
		}

		$response = $this->get(route('article', ['idArticle' => $article->id_art0, 'friendly' => \Str::slug($article->des_art0)]));

		$response->assertSuccessful();
	}

	/**
	 * A test for the static pages
	 * @return void
	 */
	public function test_static_page_is_succesful()
	{
		$page = self::getDatabaseSingleValues(
			new Web_Page(),
			[],
			['CONTENT_WEB_PAGE'],
			'ID_WEB_PAGE'
		);

		$response = $this->get(route('staticPage', ['lang' => mb_strtolower($page->lang_web_page), 'pagina' => $page->key_web_page]));

		$response->assertSuccessful();
	}

	/**
	 * A test for the faqs page.
	 * @return void
	 */
	public function test_faqs_page_is_succesful()
	{
		$response = $this->get(route('faqs_page'));

		$response->assertSuccessful();
	}

	/**
	 * A test for the calendar page.
	 * @return void
	 */
	public function test_calendar_page_is_succesful()
	{
		$response = $this->get(route('calendar'));

		$response->assertSuccessful();
	}

	/**
	 * A test for the blog index page.
	 * @return void
	 */
	public function test_blog_index_page_is_succesful()
	{
		$existsView = view()->exists('front::content.slider');

		if (!$existsView) {
			$this->markTestIncomplete('The view "content.slider" does not exist.');
		}

		$response = $this->get(route('blog.index', ['lang' => Config::get('app.locale')]));

		$response->assertSuccessful();
	}

}
