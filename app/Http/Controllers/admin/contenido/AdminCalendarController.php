<?php

namespace App\Http\Controllers\admin\contenido;

use App\Http\Controllers\Controller;
use View;
use App\libs\FormLib;
use App\Models\V5\WebCalendar;
use App\Models\V5\WebCalendarEvent;

use Illuminate\Pagination\LengthAwarePaginator;


class AdminCalendarController extends Controller
{

	/*index, create, show, edit, store, update, destroy*/

	/**
	 * Mostrar página incial
	 * */
	function index(){
		$actualPage = request('page',1);
		$itemsPerPage = 25;
		$data['events'] = WebCalendar::select("ID_CALENDAR, NAME_CALENDAR, COD_CALENDAR_EVENT, START_CALENDAR,END_CALENDAR, count(emp_calendar) over (partition by emp_calendar) as numevents")
						->JoinEvent()->orderby("start_calendar", "desc")
						->skip(($actualPage-1) *  $itemsPerPage)
						->take($itemsPerPage)
						->get();
		$totalLots =0;
		if(count($data['events']) > 0)	{
			$totalLots = $data['events'][0]->numevents;
		}

		$url = "/admin/calendar";
		$data['paginator'] = new LengthAwarePaginator(range(1,$totalLots), $totalLots, $itemsPerPage, $actualPage,["path" => $url]);

		return View::make('admin::pages.contenido.calendar.index', $data);
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

		$id= request("id",0);


		$data['token'] = Formlib::Hidden("_token", 1, csrf_token());
		$data['id'] = Formlib::Hidden("id", 1, $id);

		$name ="";
		$description="";
		$codevent = "";
		$url="";
		$start=date("Y-m-d");
		$end=date("Y-m-d");


		$calendar = WebCalendar::where("ID_CALENDAR", $id)->first();
		if (!empty($calendar)) {
			$name = $calendar->name_calendar;
			$description=$calendar->description_calendar;
			$codevent = $calendar->codevent_calendar;
			$url=$calendar->url_calendar;
			$start=$calendar->start_calendar;
			$end=$calendar->end_calendar;
		}

		$events=array();

		$tmpEvents = WebCalendarEvent::get();
		foreach($tmpEvents as $event){
			$events[$event->cod_calendar_event] = $event->cod_calendar_event ;
		}

		#ponemos old  para que cargue  los valores enviados por post y se puedan volver a mostrar en el formulario si se ha devuelto error
		$data['name'] = FormLib::Text("name", 1, old("name",$name));
		$data['description'] = FormLib::Text("description", 0, old("description",$description));
		$data['codevent'] = FormLib::Select("codevent", 1,  old('codevent',$codevent), $events, "", "", false);
		$data['url'] = FormLib::Text("url", 0, old('url', $url) );
		$data['start'] = FormLib::Date("start", 1, old('start', $start));
		$data['end'] = FormLib::Date("end", 1, old('end', $end));

		return View::make('admin::pages.contenido.calendar.editar', $data);

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

		$id = request("id");

		$item = [
			'name_calendar' => request('name'),
			'description_calendar' => request('description'),
			'codevent_calendar' => request("codevent") ,
			'url_calendar' => request('url'),
			'start_calendar' => date("Y-m-d h:i:s", strtotime(request('start'))),
			'end_calendar' => date("Y-m-d h:i:s", strtotime(request('end'))),
		];

		$webcalendar = new WebCalendar();
		if(empty($id)){
			$create = $webcalendar->create($item);

			if($create){
				#Si todo ha ido bien envianmos al listado para que sea mas facil crear muchos seguidos
				return redirect("admin/calendar")->with(['success' =>array(trans('admin-app.title.created_ok'))]);

				/* Esto redirigia al editor con el id correspondiente
				$idCalendar = WebCalendar::latest('id_calendar')->first();
				return redirect("admin/calendar/edit?id=". $idCalendar->id_calendar)->with(['success' =>array(trans('admin-app.title.updated_ok'))]);
				*/
			}

			return redirect()->back()
				->with(['errors' =>array(trans('admin-app.error.no_create')) ])->withInput();

		}else{



			$update = $webcalendar->where("ID_CALENDAR", $id )->update($item);

			if($update){
				return back()->with(['success' =>array(trans('admin-app.title.updated_ok'))]);
			}

			#ponemos with input para que devuelva los valores enviados por post y se puedan volver a mostrar en el formulario
			return redirect()->back()
				->with(['errors' =>array(trans('admin-app.error.no_update')) ])->withInput();
		}







	}

	/**
	 * Eliminar item
	 * */
	function destroy(){

		$idCalendar = request("idCalendar");
		if(empty($idCalendar)){
			return back()->with('errors', array(trans('admin-app.error.no_id_delete')) );
		}
		WebCalendar::where("id_calendar", $idCalendar)->delete();

		return back()->with('success', array(trans('admin-app.title.deleted_ok')) );

	}


}
