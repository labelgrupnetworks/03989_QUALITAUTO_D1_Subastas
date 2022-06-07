<div class="user-accout-items-content   ">
	<div class="user-accout-item-wrapper  col-xs-12 no-padding">
		<div class="d-flex">
			<div class="col-xs-12 col-sm-6  col-one user-account-item ">
				<div class="col-xs-12 col-sm-2 no-padding ">
					<img src="{{ \Tools::url_img("lote_small", $lot->num_hces1, $lot->lin_hces1) }}" class="img-responsive">
				</div>
				<div class="col-xs-12 col-sm-8 col-sm-offset-1 no-padding">

					<div class="visible-xs">{{ trans(\Config::get('app.theme').'-app.user_panel.units') }}</div>
					<div class="user-account-item-lot"><span>{{ trans(\Config::get('app.theme').'-app.user_panel.lot') }}
						@php
						$refLot =$lot->ref_asigl0;
						#si  tiene el . decimal hay que ver si se debe separar
						if(strpos($refLot,'.')!==false){

							$refLot =str_replace(array(".1",".2",".3", ".4", ".5"), array("-A", "-B", "-C", "-D", "-E"),  $refLot);

							#si hay que recortar
						}elseif( \config::get("app.substrRef")){
							#cogemos solo los últimos x numeros, ya que se usaran hasta 9, los  primeros para diferenciar un lote cuando se ha vuelto a subir a subasta
							#le sumamos 0 para convertirlo en numero y así eliminamos los 0 a la izquierda
							$refLot = substr($refLot,-\config::get("app.substrRef"))+0;
						}
					@endphp
					{{$refLot}}



					</span></div>
					<div class="user-account-item-title">{{$lot->descweb_hces1}}</div>
					@if($lot->permisoexp_hces1=='S')
					<div class="user-account-item-title">

						<br>	{!! trans(\Config::get('app.theme').'-app.lot.permiso_exportacion') !!}

					</div>
				@endif

				</div>
			</div>
			<div class="col-xs-12 col-sm-1  account-item-border">
				<div class="user-account-item-date d-flex flex-direction-column align-items-center justify-content-center">

					<p>1</p>

				</div>
			</div>
			<div class="col-xs-12 col-sm-2  account-item-border">
				<div class="user-account-item-date d-flex flex-direction-column align-items-center justify-content-center">
					<div class="visible-xs">{{ trans(\Config::get('app.theme').'-app.user_panel.unit_price') }}</div>
					<p>{{ \Tools::moneyFormat($lot->impsalhces_asigl0, trans(\Config::get('app.theme').'-app.subastas.euros'), 2) }} </p>

				</div>
			</div>
			<div class="col-xs-12 col-sm-2  account-item-border">
					<div class="user-account-item-price  d-flex align-items-center justify-content-center">
						<div class="visible-xs">{{ trans(\Config::get('app.theme').'-app.user_panel.price_clean') }}</div>
						<div><strong>{{ \Tools::moneyFormat($lot->impsalhces_asigl0, trans(\Config::get('app.theme').'-app.subastas.euros'), 2) }} </strong></div>
					</div>
			</div>
			<div class="col-xs-12 col-sm-1  account-item-border">
				<div class="user-account-item-price  d-flex align-items-center justify-content-center">
					<div class="deleteLot_JS cursor" data-sub="{{$lot->sub_asigl0}}" data-ref="{{$lot->ref_asigl0}}" style="color:red">{{ trans(\Config::get('app.theme').'-app.user_panel.delete') }}</div>

				</div>
		</div>

		</div>
	</div>
</div>
