@include('includes.ficha._pujas_ficha_info')

<div class="info_single col-xs-12 ficha-puja-o">
	<div>
		<div class="info_single_title hist_new <?= !empty($data['js_item']['user']['ordenMaxima'])?'':'hidden'; ?> ">
			{{trans($theme.'-app.lot.max_puja')}}
			<strong><span id="tuorden">
					@if ( !empty($data['js_item']['user']['ordenMaxima']))
					{{ $data['js_item']['user']['ordenMaxima']}}
					@endif
				</span>
				{{trans($theme.'-app.subastas.euros')}}</strong>
		</div>
	</div>

	<div>
		<div class="info_single_content">
			@if( $lote_actual->cerrado_asigl0=='N' && $lote_actual->fac_hces1=='N' && strtotime("now") >
					strtotime($lote_actual->start_session) && strtotime("now") < strtotime($lote_actual->end_session) )

					<a	href='{{  Routing::translateSeo('api/subasta').$data['subasta_info']->lote_actual->cod_sub."-".str_slug($data['subasta_info']->lote_actual->name)."-".$data['subasta_info']->lote_actual->id_auc_sessions }}'>
							<button
								class="btn btn-lg btn-custom live-btn"><?=trans($theme.'-app.lot.bid_live')?></button>
					</a>

			@endif
			<div class="input-group d-flex puja-online">
				<div class="bid_amount-wrapper">
					<input id="bid_amount" placeholder="{{ $data['precio_salida'] }}"
						class="form-control input-lg control-number" type="text" value="{{ $data['precio_salida'] }}">
				</div>
				<div class="input-group-btn">
					<button type="button" data-from="modal"
						class="lot-action_pujar_on_line btn btn-lg btn-custom <?= Session::has('user')?'add_favs':''; ?>"
						type="button" ref="{{ $lote_actual->ref_asigl0 }}"
						codsub="{{ $lote_actual->cod_sub }}">{{ trans($theme.'-app.lot.pujar') }}</button>
				</div>
			</div>
			<div class="insert_bid">

				<p style="font-size: 12px">
					{{ trans($theme.'-app.lot.insert_max_puja') }}
					<br>
					<span class='explanation_bid t_insert'>
						@if (count($lote_actual->pujas) >0)
						{{ trans($theme.'-app.lot.next_min_bid') }}
						@else
						{{ trans($theme.'-app.lot.min_puja') }}
						@endif

						<span class="siguiente_puja"></span>
						{{ trans($theme.'-app.subastas.euros') }}
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

<div id="postVentaModal" class="modal modal-toast fade" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">{{ trans("$theme-app.lot.post_venta_title") }}</h4>
			</div>

			<div class="modal-body">
				<p>{{ trans("$theme-app.lot.post_venta_content") }}</p>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-lg btn-primary" data-dismiss="modal">{{ trans("$theme-app.head.close") }}</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
	$(document).ready(function() {

		//showPostVentaModal(auction_info.lote_actual);

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

	function showPostVentaModal({tipo_sub, compra_asigl0}) {

		const isMobile = window.matchMedia("(max-width: 600px)").matches;
		const keyStorage = 'postVentaModal';

		//en mobile solo se muestra una vez al dia
		if(isMobile && !shouldExecuteOncePerDay(keyStorage)){
			return;
		}


		if(tipo_sub !== 'W' || compra_asigl0 !== 'S') {
			return;
		}

		$('#postVentaModal').modal({
			show: true,
			backdrop: false,
			keyboard: false
		});

		$('#postVentaModal').on('shown.bs.modal', function () {
			$('body').removeClass('modal-open');
		});
	}

	function shouldExecuteOncePerDay(keyStorage) {

		if (!window.localStorage) {
			return true;
		}

		const now = new Date();
		const last = new Date(localStorage.getItem(keyStorage));
		if (last.getDate() !== now.getDate()) {
			localStorage.setItem(keyStorage, now);
			return true;
		}
		return false;
	}
</script>
