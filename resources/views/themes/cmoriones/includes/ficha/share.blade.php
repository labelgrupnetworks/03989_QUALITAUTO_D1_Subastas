@php
    $titulo = $lote_actual->descweb_hces1 ?? $lote_actual->titulo_hces1;
    $descripcion = $lote_actual->desc_hces1;
@endphp

<div class="zone-share-social">
    <button class="btn btn-icon btn-outline-lb-primary" data-url="{{ url()->full() }}" data-text="{{ $descripcion }}"
        data-title="{{ $titulo }}" onclick="sharePage(this.dataset)">
        @include('components.boostrap_icon', ['icon' => 'share-fill', 'size' => '18'])
        {{ trans(\Config::get('app.theme') . '-app.lot.share_lot') }}
    </button>
</div>
