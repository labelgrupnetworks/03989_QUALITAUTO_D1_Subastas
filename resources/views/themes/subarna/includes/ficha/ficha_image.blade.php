<div class="img_single_border h-100 hidden-xs">

	<div class="button-follow" style="display:none;">
		<div class="spinner">
			<div class="double-bounce1"></div>
			<div class="double-bounce2"></div>
		</div>
	</div>

	@if ($lote_actual->retirado_asigl0 != 'N')
		<div class="retired ">
			{{ trans($theme . '-app.lot.retired') }}
		</div>
	@elseif($lote_actual->fac_hces1 == 'D' || $lote_actual->fac_hces1 == 'R')
		<div class="retired" style="background:#777777;text-transform: lowercase;">
			{{ trans($theme . '-app.subastas.dont_available') }}
		</div>
	@elseif(
		$lote_actual->cerrado_asigl0 == 'S' &&
			(!empty($lote_actual->himp_csub) ||
				$lote_actual->desadju_asigl0 == 'S' ||
				($lote_actual->subc_sub == 'H' && !empty($lote_actual->impadj_asigl0))))
		<div class="retired" style="background:#777777;text-transform: lowercase;">
			{{ trans($theme . '-app.subastas.buy') }}
		</div>
	@endif

	<div class="img_single h-100" id="img_main">
		<a href="javascript:action_fav_modal('remove')" title="{{ $lote_actual->titulo_hces1 }}">
			<img src="/img/load/real/{{ $lote_actual->imagen }}" alt="{{ $lote_actual->titulo_hces1 }}">
		</a>
	</div>

</div>

<!-- Inicio Galeria Responsive -->
<div class="owl-theme owl-carousel visible-xs" id="owl-carousel-responsive">

    @foreach ($lote_actual->videos ?? [] as $key => $video)
        <div class="item_content_img_single" style="position: relative; height: 290px; overflow: hidden;">
            <video class="video_mobile" width="100%" controls>
                <source src="{{ $video }}" type="video/mp4">
            </video>
        </div>
    @endforeach

    @foreach ($lote_actual->imagenes as $key => $imagen)
        <div class="item_content_img_single" style="position: relative; height: 290px; overflow: hidden;">

            <img class="img-responsive"
                src="{{ \Tools::url_img('lote_medium_large', $lote_actual->num_hces1, $lote_actual->lin_hces1, $key) }}"
                alt="{{ $lote_actual->titulo_hces1 }}"
                style="    max-width: 100%; max-height: 190px;top: 50%; transform: translateY(-50%); position: relative; width: auto !important;    display: inherit !important;    margin: 0 auto !important;"
                loading="lazy">
        </div>
    @endforeach
</div>
