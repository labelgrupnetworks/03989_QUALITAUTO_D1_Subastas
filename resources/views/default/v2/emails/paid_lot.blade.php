

<table style="width: 100%;" cellpadding="15">
	<tbody>
		@foreach ($lots as $lot)
		<tr>
			<td style="width: 20%; text-align:center">
				<img width="100%"
					src="{{\Tools::url_img('lote_medium', $lot->num_hces1, $lot->lin_hces1)}}"
					alt="{{ $lot->descweb_hces1 }}">
			</td>
			<td style="vertical-align: top;">
				<p><strong>{{ $lot->descweb_hces1 }}</strong></p>
				<p>{{ trans("web.global.precio") }}: <strong>{{ \Tools::moneyFormat($lot->impsalhces_asigl0, '€') }}</strong></p>
			</td>
		</tr>
		@endforeach
	</tbody>
</table>
<br><br>

@if( isset($info["envio"]) &&$info["envio"] == 1)
<table style="width: 600px" cellpadding="5">
	<tbody>
		<tr>
			<td  >
				<strong>{{trans('web.user_panel.envio_agencia')}}</strong>
			</td>
			<td ></td>

		</tr>

		<tr>
			<td ><strong>{{trans('web.global.nombre')}}:  </strong> {{$cliente->nom_cli}} </td>
			<td ><strong>{{trans('web.global.telefono')}}: </strong> {{$info["telefono"]}} </td>
		</tr>
		<tr>
			<td ><strong>{{trans('web.global.direccion')}}:</strong> {{ $info["direccion"]}}  </td>
			<td ><strong>{{trans('web.global.poblacion')}}:</strong> {{$info["poblacion"]}}  ({{$info["cp"]}})</td>
		</tr>
		<tr>
			<td ><strong>{{trans('web.global.provincia')}}: </strong> {{$info["provincia"]}} </td>
			<td ><strong>{{trans('web.global.pais')}}:</strong> {{$info["pais"]}}  </td>
		</tr>
		<tr>
			<td ></td>
			<td   style=" text-align:center">
				<strong>{{trans('web.user_panel.gastos_envio')}}:</strong>
				{{\Tools::moneyFormat($info["gastosEnvio"] + $info["ivaGastosEnvio"]," €",2)}}
			</td>
		</tr>

	</tbody>
</table>
	@if(!empty($info["seguro"]) )
			<br><br> {{trans('web.user_panel.seguro_envio')}}: {{\Tools::moneyFormat($info["importeSeguro"]," €",2)}}
	@endif
@else

{{trans('web.user_panel.recogida_producto')}}<br> {{trans('web.user_panel.sala_almacen')}};
@endif

@if(!empty($info["comments"]))
	<br><br>
	<strong>{{trans('web.global.coment')}}: </strong><br> {{$info["comments"]}};
@endif
