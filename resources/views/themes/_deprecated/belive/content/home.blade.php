
<?php
$currency = new Currency();      
$divisas = $currency->getAllCurrencies();
?>

{!! \BannerLib::bannersPorKey('HOME_SUPERIOR', 'banner_home') !!}
<center><h2>{{ trans(\Config::get('app.theme').'-app.lot_list.lotes_destacados') }}</h2></center>



	<!-- Inicio lotes destacados -->
	<div id="lotes_destacados-content" class="lotes_destacados secundary-color-text">
		<div class="container">
			<div class="row">
				<div class="col-xs-12 text-right">
					<div class="col-xs-12">
						<select id="actual_currency" >
					      @foreach($divisas as $divisa)
					          @if($divisa->cod_div != 'EUR')
					              <option value='{{ $divisa->cod_div }}' <?= ($divisa->cod_div == 'USD')? 'selected="selected"' : '' ?>>
					                   {{ $divisa->cod_div }}
					              </option>
					          @endif
					      @endforeach
						</select>
					</div>
				</div>
			</div>
			<div class="row min-height flex-display flex-wrap">
				<div class="col-xs-12 col-xs-12 col-sm-12 text-center">
					<div class="lds-ellipsis loader"><div></div><div></div><div></div><div></div></div>
					<div class="owl-theme owl-carousel" id="lotes_destacados"></div>
				</div>
			</div>
		</div>
	</div>


<script>

	<?php
		$key = "lotes_destacados";
		$replace = array(
			  'lang' => \Tools::getLanguageComplete(Config::get('app.locale')) ,'emp' => Config::get('app.emp') ,
				  );
	?>
	var replace = <?= json_encode($replace) ?>;
	var key ="<?= $key ?>";
   	var keyExtra ="<?= 0//$keyExtra ?>";
	$( document ).ready(function() {
			ajax_carousel(key,replace);

	});

</script>
