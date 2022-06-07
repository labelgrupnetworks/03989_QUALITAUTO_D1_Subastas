<div class="home-slider">
    <div class="container">
        <div class="row flex-display row-custom">
            @if(!Session::has('user'))
            <div class="col-xs-3 home-slider-control hidden-xs hidden-sm hidden-md">
                <div class="banner-register">
                    <div class="banner-register-title">{{ trans(\Config::get('app.theme').'-app.home.not_account') }}</div>
                    <div class="banner-register-sub-title hidden">{{ trans(\Config::get('app.theme').'-app.login_register.crear_cuenta') }}</div>
                    <div class="banner-register-btn text-center">
                            <a class="button-principal" title="{{ trans(\Config::get('app.theme').'-app.login_register.register') }}" href="{{ \Routing::slug('login') }}">{{ trans(\Config::get('app.theme').'-app.login_register.register') }}</a>
                    </div>
                    <div class="banner-register-hr">
                        <hr>
                    </div>
                    <div class="banner-register-title">{{ trans(\Config::get('app.theme').'-app.home.account') }}</div>
                     <div class="banner-register-btn text-center">
                            <a class="secondary-button user-account btn_login" href="javascript:;">{{ trans(\Config::get('app.theme').'-app.login_register.generic_name') }}</a>
                    </div>
                </div>
            </div>
            @endif
            <div class="col-xs-12 @if(!Session::has('user'))col-lg-9 home-slider-control @else col-xs-12 @endif">
                <div class="owl-carousel owl-theme" id="owl-carousel" style="display:none">

                    <?php
                       $key = "slider_home_".strtoupper(Config::get('app.locale'));   
                       $html = "<div><div class='home-slider-content'>
                        <a class='color-letter' style='    display: block;
                            height: 100%;
                            width: 100%;
                            text-align: center;' href='{url}'> {html}</a>
                           </div><img class='img-responsive' src='{img}' /></div> ";
                       $content = \Tools::slider($key, $html);
                   ?>
                    <?= $content ?>
   
                </div>
            </div>
        </div>
    </div>

    <div class="container" style="margin-top: 40px; margin-bottom: 40px;">
        <div class="row">
                <div class="col-xs-12  home-slider-control col-xs-12">
                        <div class="owl-carousel owl-theme" id="owl-carousel-extra" style="display:none">
        
                            <?php
                               $key = "slider_extra_home_".strtoupper(Config::get('app.locale'));   
                               $html = "<div><div class='home-slider-content'>
                                <a class='color-letter' style='    display: block;
                                    height: 100%;
                                    width: 100%;
                                    text-align: center;' href='{url}'> {html}</a>
                                   </div><img class='img-responsive' src='{img}' /></div> ";
                               $content = \Tools::slider($key, $html);
                           ?>
                            <?= $content ?>
                </div>
                        </div>
        </div>
    </div>
    <!-- Inicio lotes destacados -->
<div id="lotes_destacados-content" class="lotes_destacados secundary-color-text">
        <div class="container">
            <div class="row flex-display flex-wrap">
                <div class="col-xs-12 col-sm-12 col-md-12 lotes-destacados-principal-title">
                    <div class="lotes-destacados-tittle color-letter">                     
                        {{ trans(\Config::get('app.theme').'-app.lot_list.lotes_destacados') }}
                    </div>
                </div>
                <div class="col-xs-12 col-sm-10 col-md-12 text-center">
                    <div class="lds-ellipsis loader"><div></div><div></div><div></div><div></div></div>
                    <div class="owl-theme owl-carousel" id="lotes_destacados"></div>
                </div>
            </div> 
        </div>
    </div>





<section class="blog">
        <div class="container">
            <div class="row news-section">
                <div class="col-xs-12 calendar">
                    <h2 class="lotes-destacados-tittle color-letter">
                        {{ trans(\Config::get('app.theme').'-app.home.news') }}
                    </h2>
                    <div class="scroll-buttons hidden">    
                    <div class="calendar-up" role="button"><i class="fa fa-chevron-up"></i></div>
                    <div class="calendar-down" role="button"><i class="fa fa-chevron-down"></i></div>
                        
                    </div>
                        <div class="content_art">
        
                        <div class="content_art_container">
                       <?php
                            $slidder_obj = new \App\Models\Banners;
                            $key = "article_".strtoupper(Config::get('app.locale'));
                            $slidders = $slidder_obj->getBannerByKeyname($key,20);
                        ?>
                       @foreach($slidders as $article)
                           <?= $article->content ?>
                          <br>
                       @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>  
        </section>   
        
        <script>
            $(document).ready(function() {
                if($('.content_art_container').height() - $('.content_art').height() > 0){
                    $('.scroll-buttons').removeClass('hidden')
                }
                
            $('.calendar-down').click(function(){
                var scroll = $('.content_art_container').height() - $('.content_art').height()
               if($('.content_art').scrollTop() < scroll){
                   $('.content_art').animate({scrollTop: $('.content_art').scrollTop() + (($('.content_art_container').height() / $('.contact-misc').length) / 1.5)}, 500);
               } else{
                   $('.content_art').scrollTop(scroll)
               }
            })
            
            
                $('.calendar-up').click(function(){
                var scroll = $('.content_art_container').height() - $('.content_art').height()
               if($('.content_art').scrollTop() > 0){
                   $('.content_art').animate({scrollTop: $('.content_art').scrollTop() - (($('.content_art_container').height() / $('.contact-misc').length)/ 1.5)}, 500);
                    
                    
               } else{
                   $('.content_art').scrollTop(0)
               }
            })
            
            })
            
        
        </script>

<?php /*

<div class="video-explain video-buy" style="display: none">
    <div class="close-video" role="button">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 33.697 33.544">
            <defs>
              <style>
                .close-svg {
                  fill: #fff;
                }
              </style>
            </defs>
            <g id="cancel" transform="translate(0 -0.435)">
              <path id="Path_27" data-name="Path 27" class="close-svg" d="M18.993,17.284,33.238,3.039a1.481,1.481,0,0,0,0-2.144,1.481,1.481,0,0,0-2.144,0L16.849,15.139,2.6.894a1.481,1.481,0,0,0-2.144,0,1.481,1.481,0,0,0,0,2.144L14.7,17.284.459,31.528a1.481,1.481,0,0,0,0,2.144,1.842,1.842,0,0,0,1.225.306c.306,0,.919,0,.919-.306L16.848,19.428,31.093,33.673a1.842,1.842,0,0,0,1.225.306c.306,0,.919,0,.919-.306a1.481,1.481,0,0,0,0-2.144Z" transform="translate(0 0)"/>
            </g>
          </svg>
    </div>
        

</div>
<div class="how-to-buy color-letter">
    <?php
    $key = "how_to_buy_".strtoupper(Config::get('app.locale'));   
    $html = "{html}";
    $content = \Tools::slider($key, $html);
?>
 <?= $content ?>
</div>
<div class="how-to-buy color-letter">
    <?php
    $key = "calendario_home_".strtoupper(Config::get('app.locale'));   
    $html = "{html}";
    $content = \Tools::slider($key, $html);
?>
 <?= $content ?>
</div>

<?php /*
<div id="mas_altas-content" class="mas-pujados color-letter">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="mas-pujados-title"><span>{{ trans(\Config::get('app.theme').'-app.home.more_bids') }}</span></div>
            </div>
            <div class="col-xs-12 text-center">
                    <div class="lds-ellipsis loader"><div></div><div></div><div></div><div></div></div>
                    <div class="owl-theme owl-carousel carousel-extra" id="mas_altas"></div>
                </div>
        </div>
    </div>
</div>
*/
?>
<script>
    <?php
        $key = "lotes_destacados";
        //$keyExtra = "mas_altas";
        $replace = array(
              'lang' => Config::get('app.language_complete')[Config::get('app.locale')] ,'emp' => Config::get('app.emp') ,
                  );
    ?>
    var replace = <?= json_encode($replace) ?>;
    var key ="<?= $key ?>";
   var keyExtra ="<?= 0//$keyExtra ?>";
    $( document ).ready(function() {
            ajax_carousel(key,replace);
           // ajax_carousel(keyExtra,replace);
            
     });

     $('.close-video').click(function() {
         $('.video-explain').fadeOut()
     })

    //  $('.item-play').click(function(){
    //     $('.video-explain.video-'+ $(this).attr('id')).fadeIn()
    //  })
</script>






