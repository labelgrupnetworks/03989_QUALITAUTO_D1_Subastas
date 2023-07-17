@php
    $image = Tools::url_img('real', $lot->num_hces1, $lot->lin_hces1);
    $url = Tools::url_lot($lot->cod_sub, $lot->id_auc_sessions, $lot->name, $lot->ref_asigl0, $lot->num_hces1, $lot->webfriend_hces1, $lot->descweb_hces1);
   /*  if (!empty($artist)) {
        $url .= '?artistaFondoGaleria=' . $artist->id_artist;
    } */

	$descweb_hces1 = $lot->descweb_hces1;
	$year = "";
	$descweb_hces1 = explode(",", $descweb_hces1);
	if(count($descweb_hces1) == 2){
		$year = $descweb_hces1[1];
	}
	$descweb_hces1 = $descweb_hces1[0];

@endphp

<div class="gallery-lot position-relative {{ $class }}">
	<div class="galery-lot-image-wrapper h-100">
		<img class="" src="{{ $image }}" alt="" {{-- loading="lazy" --}}>
	</div>
    <div class="card-body-lot d-none d-lg-block">
        <p class="card-title ff-highlight fs-20">{{ $descweb_hces1 }}</p>
		<p>{{ $year }}</p>
    </div>
    <a class="stretched-link" href="{{ $url }}"></a>
</div>
