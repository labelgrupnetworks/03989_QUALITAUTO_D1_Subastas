 <div class="sidebar_lot info-buy-filters">

	@if(!isset($in_indice_subasta) || $in_indice_subasta == false)

	<?php
		$indices = App\Models\Amedida::indice($data['cod_sub'], $data['id_auc_sessions']);
	?>

	@if(!empty($indices))
		<div class="block_filters text">
			<label for="input_description">{{ $indice }}</label>
			<div class="tcenter">
				<a title="{{ trans(\Config::get('app.theme').'-app.lot_list.open_indice') }}" href="{{$data['url_indice']}}" class="btn btn-filter listaIndice btn-color" >{{ trans(\Config::get('app.theme').'-app.lot_list.open_indice') }}</a>
			</div>
		</div>
	@endif
	@endif
        @if( (!empty($ficha_subasta->expofechas_sub)) ||  (!empty($ficha_subasta->expohorario_sub)) || (!empty($ficha_subasta->expolocal_sub)) || (!empty($ficha_subasta->expomaps_sub)))
            <h4>{{ trans(\Config::get('app.theme').'-app.subastas.inf_subasta_exposicion') }}</h4>
        @endif
        @if(!empty($ficha_subasta->expofechas_sub))
            <p><?= $ficha_subasta->expofechas_sub ?></p>
        @endif
        @if(!empty($ficha_subasta->expohorario_sub))
            <p>{{ trans(\Config::get('app.theme').'-app.subastas.inf_subasta_horario') }}: <?= $ficha_subasta->expohorario_sub ?> </p>
        @endif
        @if(!empty($ficha_subasta->expolocal_sub))
            <p>{{ trans(\Config::get('app.theme').'-app.subastas.inf_subasta_location') }}: <?= $ficha_subasta->expolocal_sub ?></p>
        @endif
        @if(!empty($ficha_subasta->expomaps_sub))
            <p><a target="_blank" title="cómo llegar" href="https://maps.google.com/?q=<?= $ficha_subasta->expomaps_sub ?>">{{ trans(\Config::get('app.theme').'-app.subastas.how_to_get') }}</a></p>
        @endif
        @if((!empty($ficha_subasta->sesfechas_sub)) || (!empty($ficha_subasta->seshorario_sub)) || (!empty($ficha_subasta->seslocal_sub)) || (!empty($ficha_subasta->sesmaps_sub)))
            <h4>
                @if($ficha_subasta->tipo_sub == 'V')
                    {{trans(\Config::get('app.theme').'-app.foot.direct_sale')}}
                @else
                    {{ trans(\Config::get('app.theme').'-app.subastas.inf_subasta_subasta') }}
                @endif

            </h4>
        @endif
        @if(!empty($ficha_subasta->sesfechas_sub))
           <p> <?= $ficha_subasta->sesfechas_sub ?></p>
        @endif
        @if(!empty($ficha_subasta->seshorario_sub))
            <p>{{ trans(\Config::get('app.theme').'-app.subastas.inf_subasta_horario') }}: <?= $ficha_subasta->seshorario_sub ?> </p>
        @endif
        @if(!empty($ficha_subasta->seslocal_sub))
            <p>{{ trans(\Config::get('app.theme').'-app.subastas.inf_subasta_location') }}: <?= $ficha_subasta->seslocal_sub ?></p>
        @endif
        @if(!empty($ficha_subasta->sesmaps_sub))
            <p><a target="_blank" title="{{ trans(\Config::get('app.theme').'-app.subastas.how_to_get') }}" href="https://maps.google.com/?q=<?= $ficha_subasta->sesmaps_sub ?>">{{ trans(\Config::get('app.theme').'-app.subastas.how_to_get') }}</a></p>
        @endif
    </div>
