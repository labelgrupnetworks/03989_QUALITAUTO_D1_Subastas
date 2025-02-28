@php
$auction = $data['auction'];
@endphp

<div class="container auction-detail">
	<div class="row">

		<div class="col-sm-7">
			<div class="auction-detail-content h-100 d-flex flex-column gap-4">
				<h2>{!! $auction->descdet_sub ?? '' !!}</h2>

				<div class="auction-address">
					@if( (!empty($auction->expofechas_sub)) || (!empty($auction->expohorario_sub)) ||
					(!empty($auction->expolocal_sub)) || (!empty($auction->expomaps_sub)))
					<h3>{{ trans("web.subastas.inf_subasta_exposicion") }}</h3>
					@endif

					@if(!empty($auction->expofechas_sub))
					<p>{{ $auction->expofechas_sub }}</p>
					@endif

					@if(!empty($auction->expohorario_sub))
					<p>{{ trans("web.subastas.inf_subasta_horario") }}: {{ $auction->expohorario_sub }}</p>
					@endif

					@if(!empty($auction->expolocal_sub))
					<p>{{ trans("web.subastas.inf_subasta_location") }}: {{ $auction->expolocal_sub }}</p>
					@endif

					@if(!empty($auction->expomaps_sub))
					<p>
						<a target="_blank" title="cómo llegar" href="{{ "https://maps.google.com/?q={$auction->expomaps_sub}" }}">
							<i class="fas fa-map-marker-alt"></i>
						</a>
					</p>
					@endif
				</div>

				<div class="auction-session">

					@if((!empty($auction->sesfechas_sub)) || (!empty($auction->seshorario_sub)) ||
						(!empty($auction->seslocal_sub)) || (!empty($auction->sesmaps_sub)))

					<h3 class="mt-3">{{ trans("web.subastas.inf_subasta_subasta") }}</h3>
					@endif

					@if(!empty($auction->sesfechas_sub))
					<p> {{ $auction->sesfechas_sub }}</p>
					@endif

					@if(!empty($auction->seshorario_sub))
					<p>{{ trans("web.subastas.inf_subasta_horario") }}: {{ $auction->seshorario_sub }} </p>
					@endif

					@if(!empty($auction->seslocal_sub))
					<p>{{ trans("web.subastas.inf_subasta_location") }}: {{ $auction->seslocal_sub }}</p>
					@endif

					@if(!empty($auction->sesmaps_sub))
					<p>
						<a target="_blank" title="{{ trans("web.subastas.how_to_get") }}"
							href="{{ "https://maps.google.com/?q={$auction->sesmaps_sub}" }}">
							<i class="fas fa-map-marker-alt"></i>
						</a>
					</p>
					@endif
				</div>

				<div class="auction-detail-links">
					@foreach ($data['sessions'] as $session)
					<a class="btn btn-lb-primary" title="Ver lotes"
						href="{{ Tools::url_auction($session->auction, $session->name, $session->id_auc_sessions, $session->reference) }}">
						<p>{{$session->name}}</p>
					</a>
					@endforeach
				</div>

				<div class="auction-detail-share mt-auto">
					<h4>{{ trans("web.subastas.shared_auctions") }}</h4>

					<ul class="list-unstyled d-flex">
						<li>
							<a class="lb-text-primary" href="http://www.facebook.com/sharer.php?u={{$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] }}" target="_blank">
								<svg class="bi" width="24" height="24" fill="currentColor">
									<use xlink:href="/bootstrap-icons.svg#facebook"></use>
								</svg>
							</a>
						</li>
						<li class="ms-3">
							<a class="lb-text-primary" href="http://twitter.com/share?url={{$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] }}&amp;text={{ $auction->des_sub}}&url={{$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] }}" target="_blank">
								<svg class="bi" width="24" height="24" fill="currentColor">
									<use xlink:href="/bootstrap-icons.svg#twitter"></use>
								</svg>
							</a>
						</li>
						<li class="ms-3">
							<a class="lb-text-primary" title="Compartir por e-mail" href="mailto:?Subject={{ trans(" web.head.title_app")}}&body={{$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] }}" target="_blank">
								<svg class="bi" width="24" height="24" fill="currentColor">
									<use xlink:href="/bootstrap-icons.svg#envelope"></use>
								</svg>
							</a>
						</li>
					</ul>
				</div>

			</div>
		</div>

		<div class="col-sm-5 text-center">
			<img class="img-fluid" src="{{\Tools::url_img_auction('subasta_large',$auction->cod_sub)}}">
		</div>

	</div>
</div>
