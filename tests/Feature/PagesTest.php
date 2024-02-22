<?php

namespace Tests\Feature;

use App\Models\articles\FgArt0;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FgFamart;
use App\Models\V5\FgOrtsec0;
use App\Models\V5\FgOrtsec1;
use App\Models\V5\FgSub;
use App\Models\V5\FxSec;
use App\Models\V5\FxSubSec;
use App\Models\V5\Web_Artist;
use Tests\TestCase;

class PagesTest extends TestCase
{
	#region Helper methods

	// private $themesAucExclude = ['durangallery'];

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

	private function getAnArtist()
	{
		return Web_Artist::select("NAME_ARTIST, ID_ARTIST")->where("ACTIVE_ARTIST",1)->orderby("ID_ARTIST", "asc")->first();
	}

	/**
	 * Get the values from the database.
	 * @param mixed $dataTable
	 * @param array $whereCases
	 * @param array $whereIsNotNullCases
	 * @param string $orderBy
	 * @return mixed
	 */
	private function getDatabaseValues($dataTable, $whereCases = [], $whereIsNotNullCases = [], $orderBy = '')
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
		return $dataTable->first();
	}

	#endregion

    /**
     * A home page test.
     * @return void
     */
    public function test_home_page_is_successful()
    {
		self::setHTTP_HOST(route('home'));

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
		self::setHTTP_HOST(route('subastas.todas'));

		$response = $this->get(route('subastas.todas'));

		$response->assertSuccessful();
	}

	/**
	 * A test for the active auctions page.
	 * @return void
	 */
	public function test_subastas_activas_is_succesful()
	{
		$response = $this->get(route('subastas.activas'));

		$response->assertSuccessful();
	}

	/**
	 * A test for the special auctions page.
	 * @return void
	 */
	public function test_subastas_especiales_is_succesful()
	{
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
		$category = self::getDatabaseValues(new FgOrtsec0(), ['sub_ortsec0' => 0], ['key_ortsec0'], 'lin_ortsec0');

		if ($category == null) {
			$this->markTestSkipped('The category is empty.');
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
		$category = self::getDatabaseValues(new FgOrtsec0(), ['sub_ortsec0' => 0], ['key_ortsec0'], 'lin_ortsec0');

		if ($category == null) {
			$this->markTestSkipped('The category is empty.');
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
		$category = self::getDatabaseValues(new FgOrtsec0(), ['sub_ortsec0' => 0], ['key_ortsec0'], 'lin_ortsec0');

		if ($category == null) {
			$this->markTestSkipped('The category is empty.');
		}

		$subcategory = self::getDatabaseValues(new FgOrtsec1(), ['lin_ortsec1' => $category->lin_ortsec0, 'sub_ortsec1' => 0], [], 'lin_ortsec1');

		if ($subcategory == null) {
			$this->markTestSkipped('The subcategory is empty.');
		}

		$section = self::getDatabaseValues(new FxSec(), ['cod_sec' => $subcategory->sec_ortsec1], ['key_sec'], 'cod_sec');

		if ($section == null) {
			$this->markTestSkipped('The section is empty.');
		}

		$response = $this->get(route('section', ['keycategory' => $category->key_ortsec0, 'keysubcategory' => $section->key_sec]));

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
		self::setHTTP_HOST(route('register', ['lang' => \Config::get('app.locale')]));

		$response = $this->get(route('register', ['lang' => \Config::get('app.locale')]));

		$response->assertSuccessful();
	}

	/**
	 * A test for the old and new lot grid page.
	 * @return void
	 */
	public function test_lot_grid_is_succesful()
	{
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
			$this->markTestSkipped('The view does not exist.');
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
			$this->markTestSkipped('The view does not exist.');
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
		$family = self::getDatabaseValues(new FgFamart(), [], ['cod_famart'], 'id_famart');

		if ($family == null) {
			$this->markTestSkipped('The family is empty.');
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
		$category = self::getDatabaseValues(new FgOrtsec0(), ['sub_ortsec0' => 0], ['key_ortsec0'], 'lin_ortsec0');

		if ($category == null) {
			$this->markTestSkipped('The category is empty.');
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
		$category = self::getDatabaseValues(new FgOrtsec0(), ['sub_ortsec0' => 0], ['key_ortsec0'], 'lin_ortsec0');

		if	 ($category == null) {
			$this->markTestSkipped('The category is empty.');
		}

		$subcategory = self::getDatabaseValues(new FgOrtsec1(), ['lin_ortsec1' => $category->lin_ortsec0, 'sub_ortsec1' => 0], [], 'lin_ortsec1');

		if ($subcategory == null) {
			$this->markTestSkipped('The subcategory is empty.');
		}

		$section = self::getDatabaseValues(new FxSec(), ['cod_sec' => $subcategory->sec_ortsec1], ['key_sec'], 'cod_sec');

		if ($section == null) {
			$this->markTestSkipped('The section is empty.');
		}

		$response = $this->get(route('articles-subcategory', ['category' => $category->key_ortsec0, 'subcategory' => $section->key_sec]));

		$response->assertSuccessful();
	}

	public function test_article_page_is_succesful()
	{
		$article = self::getDatabaseValues(new FgArt0(), [], ['des_art0'], 'id_art0');

		if ($article == null) {
			$this->markTestSkipped('The article is empty.');
		}

		$response = $this->get(route('article', ['idArticle' => $article->id_art0, 'friendly' => \Str::slug($article->des_art0)]));

		$response->assertSuccessful();
	}

	public function test_static_page_is_succesful()
	{

	}

}
