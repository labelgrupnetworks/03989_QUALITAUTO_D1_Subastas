@if (empty($lots))
	<br><br>
	<center><big><big>{{ trans(\Config::get('app.theme').'-app.lot_list.no_results') }}</big></big></center>
@else
	@foreach ($lots as  $item)
		<?php
				#transformo el array en variables para conservar los nombres antiguos
				# si es necesario ampliar varibles se puede hacer en la funcion setVarsLot del lotlistcontroller, o si solo es para este cleinte ponerlas aquí.

			foreach($item->bladeVars as $key => $value){
				${$key} = $value;
			}
			$titulo=$item->descweb_hces1;

			$class_square = 'col-xs-12 col-sm-6 col-md-4 col-lg-3';

			if(empty(\Config::get("app.paginacion_grid_lotes"))){
				$idlot="lot_".$item->sub_asigl0."_".$item->ref_asigl0;
				$codeScrollBack =" id=\"$idlot\" onclick=\"changeURL('$actualPage', '$idlot')\" ";
			}else{
				$codeScrollBack ="";
			}
		?>
		@if($item->tipo_sub == 'E')
			@include('includes.grid.lotPrivate')
		@else
			@include('includes.grid.lot')
		@endif

	@endforeach
@endif
