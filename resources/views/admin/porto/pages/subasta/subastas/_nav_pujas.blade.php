<div class="col-xs-12">
	<div class="col-xs-12 text-right mb-1 pt-1 pb-1" style="background-color: #ffe7e7">
			<a class="btn btn-success btn-sm" href="{{ route('lote.export', ['cod_sub' => $cod_sub]) }}">{{ trans('admin-app.button.download_excel') }}</a>
			@include('admin::includes.config_table', ['id' => 'tablePujas', 'params' => ((array) $filter)])

	</div>
</div>

<div class="col-xs-12">
	<table id="tablePujas" class="table table-striped table-condensed table-responsive" style="width:100%" data-order-name="order_pujas">
		<thead>
			<th class="ref_asigl1"  style="cursor: pointer" data-order="ref_asigl1">
				{{trans('admin-app.title.reference_lot')}}
				   @if(request()->order_pujas == 'ref_asigl1')
					   <span style="margin-left: 5px; float: right;">
						   @if(request()->order_pujas_dir == 'asc')
								<i class="fa fa-arrow-up" style="color:green" aria-hidden="true"></i>
							   @else
							<i class="fa fa-arrow-down" style="color:red" aria-hidden="true"></i>
						   @endif
					   </span>
				   @endif
			</th>
			<th class="idorigen_asigl0"  style="cursor: pointer" data-order="idorigen_asigl0">
				{{trans('admin-app.fields.idorigen_asigl0')}}
				   @if(request()->order_pujas == 'idorigen_asigl0')
					   <span style="margin-left: 5px; float: right;">
						   @if(request()->order_pujas_dir == 'asc')
								<i class="fa fa-arrow-up" style="color:green" aria-hidden="true"></i>
							   @else
							<i class="fa fa-arrow-down" style="color:red" aria-hidden="true"></i>
						   @endif
					   </span>
				   @endif
			</th>
			<th class="lin_asigl1" style="cursor: pointer" data-order="lin_asigl1">
 				{{trans('admin-app.fields.lot.lin_asigl1')}}
 			   @if(request()->order_pujas == 'lin_asigl1')
				   <span style="margin-left: 5px; float: right;">
					   @if(request()->order_pujas_dir == 'asc')
							<i class="fa fa-arrow-up" style="color:green" aria-hidden="true"></i>
						   @else
						<i class="fa fa-arrow-down" style="color:red" aria-hidden="true"></i>
					   @endif
				   </span>
			   @endif
			</th>
			<th class="pujrep_asigl1" style="cursor: pointer" data-order="pujrep_asigl1">


				{{trans_choice('admin-app.fields.pujrep_asigl1', $tipo_sub ?? '')}}

			   @if(request()->order_pujas == 'pujrep_asigl1')
				  <span style="margin-left: 5px; float: right;">
					  @if(request()->order_pujas_dir == 'asc')
						   <i class="fa fa-arrow-up" style="color:green" aria-hidden="true"></i>
						  @else
					   <i class="fa fa-arrow-down" style="color:red" aria-hidden="true"></i>
					  @endif
				  </span>
			  @endif
		   </th>
			<th class="type_asigl1" style="cursor: pointer" data-order="type_asigl1">
				{{trans('admin-app.fields.type_asigl1')}}
			   @if(request()->order_pujas == 'type_asigl1')
				  <span style="margin-left: 5px; float: right;">
					  @if(request()->order_pujas_dir == 'asc')
						   <i class="fa fa-arrow-up" style="color:green" aria-hidden="true"></i>
						  @else
					   <i class="fa fa-arrow-down" style="color:red" aria-hidden="true"></i>
					  @endif
				  </span>
			  @endif
		   </th>
			<th class="descweb_hces1">
 				{{trans('admin-app.fields.descweb_hces1')}}
			</th>
			<th class="nom_cli"  style="cursor: pointer" data-order="nom_cli">
				{{trans('admin-app.fields.nom_cli')}}
				   @if(request()->order_pujas == 'nom_cli')
					   <span style="margin-left: 5px; float: right;">
						   @if(request()->order_pujas_dir == 'asc')
								<i class="fa fa-arrow-up" style="color:green" aria-hidden="true"></i>
							   @else
							<i class="fa fa-arrow-down" style="color:red" aria-hidden="true"></i>
						   @endif
					   </span>
				   @endif
			</th>
			<th class="licit_asigl1" style="cursor: pointer" data-order="licit_asigl1">
 				{{trans('admin-app.fields.licit_asigl1')}}
 				   @if(request()->order_pujas == 'licit_asigl1')
					   <span style="margin-left: 5px; float: right;">
						   @if(request()->order_pujas_dir == 'asc')
								<i class="fa fa-arrow-up" style="color:green" aria-hidden="true"></i>
							   @else
							<i class="fa fa-arrow-down" style="color:red" aria-hidden="true"></i>
						   @endif
					   </span>
				   @endif
			</th>
			<th class="cod2_cli" style="cursor: pointer" data-order="cod2_cli">
 				{{trans('admin-app.fields.cod2_cli')}}
 				   @if(request()->order_pujas == 'cod2_cli')
					   <span style="margin-left: 5px; float: right;">
						   @if(request()->order_pujas_dir == 'asc')
								<i class="fa fa-arrow-up" style="color:green" aria-hidden="true"></i>
							   @else
							<i class="fa fa-arrow-down" style="color:red" aria-hidden="true"></i>
						   @endif
					   </span>
				   @endif
			</th>
			<th class="fec_asigl1" style="cursor: pointer" data-order="fec_asigl1">
				{{trans('admin-app.fields.fecfra_csub')}}
 				   @if(request()->order_pujas == 'fec_asigl1')
					   <span style="margin-left: 5px; float: right;">
						   @if(request()->order_pujas_dir == 'asc')
								<i class="fa fa-arrow-up" style="color:green" aria-hidden="true"></i>
							   @else
							<i class="fa fa-arrow-down" style="color:red" aria-hidden="true"></i>
						   @endif
					   </span>
				   @endif
			</th>
			<th class="imp_asigl1" style="cursor: pointer" data-order="imp_asigl1">
 				{{trans('admin-app.fields.himp_orlic')}}
 				   @if(request()->order_pujas == 'imp_asigl1')
					   <span style="margin-left: 5px; float: right;">
						   @if(request()->order_pujas_dir == 'asc')
								<i class="fa fa-arrow-up" style="color:green" aria-hidden="true"></i>
							   @else
							<i class="fa fa-arrow-down" style="color:red" aria-hidden="true"></i>
						   @endif
					   </span>
				   @endif
			</th>
			<th class="imp_asigl1" style="cursor: pointer" data-order="retirado_asigl0">
				{{trans('admin-app.fields.retired')}}
				   @if(request()->order_pujas == 'retirado_asigl0')
					  <span style="margin-left: 5px; float: right;">
						  @if(request()->order_pujas_dir == 'asc')
							   <i class="fa fa-arrow-up" style="color:green" aria-hidden="true"></i>
							  @else
						   <i class="fa fa-arrow-down" style="color:red" aria-hidden="true"></i>
						  @endif
					  </span>
				  @endif
		   </th>
		   <th class="ffin_asigl0" style="cursor: pointer" data-order="ffin_asigl0">
			{{trans('admin-app.fields.enddate')}}
				@if(request()->order_pujas == 'ffin_asigl0')
				   <span style="margin-left: 5px; float: right;">
					   @if(request()->order_pujas_dir == 'asc')
							<i class="fa fa-arrow-up" style="color:green" aria-hidden="true"></i>
						   @else
						<i class="fa fa-arrow-down" style="color:red" aria-hidden="true"></i>
					   @endif
				   </span>
			   @endif
		</th>

			<th>Acciones</th>
		</thead>
		<tbody>
			<tr id="filters">
				<form class="form-group" action="">
					<input type="hidden" name="order_pujas" value="{{ request('order_pujas', 'cod_cli') }}">
					<input type="hidden" name="order_pujas_dir" value="{{ request('order_pujas_dir', 'desc') }}">
					@foreach($filter as $index => $item)
						<td class="{{$index}}">{!! $item !!}</td>
					@endforeach
					<td class="d-flex">
						<input type="submit" class="btn btn-info w-100"
							value="{{ trans("admin-app.button.search") }}">
							<a
							href="{{ route( request()->route()->getName(), ['cod_sub' => $cod_sub, 'menu' => 'subastas'])}}"

							class="btn btn-warning w-100">{{ trans("admin-app.button.restart") }}</a>
					</td>
				</form>
			</tr>
			@foreach($pujas as $k => $item)
				<tr id="puja---{{$item->lin_asigl1}}---{{$item->ref_asigl1}}" style=" @if($item->retirado_asigl0=='S') color:red @endif">
					<td class="ref_asigl1">{!! $item->ref_asigl1 !!}</td>
					<td class="idorigen_asigl0">{!! $item->idorigen_asigl0 !!}</td>
					<td class="lin_asigl1">{!! $item->lin_asigl1 !!}</td>
					<td class="pujrep_asigl1">{!! $pujrepsArray[$item->pujrep_asigl1] ?? '' !!}</td>
					<td class="type_asigl1">{!! $typesArray[$item->type_asigl1] ?? '' !!}</td>
					<td class="descweb_hces1">{!! $item->descweb_hces1 !!}</td>
					@if (isset($item->nom_cli))
						<td class="nom_cli">{{ $item->nom_cli }} </td>
					@else
						<td class="nom_cli">No se ha encontrado el licitador en la subasta</td>
					@endif
					<td class="licit_asigl1">{{$item->licit_asigl1}}</td>
					<td class="cod2_cli">{{$item->cod2_cli}}</td>
					<td class="fec_asigl1">{!! \Tools::Construir_fecha($item->fec_asigl1) !!} {!! $item->hora_asigl1 !!}</td>
					<td class="imp_asigl1">{!! \Tools::moneyFormat($item->imp_asigl1, trans(\Config::get('app.theme').'-app.subastas.euros'), 2) !!}</td>
					<td class="retirado_asigl0" >{{$item->retirado_asigl0}}</td>
					<td class="ffin_asigl0" > {!! \Tools::Construir_fecha($item->ffin_asigl0) !!} {!! $item->hfin_asigl0 !!} </td>
					<td><a href="javascript:borrarPuja('{!! $item->ref_asigl1 !!}---{!! $item->lin_asigl1 !!}---{!!$cod_sub!!}---{!!$item->asigl0_aux??'NO'!!}');"
							class="btn btn-danger">Borrar</a></td>

				</tr>
			@endforeach
		</tbody>
	</table>
	{{ $pujas->appends(array_except(Request::query(), ['pujasPage']))->links()}}
</div>
