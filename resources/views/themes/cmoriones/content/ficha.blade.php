@php
    use App\Models\V5\FgDeposito;

    $cerrado = $lote_actual->cerrado_asigl0 == 'S';
    $cerrado_N = $lote_actual->cerrado_asigl0 == 'N';
    $hay_pujas = count($lote_actual->pujas) > 0;
    $devuelto = $lote_actual->cerrado_asigl0 == 'D';
    $remate = $lote_actual->remate_asigl0 == 'S';
    $compra = $lote_actual->compra_asigl0 == 'S';
    $subasta_online = $lote_actual->tipo_sub == 'P' || ($lote_actual->tipo_sub == 'O' && $lote_actual->inversa_sub != 'S');
    $subasta_inversa = $lote_actual->tipo_sub == 'O' && $lote_actual->inversa_sub == 'S';
    $subasta_venta = $lote_actual->tipo_sub == 'V';
    $subasta_web = $lote_actual->tipo_sub == 'W';
    $subasta_make_offer = $lote_actual->tipo_sub == 'M';

    $subasta_abierta_O = $lote_actual->subabierta_sub == 'O';
    $subasta_abierta_P = $lote_actual->subabierta_sub == 'P';
    $retirado = $lote_actual->retirado_asigl0 != 'N';
    $sub_historica = $lote_actual->subc_sub == 'H';
    $sub_cerrada = $lote_actual->subc_sub != 'A' && $lote_actual->subc_sub != 'S';
    $remate = $lote_actual->remate_asigl0 == 'S';
    $awarded = \Config::get('app.awarded');
    // D = factura devuelta, R = factura pedniente de devolver
    $fact_devuelta = $lote_actual->fac_hces1 == 'D' || $lote_actual->fac_hces1 == 'R';
    $fact_N = $lote_actual->fac_hces1 == 'N';
    $start_session = strtotime('now') > strtotime($lote_actual->start_session);
    $end_session = strtotime('now') > strtotime($lote_actual->end_session);

    $start_orders = strtotime('now') > strtotime($lote_actual->orders_start);
    $end_orders = strtotime('now') > strtotime($lote_actual->orders_end);

    $userSession = session('user');
    $deposito = (new FgDeposito())->isValid($userSession['cod'] ?? null, $lote_actual->cod_sub, $lote_actual->ref_asigl0);

    # listamos los recursos que se hayan puesto en la carpeta de videos para mostrarlos en la imagen principal
    $resourcesList = [];
    foreach ($lote_actual->videos ?? [] as $key => $video) {
        $resource = ['src' => $video, 'format' => 'VIDEO'];
        if (strtolower(substr($video, -4)) == '.gif') {
            $resource['format'] = 'GIF';
        }
        $resourcesList[] = $resource;
    }

    $refLot = $lote_actual->ref_asigl0;
    #si  tiene el . decimal hay que ver si se debe separar
    if (strpos($refLot, '.') !== false) {
        $refLot = str_replace(['.1', '.2', '.3', '.4', '.5'], ['-A', '-B', '-C', '-D', '-E'], $refLot);
        #si hay que recortar
    } elseif (config('app.substrRef')) {
        #cogemos solo los últimos x numeros, ya que se usaran hasta 9, los  primeros para diferenciar un lote cuando se ha vuelto a subir a subasta
        #le sumamos 0 para convertirlo en numero y así eliminamos los 0 a la izquierda
        $refLot = substr($refLot, -\config::get('app.substrRef')) + 0;
    }

    if ($subasta_web) {
        $nameCountdown = 'countdown';
        $timeCountdown = $lote_actual->start_session;
    } elseif ($subasta_online) {
        $nameCountdown = 'countdownficha';
        $timeCountdown = $lote_actual->close_at;
    } elseif ($subasta_inversa) {
        $nameCountdown = 'countdownficha';
        $timeCountdown = $lote_actual->close_at;
    } else {
        $nameCountdown = 'countdown';
        $timeCountdown = $lote_actual->end_session;
    }

@endphp

<div class="ficha-content container">

    <div class="ficha-grid row" data-tipe-sub="{{ $lote_actual->tipo_sub }}">
        <section class="ficha-title">
            <h1>{{ $refLot }} - {!! $lote_actual->descweb_hces1 ?? $lote_actual->titulo_hces1 !!}</h1>
        </section>

        <div class="col-12 col-md-5 col-xl-3 order-1 order-md-0">
            <section class="ficha-description">
                @include('includes.ficha.ficha_description')
            </section>

            <section class="ficha-features">
				@include('includes.ficha.features')
            </section>

			@if (in_array($lote_actual->cod_sub, ['CEREMATE', 'COMDEUDA', 'REOS']))
				<section class="ficha-login">
					<p class="mb-2">
						Si quieres tener una VALORACIÓN REAL de este activo a fecha de hoy por el que muestras interés de forma
						TOTALMENTE GRATUITA, ve a <button class="btn btn-primary btn_login">login</button> completa los datos y
						solicita más información. Uno de nuestros expertos
						valorará el activo con el máximo detalle y precisión y se pondrá en contacto contigo para transmitirte el
						resultado de la valoración.
					</p>
					@if (Session::has('user'))
						<button class="btn btn-lb-primary w-100">
							Sí, quiero una valoración real
						</button>
					@endif
				</section>
			@endif

        </div>

        <div class="col-12 col-md-7 col-xl-6 order-0 order-md-1">

			<section class="ficha-image">
				@include('includes.ficha.ficha_image')
			</section>

			<section class="ficha-previous-next">
				@include('includes.ficha.previous_next')
			</section>

		</div>

        <div class="col-12 col-xl-3 order-3">
			<section class="ficha-contact">
                <a class="btn btn-success"
                    href="https://wa.me/34602252061?text=Estoy%20interesado%20es%20el%20siguiente%20activo%20{{ URL::current() }}"
                    style="--lb-border-radius:.375rem" target="_blank">
                    <svg class="bi" width="24" height="24" fill="currentColor">
                        <use xlink:href="/bootstrap-icons.svg#whatsapp"></use>
                    </svg>
                    Contacta con nosotros
                </a>
            </section>

			<section class="ficha-countdown">
				@if($cerrado_N && !empty($timeCountdown) && strtotime($timeCountdown) > getdate()[0])
				<p class="ficha-info-clock">
					<span class="timer"
						data-{{$nameCountdown}}="{{ strtotime($timeCountdown) - getdate()[0] }}"
						data-format="<?= \Tools::down_timer($timeCountdown, "complete"); ?>">
					</span>
				</p>
				@endif
			</section>

			<section class="ficha-pujas">
				@include('includes.ficha.ficha_pujas')
			</section>

			<section class="ficha-history">
				@if (
					($subasta_online || $subasta_inversa || ($subasta_web && $subasta_abierta_P) || $subasta_make_offer) &&
						!$cerrado &&
						!$retirado)
					@include('includes.ficha.history')
				@endif
			</section>

			<section class="ficha-files">
				@include('includes.ficha.files')
			</section>

			<section class="ficha-share">
				@include('includes.ficha.share')
			</section>
		</div>

        {{-- @includeIf('includes.ficha.custom_sections') --}}

    </div>

    <section class="ficha-recomendados mt-3">
        <div class="lotes_destacados" id="lotes_recomendados-content">
            <h1 class="mas-pujados-title">{{ trans(\Config::get('app.theme') . '-app.lot.recommended_lots') }}</h1>

            <div class='loader d-none'></div>
            <div class="owl-theme owl-carousel" id="lotes_recomendados"></div>

            <div class="m-0 pl-10" id="navs-arrows">
                <div class="owl-nav">
                    <div class="owl-prev"><i class="fas fa-chevron-left"></i></div>
                    <div class="owl-next"><i class="fas fa-chevron-right"></i></div>
                </div>
            </div>
        </div>
    </section>
</div>

@php
    $key = 'lotes_recomendados';
    $replace = [
        'emp' => Config::get('app.emp'),
        'sec_hces1' => $lote_actual->sec_hces1,
        'num_hces1' => $lote_actual->num_hces1,
        'lin_hces1' => $lote_actual->lin_hces1,
        'lang' => Config::get('app.language_complete')['' . Config::get('app.locale') . ''],
    ];
    $lang = Config::get('app.locale');
@endphp


<script>
    const replace = @json($replace);
    const key = "lotes_recomendados";
    const isContExtra = @json(!empty($lote_actual->contextra_hces1));

    $(function() {

        ajax_newcarousel(key, replace, '{{ $lang }}');

        //Mostramos la fecha
        $("#cierre_lote").html(format_date_large(new Date("{{ $timeCountdown }}".replace(/-/g, "/")), ''));

        if (isContExtra) {
            image360Init();
        }
    });
</script>

@include('includes.ficha.modals_ficha')
