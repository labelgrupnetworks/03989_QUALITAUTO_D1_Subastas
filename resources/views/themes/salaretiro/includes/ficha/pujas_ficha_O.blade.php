<div class="col-xs-12 info_single">

    <div class="info_single_title col-xs-12">
        <div class="sub-o">
            <p class="">{{ trans($theme . '-app.subastas.lot_subasta_online') }}</p>
            <span class="clock "><i class="fa fa-clock-o"></i>
                <span data-countdown="{{ strtotime($lote_actual->close_at) - getdate()[0] }}"
                    data-format="<?= \Tools::down_timer($lote_actual->close_at, 'large') ?>"
                    class="timer"></span>
            </span>
        </div>
        <div class="date_top_side_small">
            <span id="cierre_lote">{{ $lote_actual->close_at }}</span>
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
        <div class="hidden">
            <p class="min">{{ trans($theme . '-app.subastas.price_minim') }}</p>
            <div class="min precio_minimo_alcanzado hidden">
                {{ trans($theme . '-app.subastas.reached') }}
            </div>
            <div class="min precio_minimo_no_alcanzado hidden">
                {{ trans($theme . '-app.subastas.no_reached') }}
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6">
        <p class="cat">{{ trans($theme . '-app.lot.categories') }}</p>
        <p>
			@foreach($data['categories'] as $sec)
			   {{$sec->des_tsec}}
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

@if(now() > $lote_actual->orders_start)
<div class="info_single col-xs-12 ficha-puja-o">
    <div class="col-lg-12">
        <div class="info_single_title hist_new <?= !empty($data['js_item']['user']['ordenMaxima']) ? '' : 'hidden' ?> ">
            {{ trans($theme . '-app.lot.max_puja') }}
            <strong><span id="tuorden">
                    @if (!empty($data['js_item']['user']['ordenMaxima']))
                        {{ $data['js_item']['user']['ordenMaxima'] }}
                    @endif
                </span>
                {{ trans($theme . '-app.subastas.euros') }}</strong>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="info_single_content">
            <?php
            $you_bid = false;
            foreach ($lote_actual->pujas as $bid) {
                if (!empty($data['js_item']['user']) && $bid->cod_licit == $data['js_item']['user']['cod_licit']) {
                    $you_bid = true;
                }
            }
            ?>
            @if (count($lote_actual->pujas) > 0 && Session::has('user') && $you_bid == true)
                <span id="text_actual_max_bid"
                    class=" ">{{ trans($theme . '-app.lot.puja_actual') }}</span>
                <span id="actual_max_bid"
                    class="<?= $lote_actual->max_puja->cod_licit == $data['js_item']['user']['cod_licit'] ? 'mine' : 'other' ?>">{{ $lote_actual->formatted_actual_bid }}
                    €</span>
            @elseif(count($lote_actual->pujas) == 0)
                <span id="text_actual_no_bid"> <?php /* trans($theme.'-app.lot_list.no_bids') */?> </span>
                <span id="text_actual_max_bid"
                    class="hidden">{{ trans($theme . '-app.lot.puja_actual') }}</span>
                <span id="actual_max_bid" class="hidden"></span>
            @elseif($lote_actual->pujas > 0 && $you_bid == false)
                <span id="text_actual_max_bid"
                    class=" ">{{ trans($theme . '-app.lot.puja_actual') }}</span>
                <span id="actual_max_bid">{{ $lote_actual->formatted_actual_bid }} €</span>
            @endif
            <br>

			@if (\Session::has('user'))
				@php
					$userController = new \App\Http\Controllers\UserController();
					$ccAndCIFverif = $userController->getCreditCardAndCIFImages(\Session::get('user.cod'));
				@endphp
				@if ($ccAndCIFverif)
					@if (count($lote_actual->pujas) > 0)
						<small class='explanation_bid t_insert'
							style="font-size:13px; font-weight: 900">{{ trans($theme . '-app.lot.next_min_bid') }}
							<strong><span class="siguiente_puja">{{ $data['precio_salida'] }}</span></strong>
							{{ trans($theme . '-app.subastas.euros') }} </small>
					@else
						<small class='explanation_bid t_insert'
							style="font-size:13px; font-weight: 900">{{ trans($theme . '-app.lot.min_puja') }}
							<strong><span class="siguiente_puja">{{ $data['precio_salida'] }}</span></strong>
							{{ trans($theme . '-app.subastas.euros') }} </small>
					@endif
					<div class="insert_bid">
						<p><strong>{{ trans($theme . '-app.lot.insert_max_puja') }}</strong></p>
					</div>
					<div class="input-group">
						<input id="bid_amount" placeholder="{{ $data['precio_salida'] }}"
							class="form-control input-lg control-number" type="text" value="{{ $data['precio_salida'] }}">
							<div class="input-group-btn" style="vertical-align: top;">
								<button type="button" data-from="modal"
									class="lot-action_pujar_on_line btn btn-lg btn-custom <?= Session::has('user') ? 'add_favs' : '' ?>"
									type="button" ref="{{ $lote_actual->ref_asigl0 }}" ref="{{ $lote_actual->ref_asigl0 }}"
									codsub="{{ $lote_actual->cod_sub }}">{{ trans($theme . '-app.lot.pujar') }}</button>
							</div>
						<br>
					</div>
				@endif
			@else
				@if (count($lote_actual->pujas) > 0)
					<small class='explanation_bid t_insert'
						style="font-size:13px; font-weight: 900">{{ trans($theme . '-app.lot.next_min_bid') }}
						<strong><span class="siguiente_puja">{{ $data['precio_salida'] }}</span></strong>
						{{ trans($theme . '-app.subastas.euros') }} </small>
				@else
					<small class='explanation_bid t_insert'
						style="font-size:13px; font-weight: 900">{{ trans($theme . '-app.lot.min_puja') }}
						<strong><span class="siguiente_puja">{{ $data['precio_salida'] }}</span></strong>
						{{ trans($theme . '-app.subastas.euros') }} </small>
				@endif
				<div class="insert_bid">
					<p><strong>{{ trans($theme . '-app.lot.insert_max_puja') }}</strong></p>
				</div>
				<div class="input-group">
					<input id="bid_amount" placeholder="{{ $data['precio_salida'] }}"
						class="form-control input-lg control-number" type="text" value="{{ $data['precio_salida'] }}">
						<div class="input-group-btn" style="vertical-align: top;">
							<button type="button" data-from="modal"
								class="lot-action_pujar_on_line btn btn-lg btn-custom <?= Session::has('user') ? 'add_favs' : '' ?>"
								type="button" ref="{{ $lote_actual->ref_asigl0 }}" ref="{{ $lote_actual->ref_asigl0 }}"
								codsub="{{ $lote_actual->cod_sub }}">{{ trans($theme . '-app.lot.pujar') }}</button>
						</div>
					<br>
				</div>
			@endif
				<div>
					@if (\Session::has('user'))
						@if (!$ccAndCIFverif)
							<p class="color-red">{!! trans("$theme-app.lot.info_no_cc_and_cif") !!}</p>
						@endif
					@endif
				</div>

				{{-- FALTA CREAR UN PARA QUE SE VEA EL MENSAJE CON EL !$ccAndCIFverif y "<p>{{ trans("$theme-app.lot.info_no_cc_and_cif") }}</p>" --}}

				<div class="input-group">
					<br>
					@if (Session::has('user') && Session::get('user.admin'))
						<input id="ges_cod_licit" name="ges_cod_licit" class="form-control" type="text" value=""
							type="text" style="border: 1px solid red;" placeholder="Código de licitador">
						@if ($lote_actual->subabierta_sub == 'P')
							<input type="hidden" id="tipo_puja_gestor" value="abiertaP">
						@endif
					@endif
				</div>


            <div class="col-xs-12">
                <div class="checkbox">
                    <label for="recibir-newletter">
                        <?= trans($theme . '-app.subastas.read_conditions') ?>
                    </label>
                </div>
            </div>

        </div>
    </div>
</div>
@endif

<script>
    $(document).ready(function() {

        //calculamos la fecha de cierre
        //$("#cierre_lote").html(format_date(new Date("{{ $lote_actual->close_at }}".replace(/-/g, "/"))));
        $("#actual_max_bid").bind('DOMNodeInserted', function(event) {
            if (event.type == 'DOMNodeInserted') {

                $.ajax({
                    type: "GET",
                    url: "/lot/getfechafin",
                    data: {
                        cod: cod_sub,
                        ref: ref
                    },
                    success: function(data) {

                        if (data.status == 'success') {
                            $(".timer").data('ini', new Date().getTime());
                            $(".timer").data('countdown', data.countdown);


                        }


                    }
                });
            }
        });
    });
</script>
