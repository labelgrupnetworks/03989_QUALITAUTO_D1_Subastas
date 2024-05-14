@if (empty($lots))
<br><br>
<center><big><big>{{ trans($theme.'-app.lot_list.no_results') }}</big></big></center>
@else

<script src="/vendor/photoswipe/photoswipe.umd.min.js"></script>
<script src="/vendor/photoswipe/photoswipe-lightbox.umd.min.js"></script>
<link href="/vendor/photoswipe/photoswipe.css" rel="stylesheet">
@foreach ($lots as $item)
<?php
				#transformo el array en variables para conservar los nombres antiguos
				# si es necesario ampliar varibles se puede hacer en la funcion setVarsLot del lotlistcontroller, o si solo es para este cleinte ponerlas aquí.

			foreach($item->bladeVars as $key => $value){
				${$key} = $value;
			}

			#recalculamos las variables que no sirven

				//Si no esta retirado tendrá enlaces
			if(!$retirado  && !$devuelto){
				$url_friendly = \Tools::url_lot($item->cod_sub,$item->id_auc_sessions,$item->name,$item->ref_asigl0,$item->num_hces1,$item->webfriend_hces1,$item->titulo_hces1);
				$url = "href='$url_friendly'";
			}
				$titulo = $item->titulo_hces1;
				$class_square = 'col-xs-12 col-sm-6 col-lg-4';


			$subastaModel = new \App\Models\Subasta();
			$numLin = new \stdClass();
			$numLin->num_hces1 = $item->num_hces1;
			$numLin->lin_hces1 = $item->lin_hces1;
			$numFotos = count($subastaModel->getLoteImages($numLin));

			$rarity = $item->rarity;

			if(!Session::has('user') || empty($item->user_have_bid)){
				$winner = "gold";
			}elseif ( Session::get('user.cod') == $item->cli_win_bid ){
				$winner = "winner";

			}else{
				$winner = "no_winner";
			}

		?>
@include('includes.grid.lot')
@endforeach
@endif
