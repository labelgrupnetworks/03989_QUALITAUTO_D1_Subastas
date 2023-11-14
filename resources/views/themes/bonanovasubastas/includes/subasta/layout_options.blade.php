<div class="row">
	<div class="col-xs-12 col-sm-12 title-head-grid">
		<div class="col-xs-5 col-sm-3 col-md-3 col-lg-3">
		   <?php //Si quieren mostrar nombre de la subasta o que se vea texto Lotes ?>
			@if(empty($data['subastas']))
				<h1 class="titlePage-custom"> {{$data['name']}} </h1>
			@else
				 <h1 class="titlePage-custom">{{ trans(\Config::get('app.theme').'-app.lot_list.lots') }}</h1>
			@endif
		</div>
		<div class="col-xs-7 col-sm-5 col-md-4 col-lg-5 lot-count">
			@if(!empty( $data['subastas']) && $data['subastas'][0]->tipo_sub == 'W'  && ($data['subastas'][0]->subc_sub == 'A' ||$data['subastas'][0]->subc_sub == 'S' )  && strtotime($data['subastas'][0]->start_session) > time())
				<div  class="text-right timeLeft">
					<span data-countdown="{{ strtotime($data['subastas'][0]->start_session) - getdate()[0] }}"  data-format="<?= \Tools::down_timer($data['subastas'][0]->start_session); ?>" data-closed="{{ $data['subastas'][0]->cerrado_asigl0 }}" class="timer"></span>
					<span class="clock"></span>
				</div>
			@endif
		</div>
		<div class="col-xs-9 col-sm-4 col-md-3 col-lg-3 refresh text-right">
			<?php // si es uan subasta w y abierta o si es uan subasta tipo O o P ?>
			@if(!empty( $data['subastas']) && ( ($data['subastas'][0]->tipo_sub == 'W' && $data['subastas'][0]->subabierta_sub != 'N') || $data['subastas'][0]->tipo_sub == 'P'  || $data['subastas'][0]->tipo_sub == 'O' )  && ($data['subastas'][0]->subc_sub == 'A' ||$data['subastas'][0]->subc_sub == 'S' )  )

				<a href=""> {{ trans(\Config::get('app.theme').'-app.lot_list.refresh_prices') }} <i class="fa fa-refresh" aria-hidden="true"></i></a>

			@endif
			 @if(!empty($data['sub_data']) && !empty($data['sub_data']->opcioncar_sub && !empty($data['subastas'][0])) && $data['sub_data']->opcioncar_sub == 'S' && strtotime($data['subastas'][0]->start_session) > time())
				@if(Session::has('user'))
				   <i class="fa fa-gavel  fa-1x"></i> <a href="{{ \Routing::slug('user/panel/modification-orders') }}?sub={{$data['sub_data']->cod_sub}}" ><?= trans(\Config::get('app.theme').'-app.lot_list.ver_ofertas') ?></a>
				@endif
			@endif

		</div>
		<div class="col-xs-3 col-md-2 col-lg-1 lot-grid text-right hidden-xs hidden-sm">
			<a id="large_square" href="javascript:;"><i class="fa fa-th-list fa-lg"></i></a>
			<a id="square" href="javascript:;"><i class="fa fa fa-th-large fa-lg"></i></a>
			<a id="small_square" href="javascript:;"><i class="fa fa-th fa-lg hidden-xs hidden-sm"></i></a>
		</div>
	</div>
</div>
