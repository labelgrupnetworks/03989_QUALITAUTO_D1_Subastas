@php
$url_lotes= \Tools::url_auction($subasta->cod_sub,$subasta->name,$subasta->id_auc_sessions,$subasta->reference);
$url_tiempo_real=\Tools::url_real_time_auction($subasta->cod_sub,$subasta->name,$subasta->id_auc_sessions);
$url_subasta=\Tools::url_info_auction($subasta->cod_sub,$subasta->name);

//Obtener los archivos de una subasta
$sub = new App\Models\Subasta;
$files = $sub->getFiles($subasta->cod_sub);
@endphp

<article class="card card-custom-large h-100">
	<div class="row g-0 h-100">
		<div class="col-md-8 d-flex flex-column">

			<div class="card-body d-flex flex-column align-items-start">
				<header>
					<h5 class="card-title">{{ $subasta->name }}</h5>
					<h6 class="card-subtitle mb-2 text-muted">{{ trans("web.user_panel.date") . ': ' . date("d-m-Y H:i", strtotime($subasta->session_start))}}</h6>
				</header>

				<p class="card-text max-line-3 mb-2">{{ strip_tags($subasta->description ?? '') }}</p>

				<a href="{{ $url_lotes }}" class="btn btn-lb-primary mt-auto">{{ trans("web.subastas.see_lotes") }}</a>
			</div>

			<footer class="card-footer">
				<div class="row row-cols-2 gy-1 card-links">
					<div class="col">
						<a class="btn btn-sm btn-outline-border-lb-primary" href="{{ $url_subasta }}" aria-label="Plus">
							<svg class="bi" width="12" height="12" fill="currentColor">
								<use xlink:href="/bootstrap-icons.svg#plus"></use>
							</svg>
							{{ trans("web.subastas.see_subasta") }}
						</a>
					</div>
					<div class="col">
						<button class="btn btn-sm btn-outline-border-lb-primary js-auction-files" data-auction="{{$subasta->cod_sub}}" data-reference="{{$subasta->reference}}">
							<svg class="bi" width="12" height="12" fill="currentColor">
								<use xlink:href="/bootstrap-icons.svg#folder"></use>
							</svg>
							{{ trans("web.subastas.documentacion") }}
						</button>
					</div>
					@if($subasta->upcatalogo == 'S')
					<div class="col">
						<a class="btn btn-sm btn-outline-border-lb-primary" href="">
							<svg class="bi" width="12" height="12" fill="currentColor">
								<use xlink:href="/bootstrap-icons.svg#file-pdf"></use>
							</svg>
							{{ trans("web.subastas.pdf_catalog") }}
						</a>
					</div>
					@endif

				</div>
			</footer>

		</div>
		<div class="col-md-4 card-img-wrapper">

			<div class="activity"></div>

			<img
				 src="{{\Tools::url_img_session('subasta_medium',$subasta->cod_sub,$subasta->reference)}}"

				class="w-100 h-100" alt="{{ $subasta->name }}"
				@if($loop->index > 2)
				loading="lazy"
				@endif>

				@if($subasta->tipo_sub =='W' && strtotime($subasta->session_end) > time())
				<div class="btn-live-wrapper">
					<a class="btn btn-lb-primary" href="{{ $url_tiempo_real }}"
						title="{{ trans("web.lot_list.from") }} {{ date_format(date_create_from_format('Y-m-d H:i:s',$subasta->session_start),'d/m/Y H:i') }} {{ trans("web.lot_list.to") }} {{ date_format(date_create_from_format('Y-m-d H:i:s', $subasta->session_end),'d/m/Y H:i') }}"
						target="_blank">{{ trans("web.lot.bid_live") }}</a>
				</div>
				@endif

		</div>
	</div>
</article>


{{-- pos si lo necesitamos
@if( $subasta->tipo_sub =='W' && strtotime($subasta->session_end) > time() )
<div class="bid-online"></div>
<div class="bid-online animationPulseRed"></div>
@endif
 --}}
