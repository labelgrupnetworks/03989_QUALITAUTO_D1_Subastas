



	{{-- Banner superior de la home --}}
{{--
    <div class="home-banner mt-2 mb-2">
        {!! \BannerLib::bannersPorKey('HOME-TOP', 'HOME-TOP', ['arrows' => false, 'dots' => false, 'infinite' => true, 'autoplay' => true, 'autoplaySpeed' => 3000 ]) !!}
    </div>
--}}


@if(strtotime("now") > strtotime("03-11-2022 19:00:00"))
<div class="container">
	<div class="col-xs-12 no-padding">
        <div >
            <a  class="home-live-btn-link " href="/es/api/subasta/LLUIS-subasta-solidaria-2822">
                <div class="bid-online"></div>
                <div class="bid-online animationPulseRed"></div>
               14/11/2022 20:00h  Pujar en vivo
			</a>
        </div>
    </div>
</div>
@endif
<div class="container">
	<div class="xs-col-12 mt-2">
		<video  width="100%" height="100%" controls  title="home">
			<source src="/themes/ferreries/assets/img/video.mp4" type="video/mp4">
		</video>
	</div>
</div>
{{--
<div class="video-banner mt-4 mb-2">
	{!! \BannerLib::bannersPorKey('lluis', 'lluis_banner', ['arrows' => false, 'dots' => false, 'infinite' => true, 'autoplay' => true, 'autoplaySpeed' => 3000 ]) !!}
</div>
--}}
<div class="home-banner mt-4 mb-4">
	<div class="container"><div class="row rowBanner"><div class="column_banner col-xs-12 col-md-1"></div><div class="column_banner col-xs-12 col-md-2"><div id="banner72" class="HOME-TOP slick-initialized slick-slider"><div class="slick-list draggable"><div class="slick-track" style="opacity: 1; width: 165px; transform: translate3d(0px, 0px, 0px);"><div class="item item_imagen pos_item_0 hidden-xs slick-slide slick-current slick-active" data-slick-index="0" aria-hidden="false" style="width: 165px;" tabindex="0"><img src="/img/banner/ferreries/003/2/34/ES.jpg?a=1664358944" width="100%"></div></div></div></div></div><script>$('#banner72').slick({"arrows":false,"dots":false,"infinite":true,"autoplay":true,"autoplaySpeed":3000});</script><div class="column_banner col-xs-12 col-md-2"><div id="banner791" class="HOME-TOP slick-initialized slick-slider"><div class="slick-list draggable"><div class="slick-track" style="opacity: 1; width: 165px; transform: translate3d(0px, 0px, 0px);"><div class="item item_imagen pos_item_0 hidden-xs slick-slide slick-current slick-active" data-slick-index="0" aria-hidden="false" style="width: 165px;" tabindex="0"><img src="/img/banner/ferreries/003/2/35/ES.jpg?a=1664359030" width="100%"></div></div></div></div></div><script>$('#banner791').slick({"arrows":false,"dots":false,"infinite":true,"autoplay":true,"autoplaySpeed":3000});</script><div class="column_banner col-xs-12 col-md-2"><div id="banner504" class="HOME-TOP slick-initialized slick-slider"><div class="slick-list draggable"><div class="slick-track" style="opacity: 1; width: 165px; transform: translate3d(0px, 0px, 0px);"><div class="item item_imagen pos_item_0 hidden-xs slick-slide slick-current slick-active" data-slick-index="0" aria-hidden="false" style="width: 165px;" tabindex="0"><img src="/img/banner/ferreries/003/2/36/ES.jpg?a=1664364549" width="100%"></div></div></div></div></div><script>$('#banner504').slick({"arrows":false,"dots":false,"infinite":true,"autoplay":true,"autoplaySpeed":3000});</script><div class="column_banner col-xs-12 col-md-2"><div id="banner624" class="HOME-TOP slick-initialized slick-slider"><div class="slick-list draggable"><div class="slick-track" style="opacity: 1; width: 165px; transform: translate3d(0px, 0px, 0px);"><div class="item item_imagen pos_item_0 hidden-xs slick-slide slick-current slick-active" data-slick-index="0" aria-hidden="false" style="width: 165px;" tabindex="0"><img src="/img/banner/ferreries/003/2/39/ES.jpg?a=1664359379" width="100%"></div></div></div></div></div><script>$('#banner624').slick({"arrows":false,"dots":false,"infinite":true,"autoplay":true,"autoplaySpeed":3000});</script><div class="column_banner col-xs-12 col-md-2"><div id="banner238" class="HOME-TOP slick-initialized slick-slider"><div class="slick-list draggable"><div class="slick-track" style="opacity: 1; width: 165px; transform: translate3d(0px, 0px, 0px);"><div class="item item_imagen pos_item_0 hidden-xs slick-slide slick-current slick-active" data-slick-index="0" aria-hidden="false" style="width: 165px;" tabindex="0"><img src="/img/banner/ferreries/003/2/38/ES.jpg?a=1664359119" width="100%"></div></div></div></div></div></div></div>
</div>


<div class="video-banner ">
	{!! \BannerLib::bannersPorKey('COLABORADORES', 'COLABORADORES', ['arrows' => false, 'dots' => false, 'infinite' => true, 'autoplay' => true, 'autoplaySpeed' => 3000 ]) !!}
</div>

<div class="container" style="font-size: 22px;font-weight: bold;">
	<div class="xs-col-12 col-md-6 text-center">
		Descripción proyecto
	</div>
	<div class="xs-col-12 col-md-6 text-center">
		Project description
	</div>
</div>

	<div class="video-banner  mb-4">
        {!! \BannerLib::bannersPorKey('PDF_CAST_ING', 'pdf_banner', ['arrows' => false, 'dots' => false, 'infinite' => true, 'autoplay' => true, 'autoplaySpeed' => 3000 ]) !!}
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
