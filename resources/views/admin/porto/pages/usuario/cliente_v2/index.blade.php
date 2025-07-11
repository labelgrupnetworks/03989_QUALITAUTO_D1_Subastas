@extends('admin::layouts.logged')
@section('content')

<section role="main" class="content-body">
		@csrf

	<div class="row well header-well d-flex align-items-center">


		<div class="col-xs-12">
			<h1>{{ trans_choice("admin-app.title.client", 2) }}</h1>
		</div>
	</div>

	<div class="row well">

		<div class="col-xs-12 text-right mb-1 pt-1 pb-1" style="background-color: #ffe7e7">

			<div class="btn-group left" id="js-dropdownItems">
				<button class="btn btn-default btn-sm" type="button">{{ trans("admin-app.button.selecteds") }}</button>
				<button
					data-objective="cli_ids"
					class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" type="button"
					aria-haspopup="true" aria-expanded="false">
					<span class="caret"></span>
				</button>

				<ul aria-labelledby="js-dropdownItems" class="dropdown-menu">

					<li>
						<button class="btn" data-objective="cli_ids"
							data-allselected="js-selectAll"
							data-title="{{ trans("admin-app.questions.erase_mass_cli") }}"
							data-response="{{ trans("admin-app.success.erase_mass_cli") }}"
							data-url="{{ route('clientes.destroy_selections') }}"
							data-urlwithfilters="{{ route('clientes.destroy_with_filters') }}"
							onclick="removeClientSelecteds(this.dataset)">
							{{ trans("admin-app.button.destroy") }}
						</button>
					</li>

					<li>
						<button class="btn" data-toggle="modal" data-target="#editMultpleClientsModal">
							{{ trans("admin-app.button.modify") }}
						</button>
					</li>

				</ul>
			</div>

			<a id="clientesExport" href="{{ route('clientes.export') }}" class="btn btn-sm btn-primary"
			>{{ trans('admin-app.button.export') }}</a>

			<a href="{{ route('clientes.create') }}"
				class="btn btn-sm btn-primary">{{ trans("admin-app.button.new") }}
				{{ trans("admin-app.fields.cli_creditosub") }}</a>

			@include('admin::includes.config_table', ['id' => 'clientes', 'params' => $tableParams])
		</div>

		<div class="col-xs-12 table-responsive">
			<table id="clientes" class="table table-striped table-condensed table-responsive" style="width:100%" data-order-name="order">
				<thead>
					<tr>
						<th>
							<label>
								<input id="selectAllClients" name="js-selectAll" data-objective="cli_ids" type="checkbox" value="true">
								<input id="urlAllSelected" name="url-allSelected"  type="hidden" value="{{ route('clientes.update_with_filters') }}">
							</label>
						</th>
						@foreach ($tableParams as $param => $display)

						<th class="{{$param}}"  style="cursor: pointer; @if(!$display) display: none; @endif" data-order="{{$param}}">
							{{ trans("admin-app.fields.$param") }}
							@if(request()->order == $param)
								<span style="margin-left: 5px; float: right;">
									@if(request()->order_dir == 'asc')
										<i class="fa fa-arrow-up" style="color:green" aria-hidden="true"></i>
									@else
											<i class="fa fa-arrow-down" style="color:red" aria-hidden="true"></i>
									@endif
								</span>
							@endif

						</th>

						@endforeach
						<th>
							<span>{{ trans("admin-app.fields.actions") }}</span>
						</th>
					</tr>
				</thead>

				<tbody>

					<tr id="filters">
						<form class="form-group" action="">
							<input type="hidden" name="order" value="{{ request('order', 'cod_cli') }}">
							<input type="hidden" name="order_dir" value="{{ request('order_dir', 'desc') }}">

							<td></td>
							@foreach ($tableParams as $param => $display)
								<td class="{{$param}}" @if(!$display) style="display: none" @endif> {!! $formulario->$param ?? '' !!}</td>
							@endforeach

							<td class="d-flex">
								<input type="submit" class="btn btn-info w-100"
									value="{{ trans("admin-app.button.search") }}"><a
									href="{{route('clientes.index')}}"
									class="btn btn-warning w-100">{{ trans("admin-app.button.restart") }}</a>
							</td>
						</form>
					</tr>

					@forelse ($clientes as $cliente)

					<tr id="{{$cliente->cod_cli}}">
						<td>
							<label>
								<input type="checkbox" name="cli_ids" value="{{$cliente->cod2_cli}}">
							</label>
						</td>
						<td class="cod_cli" @if(!$tableParams['cod_cli']) style="display: none" @endif>{{$cliente->cod_cli}}</td>
						<td class="cod2_cli" @if(!$tableParams['cod2_cli']) style="display: none" @endif>{{$cliente->cod2_cli}}</td>
						<td class="tipo_cli" @if(!$tableParams['tipo_cli']) style="display: none" @endif>{{$cliente->tipoCli->des_tcli ?? ''}}</td>
						<td class="nom_cli" @if(!$tableParams['nom_cli']) style="display: none" @endif>{{$cliente->nom_cli}}</td>
						<td class="rsoc_cli" @if(!$tableParams['rsoc_cli']) style="display: none" @endif>{{$cliente->rsoc_cli}}</td>
						<td class="email_cli" @if(!$tableParams['email_cli']) style="display: none" @endif>{{$cliente->email_cli}}</td>
						<td class="tel1_cli" @if(!$tableParams['tel1_cli']) style="display: none" @endif>{{$cliente->tel1_cli}}</td>
						<td class="pais_cli" @if(!$tableParams['pais_cli']) style="display: none" @endif>{{$cliente->pais_cli}}</td>
						<td class="pro_cli" @if(!$tableParams['pro_cli']) style="display: none" @endif>{{$cliente->pro_cli}}</td>
						<td class="cp_cli" @if(!$tableParams['cp_cli']) style="display: none" @endif>{{$cliente->cp_cli}}</td>
						<td class="pob_cli" @if(!$tableParams['pob_cli']) style="display: none" @endif>{{$cliente->pob_cli}}</td>
						<td class="complete_direction" @if(!$tableParams['complete_direction']) style="display: none" @endif>{{$cliente->complete_direction}}</td>
						<td class="idioma_cli" @if(!$tableParams['idioma_cli']) style="display: none" @endif>{{$cliente->idioma_cli}}</td>
						<td class="fisjur_cli" @if(!$tableParams['fisjur_cli']) style="display: none" @endif>{{$cliente->tipo_fisJur_types}}</td>

						<td class="baja_tmp_cli" @if(!$tableParams['baja_tmp_cli']) style="display: none" @endif>
							{!! \FormLib::Select('baja_tmp', 0, $cliente->baja_tmp_cli, $fxcli->getTipoBajaTmpTypes(), '', '', false) !!}
						</td>
						<td class="fecalta_cliweb" @if(!$tableParams['fecalta_cliweb']) style="display: none" @endif>{{date('d-m-Y', strtotime($cliente->fecalta_cliweb))}}</td>

						<td class="f_modi_cli" @if(!$tableParams['f_modi_cli']) style="display: none" @endif>
							{{ \Tools::getDateFormat($cliente->f_modi_cli, 'Y-m-d H:i:s', 'd-m-Y H:i' ) }}
						</td>

						<td class="envcat_cli2" @if(!$tableParams['envcat_cli2']) style="display: none" @endif>{{$cliente->cli2->envcat_cli2 ?? null}}</td>
						<td class="email_clid" @if(!$tableParams['email_clid']) style="display: none" @endif>{{$cliente->email_clid ?? null}}</td>

						@foreach ($newslettersSelect as $newsletter)
							<td class="{{$newsletter}}" @if(!$tableParams[$newsletter]) style="display: none" @endif>{{ $cliente->{$newsletter} }}</td>
						@endforeach

						<td>

							<a title="{{ trans("admin-app.button.edit") }}"
							href="{{ route('clientes.edit', $cliente->cod_cli) }}"
							class="btn btn-success btn-sm"><i class="fa fa-pencil-square-o"
								aria-hidden="true"></i>{{ trans("admin-app.button.edit") }}</a>

							<a data-idorigin="{{$cliente->cod2_cli}}" class="js-delete_cli btn btn-danger btn-sm"> {{ trans("admin-app.button.delete") }} </a>

							@if ( Config::get('app.WebServiceClient') && (strtoupper(session('user.usrw')) == 'SUBASTAS@LABELGRUP.COM') )
								<br/>
								<a data-codcli="{{$cliente->cod_cli}}" class="js-send_webservice_cli btn btn-send-webservice btn-sm"> {{ trans("admin-app.button.send_webservice",["empresa" => \Config::get("app.theme")]) }} </a>
							@endif
						</td>
					</tr>

					@empty

					<tr>
						<td colspan="6">
							<h3 class="text-center">{{ trans("admin-app.title.without_results") }}</h3>
						</td>
					</tr>

					@endforelse
				</tbody>
			</table>

		</div>
		<div class="col-xs-12 d-flex justify-content-center">
			{{ $clientes->appends(array_except(Request::query(), ['page']))->links() }}
		</div>
	</div>

</section>

@include('admin::pages.usuario.cliente_v2._edit_selecteds')

@stop
