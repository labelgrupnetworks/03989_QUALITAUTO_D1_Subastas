<div class="tri-slider">
    <div class="container">
        <div class="row">
            <div class="col-md-8 slider-home slider-one">

                {!! \BannerLib::bannersPorKey('HOME_IZQUIERDA', 'banner_home') !!}
            </div>
            <div class="col-md-4 col-xs-12 mini-slider">
                <div class="slider-home slider-two col-xs-6 col-md-12">

                    {!! \BannerLib::bannersPorKey('HOME_DERECHA_1', 'banner_home2') !!}
                </div>

                <div class="slider-home slider-three col-xs-6 col-md-12">

                    {!! \BannerLib::bannersPorKey('HOME_DERECHA_2', 'banner_home3') !!}
                </div>
            </div>
        </div>
    </div>
</div>

    <div id="carousel" class="hide">
    <div class="owl-carousel-home owl-carousel owl-theme">
        <?php
            $key = "Slider_home_".strtoupper(Config::get('app.locale'));
            $html="<div class='item'>
                <div class='slide-carousel' style='background-image:url(\"{img}\")'>
                <div class='content-slide'>
                        {html}
                        <a href=\"{url}\" {target} class='btn btn-white-img' {hidden}>".trans(\Config::get('app.theme').'-app.home.see')."<i class='fa fa-chevron-circle-right'></i></a>

                    </div></div></div>";
            $content = \Tools::slider($key, $html);
        ?>
        <?= $content ?>


    </div>
</div>
<?php

    $subastaObj        = new \App\Models\Subasta();
    $session_end_sub = false;
    $has_subasta = $subastaObj->auctionList ('S', 'W');
    if( empty($has_subasta) && Session::get('user.admin')){
        $has_subasta= array_merge($has_subasta,$subastaObj->auctionList ('A', 'W'));
    }
    foreach($has_subasta as $sub_ses){
        if(strtotime($sub_ses->session_end) > time()){
            $session_end_sub = true;
        }
    }

?>
    <div class="recomendados">
        <div class="bar top-bar-medium">
            <div div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="tabs-custom">
                            <ul class="nav nav-tabs" role="tablist">
                                <?php
                                        $key = "lotes_destacados";
                                        $replace = array(
                                        'lang' => Config::get('app.language_complete')[''.Config::get('app.locale').''] , 'emp' => Config::get('app.emp') ,
                                        );
                                    ?>
                                <li role="presentation" class="active lotes_destacados"><a href="#home" aria-controls="home" role="tab" data-toggle="tab" onclick='ajax_carousel("<?= $key ?>",<?= json_encode($replace) ?>)' >{{ trans(\Config::get('app.theme').'-app.home.finalizando') }}</a></li>
                                    <?php
                                        $key = "last_bids";
                                        $replace = array(
                                        'lang' => Config::get('app.language_complete')[''.Config::get('app.locale').''] , 'emp' => Config::get('app.emp') ,
                                        );
                                    ?>
                                @if(!empty($has_subasta) && $session_end_sub)
                                    <li role="presentation" class="last_bids" ><a class="selector" href="#profile" aria-controls="profile" role="tab" data-toggle="tab" onclick='ajax_carousel("<?= $key ?>",<?= json_encode($replace) ?>)' >{{ trans(\Config::get('app.theme').'-app.home.last_bids') }}</a></li>
                                @endif
                                <?php
                                    $key = "mas_pujado";
                                    $replace = array(
                                    'lang' => Config::get('app.language_complete')[''.Config::get('app.locale').''] ,'emp' => Config::get('app.emp') ,
                                    );
                                ?>
                                @if(!empty($has_subasta) && $session_end_sub)
                                    <li role="presentation" onclick=""><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab" onclick='ajax_carousel("<?= $key ?>",<?= json_encode($replace) ?>)'>{{ trans(\Config::get('app.theme').'-app.home.mas_pujado') }}</a></li>
                                @endif
                                    <?php
                                        $key = "mas_altas";
                                        $replace = array(
                                        'lang' => Config::get('app.language_complete')[''.Config::get('app.locale').''] ,'emp' => Config::get('app.emp') ,
                                        );
                                    ?>
                                @if(!empty($has_subasta) && $session_end_sub)
                                    <li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab"onclick='ajax_carousel("<?= $key ?>",<?= json_encode($replace) ?>)'>{{ trans(\Config::get('app.theme').'-app.home.mas_altas') }}</a></li>
                                @endif
                                 <?php
                                        $key = "lotes_360";
                                        $replace = array(
                                        'lang' => Config::get('app.language_complete')[''.Config::get('app.locale').''] ,'emp' => Config::get('app.emp') ,
                                        );
                                    ?>
                                @if(!empty($has_subasta) && $session_end_sub)
                                    <li role="presentation">
                                        <a href="#pic360" aria-controls="pic360" role="tab" data-toggle="tab"onclick='ajax_carousel("<?= $key ?>",<?= json_encode($replace) ?>)'>{{ trans(\Config::get('app.theme').'-app.home.pic360') }}</a>
                                    </li>
                                @endif

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 lot-list-recomend tab-content">
                    <div role="tabpanel" class="tab-pane fade in active" id="home">
                        <div class='loader hidden firts-loader'></div>
                        <div id="lotes_destacados" class="owl-carousel owl-carousel owl-theme owl-controls"></div>

                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="profile">
                        <div class='loader hidden'></div>
                        <div id="last_bids" class="owl-carousel owl-carousel owl-theme owl-controls" ></div>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="messages">
                        <div class='loader hidden'></div>
                        <div id="mas_pujado" class="owl-carousel owl-carousel owl-theme owl-controls" ></div>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="settings">
                        <div class='loader hidden'></div>
                        <div id="mas_altas" class="owl-carousel owl-carousel owl-theme owl-controls" ></div>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="pic360">
                        <div class='loader hidden'></div>
                        <div id="lotes_360" class="owl-carousel owl-carousel owl-theme owl-controls" ></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<section class="how">
    <div class="container">
        <div class="row">
            <?php
            $key = "banner_home_".strtoupper(Config::get('app.locale'));
            $html="<div class='col-xs-12 col-sm-4 col-about-us text-center'>
                    <a title='{html}' href='{url}'><div class='about-us-content' style='background: url({img});background-size: cover;background-position: center;'><p>{html}</p></div></a>
                    </div>";
            $content = \Tools::slider($key, $html);
            ?>
            <?= $content ?>
        </div>
    </div>

    <section class="seo_home">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                 <?php
            $key = "banner_seo_".strtoupper(Config::get('app.locale'));
            $html="<div class='banner_seo_content'>
                    {html}
                    </div>";
            $content = \Tools::slider($key, $html);
            ?>
            <?= $content ?>
            </div>
        </div>
    </div>
</section>

@if(config('app.google_review', 0))
	@include('includes.google_reviews')
@endif

</section>


        <section id="contact-home">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="col-xs-12 col-lg-6 col-lg-offset-3 text-center">
                            <div class="title-contact">
                                <div class="h1_seo">{{trans(\Config::get('app.theme').'-app.home.have_questions')}}</div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-8 col-md-offset-2 text-center">
                            <form action="/api-ajax/mail" method="post">

                                <div class="input-group">
                                    <input type="text" placeholder="<?= trans(\Config::get('app.theme').'-app.login_register.nombre_apellido') ?>" name="name" required>
                                    <input type="email" placeholder="{{trans(\Config::get('app.theme').'-app.home.have_questions_email')}}" name="email" required>
                                    <input type="text" placeholder="{{trans(\Config::get('app.theme').'-app.home.have_questions_telf')}}" name="telf" required>

                                </div>

                                <div class="text-area">
                                    <textarea placeholder="{{trans(\Config::get('app.theme').'-app.home.have_questions_coment')}}" required name="comentario"></textarea>
                                </div>
                                <div class="checkbox" style="text-align: left;">
                                    <label>
                                        <input name="condiciones" required="" style="opacity: 1" type="checkbox"><?= trans(\Config::get('app.theme').'-app.home.teminos_contacto') ?>
                                    </label>
                                </div>
                                <div id="html_element" style="position:absolute"></div>
                                <div class="send-button">
                                    <button id="buttonSend" class="btn btn-color" disabled>{{trans(\Config::get('app.theme').'-app.home.have_questions_send')}} <i class="fa fa-paper-plane"></i></button>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </section>



        <script>
<?php

    $key = "lotes_destacados";
    $replace = array(
          'lang' => Config::get('app.language_complete')[Config::get('app.locale')] ,'emp' => Config::get('app.emp') ,
              );


            $keydate = "banner_calendar_".strtoupper(Config::get('app.locale'));
            $html="";
            $content = \Tools::slider($keydate, $html);
            echo $content;
?>


var replace = <?= json_encode($replace) ?>;
var key ="<?= $key ?>";
$( document ).ready(function() {
    $('.developedBy').removeClass('hidden')


        //Calendario
        //Variable dates se obtiene desde al blade de slider




    ajax_carousel(key,replace);


    $('#calendarClose').click(function () {
        $('#calendar-screen').hide()
    })

});






</script>




    <script type="text/javascript">

    var verifyCallback = function(response) {
        $('#buttonSend').attr('disabled', false)
      };

      var onloadCallback = function() {
        grecaptcha.render('html_element', {
          'sitekey' : '6Lf-2FAUAAAAAFM3N6PiPGXmi40jkLIQtKVoG6qK',
          'callback' : verifyCallback,
          'theme' : 'light'
        });
      };
    </script>

<script type="text/javascript">

	let slicksDots = document.querySelectorAll('.slick-dots');

	for (let index = 0; index < slicksDots.length; index++) {
		let parent = slicksDots[index].parentElement.querySelector('.slick-list');
		parent.appendChild(slicksDots[index]);
	}


</script>
