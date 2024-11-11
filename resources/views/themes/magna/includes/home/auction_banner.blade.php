@php
    $url_lotes = Tools::url_auction(
        $auction->cod_sub,
        $auction->name,
        $auction->id_auc_sessions,
        $auction->reference,
    );
@endphp
<section class="container-fluid border-top border-bottom">
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="d-flex flex-column align-items-center gap-4 py-5 border-end">
                <h1>{{ $auction->name }}</h1>

                <a class="btn btn-outline-lb-primary rounded-5" href="{{ $url_lotes }}">
                    Ir a la subasta
                </a>

                <a class="btn btn-outline-lb-primary rounded-5" href="{{ $url_lotes }}">
                    Ver catálogo
                </a>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <img src="" alt="">
        </div>
    </div>
</section>
