



	{{-- Banner superior de la home --}}
{{--
    <div class="home-banner mt-2 mb-2">
        {!! \BannerLib::bannersPorKey('HOME-TOP', 'HOME-TOP', ['arrows' => false, 'dots' => false, 'infinite' => true, 'autoplay' => true, 'autoplaySpeed' => 3000 ]) !!}
    </div>
--}}
<div class="video-banner mt-4 mb-2">
	{!! \BannerLib::bannersPorKey('lluis', 'lluis_banner', ['arrows' => false, 'dots' => false, 'infinite' => true, 'autoplay' => true, 'autoplaySpeed' => 3000 ]) !!}
</div>
<div class="home-banner mt-4 mb-2">
	<div class="container"><div class="row rowBanner"><div class="column_banner col-xs-12 col-md-1"></div><div class="column_banner col-xs-12 col-md-2"><div id="banner72" class="HOME-TOP slick-initialized slick-slider"><div class="slick-list draggable"><div class="slick-track" style="opacity: 1; width: 165px; transform: translate3d(0px, 0px, 0px);"><div class="item item_imagen pos_item_0 hidden-xs slick-slide slick-current slick-active" data-slick-index="0" aria-hidden="false" style="width: 165px;" tabindex="0"><img src="http://www.newsubastas.test/img/banner/ferreries/003/2/34/ES.jpg?a=1664358944" width="100%"></div></div></div></div></div><script>$('#banner72').slick({"arrows":false,"dots":false,"infinite":true,"autoplay":true,"autoplaySpeed":3000});</script><div class="column_banner col-xs-12 col-md-2"><div id="banner791" class="HOME-TOP slick-initialized slick-slider"><div class="slick-list draggable"><div class="slick-track" style="opacity: 1; width: 165px; transform: translate3d(0px, 0px, 0px);"><div class="item item_imagen pos_item_0 hidden-xs slick-slide slick-current slick-active" data-slick-index="0" aria-hidden="false" style="width: 165px;" tabindex="0"><img src="http://www.newsubastas.test/img/banner/ferreries/003/2/35/ES.jpg?a=1664359030" width="100%"></div></div></div></div></div><script>$('#banner791').slick({"arrows":false,"dots":false,"infinite":true,"autoplay":true,"autoplaySpeed":3000});</script><div class="column_banner col-xs-12 col-md-2"><div id="banner504" class="HOME-TOP slick-initialized slick-slider"><div class="slick-list draggable"><div class="slick-track" style="opacity: 1; width: 165px; transform: translate3d(0px, 0px, 0px);"><div class="item item_imagen pos_item_0 hidden-xs slick-slide slick-current slick-active" data-slick-index="0" aria-hidden="false" style="width: 165px;" tabindex="0"><img src="http://www.newsubastas.test/img/banner/ferreries/003/2/36/ES.jpg?a=1664364549" width="100%"></div></div></div></div></div><script>$('#banner504').slick({"arrows":false,"dots":false,"infinite":true,"autoplay":true,"autoplaySpeed":3000});</script><div class="column_banner col-xs-12 col-md-2"><div id="banner624" class="HOME-TOP slick-initialized slick-slider"><div class="slick-list draggable"><div class="slick-track" style="opacity: 1; width: 165px; transform: translate3d(0px, 0px, 0px);"><div class="item item_imagen pos_item_0 hidden-xs slick-slide slick-current slick-active" data-slick-index="0" aria-hidden="false" style="width: 165px;" tabindex="0"><img src="http://www.newsubastas.test/img/banner/ferreries/003/2/39/ES.jpg?a=1664359379" width="100%"></div></div></div></div></div><script>$('#banner624').slick({"arrows":false,"dots":false,"infinite":true,"autoplay":true,"autoplaySpeed":3000});</script><div class="column_banner col-xs-12 col-md-2"><div id="banner238" class="HOME-TOP slick-initialized slick-slider"><div class="slick-list draggable"><div class="slick-track" style="opacity: 1; width: 165px; transform: translate3d(0px, 0px, 0px);"><div class="item item_imagen pos_item_0 hidden-xs slick-slide slick-current slick-active" data-slick-index="0" aria-hidden="false" style="width: 165px;" tabindex="0"><img src="http://www.newsubastas.test/img/banner/ferreries/003/2/38/ES.jpg?a=1664359119" width="100%"></div></div></div></div></div></div></div>
</div>







	<div class="video-banner mt-4 mb-4">
        {!! \BannerLib::bannersPorKey('PDF_CAST_ING', 'pdf_banner', ['arrows' => false, 'dots' => false, 'infinite' => true, 'autoplay' => true, 'autoplaySpeed' => 3000 ]) !!}
    </div>



<div class="video-banner mt-4 mb-4">
        {!! \BannerLib::bannersPorKey('Videos', 'video_banner', ['arrows' => false, 'dots' => false, 'infinite' => true, 'autoplay' => true, 'autoplaySpeed' => 3000 ]) !!}
    </div>











</div>

















































<script>
    <?php
    $key = 'lotes_destacados';

    $replace = [
        'lang' => \Tools::getLanguageComplete(Config::get('app.locale')),
        'emp' => Config::get('app.emp'),
    ];
    ?>
    var replace = <?= json_encode($replace) ?>;
    var key = "<?= $key ?>";

    $(document).ready(function() {
        ajax_carousel(key, replace);


    });
</script>
