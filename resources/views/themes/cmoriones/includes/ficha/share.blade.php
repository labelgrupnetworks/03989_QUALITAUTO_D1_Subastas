@php
    $titulo = $titulo ?? $data['subasta_info']->lote_actual->titulo_hces1;
@endphp

<div class="zone-share-social d-flex">
    <p class="shared">{{ trans(\Config::get('app.theme') . '-app.lot.share_lot') }}</p>

    <ul class="list-unstyled d-flex m-0">
        <li class="ms-3">
            <a class="lb-text-primary"
                href="http://www.facebook.com/sharer.php?u=<?= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>"
                title="{{ trans(\Config::get('app.theme') . '-app.lot.share_facebook') }}" target="_blank">
                <svg class="bi" width="24" height="24" fill="currentColor">
                    <use xlink:href="/bootstrap-icons.svg#facebook"></use>
                </svg>
            </a>
        </li>

        <li class="ms-3">
            <a class="lb-text-primary"
                href="http://twitter.com/share?text=<?= $titulo . ' ' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>&url=<?= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>"
                title="{{ trans(\Config::get('app.theme') . '-app.lot.share_twitter') }}" target="_blank">
                @include('components.x-icon', ['size' => '24'])
            </a>
        </li>
        <li class="ms-3">
            <a class="lb-text-primary"
                href="mailto:?Subject=<?= \Config::get('app.name') ?>&body=<?= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>"
                title="{{ trans(\Config::get('app.theme') . '-app.lot.share_email') }}" target="_blank">
                <svg class="bi" width="24" height="24" fill="currentColor">
                    <use xlink:href="/bootstrap-icons.svg#envelope-fill"></use>
                </svg>
            </a>
        </li>
    </ul>

</div>
