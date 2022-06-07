@extends('layouts.default')

@section('title')

@stop

@section('content')
@php
	use App\Models\V5\FgSub;
@endphp

<section class="all-aution-title title-content pb-1">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 h1-titl text-center">
				<h1 class="page-title mb-0">{{ trans("$theme-app.foot.map") }}</h1>
			</div>
		</div>
	</div>
</section>

<div class="container mt-3">

	<div class="row">
		<div class="col-xs-12">

			<h2>
				<a href="/">{{(\Config::get( 'app.name' ))}}</a>
			</h2>

			<ul class="ul-site">
				<li class="li-site">
					<h3>{{ trans("$theme-app.global.content") }}</h3>
					<ul class="ul-site">
						@foreach ($pages as $page)
						<li class="li-site">
							<h4><a href="{{ \Routing::translateSeo('pagina') . $page->key_web_page }}">{{ $page->name_web_page }}</a></h4>
						</li>
						@endforeach
					</ul>
				</li>

				@if($categorias)
				<li class="li-site">
					<h3>Categorias</h3>
					<ul class="ul-site">
						@foreach ($categorias as $categoria)
						<li class="li-site">
							<h4><a href="{{ route("category", ['keycategory' => $categoria->key_ortsec0]) }}">{{$categoria->des_ortsec0}}</a></h4>
						</li>
						@endforeach
					</ul>
				</li>
				@endif

				<li class="li-site">
					<h3>
						<a
							href="{{ (config('app.gridLots', '') == "new") ? route('allCategories') : \Routing::translateSeo('todas-subastas') }}">
							{{ trans("$theme-app.foot.auctions") }}
						</a>
					</h3>
					<ul class="ul-site">

						@if ($subastas->where('subc_sub', FgSub::SUBC_SUB_ACTIVO)->contains('tipo_sub', FgSub::TIPO_SUB_PRESENCIAL))
						<li class="li-site">
							<h4><a href="{{ \Routing::translateSeo('presenciales') }}">{{ trans("$theme-app.lot_list.face_auction") }}</a></h4>
                        </li>
						@endif
						@if ($subastas->where('subc_sub', FgSub::SUBC_SUB_ACTIVO)->contains('tipo_sub', FgSub::TIPO_SUB_ONLINE))
						<li class="li-site">
							<h4><a href="{{ \Routing::translateSeo('subastas-online') }}">{{ trans("$theme-app.lot_list.online_auction") }}</a></h4>
                        </li>
						@endif
						@if ($subastas->where('subc_sub', FgSub::SUBC_SUB_ACTIVO)->contains('tipo_sub', FgSub::TIPO_SUB_PERMANENTE))
						<li class="li-site">
							<h4><a href="{{ \Routing::translateSeo('subastas-permanentes') }}">{{ trans("$theme-app.lot_list.permanent_auction") }}</a></h4>
                        </li>
						@endif
						@if ($subastas->where('subc_sub', FgSub::SUBC_SUB_ACTIVO)->contains('tipo_sub', FgSub::TIPO_SUB_VENTA_DIRECTA))
						<li class="li-site">
							<h4><a href="{{ \Routing::translateSeo('venta-directa') }}">{{ trans("$theme-app.lot_list.direct_sale") }}</a></h4>
                        </li>
						@endif
						@if ($subastas->contains('subc_sub', FgSub::SUBC_SUB_HISTORICO))
						<li class="li-site">
							<h4><a href="{{ \Routing::translateSeo('subastas-historicas') }}">{{ trans("$theme-app.lot_list.historic") }}</a></h4>
                        </li>
						@endif
						@if ($subastas->where('subc_sub', FgSub::SUBC_SUB_HISTORICO)->contains('tipo_sub', FgSub::TIPO_SUB_PERMANENTE))
						<li class="li-site">
							<h4><a href="{{ \Routing::translateSeo('subastas-historicas-presenciales') }}">{{ trans("$theme-app.lot_list.historic") }}</a></h4>
                        </li>
						@endif
						@if ($subastas->where('subc_sub', FgSub::SUBC_SUB_HISTORICO)->contains('tipo_sub', FgSub::TIPO_SUB_ONLINE))
						<li class="li-site">
							<h4><a href="{{ \Routing::translateSeo('subastas-historicas-online') }}">{{ trans("$theme-app.lot_list.historic") }}</a></h4>
                        </li>
						@endif

						@foreach ($subastas as $subasta)
						<li class="li-site">
							<h4><a href="{{ \Tools::url_info_auction($subasta->cod_sub, $subasta->name) }}">{{ trans("$theme-app.subastas.inf_subasta_subasta") }} {{ $subasta->name }}</a></h4>
						</li>


						@foreach ($subasta->lotes as $lote)

						@if ($loop->first)
						<li class="li-site">
							<h4><a
									href="{{ \Tools::url_auction($subasta->cod_sub, $subasta->name ,$subasta->id_auc_sessions) }}">{{ trans("$theme-app.lot_list.indice_auction") }} {{ $subasta->name }}</a>
							</h4>
							<ul class="ul-site">
								@endif
								<li class="li-site">
									<h5><a
											href="{{ \Tools::url_lot($lote->sub_asigl0, $lote->id_auc_sessions, $lote->name, $lote->ref_asigl0, $lote->num_hces1, $lote->webfriend_hces1, !empty($lote->descweb_hces1) ? $lote->descweb_hces1 : $lote->titulo_hces1) }}">{{ !empty($lote->descweb_hces1) ? $lote->descweb_hces1 : $lote->titulo_hces1 }}</a>
									</h5>
								</li>
								@if ($loop->last)
							</ul>
						</li>
						@endif

						@endforeach


						@endforeach
					</ul>

				</li>





			</ul>

		</div>
	</div>
</div>
@stop

