<?php

namespace Tests\Feature;

use App\Models\articles\FgArt0;
use App\Models\V5\FgFamart;
use App\Models\V5\FgOrtsec0;
use App\Models\V5\FgOrtsec1;
use App\Models\V5\FgSub;
use App\Models\V5\FxSec;
use App\Models\V5\Web_Artist;
use App\Models\V5\Web_Page;
use App\Providers\ToolsServiceProvider as Tools;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class PagesTest extends TestCase
{

	#region Helper methods

	public static function setHTTP_HOST($route)
	{
		$url = explode('/',url()->current());
		$http_host = end($url);
		$_SERVER['HTTP_HOST'] = $http_host;
		$_SERVER['REQUEST_URI'] = $route;
	}

	private function getAnArtist()
	{
		return Web_Artist::select("NAME_ARTIST, ID_ARTIST")
		->where("ACTIVE_ARTIST",1)
		->orderby("ID_ARTIST", "asc")
		->first();
	}

	private function disbleRecaptcha()
	{
		Config::set('app.codRecaptcha', false);
		Config::set('app.captcha_v3', false);
		Config::set('app.codRecaptchaEmail', false);
	}

	#endregion

    /**
     * A home page test.
     * @return void
     */
    public function test_home_page_is_successful()
    {
		$url = route('home');

		self::setHTTP_HOST($url);

        $response = $this->get($url);

		if ($response->baseResponse->getStatusCode() == 200) {
			$response->assertSuccessful();
		} else {
			$response->assertRedirect($url);
		}
    }

	/**
	 * A test for the success evaluation page.
	 * @return void
	 */
	public function test_valoracion_articulos_success_is_succesful()
	{
		self::setHTTP_HOST(route('valoracion-success'));

		$url = route('valoracion-success');

		$response = $this->get($url);

		$response = $this->get($url);

		$response->assertSuccessful();
	}

	/**
	 * A test for the spetialists page.
	 * @return void
	 */
	public function test_especialistas_is_succesful()
	{
		$url = route('especialistas');

		self::setHTTP_HOST($url);

		$response = $this->get($url);

		$response->assertSuccessful();
	}

	/**
	 * A test for the V5 contact page.
	 * @return void
	 */
	public function test_contacto_is_succesful()
	{
		$url = route('contact_page');

		self::setHTTP_HOST($url);

		$response = $this->get($url);

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
			$url = route('artists');

			self::setHTTP_HOST($url);

			$response = $this->get($url);

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

			if ($artist == null) {
				$this->markTestIncomplete('The artist is empty.');
			}

			$url = route('artist', ['name' => \Str::slug($artist->name_artist), 'idArtist' => $artist->id_artist]);

			self::setHTTP_HOST($url);

			$response = $this->get($url);

			$response->assertSuccessful();
		}
	}

	/**
	 * A test for the articles page.
	 * @return void
	 */
	public function test_articles_page_is_successful()
	{
		$url = route('articles');

		self::setHTTP_HOST($url);

		$response = $this->get($url);

		$response->assertSuccessful();
	}

	/**
	 * A test for the articles page with family.
	 * @return void
	 */
	public function test_articles_page_with_family_is_succesful()
	{
		$family = Tools::getDatabaseSingleValues(
			(new FgFamart())->query(),
			[],
			['cod_famart'],
			'id_famart'
		);

		if ($family == null) {
			$this->markTestIncomplete('The family is empty.');
		}

		$url = route('articles_family', ['family' => $family->cod_famart]);

		self::setHTTP_HOST($url);

		$response = $this->get($url);

		$response->assertSuccessful();

	}

	/**
	 * A test for the articles page with category.
	 * @return void
	 */
	public function test_articles_page_with_category_is_succesful()
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

		$url = route('articles-category', ['category' => $category->key_ortsec0]);

		self::setHTTP_HOST($url);

		$response = $this->get($url);

		$response->assertSuccessful();
	}

	/**
	 * A test for the articles page with category and subcategory.
	 * @return void
	 */
	public function test_articles_page_with_category_and_subcategory_is_succesful()
	{
		$category = Tools::getDatabaseSingleValues(
			(new FgOrtsec0())->query(),
			['sub_ortsec0' => 0],
			['key_ortsec0'],
			'lin_ortsec0'
		);

		if	 ($category == null) {
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

		$url = route('articles-subcategory', ['category' => $category->key_ortsec0, 'subcategory' => $section->key_sec]);

		self::setHTTP_HOST($url);

		$response = $this->get($url);

		$response->assertSuccessful();
	}

	/**
	 * A test for the article page.
	 * @return void
	 */
	public function test_article_page_is_succesful()
	{
		$article = Tools::getDatabaseSingleValues(
			(new FgArt0())->query(),
			[],
			['des_art0'],
			'id_art0'
		);

		if ($article == null) {
			$this->markTestIncomplete('The article is empty.');
		}

		$url = route('article', ['idArticle' => $article->id_art0, 'friendly' => \Str::slug($article->des_art0)]);

		self::setHTTP_HOST($url);

		$response = $this->get($url);

		$response->assertSuccessful();
	}

	/**
	 * A test for the static pages
	 * @return void
	 */
	public function test_static_page_is_succesful()
	{
		$page = Tools::getDatabaseSingleValues(
			(new Web_Page())->query(),
			[],
			['CONTENT_WEB_PAGE'],
			'ID_WEB_PAGE'
		);

		if ($page == null) {
			$this->markTestIncomplete('The page is empty.');
		}

		$url = route('staticPage', ['lang' => mb_strtolower($page->lang_web_page), 'pagina' => $page->key_web_page]);

		self::setHTTP_HOST($url);

		$response = $this->get($url);

		$response->assertSuccessful();
	}

	/**
	 * A test for the faqs page.
	 * @return void
	 */
	public function test_faqs_page_is_succesful()
	{
		$url = route('faqs_page');

		self::setHTTP_HOST($url);

		$response = $this->get($url);

		$response->assertSuccessful();
	}

	/**
	 * A test for the calendar page.
	 * @return void
	 */
	public function test_calendar_page_is_succesful()
	{
		$url = route('calendar');

		self::setHTTP_HOST($url);

		$response = $this->get($url);

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

		$url = route('blog.index', ['lang' => Config::get('app.locale')]);

		self::setHTTP_HOST($url);

		$response = $this->get($url);

		$response->assertSuccessful();
	}

	/**
	 * A test for verify if the contact form works correctly.
	 * @return void
	 */
	public function test_send_contact_form_ajax_is_succesful()
	{
		$this->disbleRecaptcha();

		$url = route('contactSendmail');

		self::setHTTP_HOST($url);

		$response = $this->post($url, [
			"_token" => csrf_token(),
			"nombre" => "Test de parte de los tests",
			"email" => Config::get('app.admin_email'),
			"telefono" => "123456789",
			"comentario" => "Nam vitae egestas massa. Aenean luctus imperdiet velit non ultrices.",
			"condiciones" => null
		]);

		$response->assertSuccessful();
	}


}
