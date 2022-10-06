@if (empty($lots))
	<br><br>
	<center><big><big>{{ trans(\Config::get('app.theme').'-app.lot_list.no_results') }}</big></big></center>
@else
	@foreach ($lots as  $item)

		<?php
				#transformo el array en variables para conservar los nombres antiguos
				# si es necesario ampliar varibles se puede hacer en la funcion setVarsLot del lotlistcontroller, o si solo es para este cleinte ponerlas aquí.

				$lotsWithContentExtra = \App\Models\V5\FgHces1::select('ref_hces1')->where('sub_hces1', $item->sub_asigl0)->whereNotNull('contextra_hces1')->pluck('ref_hces1');


			foreach($item->bladeVars as $key => $value){
				${$key} = $value;
			}
				$class_square = 'col-xs-12 col-sm-6 col-lg-4';
				$contentExtra = $lotsWithContentExtra->contains($item->ref_asigl0);

				$titulo = $item->descweb_hces1;

			if(empty(\Config::get("app.paginacion_grid_lotes"))){
				$idlot="lot_".$item->sub_asigl0."_".$item->ref_asigl0;
				$codeScrollBack =" id=\"$idlot\" onclick=\"changeURL('$actualPage', '$idlot')\" ";
			}else{
				$codeScrollBack ="";
			}
		?>
		@include('includes.grid.lot')
	@endforeach
@endif
