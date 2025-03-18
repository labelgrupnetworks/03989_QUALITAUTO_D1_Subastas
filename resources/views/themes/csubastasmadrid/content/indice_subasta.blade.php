@inject('auctionService', 'App\Services\Auction\AuctionService')

@php
	use App\Models\V5\AucSessions;

	$ficha_subasta = $data['subasta'];

	$previousAuction = AucSessions::previousReference($ficha_subasta->cod_sub, $ficha_subasta->reference);
	$nextAuction = AucSessions::nextReference($ficha_subasta->cod_sub, $ficha_subasta->reference);

	$indices = $auctionService->getAuctionIndexs($ficha_subasta->cod_sub, $ficha_subasta->id_auc_sessions);

	//mostrar cuenta atras
    $showCountdown = $ficha_subasta->tipo_sub == 'W' && in_array($ficha_subasta->subc_sub, ['A', 'S']) && strtotime($ficha_subasta->start) > time();
@endphp

<div class="container mb-1">
    <div class="row">
        <div class="col-xs-12">
			<div class="grid-title-wrapper">
				<h1 class="grid-title">
					@if (!empty($ficha_subasta))
						{{ trans("$theme-app.subastas.inf_subasta_subasta") }} {{ $ficha_subasta->name }}
					@else
						{{ $indice }}
					@endif
				</h1>
				<div class="next">
					@if ($previousAuction)
						<a class="nextLeft" title="{{ trans("$theme-app.lot_list.previous_session") }}"
							href="{{ $previousAuction->url_indice }}">
							<i class="fa fa-angle-left fa-angle-custom"></i>
							{{ trans("$theme-app.lot_list.previous_session") }}
						</a>
					@endif
					@if ($nextAuction)
						<a class="nextRight" title="{{ trans("$theme-app.lot_list.next_session") }}"
							href="{{ $nextAuction->url_indice }}">
							{{ trans("$theme-app.lot_list.next_session") }}
							<i class="fa fa-angle-right fa-angle-custom"></i>
						</a>
					@endif
				</div>
			</div>
        </div>
    </div>

    @if (!empty($ficha_subasta))
		@include('includes.auction_cover', ['ficha_subasta' => $ficha_subasta, 'urlIndice' => false])
    @endif

	@include('includes.subasta_top_filters', ['ficha_subasta' => $ficha_subasta, 'urlToForm' => $data['url']])


</div>

<div class="container">
	<div class="row">
		<div class="col-xs-12">
			<table class="table-custom">
				<thead>
					<tr>
						<th class="first-column-orders">
							{{-- {{ trans(\Config::get('app.theme') . '-app.lot_list.name') }}</th> --}}
							@if (!empty($ficha_subasta))
								{{ $indice }} {{ $ficha_subasta->name }}
							@else
								{{ $indice }}
							@endif
						</th>
						<th class="second-column-orders text-right text-lowercase">
							{{ trans(\Config::get('app.theme') . '-app.lot_list.from') }}</th>
						<th class="third-column-orders text-right text-lowercase">
							{{ trans(\Config::get('app.theme') . '-app.lot_list.to') }}</th>
					</tr>
				</thead>
				<tbody>
					<tr id="order-row-tpl" style="display: table-row;">
						<td class="class-content-orders" onclick="window.location='{{ $data['url'] }}'">
							<div class="nt-block-th left">
								<span>
									{{ trans(\Config::get('app.theme') . '-app.subastas.see-all') }}
								</span>
							</div>
						</td>
						<td class="class-content-orders">
							<div class="nt-block-th text-right">
								<span>{{ $ficha_subasta->init_lot }}</span>
							</div>

						</td>
						<td class="class-content-orders class-content-orders-max">
							<div class="nt-block-th text-right">
								<span>{{ $ficha_subasta->end_lot }}</span>
							</div>

						</td>
					</tr>
					@foreach ($indices as $indice)
						<tr id="order-row-tpl" style="display: table-row;">
							<td class="class-content-orders"
								@if (!empty($indice->dref_subind)) onclick="window.location='{{ $data['url'] }}?first_lot={{ $indice->dref_subind }}&last_lot={{ $indice->href_subind }}&index_hide=1'" @endif>
								<div class="nt-block-th left">
									<span data-lb-nivel="{{ $indice->nivel_subind }}">
										{!! $indice->des_subind ?? '&nbsp;' !!}
									</span>
								</div>
							</td>
							<td class="class-content-orders">
								<div class="nt-block-th text-right">
									<span class="@if($indice->nivel_subind == "1") bold @endif">{{ $indice->dref_subind }}</span>
								</div>

							</td>
							<td class="class-content-orders class-content-orders-max">
								<div class="nt-block-th text-right">
									<span class="@if($indice->nivel_subind == "1") bold @endif">{{ $indice->href_subind }}</span>
								</div>
							</td>
						</tr>
					@endforeach
				<tbody>
			</table>
		</div>

	</div>
</div>
