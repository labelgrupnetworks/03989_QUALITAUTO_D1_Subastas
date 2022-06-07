@include('includes.ficha._pujas_ficha_info')

<div class="info_single col-xs-12 ficha-puja-o">
	<div>
		<div class="info_single_title hist_new <?= !empty($data['js_item']['user']['ordenMaxima'])?'':'hidden'; ?> ">
			{{trans(\Config::get('app.theme').'-app.lot.max_puja')}}
			<strong><span id="tuorden">
					@if ( !empty($data['js_item']['user']['ordenMaxima']))
					{{ $data['js_item']['user']['ordenMaxima']}}
					@endif
				</span>
				{{trans(\Config::get('app.theme').'-app.subastas.euros')}}</strong>
		</div>
	</div>
	<div>
		<div class="info_single_content">

			<div class="input-group d-flex puja-online">
				<div class="bid_amount-wrapper">
					<input id="bid_amount" placeholder="{{ $data['precio_salida'] }}"
						class="form-control input-lg control-number" type="text" value="{{ $data['precio_salida'] }}">
				</div>
				<div class="input-group-btn">
					<button type="button" data-from="modal"
						class="lot-action_pujar_on_line btn btn-lg btn-custom <?= Session::has('user')?'add_favs':''; ?>"
						type="button" ref="{{ $lote_actual->ref_asigl0 }}"
						codsub="{{ $lote_actual->cod_sub }}">{{ trans(\Config::get('app.theme').'-app.lot.pujar') }}</button>
				</div>
			</div>
			<div class="insert_bid">

				<p style="font-size: 12px">
					{{ trans(\Config::get('app.theme').'-app.lot.insert_max_puja') }}
					<br>
					<span class='explanation_bid t_insert'>
						@if (count($lote_actual->pujas) >0)
						{{ trans(\Config::get('app.theme').'-app.lot.next_min_bid') }}
						@else
						{{ trans(\Config::get('app.theme').'-app.lot.min_puja') }}
						@endif

						<span class="siguiente_puja"></span>
						{{ trans(\Config::get('app.theme').'-app.subastas.euros') }}
					</span>
				</p>

			</div>

			{{-- <div class="temp-text-info" style="background-color: ">
				<p>{{ trans("$theme-app.lot.extra_ficha_o") }}</p>
			</div> --}}

			<div class="input-group">
				<br>
				@if (Session::has('user') && Session::get('user.admin'))
				<input id="ges_cod_licit" name="ges_cod_licit" class="form-control" type="text" value="" type="text"
					style="border: 1px solid red;" placeholder="Código de licitador">
				@if ($lote_actual->subabierta_sub == 'P')
				<input type="hidden" id="tipo_puja_gestor" value="abiertaP">
				@endif
				@endif
			</div>

		</div>
	</div>
</div>

<script>
	$(document).ready(function() {

        //calculamos la fecha de cierre
        //$("#cierre_lote").html(format_date(new Date("{{$lote_actual->close_at}}".replace(/-/g, "/"))));
        $("#actual_max_bid").bind('DOMNodeInserted', function(event) {
            if (event.type == 'DOMNodeInserted') {

               $.ajax({
                    type: "GET",
                    url:  "/lot/getfechafin",
                    data: { cod: cod_sub, ref: ref},
                    success: function( data ) {

                        if (data.status == 'success'){
                           $(".timer").data('ini', new Date().getTime());
                           $(".timer").data('countdown',data.countdown);


                        }


                    }
                });
            }
        });
    });
</script>
