<div class="col-xs-12 info_single">
    <div class="info_single_title col-xs-12">
        <div class="sub-o">

            <p class="">{{ trans($theme . '-app.subastas.lot_subasta_presencial') }}</p>
            @if (strtotime($lote_actual->start_session) - getdate()[0] > 0)
                <span class="clock"><i class="fa fa-clock-o"></i><span
                        data-countdown="{{ strtotime($lote_actual->start_session) - getdate()[0] }}"
                        data-format="<?= \Tools::down_timer($lote_actual->start_session) ?>"
                        data-closed="{{ $lote_actual->cerrado_asigl0 }}" class="timer"></span>
                </span>
            @endif
        </div>
        <div class="date_top_side_small">
            <span class="cierre_lote"></span>
            <?php /* no ponemos CET   <span id="cet_o"> {{ trans($theme.'-app.lot.cet') }}</span> */
            ?>
        </div>
    </div>
    <div class="col-xs-10 col-sm-6 exit-price">
        @if (\Config::get('app.estimacion'))
            <p class="pre">{{ trans($theme . '-app.subastas.estimate') }}</p>
            <div class="pre">
                {{ $lote_actual->formatted_imptas_asigl0 }} - {{ $lote_actual->formatted_imptash_asigl0 }}
                {{ trans($theme . '-app.subastas.euros') }}
            </div>
        @elseif(\Config::get('app.impsalhces_asigl0') && $lote_actual->ocultarps_asigl0 != 'S')
            <p class="pre">{{ trans($theme . '-app.lot.lot-price') }}</p>
            <div class="pre">
                {{ $lote_actual->formatted_impsalhces_asigl0 }}
                {{ trans($theme . '-app.subastas.euros') }}
            </div>
        @endif
    </div>
    <div class="col-xs-12 col-sm-6">
        <p class="cat">{{ trans($theme . '-app.lot.categories') }}</p>
        <p>
            @foreach($data['categories'] as $sec)
                {{ $sec->des_tsec }}
            @endforeach
        </p>
        <p class="shared">{{ trans($theme . '-app.lot.share_lot') }}</p>
        @include('includes.ficha.share')

    </div>
	<div class="col-xs-12">
		<?php
			$lotFotURL = $lote_actual->cod_sub . '-' . $lote_actual->ref_asigl0;
			$urlCompletePackengers = \Config::get('app.urlToPackengers') . $lotFotURL;
		?>
		@if (\Config::get('app.urlToPackengers'))
			<div class="packengers-container-button-ficha">
				<a class="packengers-button-ficha" href="{{ $urlCompletePackengers }}" target="_blank">
					<i class="fa fa-truck" aria-hidden="true"></i>
					{{ trans("$theme-app.lot.packengers-ficha") }}
				</a>
			</div>
		@endif
	</div>
</div>
@if ($lote_actual->fac_hces1 != 'D')
    <div class="info_single col-xs-12 ficha-puja">
        <div class="col-lg-12">
            {{-- Eloy: Peticion de quitarlo 31/01/22 --}}
            {{-- <div class="info_single_title hist_new  {{ !empty($data['js_item']['user']['ordenMaxima']) ? '' : 'hidden' }}">
        {{trans($theme.'-app.lot.max_puja')}}
            <strong><span id="tuorden">
            @if (!empty($data['js_item']['user']['ordenMaxima']))
            {{ $data['js_item']['user']['ordenMaxima']}}
            @endif
            </span>
        {{trans($theme.'-app.subastas.euros')}}</strong>
        </div> --}}
        </div>
        @if ($lote_actual->tipo_sub == 'W' && ($lote_actual->subabierta_sub == 'S' || $lote_actual->subabierta_sub == 'O') && $lote_actual->cerrado_asigl0 == 'N')
            <div class="col-lg-12">
                <div class="info_single_title">
                    <div id="text_actual_max_bid" class=" <?= $lote_actual->open_price > 0 ? '' : 'hidden' ?>">
                        {{ trans($theme . '-app.lot.puja_actual') }} <strong><span
                                id="actual_max_bid">{{ \Tools::moneyFormat($lote_actual->open_price) }} </span>
                            {{ trans($theme . '-app.subastas.euros') }}</strong>

                        @if (isset($data['js_item']['user']))
                            {{-- <span class="winner {{(count($data['ordenes']) > 0 && head($data['ordenes'])->cod_licit == $data['js_item']['user']['cod_licit'])? '':'hidden' }}" >{{ trans($theme.'-app.subastas.exceeded') }}</span> --}}
                            {{-- <span  class="no_winner  {{ (count($data['ordenes']) > 0 && head($data['ordenes'])->cod_licit == $data['js_item']['user']['cod_licit'])? 'hidden':'' }}">{{ trans($theme.'-app.subastas.not_exceeded') }}</span> --}}
                        @endif
                    </div>
                    <div id="text_actual_no_bid" class=" <?= $lote_actual->open_price > 0 ? 'hidden' : '' ?>">
                        {{ trans($theme . '-app.lot_list.no_bids') }} </div>
                </div>
            </div>
        @endif
		@php
			$userController = new \App\Http\Controllers\UserController();
		@endphp

        <div class="col-xs-12">
            <div class="info_single_content">
                @if ($lote_actual->cerrado_asigl0 == 'N' && $lote_actual->fac_hces1 == 'N' && strtotime('now') > strtotime($lote_actual->start_session) && strtotime('now') < strtotime($lote_actual->end_session))
					@if (\Session::has('user'))
						@if ($userController->getCreditCardAndCIFImages(\Session::get('user.cod')))
								<a href='{{ Routing::translateSeo('api/subasta') .$data['subasta_info']->lote_actual->cod_sub .'-' .str_slug($data['subasta_info']->lote_actual->name) .'-' .$data['subasta_info']->lote_actual->id_auc_sessions }}'>
								<button class="btn btn-lg btn-custom live-btn"><?= trans($theme . '-app.lot.bid_live') ?></button>
							</a>
						@else
							<p class="color-red">{!! trans("$theme-app.lot.info_no_cc_and_cif") !!}</p>
						@endif
					@else
							<a href='{{ Routing::translateSeo('api/subasta') .$data['subasta_info']->lote_actual->cod_sub .'-' .str_slug($data['subasta_info']->lote_actual->name) .'-' .$data['subasta_info']->lote_actual->id_auc_sessions }}'>
							<button class="btn btn-lg btn-custom live-btn"><?= trans($theme . '-app.lot.bid_live') ?></button>
						</a>
					@endif
                @endif
                @if ($lote_actual->cerrado_asigl0 == 'N' && $lote_actual->fac_hces1 == 'N' && strtotime('now') > strtotime($lote_actual->orders_start) && strtotime('now') < strtotime($lote_actual->orders_end))
					@if (\Session::has('user'))
						@if ($userController->getCreditCardAndCIFImages(\Session::get('user.cod')))
							<p><strong><?= trans_choice($theme . '-app.lot.insert_max_puja_start', 1, ['price' => $data['precio_salida']]) ?></strong>
							</p>
							<div class="input-group group-pujar-custom">
								<input id="bid_modal_pujar" placeholder="{{ $data['precio_salida'] }}"
									class="form-control input-lg control-number" value="{{ $data['precio_salida'] }}"
									type="text">
								<div class="input-group-btn">
									<button id="pujar_ordenes_w" data-from="modal" type="button" class="btn btn-lg btn-custom"
										ref="{{ $data['subasta_info']->lote_actual->ref_asigl0 }}"
										codsub="{{ $data['subasta_info']->lote_actual->cod_sub }}">{{ trans($theme . '-app.lot.place_bid') }}</button>
								</div>
							</div>
						@else
							<p class="color-red">{!! trans("$theme-app.lot.info_no_cc_and_cif") !!}</p>
						@endif
					@else
						<p><strong><?= trans_choice($theme . '-app.lot.insert_max_puja_start', 1, ['price' => $data['precio_salida']]) ?></strong>
						</p>
						<div class="input-group group-pujar-custom">
							<input id="bid_modal_pujar" placeholder="{{ $data['precio_salida'] }}"
								class="form-control input-lg control-number" value="{{ $data['precio_salida'] }}"
								type="text">
							<div class="input-group-btn">
								<button id="pujar_ordenes_w" data-from="modal" type="button" class="btn btn-lg btn-custom"
									ref="{{ $data['subasta_info']->lote_actual->ref_asigl0 }}"
									codsub="{{ $data['subasta_info']->lote_actual->cod_sub }}">{{ trans($theme . '-app.lot.place_bid') }}</button>
							</div>
						</div>
						@endif
					<div class="checkbox">
						<label for="recibir-newletter">
							<?= trans($theme . '-app.subastas.read_conditions') ?>
						</label>
					</div>
                @endif

            </div>
        </div>

    </div>

@endif





<script>
    $(document).ready(function() {
        //calculamos la fecha de cierre
        $(".cierre_lote").html(format_date_large(new Date("{{ $lote_actual->start_session }}".replace(/-/g,
            "/")), '{{ trans($theme . '-app.lot.from') }}'));
    });
</script>
