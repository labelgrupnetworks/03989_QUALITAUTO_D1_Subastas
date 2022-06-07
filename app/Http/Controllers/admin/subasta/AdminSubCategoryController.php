<?php

namespace App\Http\Controllers\admin\subasta;

use App\Http\Controllers\Controller;
use View;
use App\libs\FormLib;
use App\Http\Controllers\apilabel\SubCategoryController;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\V5\FxSec;
use App\Models\V5\FgOrtsec0;
use App\Models\V5\FgOrtsec1;

class AdminSubCategoryController extends Controller
{

	/*index, create, show, edit, store, update, destroy*/

	/**
	 * Mostrar página incial
	 * */
	function index(){
		$actualPage = request('page',1);
		$itemsPerPage = 25;
		$sec =  New FxSec();
		$sec = $sec->select("COD_SEC, DES_SEC, KEY_SEC, LIN_ORTSEC1, ORDEN_ORTSEC1, count(GEMP_SEC) over (partition by GEMP_SEC) as NUMSUBCATEGORIES")->
								JoinFgOrtsecFxSec()->
								#AUNQUE SOLO DEBERÍA HABER DE LA SUBASTA 0 PARA LOS QUE NO TIENEN ERP, MEJOR PREVENIR
								where("SUB_ORTSEC1", 0)->
								orderby("LIN_ORTSEC1, ORDEN_ORTSEC1, DES_SEC")->
								skip(($actualPage-1) *  $itemsPerPage)->
								take($itemsPerPage);
		if(!empty(request("idcategory"))){
			$sec = $sec->where("LIN_ORTSEC1", request("idcategory"));
		}

		$data['subcategories'] = $sec->get();

		$totalSubCategories = $data['subcategories'][0]->numsubcategories ?? 0;

		#listado de categorias
		$fgOrtsec0 = FgOrtsec0::where("SUB_ORTSEC0",0)->select("LIN_ORTSEC0, DES_ORTSEC0")->get();
		$categories = array();
		foreach($fgOrtsec0 as $category){
			$categories[$category->lin_ortsec0] = $category->des_ortsec0;
		}

		$data["categories"] = $categories;
		$url = "/admin/subcategory";
		if(!empty(request("idcategory"))){
			$url .= "?idcategory=".request("idcategory");
		}

		$data['paginator'] = new LengthAwarePaginator(range(1,$totalSubCategories), $totalSubCategories, $itemsPerPage, $actualPage,["path" => $url]);

		return View::make('admin::pages.subasta.subcategory.index', $data);
	}

	/**
	 * Mostrar formulario para crear uno nuevo
	 * */
	function create(){
	}

	/**
	 * Mostrar item
	 * */
	function show(){
	}

	/**
	 * Formulario con item
	 * */
	function edit(){

		$idsubcategory= request("idsubcategory",0);


		$data['token'] = Formlib::Hidden("_token", 1, csrf_token());
		$data['old_idsubcategory'] = Formlib::Hidden("old_idsubcategory", 1, $idsubcategory);
		$idcategory="";
		$description ="";
		$order="";
		$metadescription = "";
		$metatitle="";
		$metacontent="";
		$urlfriendly="";

		#listado de categorias
		$fgOrtsec0 = FgOrtsec0::where("SUB_ORTSEC0",0)->select("LIN_ORTSEC0, DES_ORTSEC0")->get();
		$categories = array();
		foreach($fgOrtsec0 as $category){
			$categories[$category->lin_ortsec0] = $category->des_ortsec0;
		}


		$subcategory = FxSec::where("cod_sec", $idsubcategory)->JoinFgOrtsecFxSec()->where("SUB_ORTSEC1", 0)->first();
		if (!empty($subcategory)) {
			$idsubcategory = $subcategory->cod_sec;
			$description = $subcategory->des_sec;
			$order= $subcategory->orden_ortsec1;
			$idcategory= $subcategory->lin_ortsec1;
			$metadescription = $subcategory->meta_description_sec;
			$metatitle= $subcategory->meta_titulo_sec;
			$metacontent= $subcategory->meta_contenido_sec;
			$urlfriendly= $subcategory->key_sec;
		}



		#ponemos old  para que cargue  los valores enviados por post y se puedan volver a mostrar en el formulario si se ha devuelto error

		$data['description'] = FormLib::Text("description", 1, old("description",$description));
		$data['order'] = FormLib::Text("order", 1,  old('order',$order));
		if(empty($idsubcategory)){
			$data['idsubcategory'] = FormLib::Text("idsubcategory", 1,  "");
		}else{
			$data['idsubcategory'] = FormLib::TextReadOnly("idsubcategory", 0,  $idsubcategory);
		}


		$data['idcategory'] = FormLib::Select("idcategory", 1,  old('idcategory',$idcategory),$categories );
		$data['urlfriendly'] = FormLib::Text("urlfriendly", 0, old('urlfriendly', $urlfriendly) );
		$data['metadescription'] = FormLib::Textarea("metadescription", 0, old('metadescription', $metadescription) );
		$data['metatitle'] = FormLib::Textarea("metatitle", 0, old('metatitle', $metatitle) );
		$data['metacontent'] = FormLib::Textarea("metacontent", 0, old('metacontent', $metacontent) );

		return View::make('admin::pages.subasta.subcategory.editar', $data);

	}

	/**
	 * Guardar con item
	 * */
	function store(){

	}

	/**
	 * Actualizar item
	 * */
	#Actualiza y crea si el id es 0 o vacio
	function update(){

		$old_idsubcategory = request("old_idsubcategory");

		$subcategory = [
			'idsubcategory'=>request("idsubcategory"),
			'description' => request('description'),
			'idcategory' => request('idcategory'),
			'order' => request('order'),
			'urlfriendly' => request("urlfriendly") ,
			'metadescription' => request('metadescription'),
			'metatitle' => request('metatitle'),
			'metacontent' => request('metacontent'),
		];



		$subcategories[] = $subcategory;

		$SubCategoryController = new SubCategoryController();
		if(empty($old_idsubcategory)){
			$json = $SubCategoryController->createSubCategory($subcategories);
		}else{
			$json = $SubCategoryController->updateSubCategory($subcategories);
		}

		$result = json_decode($json);

		if ($result->status == 'ERROR') {
			$errors = array();
			foreach($result->items as $itemError){

				if(gettype($itemError) =="string" ){
					$errors[] = $result->message;

				}else{
					foreach($itemError as  $fieldError){
						foreach ($fieldError as $error){
							$errors[] = $error;
						}
					}
				}

			}
			return redirect()->back()
			->withErrors($errors)->withInput();
		}else{
			if(empty($old_idsubcategory)){
				#Si todo ha ido bien envianmos al listado para que sea mas facil crear muchos seguidos
				return redirect("admin/subcategory")->with(['success' =>array(trans('admin-app.title.created_ok'))]);

			}else{
				return redirect("admin/subcategory")
				->with(['success' =>array(trans('admin-app.title.updated_ok')) ]);
			}

		}

	}

	/**
	 * Eliminar item
	 * */
	function destroy(){

		$idsubcategory = request("idsubcategory");
		if(empty($idsubcategory)){
			return back()->withErrors(array(trans('admin-app.error.no_id_delete')));
		}
		FxSec::where("cod_sec", $idsubcategory)->delete();
		FgOrtsec1::where("sec_ortsec1", $idsubcategory)->where("SUB_ORTSEC1", 0)->delete();

		return back()->with('success', array(trans('admin-app.title.deleted_ok')) );

	}


}
