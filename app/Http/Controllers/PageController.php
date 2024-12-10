<?php
namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class PageController extends Controller
{

   public function getPagina($lang, $key)
   {
        $pagina = new Page();

        $data  = $pagina->getPagina($lang,$key);
        if(empty( $data )) {
            return abort(404);
        }

       # Asignamos
        //$data->name = $data->title.' - '.Config::get('app.name');

        $SEO_metas= new \stdClass();
        if(!empty($data->webnoindex_web_page) && $data->webnoindex_web_page == 1){
            $SEO_metas->noindex_follow = true;
        }else{
             $SEO_metas->noindex_follow = false;
        }


        $SEO_metas->meta_title = $data->webmetat_web_page;
        $SEO_metas->meta_description = $data->webmetad_web_page;

        if(empty($_GET['modal'])){
            $data = array(
            'data' => $data,
            'seo' => $SEO_metas ,
			'lang' => $lang
        );

            return View::make('front::pages.page', array('data' => $data));
        }else{
            //return View::make('front::includes.ficha.modals_information', array('data' => $data->value));
            return $data->content_web_page;
        }
   }

    /**
     * Metodo usado en Alcala para mostrar articulos en paginas estaticas
     * @param type $id
     * @return type
     */
    public function getArticle($id){

       $sql="SELECT * FROM WEB_RESOURCE WHERE ID_WEB_RESOURCE = :id AND ID_EMP = :emp";
       $bindings =  array(
                        'emp'       => Config::get('app.emp'),
                        'id'       => $id,
                    );
        $res = DB::select($sql, $bindings);

        if(empty($res)){
           exit (View::make('front::errors.404'));
        }

        $data = array(
            'data' => $res[0],
        );

        $data['data']->name_web_page = $res[0]->title;
        $data['data']->id_web_page = $res[0]->title;
        $data['data']->content_web_page = $res[0]->content;

        return View::make('front::pages.page', array('data' => $data));

    }

    public function getDepartment($lang){

        return $this->getPagina($lang,"departamentos");
	}

	public function siteMapPage()
	{
		$lang = strtoupper(Config::get('app.locale', 'es'));

		['pages' => $pages, 'subastas' => $subastas, 'lotes' => $lotes, 'categorias' => $categorias] = (new ContentController())->contentAvailable($lang);

		$subastas = $subastas->map(function ($subasta, $key) use ($lotes) {
			$subasta->lotes = $lotes->where('sub_asigl0', $subasta->cod_sub);
			return $subasta;
		});

		return view('front::pages.site_map', compact('subastas', 'pages', 'categorias'));
	}

}
