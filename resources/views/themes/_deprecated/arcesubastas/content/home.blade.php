
{!! \BannerLib::bannersPorKey('slider_home', 'slider_home') !!}
<!-- Fin slider -->

<!-- Inicio lotes destacados -->
<div class="lotes_destacados">
	<div class="container">
		<div class="title_lotes_destacados">
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



