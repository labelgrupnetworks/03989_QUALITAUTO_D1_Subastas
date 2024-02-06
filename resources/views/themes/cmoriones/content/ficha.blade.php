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

            @php
                $category = App\Models\V5\FgOrtsec1::select('lin_ortsec1', 'des_ortsec0', 'key_ortsec0')
                    ->JoinFgOrtsec0()
                    ->where('sec_ortsec1', $lote_actual->sec_hces1)
                    ->first();
                $caracteristicas = App\Models\V5\FgCaracteristicas_Hces1::getByLot($lote_actual->num_hces1, $lote_actual->lin_hces1);
            @endphp

            <section class="ficha-features">

                @if (count($caracteristicas) !== 0)
                    <div class="features mb-3">
                        <h5>{{ trans("$theme-app.features.features") }}</h5>

                        <div class="gird-features">
                            @foreach ($caracteristicas as $caracteristica)
                                <p class="feature-name">
                                    {{ trans("$theme-app.features.$caracteristica->name_caracteristicas") }}
                                </p>
                                <p class="feature-value">{{ $caracteristica->value_caracteristicas_hces1 }}</p>
                            @endforeach
                        </div>

                    </div>
                @endif

                @if (!empty($category))
                    <div class="categories">
                        <h5>{{ trans(\Config::get('app.theme') . '-app.lot.categories') }}</h5>

                        <a class="no-decoration"
                            href="{{ route('category', ['keycategory' => $category->key_ortsec0]) }}"
                            alt="{{ $category->des_ortsec0 }}">
                            <span class="badge badge-custom-primary">{{ $category->des_ortsec0 }}</span>
                        </a>
                    </div>
                @endif
            </section>

        </div>

        <div class="col order-0 order-md-1">

			<section class="ficha-image">
				@include('includes.ficha.ficha_image')
			</section>

			<section class="ficha-share d-none d-md-flex">
				@include('includes.ficha.share')
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

			<section class="ficha-share d-md-none">
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
