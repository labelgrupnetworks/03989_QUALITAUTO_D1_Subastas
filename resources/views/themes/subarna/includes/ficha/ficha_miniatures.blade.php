<div class="slider-thumnail-container">

    <div class="row-up control" onClick="clickControl(this)">
        <i class="fa fa-chevron-up" aria-hidden="true"></i>
    </div>

    <div class="miniImg hidden-xs ">

        @foreach ($lote_actual->videos ?? [] as $key => $video)
            <a class="ficha-thumnail">
                <img class="btn-play img-responsive" data-toggle="modal" data-target="#modalVideo"
                    data-video="{{ $video }}" src="/themes/{{ $theme }}/assets/img/play_1.png"
                    alt="video"
                    style="background-image: url({{ \Tools::url_img('lote_small', $lote_actual->num_hces1, $lote_actual->lin_hces1, 0) }}); background-size: cover;"></a>
            </a>
        @endforeach

        @foreach ($lote_actual->imagenes as $key => $imagen)
            <a class="ficha-thumnail" href="javascript:loadSeaDragon('{{ $imagen }}');">
                <div class="img-openDragon" alt="{{ $lote_actual->titulo_hces1 }}"
                    style="background-image:url('{{ \Tools::url_img('lote_small', $lote_actual->num_hces1, $lote_actual->lin_hces1, $key) }}'); background-size: contain; background-position: center; background-repeat: no-repeat;">
                </div>
            </a>
        @endforeach
    </div>

    <!-- Inicio Galeria Desktop -->
    <div class="row-down control" onClick="clickControl(this)">
        <i class="fa fa-chevron-down" aria-hidden="true"></i>
    </div>

    {{--  <script>
        if ($('.slider-thumnail')[0].scrollHeight > 486) {
            $('.control').show()
        }

		 /* if ($('.slider-thumnail')[0].scrollHeight > 485) {
        $('.control').show()
    } else {
        $('.control').hide()
    } */
    </script> --}}

</div>
