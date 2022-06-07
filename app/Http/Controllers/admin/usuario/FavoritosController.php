<?php

namespace App\Http\Controllers\admin\usuario;

use App\Http\Controllers\Controller;
use App\Models\V5\Web_Favorites;


class FavoritosController extends Controller
{

    // Administrar la información de las casas de subastas

    /*
     * Pendiente mirar de guardar en alguna variable el idioma actual
     */

    public function index()
    {
        $data = array('menu' => 3);

        #solo subastas activas
        $fav_db = Web_Favorites::joinCliWebFavorites()->
                joinSubFavorites()->
                joinAsigl0Favorites()->
                joinHces1Favorites()->
                where("id_emp",\Config::get('app.emp'))->where("subc_sub","S")->get();
        $favorites = array();
        foreach($fav_db as $fav){
            if(empty($favorites[$fav->cod_sub])){
                $favorites[$fav->cod_sub] = array();
            }
            if(empty($favorites[$fav->cod_sub][$fav->ref_asigl0])){
                $favorites[$fav->cod_sub][$fav->ref_asigl0] = array();
            }

            $favorites[$fav->cod_sub][$fav->ref_asigl0][$fav->cod_cliweb] = $fav->toarray();


        }

      foreach($favorites as $cod_sub => $auction){
         #ordenamos los lotes
        ksort($favorites[$cod_sub]);
      }


        $data['favorites'] = $favorites;

        return \View::make('admin::pages.usuario.favoritos.favoritos',$data);



    }




}
