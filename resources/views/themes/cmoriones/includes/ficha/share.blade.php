@php
    $titulo = $titulo ?? $data['subasta_info']->lote_actual->titulo_hces1;
	$linkToShare = URL::current();
@endphp



<div class="zone-share-social d-flex justify-content-between">
    <p class="shared">{{ trans(\Config::get('app.theme') . '-app.lot.share_lot') }}</p>

    <ul class="list-unstyled d-flex m-0">

		<li class="ms-3">
            <a class="lb-text-primary"
                href="https://www.instagram.com/?url={{ $linkToShare }}"
                title="{{ trans(\Config::get('app.theme') . '-app.lot.share_twitter') }}" target="_blank">
				@include('components.boostrap_icon', ['icon' => 'instagram', 'size' => 24])
            </a>
        </li>

        <li class="ms-3">
            <a class="lb-text-primary"
                href="https://www.facebook.com/sharer.php?u={{ $linkToShare }}"
                title="{{ trans(\Config::get('app.theme') . '-app.lot.share_facebook') }}" target="_blank">
                <svg class="bi" width="24" height="24" fill="currentColor">
                    <use xlink:href="/bootstrap-icons.svg#facebook"></use>
                </svg>
            </a>
        </li>

        <li class="ms-3">
            <a class="lb-text-primary"
				href="https://twitter.com/intent/tweet?url={{ $linkToShare }}&text={{ $titulo }}"
                title="{{ trans(\Config::get('app.theme') . '-app.lot.share_twitter') }}" target="_blank">
                @include('components.x-icon', ['size' => '24'])
            </a>
        </li>

        <li class="ms-3">
            <a class="lb-text-primary"
                href="https://wa.me/?text={{ $linkToShare }}"
                title="{{ trans(\Config::get('app.theme') . '-app.lot.share_twitter') }}" target="_blank">
				@include('components.boostrap_icon', ['icon' => 'whatsapp', 'size' => 24])
            </a>
        </li>

        <li class="ms-3">
            <a class="lb-text-primary"
				href="https://www.linkedin.com/cws/share?url={{ $linkToShare }}"
                title="{{ trans(\Config::get('app.theme') . '-app.lot.share_twitter') }}" target="_blank">
				@include('components.boostrap_icon', ['icon' => 'linkedin', 'size' => 24])
            </a>
        </li>

        <li class="ms-3">
            <a class="lb-text-primary"
                href="mailto:?Subject=<?= \Config::get('app.name') ?>&body={{ $linkToShare }}"
                title="{{ trans(\Config::get('app.theme') . '-app.lot.share_email') }}" target="_blank">
                <svg class="bi" width="24" height="24" fill="currentColor">
                    <use xlink:href="/bootstrap-icons.svg#envelope-fill"></use>
                </svg>
            </a>
        </li>
    </ul>

</div>

@if (in_array($lote_actual->cod_sub, ['CEREMATE', 'COMDEUDA', 'REOS']))
    <section class="ficha-login">
        <p class="mb-2">
            Si quieres tener una VALORACIÓN REAL de este activo a fecha de hoy por el que muestras interés de forma
            TOTALMENTE GRATUITA, ve a <button class="btn btn-link btn_login p-0">login</button> completa los datos y
            solicita más información. Uno de nuestros expertos
            valorará el activo con el máximo detalle y precisión y se pondrá en contacto contigo para transmitirte el
            resultado de la valoración.
        </p>
        @if (Session::has('user'))
            <button class="btn btn-lb-primary w-100">
                Solicitar valoración
            </button>
        @endif
    </section>
@endif

@php
    $previuos = $data['previous'];
    $next = $data['next'];
@endphp

<div class="d-flex align-content-center">
    @if ($previuos)
        <div class="btn-group me-auto">
            <a class="btn btn-lb-primary d-flex align-items-center" href="{{ $previuos }}">
                @include('components.boostrap_icon', ['icon' => 'chevron-left'])
            </a>
            <a class="btn btn-light" href="{{ $previuos }}">{{ trans("$theme-app.subastas.last") }}</a>
        </div>
    @endif

    @if ($next)
	<div class="btn-group ms-auto">
			<a class="btn btn-light" href="{{ $next }}">{{ trans("$theme-app.subastas.next") }}</a>
            <a class="btn btn-lb-primary d-flex align-items-center" href="{{ $next }}">
                @include('components.boostrap_icon', ['icon' => 'chevron-right'])
            </a>
        </div>
    @endif
</div>
