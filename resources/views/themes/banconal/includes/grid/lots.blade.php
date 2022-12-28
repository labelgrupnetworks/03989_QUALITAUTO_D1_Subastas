@if(!Session::has('user'))
	<div class="col-xs-12 ">
		<br><br>
		<center><big><big>{{ trans(\Config::get('app.theme').'-app.lot.necesario-login') }}</big></big></center>
	</div>
@elseif (empty($lots))
	<div class="col-xs-12 ">
		<br><br>
		<center><big><big>{{ trans(\Config::get('app.theme').'-app.lot_list.no_results') }}</big></big></center>
	</div>
@else
<div class="col-xs-12 p-0 col-sm-4  ">
</div>
<div class="col-xs-12 col-sm-4 p-0">
	@foreach ($lots as  $item)
		<?php

				#transformo el array en variables para conservar los nombres antiguos
				# si es necesario ampliar varibles se puede hacer en la funcion setVarsLot del lotlistcontroller, o si solo es para este cleinte ponerlas aquí.

			foreach($item->bladeVars as $key => $value){
				${$key} = $value;
			}
				$class_square = 'col-xs-12 ';


			if(empty(\Config::get("app.paginacion_grid_lotes"))){
				$idlot="lot_".$item->sub_asigl0."_".$item->ref_asigl0;
				$codeScrollBack =" id=\"$idlot\" onclick=\"changeURL('$actualPage', '$idlot')\" ";
			}else{
				$codeScrollBack ="";
			}
		?>
		@include('includes.grid.lot')
	@endforeach
</div>
<div class="col-xs-12 p-0  col-sm-4">
</div>

@endif
