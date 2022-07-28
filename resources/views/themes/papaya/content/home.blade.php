

{{-- <div class="home-slider">
    <div class="container-fluid">
        <div class="row flex-display row-custom">
            @if(!Session::has('user'))
            <div class="col-xs-3 home-slider-control hidden-xs hidden-sm hidden-md">
                <div class="banner-register d-flex flex-column justify-content-space-bettween">
                    <div class = "d-flex flex-column justify-content-space-bettween">
                        <div class="banner-register-title">{{ trans(\Config::get('app.theme').'-app.home.not_account') }}</div>

                        <div class="banner-register-btn text-center">
                            <a class="button-principal" title="{{ trans(\Config::get('app.theme').'-app.login_register.registration') }}" href="{{ \Routing::slug('register') }}">{{ trans(\Config::get('app.theme').'-app.login_register.registration') }}</a>
                        </div>
                    </div>

                    <div class="banner-register-line"></div>

                    <div class = "d-flex flex-column justify-content-space-bettween">
                        <div class="banner-register-title">{{ trans(\Config::get('app.theme').'-app.home.account') }}</div>
                        <div class="banner-register-btn text-center">
                            <a class="secondary-button user-account btn_login" href="javascript:;">{{ trans(\Config::get('app.theme').'-app.login_register.generic_name') }}</a>
                        </div>
                    </div>

                </div>
            </div>
            @endif
            <div class="col-xs-12 p-0 @if(!Session::has('user'))col-lg-9 home-slider-control @endif">
				{!! \BannerLib::bannersPorKey('papayaHome', 'home-top-banner', ['dots' => true, 'autoplay' => false, 'autoplaySpeed' => 5000, 'slidesToScroll' => 1, 'arrows' => false]) !!}
            </div>
        </div>
    </div>
</div> --}}

<div class="home-slider">
    <div class="container-fluid">
        <div class="row flex-display row-custom">

			<div class="col-xs-12 col-lg-3">

				<div class="row h-100 d-flex flex-direction-column">
					<div class="col-xs-12" style="flex: 1">
						{!! \BannerLib::bannersPorKey('home_left_mini', 'home-top-banner', ['dots' => false, 'autoplay' => false, 'autoplaySpeed' => 5000, 'slidesToScroll' => 1, 'arrows' => false]) !!}
					</div>
					<div class="col-xs-12">
						@if(!Session::has('user'))
						<div class="home-slider-control hidden-xs hidden-sm hidden-md">
							<div class="banner-register d-flex flex-column justify-content-space-bettween">
								<div class = "d-flex flex-column justify-content-space-bettween">
									<div class="banner-register-title">{{ trans(\Config::get('app.theme').'-app.home.not_account') }}</div>

									<div class="banner-register-btn text-center">
										<a class="button-principal" title="{{ trans(\Config::get('app.theme').'-app.login_register.registration') }}" href="{{ \Routing::slug('register') }}">{{ trans(\Config::get('app.theme').'-app.login_register.registration') }}</a>
									</div>
								</div>

								<div class="banner-register-line"></div>

								<div class = "d-flex flex-column justify-content-space-bettween">
									<div class="banner-register-title">{{ trans(\Config::get('app.theme').'-app.home.account') }}</div>
									<div class="banner-register-btn text-center">
										<a class="secondary-button user-account btn_login" href="javascript:;">{{ trans(\Config::get('app.theme').'-app.login_register.generic_name') }}</a>
									</div>
								</div>

							</div>
						</div>
						@endif
					</div>
				</div>

			</div>

			<div class="col-xs-12 col-lg-9 home-slider-control p-0">
				{!! \BannerLib::bannersPorKey('papayaHome', 'home-top-banner', ['dots' => true, 'autoplay' => true, 'autoplaySpeed' => 5000, 'slidesToScroll' => 1, 'arrows' => false]) !!}
            </div>

        </div>
    </div>
</div>

<div class="recomendados mb-3">
        <div class="bar top-bar-medium">
            <div div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="tabs-custom">
                            <ul class="nav nav-tabs" role="tablist">
                                <?php
                                $key = "lotes_destacados";
                                $replace = array(
                                    'lang' => Config::get('app.language_complete')['' . Config::get('app.locale') . ''], 'emp' => Config::get('app.emp'),
                                );
                                ?>
                                <li role="presentation" class="active lotes_destacados"><a href="#home" aria-controls="home" role="tab" data-toggle="tab" onclick='ajax_carousel("<?= $key ?>",<?= json_encode($replace) ?>)' >{{ trans(\Config::get('app.theme').'-app.home.featured_lots') }}</a></li>
                                <?php
                                $key = "last_bids";
                                $replace = array(
                                    'lang' => Config::get('app.language_complete')['' . Config::get('app.locale') . ''], 'emp' => Config::get('app.emp'),
                                );
                                ?>
                                <!--!empty($has_subasta) && $session_end_sub -->
                                <li role="presentation" class="last_bids" ><a class="selector" href="#profile" aria-controls="profile" role="tab" data-toggle="tab" onclick='ajax_carousel("<?= $key ?>",<?= json_encode($replace) ?>)' >{{ trans(\Config::get('app.theme').'-app.home.last_bids') }}</a></li>
                                <?php
                                $key = "mas_pujado";
                                $replace = array(
                                    'lang' => Config::get('app.language_complete')['' . Config::get('app.locale') . ''], 'emp' => Config::get('app.emp'),
                                );
                                ?>
                                <!--!empty($has_subasta) && $session_end_sub -->
                                <li role="presentation" onclick=""><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab" onclick='ajax_carousel("<?= $key ?>",<?= json_encode($replace) ?>)'>{{ trans(\Config::get('app.theme').'-app.home.most_bidded') }}</a></li>
                                <?php
                                $key = "mas_altas";
                                $replace = array(
                                    'lang' => Config::get('app.language_complete')['' . Config::get('app.locale') . ''], 'emp' => Config::get('app.emp'),
                                );
                                ?>
                                <!--!empty($has_subasta) && $session_end_sub -->
								<li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab"onclick='ajax_carousel("<?= $key ?>",<?= json_encode($replace) ?>)'>{{ trans(\Config::get('app.theme').'-app.home.highest_bids') }}</a></li>
								<?php
								$key = "cerrados";
                                $replace = array('lang' => Config::get('app.language_complete')['' . Config::get('app.locale') . ''], 'emp' => Config::get('app.emp'),);
                                ?>
                                <!--!empty($has_subasta) && $session_end_sub -->
                                <li role="presentation"><a href="#closed_lots" aria-controls="closed_lots" role="tab" data-toggle="tab"onclick='ajax_carousel("<?= $key ?>",<?= json_encode($replace) ?>)'>{{ trans(\Config::get('app.theme').'-app.home.closed_lots') }}</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-sm-offset-1 col-md-12  lot-list-recomend tab-content">
                    <div role="tabpanel" class="tab-pane fade in active" id="home">
                        <div class='loader hidden firts-loader'></div>
                        <div id="lotes_destacados" ></div>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="profile">
                        <div class='loader hidden'></div>
                        <div id="last_bids"  ></div>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="messages">
                        <div class='loader hidden'></div>
                        <div id="mas_pujado" ></div>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="settings">
                        <div class='loader hidden'></div>
                        <div id="mas_altas" ></div>
					</div>
					<div role="tabpanel" class="tab-pane fade" id="closed_lots">
                        <div class='loader hidden'></div>
                        <div id="cerrados" ></div>
                    </div>
                    <div class="owl-theme owl-carousel owl-loaded owl-drag m-0 pl-10" id="navs-arrows">
                        <div class="owl-nav"><div class="owl-prev"><i class="fas fa-chevron-left"></i></div><div class="owl-next"><i class="fas fa-chevron-right"></i></div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class='medio-banner-container'>
        {!! \BannerLib::bannersPorKey('banner_home_2', 'banner_home') !!}
    </div>

    <section class="blog-home">
        <div class="container position-relative">

            <div class="row">
                <div class="col-xs-12">
                    <div class="blog-home-title">
                        {{ trans(\Config::get('app.theme').'-app.home.blog-title') }}
                    </div>
                </div>
            </div>

        </div>
        <div class=" p-0 position-relative content_art_container container" style="padding:0">
            <div>
                {!! \BannerLib::bannersPorUbicacion('NOTICIAS_HOME', 'noticia_content') !!}
            </div>

        </div>
        <div class="container position-relative">
            <div class="scroll-buttons hidden">
                <div class="calendar-up d-flex alig-items-center justify-content-center" role="button"><i class="fa fa-chevron-up"></i></div>
                <div class="calendar-down d-flex alig-items-center justify-content-center" role="button"><i class="fa fa-chevron-down"></i></div>

            </div>
        </div>

    </section>
    <section class="banner.inferior">
        {!! \BannerLib::bannersPorKey('banner_home_3', 'banner_home') !!}
    </section>

    <script>
        var scrolled = 0
        $(document).ready(function () {
            if ($('.content_art_container .noticia_content').length > 3) {
                $('.scroll-buttons').removeClass('hidden')
            }

            $('.calendar-down').click(function () {
                console.log(scrolled)
                scrolled = scrolled + 100

                if (scrolled < $('.content_art_container').height()) {


                    $('.content_art_container').animate({scrollTop: scrolled}, 500);
                } else {
                    scrolled = $('.content_art_container').height()
                    $('.content_art_container').animate({scrollTop: scrolled}, 500);
                }
            })


            $('.calendar-up').click(function () {
                scrolled = scrolled - 100
                if ($('.content_art_container').scrollTop() > 0) {
                    $('.content_art_container').animate({scrollTop: scrolled}, 500);
                } else {

                    scrolled = 0
                    $('.content_art').scrollTop(scrolled)
                }
            })


            menuItems = $('.nav-item');
            menuItems.each(function () {
                if (this.innerHTML == "{!! trans(\Config::get('app.theme').'-app.home.home')!!}") {
                    $(this).addClass('color-brand');
                }
            })
        })
    </script>


	@php
		$replace = array('lang' => \Tools::getLanguageComplete(Config::get('app.locale')), 'emp' => Config::get('app.emp'));
	@endphp
    <script>
        var replace = @json($replace);
        var key = "lotes_destacados";

        $(document).ready(function () {
            ajax_carousel(key, replace);
        });

        $('.close-video').click(function () {
            $('.video-explain').fadeOut()
        })

        //  $('.item-play').click(function(){
        //     $('.video-explain.video-'+ $(this).attr('id')).fadeIn()
        //  })
    </script>






