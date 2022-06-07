@extends('admin::layouts.logged')
@section('content')

<section role="main" class="content-body">
	@include('admin::includes.header_content')

	<div class="row well header-well d-flex align-items-center">
		<div class="col-xs-9">
			<h1>{{ trans("admin-app.button.edit") }} {{ trans("admin-app.title.auction") }}</h1>
		</div>
		<div class="col-xs-3">
			<a href="{{ route('subastas_concursales.index') }}"
				class="btn btn-primary right">{{ trans("admin-app.button.return") }}</a>
		</div>
	</div>

	<div class="row well">
		<div class="col-xs-12">

			<div class="row">
				<div class="col-xs-12">
					<ul class="nav nav-tabs" id="myTab" role="tablist">
						<li class="nav-item active">
							<a class="nav-link" id="opciones-tab" data-toggle="tab" href="#opciones" role="tab"
								aria-controls="opciones"
								aria-selected="false">{{ trans("admin-app.title.options") }}</a>
						</li>
						@if(count(\Config::get('app.locales')) > 1)
						<li class="nav-item">
							<a class="nav-link" id="traducciones-tab" data-toggle="tab" href="#traducciones" role="tab"
								aria-controls="traducciones"
								aria-selected="false">{{ trans("admin-app.title.translates") }}</a>
						</li>
						@endif
						<li class="nav-item">
							<a class="nav-link" id="escalado-tab" data-toggle="tab" href="#escalado" role="tab"
								aria-controls="escalado" aria-selected="false">{{ trans("admin-app.title.scaled") }}</a>
						</li>

						<li class="nav-item">
							<a class="nav-link" id="archivos-tab" data-toggle="tab" href="#archivos" role="tab"
								aria-controls="archivos" aria-selected="false">{{ trans("admin-app.title.files") }}</a>
						</li>
					</ul>
				</div>
			</div>

			<form action="{{ route('subastas_concursales.update', $fgSub->cod_sub) }}" method="POST" id="subastaUpdate"
				enctype="multipart/form-data">
				@method('PUT')
				@csrf

				<input type="hidden" name="tab" value="">
				<input type="hidden" name="route.update.image" value="{{ route('subastas.update.image') }}">

				<div class="row">

					<div class="col-xs-12">

						<div class="tab-content" id="myTabContent">

							<div class="tab-pane fade active in" id="opciones" role="tabpanel"
								aria-labelledby="opciones-tab">
								<div class="row">
									@include('admin::pages.subasta.subastas_concursales._form', compact('formulario',
									'fgSub'))
								</div>
							</div>

							<div class="tab-pane fade" id="seo" role="tabpanel" aria-labelledby="seo-tab">

							</div>

							@if(count(\Config::get('app.locales')) > 1)
							<div class="tab-pane fade" id="traducciones" role="tabpanel"
								aria-labelledby="traducciones-tab">
								<div class="row">
									@include('admin::pages.subasta.subastas._nav_traducciones', compact('fgSub'))
								</div>
							</div>
							@endif

							<div class="tab-pane fade" id="escalado" role="tabpanel" aria-labelledby="escalado-tab">
								<div class="row">
									@include('admin::pages.subasta.subastas._nav_escalado', compact('fgSub'))
								</div>
							</div>

							<div class="tab-pane fade" id="archivos" role="tabpanel" aria-labelledby="archivos-tab">
								<div class="row">
									@include('admin::pages.subasta.subastas._nav_archivos', compact('fgSub'))
								</div>
							</div>

						</div>

					</div>

				</div>

				<div class="row mt-2">
					<div class="col-xs-12 text-center">
						{!! $formulario->submit !!}
					</div>
				</div>

			</form>

		</div>
	</div>

</section>
@stop
