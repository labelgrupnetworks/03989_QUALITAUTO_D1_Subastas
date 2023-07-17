@php
    $image = Tools::url_img('square_medium', $lot->num_hces1, $lot->lin_hces1);
    $url = Tools::url_lot($lot->cod_sub, $lot->id_auc_sessions, $lot->name, $lot->ref_asigl0, $lot->num_hces1, $lot->webfriend_hces1, $lot->descweb_hces1);
	if(!empty($artist)) {
		$url .= '?artistaFondoGaleria=' . $artist->id_artist;
	}
@endphp

<div class="col">
    <article class="card auction-card lot-galery-card">
        <img class="card-img-top" src="{{ $image }}" alt="" loading="lazy">
        <div class="card-body">
            <p>
                {{ $lot->descweb_hces1 }}
            </p>
        </div>
        <a class="stretched-link" href="{{ $url }}"></a>
    </article>
</div>
