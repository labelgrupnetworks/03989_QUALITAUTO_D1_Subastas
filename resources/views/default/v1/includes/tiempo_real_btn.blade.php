{{-- $global['subastas'] ya no se sirve.
Por el momento dejo el "componente", pero para poder utilizarlo es necesario
realizar una consulta a la base de datos para obtener las subastas activas. --}}
@if (false)
	<div class="container">
		<div class="row">
			@php

				$subastas = [];//$global['subastas']['S']['W'];
				$diferencia = \Config::get('app.btnPujarHoras', 2);

			@endphp

			@foreach ($subastas as $sessions)
				@foreach ($sessions as $session)
					@php
						$tiempoPrevio = strtotime("-$diferencia hours", strtotime($session->session_start));
					@endphp

					@if (strtotime('now') > $tiempoPrevio && strtotime('now') < strtotime($session->session_end))
						<div class="col-xs-12 no-padding">
							<div>
								<a class="home-live-btn-link " href="{{ \Tools::url_real_time_auction($session->cod_sub, $session->name, $session->id_auc_sessions) }}">
									<div class="bid-online"></div>
									<div class="bid-online animationPulseRed"></div>
									{{ trans("$theme-app.lot.bid_live") }} {{ $session->name }}
								</a>
							</div>
						</div>
					@endif

				@endforeach
			@endforeach

		</div>
	</div>
@endif
