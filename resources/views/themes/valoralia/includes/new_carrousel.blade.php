
	@foreach ($lots as  $item)
		<?php
				#transformo el array en variables para conservar los nombres antiguos
				# si es necesario ampliar varibles se puede hacer en la funcion setVarsLot del lotlistcontroller, o si solo es para este cleinte ponerlas aquí.

			foreach($item->bladeVars as $key => $value){
				${$key} = $value;
			}
			$titulo=$item->descweb_hces1;

			$class_square = '';

			$codeScrollBack ="";

		?>
		<div class="col-xs-12 col-sm-4 col-lg-3 square">
		@include('includes.grid.lot')
		</div>
	@endforeach

