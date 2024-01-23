<?php

namespace App\Http\Controllers\admin\subasta;

use App\Http\Controllers\Controller;
use View;
use App\libs\FormLib;
use App\Http\Controllers\apilabel\CategoryController;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\V5\FgOrtsec0;


class AdminCategoryController extends Controller
{

	public function __construct()
	{
		view()->share(['menu' => 'subastas']);
	}

	/**
	 * Mostrar página incial
	 * */
	function index(){
		$actualPage = request('page',1);
		$itemsPerPage = 25;
		$ortsec0 =  New FgOrtsec0();
		$data['categories'] = $ortsec0->select("FGORTSEC0.*, count(EMP_ORTSEC0) over (partition by EMP_ORTSEC0) as NUMCATEGORIES")
								->where("SUB_ORTSEC0",0)->
								orderby("ORDEN_ORTSEC0,DES_ORTSEC0")->
								skip(($actualPage-1) *  $itemsPerPage)->
								take($itemsPerPage)->
								get();

		$totalCategories =0;
		if(!empty($data['categories']))	{
			$totalCategories = $data['categories'][0]->numcategories ?? 0;
		}

		$url = "/admin/category";
		$data['paginator'] = new LengthAwarePaginator(range(1,$totalCategories), $totalCategories, $itemsPerPage, $actualPage,["path" => $url]);

		return View::make('admin::pages.subasta.category.index', $data);
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

		$idcategory= request("idcategory",0);


		$data['token'] = Formlib::Hidden("_token", 1, csrf_token());
		$data['idcategory'] = Formlib::Hidden("idcategory", 1, $idcategory);

		$description ="";
		$order="";
		$metadescription = "";
		$metatitle="";
		$metacontent="";
		$urlfriendly="";


		$category = FgOrtsec0::where("lin_ortsec0", $idcategory)->first();
		if (!empty($category)) {
			$description = $category->des_ortsec0;
			$order= $category->orden_ortsec0;
			$metadescription = $category->meta_description_ortsec0;
			$metatitle= $category->meta_titulo_ortsec0;
			$metacontent= $category->meta_contenido_ortsec0;
			$urlfriendly= $category->key_ortsec0;
		}



		#ponemos old  para que cargue  los valores enviados por post y se puedan volver a mostrar en el formulario si se ha devuelto error

		$data['description'] = FormLib::Text("description", 1, old("description",$description));
		$data['order'] = FormLib::Text("order", 1,  old('order',$order));
		$data['urlfriendly'] = FormLib::Text("urlfriendly", 0, old('urlfriendly', $urlfriendly) );
		$data['metadescription'] = FormLib::Textarea("metadescription", 0, old('metadescription', $metadescription) );
		$data['metatitle'] = FormLib::Textarea("metatitle", 0, old('metatitle', $metatitle) );
		$data['metacontent'] = FormLib::Textarea("metacontent", 0, old('metacontent', $metacontent) );

		return View::make('admin::pages.subasta.category.editar', $data);

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

		$idcategory = request("idcategory");

		$category = [
			'idcategory'=> $idcategory,
			'description' => request('description'),
			'order' => request('order',0),
			'urlfriendly' => request("urlfriendly") ,
			'metadescription' => request('metadescription'),
			'metatitle' => request('metatitle'),
			'metacontent' => request('metacontent'),
		];
		#si es nuevo, no tendrá idcategory y deberemso darsleo
		if(empty($category['idcategory'])){
			#buscamos el nuevo valor sumandole uno al mas alto que haya con la subasta 0
			$fgortsec0 = FgOrtsec0::latest('LIN_ORTSEC0')->where("SUB_ORTSEC0",0)->first();
			if(!empty($fgortsec0) && !empty($fgortsec0->lin_ortsec0)){
				$category['idcategory']= $fgortsec0->lin_ortsec0 +1;
			}else{
				$category['idcategory']= 1;
			}

		}


		$categories[] = $category;

		$categoryController = new CategoryController();
		if(empty($idcategory)){
			$json = $categoryController->createCategory($categories);
		}else{
			$json = $categoryController->updateCategory($categories);
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
			if(empty($idcategory)){
				#Si todo ha ido bien envianmos al listado para que sea mas facil crear muchos seguidos
				return redirect("admin/category")->with(['success' =>array(trans('admin-app.title.created_ok'))]);

			}else{
				return redirect("admin/category")
				->with(['success' =>array(trans('admin-app.title.updated_ok')) ]);
			}

		}

	}

	/**
	 * Eliminar item
	 * */
	function destroy(){

		$idCategory = request("idcategory");
		if(empty($idCategory)){
			return back()->withErrors(array(trans('admin-app.error.no_id_delete')));
		}
		FgOrtsec0::where("LIN_ORTSEC0", $idCategory)->where("SUB_ORTSEC0", 0)->delete();

		return back()->with('success', array(trans('admin-app.title.deleted_ok')) );

	}


}
