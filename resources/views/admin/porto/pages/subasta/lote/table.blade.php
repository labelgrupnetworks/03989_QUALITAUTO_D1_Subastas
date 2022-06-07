<div style="float:right">
	<i class="fa fa-2x fa-info-circle" style="position:relative;top:6px;"></i>&nbsp;<span class="badge">La
		divisa se guarda en euros y la conversión se realiza en el momento de la carga.</span>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<a class="btn btn-success" href="/themes_admin/porto/assets/files/plantillaejemplo.xlsx" download="plantila.xlsx">Descargar plantilla Excel</a>
	&nbsp;&nbsp;&nbsp;
	<a href="/admin/lote/file/{{$cod_sub}}" class="btn btn-success">Subir Excel</a>

	@if(\Config::get("app.uploadLotFile"))
		@foreach(explode(",",\Config::get("app.uploadLotFile") ) as $typeUploadFile)
			&nbsp;&nbsp;&nbsp;
			<a href="/admin/lote/file/{{$cod_sub}}?type={{trim($typeUploadFile)}}" class="btn btn-success btn-sm">Subir {{$typeUploadFile}}</a>
		@endforeach
	@endif
	&nbsp;&nbsp;&nbsp;
	<a href="/admin/lote/nuevo/{{$cod_sub}}" class="btn btn-success">Nuevo</a>
</div>

<h4>Lotes</h4>
<br>
<table id="tableLotes" class="table table-striped table-bordered dataTable hover" style="width:100%">
	<thead>
		<th>Imagen</th>
		<th>Ref.</th>
		<th>Salida</th>
		<th>Max. Puja</th>
		<th>Max. Orden</th>
		<th>Adj.</th>
		<th>Cerrado</th>
		<th>Destacado</th>
		<th>Retirado</th>
		<th>Oculto</th>
		<th>Opciones</th>
	</thead>
	<tbody>
		@foreach($lotes as $k => $item)
		<tr id="fila{{$item->numhces_asigl0}}-{{$item->linhces_asigl0}}-{{$item->ref_asigl0}}">
			<td width="20%"><img

					src="{{ \Tools::url_img('lote_medium', $item->numhces_asigl0, $item->linhces_asigl0) }}"

					width="100%"></td>
			<td align="center">{!! $item->ref_asigl0 !!}</td>
			<td align="right">{!! \Tools::moneyFormat($item->impsalhces_asigl0)
				!!}{{$simbolos[$item->divisa_hces1]}}</td>

			<td align="right">{!! !empty($maxPujas[$item->ref_asigl0]) ?
				\Tools::moneyFormat($maxPujas[$item->ref_asigl0]) : '0'
				!!}{{$simbolos[$item->divisa_hces1]}}</td>

			<td align="right">{!! !empty($maxOrdenes[$item->ref_asigl0]) ?
				\Tools::moneyFormat($maxOrdenes[$item->ref_asigl0]) : '0'
				!!}{{$simbolos[$item->divisa_hces1]}}</td>


			<td align="right">{!! \Tools::moneyFormat($item->impadj_asigl0)
				!!}{{$simbolos[$item->divisa_hces1]}}</td>
			<td align="center">{!! $item->cerrado_asigl0 !!}</td>
			<td align="center">{!! $item->destacado_asigl0 !!}</td>
			<td align="center">{!! $item->retirado_asigl0 !!}</td>
			<td align="center">{!! $item->oculto_asigl0 !!}</td>
			<td>
				<a href="/admin/lote/edit/{!! $item->sub_asigl0 !!}/{!! $item->numhces_asigl0 !!}-{!! $item->linhces_asigl0 !!}-{!! $item->ref_asigl0 !!}"
					class="btn btn-primary">Editar</a>
				@if (!isset($ganadores[$item->ref_asigl0]))
				<a href="javascript:borrarLote('{!! $item->sub_asigl0 !!}','{!! $item->numhces_asigl0 !!}','{!! $item->linhces_asigl0 !!}','{!! $item->ref_asigl0 !!}');"
					class="btn btn-danger">Borrar</a>
				@endif
			</td>
		</tr>
		@endforeach
	</tbody>
</table>
