{!! \BannerLib::bannersPorKey('HOME_SUPERIOR', 'banner_home') !!}
{!! \BannerLib::bannersPorKey('BANNER_HOME_TRIPLE', 'banner_home') !!}
{!! \BannerLib::bannersPorKey('BANNER_HOME_TRIPLE_2', 'banner_home') !!}

<p class="text-center" style="color:#fff; cursor:context-menu;">Subastas de monedas, billetes, sellos, libros antiguos y coleccionismo en Barcelona. Expertos en numismática, filatelia, libros antiguos y coleccionismo. Valoraciones y tasaciones gratuitas.</p>

<!-- Inicio lotes destacados -->
<div class="lotes_destacados">
    <div class="container">
            <div class="title_lotes_destacados principal-color">
                     {{ trans(\Config::get('app.theme').'-app.lot_list.lotes_destacados') }}
            </div>
            <div class="loader"></div>
            <div class="owl-theme owl-carousel" id="lotes_destacados"></div>
    </div>
</div>

<script>
    <?php
        $key = "lotes_destacados";
        $replace = array(
              'lang' => Config::get('app.language_complete')[Config::get('app.locale')] ,'emp' => Config::get('app.emp') ,
                  );
    ?>
    var replace = <?= json_encode($replace) ?>;
    var key ="<?= $key ?>";
    $( document ).ready(function() {
            ajax_carousel(key,replace);
     });
</script>



<div class="container">
    <div class="row news-section">
        <div class="col-xs-12 col-sm-6">
            @include('includes.newsletter')
        </div>
        <div class="col-xs-12 col-sm-6 calendar">
            <h2 style="margin-top: 0">{{ trans(\Config::get('app.theme').'-app.home.calendar-news') }}</h2>
            <div class="content_art">
               <?php
                    $slidder_obj = new \App\Models\Banners;
                    $key = "article_".strtoupper(Config::get('app.locale'));
                    $slidders = $slidder_obj->getBannerByKeyname($key);
                ?>
               @foreach($slidders as $article)
                   <?= $article->content ?>
                  <br>
               @endforeach
            </div>
        </div>
    </div>
</div>



<!-- Fin slider -->




