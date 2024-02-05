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
                href="http://www.facebook.com/sharer.php?u={{ $linkToShare }}"
                title="{{ trans(\Config::get('app.theme') . '-app.lot.share_facebook') }}" target="_blank">
                <svg class="bi" width="24" height="24" fill="currentColor">
                    <use xlink:href="/bootstrap-icons.svg#facebook"></use>
                </svg>
            </a>
        </li>

        <li class="ms-3">
            <a class="lb-text-primary"
                href="http://twitter.com/share?text=<?= $titulo . ' ' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>&url={{ $linkToShare }}"
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
                href="https://www.linkedin.com/sharing/share-offsite/?url={{ $linkToShare }}"
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
