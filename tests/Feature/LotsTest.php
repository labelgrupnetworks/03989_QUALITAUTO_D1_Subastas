<?php

namespace Tests\Feature;

use App\Models\V5\FgAsigl0;
use App\Models\V5\FgOrtsec0;
use App\Models\V5\FgOrtsec1;
use App\Models\V5\FgSub;
use App\Models\V5\FxSec;
use App\Providers\ToolsServiceProvider as Tools;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Tests\Feature\PagesTest;
use Tests\TestCase;

class LotsTest extends TestCase
{
	#region Helper methods

	private function getLotData(array $whereCasesToAdd = [])
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

		return Tools::getDatabaseSingleValues(
			(new FgAsigl0())->query(),
			$whereCases,
			['TITULO_HCES1'],
			'fini_asigl0',
			[],
			['joinFghces1Asigl0', 'joinSessionAsigl0', 'joinSubastaAsigl0']
		);
	}

	private function getMostRecientAucSession()
	{
		return FgSub::joinSessionSub()->orderBy('"start"', 'desc')->first();
	}

	#endregion

	/**
	 * A test for the old and new lot grid page.
	 * @return void
	 */
	public function test_lot_grid_is_succesful()
	{
		$aucSession = self::getMostRecientAucSession();

		$url = Tools::url_auction($aucSession->cod_sub, $aucSession->name, $aucSession->id_auc_sessions, $aucSession->reference);

		$response = $this->get($url);

		$response->assertSuccessful();
	}

	/**
	 * A test for the lot grid page with all categories.
	 * @return void
	 */
	public function test_lot_grid_with_all_categories_is_succesful()
	{
		$url = route('allCategories');

		PagesTest::setHTTP_HOST($url);

		$response = $this->get($url);

		$response->assertSuccessful();
	}

	/**
	 * A test for the grid lot page by category and friendly text.
	 * @return void
	 */
	public function test_grid_lot_by_category_with_cod_text_friendly_is_succesful()
	{
		$category = Tools::getDatabaseSingleValues(
			(new FgOrtsec0())->query(),
			['sub_ortsec0' => 0],
			['key_ortsec0'],
			'lin_ortsec0'
		);

		if ($category == null) {
			$this->markTestIncomplete('The category is empty.');
		}

		$url = route('categoryTexFriendly', ['keycategory' => $category->key_ortsec0, 'texto' => \Str::slug($category->des_ortsec0)]);

		PagesTest::setHTTP_HOST($url);

		$response = $this->get($url);

		$response->assertSuccessful();
	}

	/**
	 * A test for the grid lot page by category.
	 * @return void
	 */
	public function test_grid_lot_by_category_is_succesful()
	{
		$category = Tools::getDatabaseSingleValues(
			(new FgOrtsec0())->query(),
			['sub_ortsec0' => 0],
			['key_ortsec0'],
			'lin_ortsec0'
		);

		if ($category == null) {
			$this->markTestIncomplete('The category is empty.');
		}

		$url = route('category', ['keycategory' => $category->key_ortsec0]);

		PagesTest::setHTTP_HOST($url);

		$response = $this->get($url);

		$response->assertSuccessful();
	}

	/**
	 * A test for the grid lot page by category and section.
	 * @return void
	 */
	public function test_grid_lot_by_category_and_section_is_succesful()
	{
		$category = Tools::getDatabaseSingleValues(
			(new FgOrtsec0())->query(),
			['sub_ortsec0' => 0],
			['key_ortsec0'],
			'lin_ortsec0'
		);

		if ($category == null) {
			$this->markTestIncomplete('The category is empty.');
		}

		$subcategory = Tools::getDatabaseSingleValues(
			(new FgOrtsec1())->query(),
			['lin_ortsec1' => $category->lin_ortsec0, 'sub_ortsec1' => 0],
			[],
			'lin_ortsec1'
		);

		if ($subcategory == null) {
			$this->markTestIncomplete('The subcategory is empty.');
		}

		$section = Tools::getDatabaseSingleValues(
			(new FxSec())->query(),
			['cod_sec' => $subcategory->sec_ortsec1],
			['key_sec'],
			'cod_sec'
		);

		if ($section == null) {
			$this->markTestIncomplete('The section is empty.');
		}

		$url = route('section', ['keycategory' => $category->key_ortsec0, 'keysubcategory' => $section->key_sec]);

		PagesTest::setHTTP_HOST($url);

		$response = $this->get($url);

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

		$this->testLotFicha($lot);
	}

	/**
	 * This is a method to execute the test for the lot ficha.
	 * @param FgAsigl0|null $lot
	 * @return void
	 */
	private function testLotFicha(FgAsigl0|null $lot)
	{
		if ($lot == null) {
			$messageInfo = "\nNo hay lote.\n";
			Log::info($messageInfo);
			$this->markTestIncomplete('The lot is empty.');
		}

		$url = Tools::url_lot($lot->sub_asigl0, $lot->id_auc_sessions, $lot->name, $lot->ref_asigl0, $lot->num_hces1, $lot->webfriend_hces1, $lot->title_hces1 ?? $lot->descweb_hces1);

		$request_uri = str_replace(Config::get('app.url'), "", $url);

		PagesTest::setHTTP_HOST($request_uri);

		$datos_lot = "\nLot cod and ref: " . $lot->cod_sub . " - " . $lot->ref_asigl0 . "\nLot tipo_sub: " . $lot->tipo_sub . "\nLot subc_sub: " . $lot->subc_sub . "\nLot cerrado_asigl0: " . $lot->cerrado_asigl0 . "\nLot retirado_asigl0: " . $lot->retirado_asigl0 . "\nLot oculto_asigl0: " . $lot->oculto_asigl0 . "\n";
		Log::info($datos_lot);

		$response = $this->get($url);

		$response->assertSuccessful();
	}
}
