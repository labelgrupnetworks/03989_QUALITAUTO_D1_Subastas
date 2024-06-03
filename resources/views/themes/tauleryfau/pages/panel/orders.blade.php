@extends('layouts.panel')

@section('title')
    {{ trans($theme . '-app.head.title_app') }}
@stop

@php
    use App\libs\Currency;
    $currency = new Currency();
    $divisa = Session::get('user.currency', 'EUR');
    $divisas = $currency->setDivisa($divisa)->getAllCurrencies();
    $isFavoritePage = !empty($data['favorites']);
@endphp

@section('content')
    <script>
        routing.node_url = '{{ Config::get('app.node_url') }}';
        var auctions_info = @JSON($data['values']);
        auctions_info.user = @JSON(\Session::get('user'));
        var rooms = [];
        var currency = @JSON($divisas);
        var divisa = @JSON($divisa);
		var replaceZeroDecimals = true;

        $(function() {
            $("#actual_currency").trigger('change');
        });
    </script>

    <script src="{{ URL::asset('vendor/tiempo-real/node_modules/socket.io/node_modules/socket.io-client/socket.io.js') }}">
    </script>
    <script src="{{ Tools::urlAssetsCache("/themes/$theme/custom_node_panel.js") }}"></script>
    <script src="{{ URL::asset('js/hmac-sha256.js') }}"></script>

    <section class="orders-page">

        <div class="panel-title">
            <h1>
                @if ($isFavoritePage)
                    {{ trans("$theme-app.user_panel.favorites") }}
                @else
                    {{ trans("$theme-app.user_panel.orders") }}
                @endif
            </h1>

            <select id="actual_currency">
                @foreach ($divisas as $divisaOption)
                    <option value='{{ $divisaOption->cod_div }}' @selected($divisaOption->cod_div == $divisa)>
                        {{ $divisaOption->cod_div }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="orders-auctions-block">
            @php
                $finalized = [];
                $notFinalized = [];
            @endphp

            @foreach ($data['values'] as $key_sub => $all_inf)
                @php
                    //ver si la subasta está cerrada
                    $SubastaTR = new \App\Models\SubastaTiempoReal();
                    $SubastaTR->cod = $all_inf['inf']->cod_sub;
                    $SubastaTR->session_reference = $all_inf['inf']->reference;

                    $ended = $SubastaTR->getStatusSessions();
                    $subasta_finalizada = false;

                    if ($ended && $all_inf['inf']->tipo_sub != 'V') {
                        $subasta_finalizada = true;
                        array_unshift($finalized, $all_inf);
                    } else {
                        array_unshift($notFinalized, $all_inf);
                    }

                    $escalado = new \App\Models\Subasta();
                    $escalado->cod = $key_sub;
                @endphp


                @if ($all_inf['inf']->tipo_sub != 'V' && !$subasta_finalizada)
                    <script>
                        rooms.push('{{ $key_sub }}');
                    </script>

                    @include('pages.panel.orders.auction', ['subasta_finalizada' => false])
                @endif
            @endforeach

            @foreach ($finalized as $all_inf)
                @include('pages.panel.orders.auction', ['subasta_finalizada' => true])
            @endforeach


        </div>
    </section>


    <div class="container modal-block mfp-hide " id="modalPujarPanel">
        <div class="modal-sub-w" data-to="pujarLotePanel">
            <section class="panel">
                <div class="panel-body">
                    <div class="modal-wrapper">
                        <div class=" text-center single_item_content_">
                            <p class="class_h1">{{ trans($theme . '-app.lot.confirm_bid') }}</p><br />
                            <span class='desc_auc' for="bid">{{ trans($theme . '-app.lot.you_are_bidding') }} </span>
                            <strong><span class="precio"></span> €</strong><br>
                            <span class="ref_orden hidden"></span>
                            <br>
                            <button class="btn btn-color button_modal_confirm btn-custom"
                                id="confirm_puja_panel">{{ trans($theme . '-app.lot.confirm') }}</button>
                            <div class='mb-10'></div>
                            <div class='mb-10'></div>
                            <ul class="items_list">
                                <li>
                                    {!! trans("$theme-app.lot.tax_not_included") !!}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@stop
