@inject('auctionService', 'App\Services\Auction\AuctionService')

@php
    $urlIndice = $urlIndice ?? '';
	$isFinishedAndNotHistory = $ficha_subasta->subc_sub != 'H' && now() > $ficha_subasta->end && $ficha_subasta->tipo_sub == 'W';
@endphp

<div class="auction-cover">
    <div class="auction-cover-image">
        <img width="100%"
            src="{{ Tools::url_img_session('subasta_medium', $ficha_subasta->cod_sub, $ficha_subasta->reference) }}"
            class="img-responsive">
    </div>
    <div class="auction-cover-info">
        <div class="session-desc">
            <p>{!! $ficha_subasta->session_info ?? ($ficha_subasta->descdet_sub ?? '') !!}</p>
        </div>
    </div>
    <div class="auction-cover-links">

        @if (!empty($urlIndice))

            @if ($auctionService->existsAuctionIndex($ficha_subasta->cod_sub, $ficha_subasta->id_auc_sessions))
                <a class="btn btn-block btn-subasta" title="{{ trans("$theme-app.lot_list.open_indice") }}"
                    href="{{ $urlIndice }}">
                    {{ trans("$theme-app.lot_list.open_indice") }}
                </a>
            @endif

			@if($isFinishedAndNotHistory && request('no_award') != 1)
			<a title="{{ $ficha_subasta->name }}" href="?no_award=1" class="btn btn-block btn-color">
				{{ trans("$theme-app.subastas.lotes_no_vendido") }}
			</a>
			@elseif($isFinishedAndNotHistory && request('no_award') == 1)
			<a title="{{ $ficha_subasta->name }}" href="{{ url()->current() }}" class="btn btn-block btn-color">
				{{ trans("$theme-app.subastas.see_lotes") }}
			</a>
			@endif

        @else
            <a type="button" class="btn btn-block btn-color"
				href="{{ Routing::translateSeo('subasta') . "{$ficha_subasta->cod_sub}-" . str_slug("{$ficha_subasta->name}-{$ficha_subasta->id_auc_sessions}") }}">
				{{ trans("$theme-app.subastas.see_lotes") }}
            </a>
        @endif

        @if ($ficha_subasta->tipo_sub == 'W' && strtotime($ficha_subasta->end) > time())
            <a class="btn btn-block btn-color"
                href="{{ Routing::translateSeo('api/subasta') . $ficha_subasta->cod_sub . '-' . str_slug($ficha_subasta->name) . '-' . $ficha_subasta->id_auc_sessions }}"
                target="_blank">{{ trans("$theme-app.subastas.lot_subasta_online") }}
            </a>
        @endif

        @if ($ficha_subasta->upcatalogo == 'S')
            <a class="btn btn-block btn-subasta" title="{{ trans("$theme-app.subastas.pdf_catalog") }}" target="_blank"
                href="{{ \Tools::url_pdf($ficha_subasta->cod_sub, $ficha_subasta->reference, 'cat') }}">
                {{ trans("$theme-app.subastas.pdf_catalog") }}
            </a>
        @endif
        <a class="btn btn-block btn-subasta" title="{{ trans("$theme-app.foot.term_condition") }}" target="_blank"
            href="{{ Routing::translateSeo('pagina') . trans("$theme-app.links.term_condition") }}">
            {{ trans("$theme-app.foot.term_condition") }}
        </a>

		 @if(Session::has('user') && $ficha_subasta->opcioncar_sub  == 'S' && strtotime($ficha_subasta->start) > time())
			<a class="btn btn-block btn-subasta" href="{{ \Routing::slug('user/panel/modification-orders') }}?sub={{$ficha_subasta->cod_sub}}">
				<x-icon.fontawesome version=5 icon=gavel></x-icon.fontawesome>
				{{ trans('web.lot_list.ver_ofertas') }}
			</a>
		@endif

        <div class="share-panel-auction">
			@include('includes.share_list', ['url' => url()->full(), 'text' => $ficha_subasta->des_sub])
        </div>

    </div>
</div>
