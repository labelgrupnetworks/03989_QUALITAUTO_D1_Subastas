<script src="/vendor/photoswipe/photoswipe.umd.min.js"></script>
<script src="/vendor/photoswipe/photoswipe-lightbox.umd.min.js"></script>
<link href="/vendor/photoswipe/photoswipe.css" rel="stylesheet">

<div class="img_single_border h-100 hidden-xs hidden-sm">

    <div class="button-follow" style="display:none;">
        <div class="spinner">
            <div class="double-bounce1"></div>
            <div class="double-bounce2"></div>
        </div>
    </div>

    @if ($lote_actual->retirado_asigl0 != 'N')
        <div class="retired">
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
<div class="ficha-galery-responsive owl-theme owl-carousel visible-xs visible-sm" id="owl-carousel-responsive">
    @foreach ($lote_actual->videos ?? [] as $key => $video)
        <div class="item_content_img_single">
            <video class="video_mobile" width="100%" controls>
                <source src="{{ $video }}" type="video/mp4">
            </video>
        </div>
    @endforeach

    @foreach ($lote_actual->imagenes as $key => $imagen)
        <div class="item_content_img_single">
            <a data-pswp-width="838" data-pswp-height="838"
                href="{{ Tools::url_img('lote_large', $lote_actual->num_hces1, $lote_actual->lin_hces1, $key) }}">
                <img class="img-responsive"
                    src="{{ Tools::url_img('lote_medium_large', $lote_actual->num_hces1, $lote_actual->lin_hces1, $key) }}"
                    alt="{{ $lote_actual->titulo_hces1 }}" loading="lazy">
            </a>
        </div>
    @endforeach
</div>

<script type="text/javascript">
    var lightbox = new PhotoSwipeLightbox({
        gallery: '.ficha-galery-responsive',
        children: 'a',
        pswpModule: PhotoSwipe
    });
    lightbox.init();

    function loadSeaDragon(img) {
        var element = document.getElementById("img_main");
        while (element.firstChild) {
            element.removeChild(element.firstChild);
        }
        OpenSeadragon({
            id: "img_main",
            prefixUrl: "/img/opendragon/",
            showReferenceStrip: true,
            tileSources: [{
                type: 'image',
                url: '/img/load/real/' + img
            }],
            showNavigator: false,
        });
    }
    loadSeaDragon('<?= $lote_actual->imagen ?>');

    $(document).ready(function() {


        $('.btn-play').on('click', function(e) {

            var element = document.getElementById("js-video");
            while (element.firstChild) {
                element.removeChild(element.firstChild);
            }

            let $video = $('<video />', {
                id: 'video',
                src: this.dataset.video,
                type: 'video/mp4',
                controls: true,
                autoplay: true
            }).css('width', '100%');
            $video.appendTo(element);

        });

        $('#modalVideo').on('hidden.bs.modal', function(e) {
            let $video = document.getElementById("video");
            $video.pause();
            $video.currentTime = 0;
        });
    });
</script>
