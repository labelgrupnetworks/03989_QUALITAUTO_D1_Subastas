@php
	$url_lotes = Tools::url_auction($subasta->cod_sub, $subasta->name, $subasta->id_auc_sessions, $subasta->reference);
	$url_tiempo_real = Tools::url_real_time_auction($subasta->cod_sub, $subasta->name, $subasta->id_auc_sessions);
	$sub = new App\Models\Subasta();
	$files = $sub->getFiles($subasta->cod_sub);
	$fileUrl = '';
	if (!empty($files)) {
	    $fileUrl = $files[0]->type == '5' ? $files[0]->url : "/files{$files[0]->path}";
	}
	$isExternalAucion = in_array($subasta->cod_sub, ['NAC']);

	$SubastaTR = new App\Models\SubastaTiempoReal();
	$SubastaTR->cod = $subasta->cod_sub;
	$SubastaTR->session_reference = $subasta->reference;
	$status = $SubastaTR->getStatus();
	$isFinished = !empty($status) && $status[0]->estado == 'ended';

@endphp
<div class="row g-0 h-100 mobile-subastas">
	<div class="col-12 card-img-wrapper{{ !$loop->first ? ' d-none' : '' }}">
		<a href="{{ $url_lotes }}">
			<div class="activity"></div>
			<img src="{{ \Tools::url_img_session('subasta_large', $subasta->cod_sub, $subasta->reference) }}" class="w-100 h-100"
				alt="{{ $subasta->name }}" @if ($loop->index > 2) loading="lazy" @endif>
		</a>
	</div>
	<div class="col-12 d-flex flex-column{{ !$loop->last ? ' auction-grid-border' : '' }}">

		<div class="card-body d-flex flex-column align-items-start">
			<a class="grid-lot-link w-100" href="{{ $url_lotes }}">
				<header>
					<h5 class="card-title">{{ $subasta->name }}</h5>
					<h6 class="card-subtitle mb-2 text-muted">
						{{ trans("$theme-app.user_panel.date") . ': ' . date('d-m-Y H:i', strtotime($subasta->session_start)) }}</h6>
				</header>
			</a>

			<div class="w-100 mb-3 d-flex align-items-center justify-content-between">
				<a href="{{ $url_lotes }}"
					class="btn btn-lb-primary mt-auto see-lots-btn">{{ trans("$theme-app.subastas.see_lotes") }}</a>
				@if ($subasta->tipo_sub == 'W' && strtotime($subasta->session_end) > time() && !$isExternalAucion)
					<a class="btn btn-lb-primary bg-danger border-0 live-btn" href="{{ $url_tiempo_real }}"
						title="{{ trans("$theme-app.global.since") . ' ' . date_format(date_create_from_format('Y-m-d H:i:s', $subasta->session_start), 'd/m/Y H:i') . ' ' . trans("$theme-app.global.to") . ' ' . date_format(date_create_from_format('Y-m-d H:i:s', $subasta->session_end), 'd/m/Y H:i') }}"
						target="_blank">
						LIVE
					</a>
				@endif
			</div>

			@if (!empty($files))
				<a class="btn btn-sm btn-outline-border-lb-primary w-100 info-btn" href="{{ $fileUrl }}" title="{{ $subasta->name }}">
					<svg class="bi" width="12" height="12" fill="currentColor">
						<use xlink:href="/bootstrap-icons.svg#plus"></use>
					</svg>
					{{ trans("$theme-app.subastas.see_subasta") }}
				</a>
			@endif

		</div>
	</div>


</div>
