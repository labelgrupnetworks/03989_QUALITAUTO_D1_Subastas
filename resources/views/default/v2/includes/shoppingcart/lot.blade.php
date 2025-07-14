
@php
$url_friendly = str_slug($lot->descweb_hces1);
$url_friendly = \Routing::translateSeo('lote').$lot->sub_asigl0."-".$lot->id_auc_sessions.'-'.$lot->id_auc_sessions."/".$lot->ref_asigl0.'-'.$lot->num_hces1.'-'.$url_friendly;

//Modificamos ref_asigl0 de _ a . porque se ha hecho al reves en el controlador por un tema de javascript
$refLot = str_replace('_','.',$lot->ref_asigl0);

#si  tiene el . decimal hay que ver si se debe separar
if(strpos($refLot, '.') !== false){
	$refLot = str_replace(array(".1",".2",".3", ".4", ".5"), array("-A", "-B", "-C", "-D", "-E"), $refLot);
	#si hay que recortar
}elseif(\Config::get("app.substrRef")){
	#cogemos solo los últimos x numeros, ya que se usaran hasta 9, los  primeros para diferenciar un lote cuando se ha vuelto a subir a subasta
	#le sumamos 0 para convertirlo en numero y así eliminamos los 0 a la izquierda
	$refLot = substr($refLot,-\Config::get("app.substrRef")) +0;
}
@endphp

<tr>
	<td class="td-img">
		<a href="{{$url_friendly}}">
			<img src="{{ Tools::url_img("lote_small", $lot->num_hces1, $lot->lin_hces1) }}" class="img-responsive">
		</a>
	</td>
	<td data-title="{{ trans("web.user_panel.lot") }}">
		{{$refLot}}
	</td>
	<td data-title="{{ trans("web.user_panel.description") }}" class="td-title">
		<span class="max-line-2">{!! $lot->descweb_hces1 !!}</span>
		@if($lot->permisoexp_hces1 == 'S')
		<span>{!! trans("web.lot.permiso_exportacion") !!}</span>
		@endif
	</td>
	<td data-title="{{ trans("web.user_panel.units") }}">
		1
	</td>
	<td data-title="{{ trans("web.user_panel.unit_price") }}">
		{{ Tools::moneyFormat($lot->impsalhces_asigl0, trans("web.subastas.euros"), 2) }}
	</td>
	<td data-title="{{ trans("web.user_panel.price_clean") }}">
		{{ Tools::moneyFormat($lot->impsalhces_asigl0, trans("web.subastas.euros"), 2) }}
	</td>
	<td>
		<div class="btn-group">
			<button type="button" class="btn btn-sm d-flex align-items-center p-2 rounded-circle" data-bs-toggle="dropdown" aria-expanded="false">
				<svg class="bi" width="16" height="16" fill="currentColor">
					<use xlink:href="/bootstrap-icons.svg#three-dots-vertical"/>
				</svg>
			</button>

			<ul class="dropdown-menu">
				<li><a class="dropdown-item" href="{{ $url_friendly }}" target="_blank">{{ trans("web.user_panel.see_lot") }}</a></li>
				<li>
					<button class="dropdown-item deleteLot_JS"
						data-sub="{{$lot->sub_asigl0}}" data-ref="{{$lot->ref_asigl0}}">
						{{ trans("web.user_panel.delete") }}
					</button>
				</li>
			</ul>

		</div>
	</td>

</tr>
