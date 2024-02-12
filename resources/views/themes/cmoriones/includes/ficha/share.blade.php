@php
    $titulo = $titulo ?? $data['subasta_info']->lote_actual->titulo_hces1;
	$linkToShare = URL::current();
@endphp



<div class="zone-share-social">
    <h5 class="shared">{{ trans(\Config::get('app.theme') . '-app.lot.share_lot') }}</h5>

    <ul class="list-unstyled d-flex justify-content-around m-0">

		<li>
            <a style="--color-share: #e1306c"
                href="https://www.instagram.com/?url={{ $linkToShare }}"
                title="{{ trans(\Config::get('app.theme') . '-app.lot.share_twitter') }}" target="_blank">
				@include('components.boostrap_icon', ['icon' => 'instagram', 'size' => 24])
            </a>
        </li>

        <li>
            <a style="--color-share: #1877F2"
                href="https://www.facebook.com/sharer.php?u={{ $linkToShare }}"
                title="{{ trans(\Config::get('app.theme') . '-app.lot.share_facebook') }}" target="_blank">
                <svg class="bi" width="24" height="24" fill="currentColor">
                    <use xlink:href="/bootstrap-icons.svg#facebook"></use>
                </svg>
            </a>
        </li>

        <li>
            <a style="--color-share: #000"
				href="https://twitter.com/intent/tweet?url={{ $linkToShare }}&text={{ $titulo }}"
                title="{{ trans(\Config::get('app.theme') . '-app.lot.share_twitter') }}" target="_blank">
                @include('components.x-icon', ['size' => '24'])
            </a>
        </li>

        <li>
            <a style="--color-share: #15d170"
                href="https://wa.me/?text={{ $linkToShare }}"
                title="{{ trans(\Config::get('app.theme') . '-app.lot.share_twitter') }}" target="_blank">
				@include('components.boostrap_icon', ['icon' => 'whatsapp', 'size' => 24])
            </a>
        </li>

        <li>
            <a style="--color-share: #0088cc"
				href="https://www.linkedin.com/cws/share?url={{ $linkToShare }}"
                title="{{ trans(\Config::get('app.theme') . '-app.lot.share_twitter') }}" target="_blank">
				@include('components.boostrap_icon', ['icon' => 'linkedin', 'size' => 24])
            </a>
        </li>

        <li>
            <a style="--color-share: #000"
                href="mailto:?Subject=<?= \Config::get('app.name') ?>&body={{ $linkToShare }}"
                title="{{ trans(\Config::get('app.theme') . '-app.lot.share_email') }}" target="_blank">
                <svg class="bi" width="24" height="24" fill="currentColor">
                    <use xlink:href="/bootstrap-icons.svg#envelope-fill"></use>
                </svg>
            </a>
        </li>
    </ul>

	<p class="mt-3">O copiar enlace</p>
	<div class="input-group">
		<input class="form-control" type="text" value="{{ $linkToShare }}" readonly>
		<button class="btn btn-lb-primary d-flex align-items-center" onclick="copyTextToClipboard('{{ $linkToShare }}')">
			@include('components.boostrap_icon', ['icon' => 'clipboard'])
		</button>
	</div>

</div>
