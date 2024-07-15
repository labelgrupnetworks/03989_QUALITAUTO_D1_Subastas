@php
    $url_lotes = Tools::url_auction($subasta->cod_sub, $subasta->name, $subasta->id_auc_sessions, $subasta->reference);
    $url_tiempo_real = Tools::url_real_time_auction($subasta->cod_sub, $subasta->name, $subasta->id_auc_sessions);
    $url_subasta = 'cambiar por archivo';
    $sub = new App\Models\Subasta();
    $file = $sub->getFirstFileWithoutLocale($subasta->cod_sub);
    $fileUrl = '';
	if (!empty($file)) {
	    $fileUrl = $file->type == '5' ? $file->url : "/files{$file->path}";
	}
    $isExternalAucion = in_array($subasta->cod_sub, ['NAC']);

    $SubastaTR = new App\Models\SubastaTiempoReal();
    $SubastaTR->cod = $subasta->cod_sub;
    $SubastaTR->session_reference = $subasta->reference;
    $status = $SubastaTR->getStatus();
    $isFinished = !empty($status) && $status[0]->estado == 'ended';

@endphp
<article class="card auction-card h-100 border-0">

    <img class="card-img-top"
        src="{{ \Tools::url_img_session('subasta_large', $subasta->cod_sub, $subasta->reference) }}"
        alt="{{ $subasta->name }}" @if ($loop->index > 12) loading="lazy" @endif>

    <div class="card-body d-flex flex-column align-items-center">
        <header class="mb-auto">
            <h4 class="auction-card-title fw-light text-lb-secondary text-center">{{ $subasta->name }}</h4>
        </header>

        <p class="card-subtitle small text-lb-gray mb-2">{{ date('d-m-Y H:i', strtotime($subasta->session_start)) }}</p>

        <div class="card-buttons gap-2">
            <a class="btn btn-lb-primary" href="{{ $url_lotes }}" aria-label="Plus">
                <svg class="bi" width="32" height="32" fill="currentColor">
                    <use xlink:href="/bootstrap-icons.svg#eye"></use>
                </svg>
            </a>

            @if (!empty($file))
                <a class="btn btn-lb-primary" href="{{ $fileUrl }}" title="{{ $subasta->name }}"
                    aria-label="Plus" target="_blank">
                    <svg class="bi" width="32" height="24" fill="currentColor">
                        <use xlink:href="/bootstrap-icons.svg#book"></use>
                    </svg>
                </a>
            @endif

            @if ($subasta->tipo_sub == 'W' && strtotime($subasta->session_end) > time() && !$isExternalAucion)
                <a class="btn btn-lb-primary bg-danger border-0" href="{{ $url_tiempo_real }}"
                    title="{{ trans("$theme-app.global.since") . ' ' . date_format(date_create_from_format('Y-m-d H:i:s', $subasta->session_start), 'd/m/Y H:i') . ' ' . trans("$theme-app.global.to") . ' ' . date_format(date_create_from_format('Y-m-d H:i:s', $subasta->session_end), 'd/m/Y H:i') }}"
                    target="_blank">
					LIVE
				</a>
            @endif

        </div>
    </div>
</article>
