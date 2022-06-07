@php
	$refs = $lots->pluck('ref_asigl0');
	$preciosEstimadosAltos = \App\Models\V5\FgAsigl0::select('ref_asigl0', 'imptash_asigl0')->where('sub_asigl0', $lots[0]->sub_asigl0)->whereIn('ref_asigl0', $refs)->pluck('imptash_asigl0', 'ref_asigl0');
@endphp


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
			$estimacionAlta = $preciosEstimadosAltos[$item->ref_asigl0];

		?>
		<div class="col-xs-12 col-sm-6 col-lg-4 square">
		@include('includes.grid.lot')
		</div>
	@endforeach

