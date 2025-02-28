<tr style="background-color: #EEE;">
	<td  style="padding: 5px;">
		<strong>{{$articulo["model_art0"]}}</strong>
		@foreach($articulo["variantes"] as $nameVariante => $valVariante)
		<br>{{ ucfirst(mb_strtolower($nameVariante)) }} : {{ ucfirst(mb_strtolower($valVariante)) }}

		@endforeach
	</td>
	<td style="text-align:center;padding: 5px;">{{ $articulo["cant_pedc1"]}}</td>
{{-- debemos moratar el precio unitario con iva, por eso dividimos por la cantidad de articulos --}}
	<td style="text-align:right;padding: 5px;">{{ \Tools::moneyFormat(($articulo["imp_pedc1"] + $articulo["impiva_pedc1"])/$articulo["cant_pedc1"], trans('web.lot.eur') ,2)}}</td>

	<td style="text-align:right;padding: 5px;">{{\Tools::moneyFormat($articulo["imp_pedc1"] + $articulo["impiva_pedc1"] ,trans('web.lot.eur'),2)}}</td>

</tr>
