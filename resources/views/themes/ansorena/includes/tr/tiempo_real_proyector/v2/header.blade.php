@php
	$refFormat = str_replace(['.1', '.2', '.3', '.4', '.5'], ['-A', '-B', '-C', '-D', '-E'], $data['subasta_info']->lote_actual->ref_asigl0);
@endphp

<header class="enc">

	<div class="lot">
		<p id="lote_actual_main">
			{{ trans("$theme-app.sheet_tr.lot") }}
			<span id="info_lot_actual">{{ $refFormat }}</span>
		</p>
	</div>

	<a class="brand" href="{{ \Routing::slug('/') }}">
        <img class="img-responsive" src="{{ $img_url }}/logo_v2.png">
    </a>

	<h1 class="auction_number">{{ $data['name'] }}</h1>
</header>
