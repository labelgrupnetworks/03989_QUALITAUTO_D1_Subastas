<a data-toggle="collapse" href="#{{$all_inf['inf']->cod_sub}}">
	<div class="panel-heading">
		<h4 class="panel-title">
			{{$all_inf['inf']->name}}
		</h4>
		<i class="fas fa-sort-down"></i>
	</div>
</a>
<div id="{{$all_inf['inf']->cod_sub}}" class="panel-collapse collapse <?= count($data['values']) == '1'? 'in':' ';?>">
	<div class="custom-head-wrapper hidden-xs hidden-sm flex">
		<div class="img-data-custom flex "></div>
		<div class="lot-data-custon">
			<p>{{ trans(\Config::get('app.theme').'-app.user_panel.lot') }}</p>
		</div>
		<div class="name-data-custom" style="font-weight: 900 !important;">
			<p>{{ trans(\Config::get('app.theme').'-app.lot.description') }}</p>
		</div>

		<div class="remat-data-custom">
			<p>{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</p>
		</div>
		<div class="auc-data-custom">
			<p>{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}</p>
		</div>
		<div class="auc-data-custom">
			<p>{{ trans(\Config::get('app.theme').'-app.user_panel.mi_puja') }}</p>

		</div>
		<div class="view-data view-fav"></div>
	</div>
	<?php          $countBid = 1; ?>
	@foreach($all_inf['lotes'] as $inf_lot)
	<?php
		$url_friendly = str_slug($inf_lot->titulo_hces1);
		$url_friendly = \Routing::translateSeo('lote').$inf_lot->cod_sub."-".str_slug($inf_lot->session_name).'-'.$inf_lot->id_auc_sessions."/".$inf_lot->ref_asigl0.'-'.$inf_lot->num_hces1.'-'.$url_friendly;
	?>

	<div class="custom-wrapper-responsive  hidden-md hidden-lg ">
		<div class="lot-data-custon">
			<p>{{ trans(\Config::get('app.theme').'-app.user_panel.lot') }}
				{{$inf_lot->ref_asigl0}} - <span>{{$inf_lot->titulo_hces1}}</span></p>
		</div>
		<div class="view-data flex view-fav auc-data-custom">
			<p></p>
			@if(!empty($data['favorites']))
			<a title="{{trans(\Config::get('app.theme').'-app.lot.del_from_fav')}}" class="delete-fav btn-del"
				href="javascript:action_fav_lote('remove','{{ $inf_lot->ref_asigl0 }}','{{$inf_lot->cod_sub }}',' <?= $data['codigos_licitador'][$inf_lot->cod_sub] ?>')"><i
					class="fas fa-minus"></i></a>
			@endif
			<a href="{{$url_friendly}}"><i class="fas fa-eye"></i></a>
		</div>
		<div class="flex justify-content-space-bettween">
			<div class="auc-data-custom">
				<p>{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</p>
				<p>{{$inf_lot->formatted_impsalhces_asigl0}}
					{{ trans(\Config::get('app.theme').'-app.lot.eur') }} </p>
				@if($divisa !='EUR')
				<p class="divisa_fav">
					{!!$currency->getPriceSymbol(0,$inf_lot->impsalhces_asigl0)!!}</p>
				@endif

			</div>
			<div class="auc-data-custom">

				<p>{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}</p>
				@if($inf_lot->cod_licit == $inf_lot->licit_winner_bid)
				<p class="mine">

					<?php //si NO tienen notificación de sobreorden no pueden saber si son ganadores o no?>
					@elseif(!Config::get('app.notice_over_bid') && $inf_lot->tipo_sub == 'W' )
					<p class="gold">
						@else
						<p class="other">
							@endif

							<?php //todas las subastas de tauler tendran pujas, ya que las w ahora seran abiertas ?>

							{{$inf_lot->implic_hces1 }}
							{{ trans(\Config::get('app.theme').'-app.lot.eur') }}

						</p>
						@if($divisa !='EUR')
						<p class="divisa_fav">
							{!!$currency->getPriceSymbol(0,$inf_lot->implic_hces1)!!}</p>
						@endif
			</div>
			<div class="auc-data-custom">
				<p>{{ trans(\Config::get('app.theme').'-app.user_panel.mi_puja') }}</p>

				<p> {{$inf_lot->formatted_imp }}
					{{ trans(\Config::get('app.theme').'-app.lot.eur') }}</p>
				@if($divisa !='EUR')
				<p class="divisa_fav" ' > {!!$currency->getPriceSymbol(0,$inf_lot->imp)!!}</p>
							@endif

						</div>

					</div>

				</div>
				@if($countBid != count($all_inf['lotes']))
					<div class="divider-prices hidden-md hidden-lg"></div>
					<?php $countBid++; ?>
				@else
					<?php $countBid=1; ?>
				@endif
			<div class="custom-wrapper hidden-xs hidden-sm flex valign {{$inf_lot->ref_asigl0}}-{{$inf_lot->cod_sub}}">
				<div class="img-data-custom flex valign">
					<img class="img-responsive"
						src="{{ \Tools::url_img("lote_medium", $inf_lot->num_hces1, $inf_lot->lin_hces1) }}">
				</div>

				<div class="lot-data-custon">
					<p>{{$inf_lot->ref_asigl0}}</p>

				</div>
				<div class="name-data-custom">
					<?= $inf_lot->desc_hces1 ?>
				</div>
				<div class="auc-data-custom">

					<p>{{$inf_lot->impsalhces_asigl0}}
						{{ trans(\Config::get('app.theme').'-app.lot.eur') }}</p>
					@if($divisa !='EUR')
					<p class="divisa_fav">
						{!!$currency->getPriceSymbol(2,$inf_lot->impsalhces_asigl0)!!} </p>
					@endif
				</div>
				<div class="auc-data-custom">
					<?php
					// TAULER TIENE UN TIPO DE SUBASTA ABIERTA QUE PERMITE PUJAS, POR LO QUE LAS CONDICIONES SON DISTINTAS
					/*
						 <?php //si no es W y es el ganador de la puja ?>
					@if(($inf_lot->tipo_sub != 'W' || $inf_lot->subabierta_sub == 'P') &&
					$inf_lot->cod_licit == $inf_lot->licit_winner_bid)
					<p class="mine">
						<?php //si tienen notificación de sobreorden y son  W y es el ganador de la orden ?>
						@elseif(Config::get('app.notice_over_bid') && $inf_lot->tipo_sub == 'W'
						&& $inf_lot->cod_licit == $inf_lot->licit_winner_order)
						<p class="mine">
							<?php //si NO tienen notificación de sobreorden no pueden saber si son ganadores o no?>
							@elseif(!Config::get('app.notice_over_bid') && $inf_lot->tipo_sub ==
							'W' )
							<p class="gold">
								@else
								<p class="other">
									@endif
									*
									*/
									?>
									@if($inf_lot->cod_licit == $inf_lot->licit_winner_bid)
									<p class="mine">

										<?php //si NO tienen notificación de sobreorden no pueden saber si son ganadores o no?>
										@elseif(!Config::get('app.notice_over_bid') &&
										$inf_lot->tipo_sub == 'W' )
										<p class="gold">
											@else
											<p class="other">
												@endif

												<?php //todas las subastas de tauler tendran pujas, ya que las w ahora seran abiertas ?>

												{{$inf_lot->implic_hces1 }}
												{{ trans(\Config::get('app.theme').'-app.lot.eur') }}

											</p>
											@if($divisa !='EUR')
											<p class="divisa_fav">
												{!!$currency->getPriceSymbol(2,$inf_lot->implic_hces1)!!}
											</p>
											@endif
				</div>
				<div class="auc-data-custom">
					<p>
						{{$inf_lot->formatted_imp }}
						{{ trans(\Config::get('app.theme').'-app.lot.eur') }}
					</p>
					@if($divisa !='EUR')
					<p class="divisa_fav"> {!!$currency->getPriceSymbol(2,$inf_lot->imp)!!}</p>
					@endif
				</div>
				<div class="view-data view-fav flex hidden-xs hidden-sm">
					@if(!empty($data['favorites']))
					<a title="{{trans(\Config::get('app.theme').'-app.lot.del_from_fav')}}" class="delete-fav btn-del"
						href="javascript:action_fav_lote('remove','{{ $inf_lot->ref_asigl0 }}','{{$inf_lot->cod_sub }}',' <?= $data['codigos_licitador'][$inf_lot->cod_sub] ?>')"><i
							class="fas fa-minus"></i></a>
					@endif
					<a href="{{$url_friendly}}"><i class="fas fa-eye"></i></a>
				</div>
			</div>
			@endforeach
		</div>
