@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')
<script>
    var info_fact = $.parseJSON('<?php echo str_replace("\u0022","\\\\\"",json_encode($data["js_item"],JSON_HEX_QUOT)); ?>');
</script>

<!-- titulo -->
<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <h1 class="titlecat">{{ trans(\Config::get('app.theme').'-app.user_panel.mi_cuenta') }}</h1>
        </div>
    </div>
</div>
<!-- Menu -->
<div class="container panel">
    <div class="row">
        <div class="col-xs-12">
            <?php $tab="pending_bills";?>
                @include('pages.panel.menu_micuenta')
        </div>
        <div class="col-xs-12">
            <div class="tabs">
                    <ul class="nav nav-tabs nav-justified" role="tablist">
                        <li role="pagar" class="active" ><a href="{{ \Routing::slug('user/panel/pending_bills') }}" >{{ trans(\Config::get('app.theme').'-app.user_panel.still_paid') }}</a></li>
                        <li role="pagadas"  ><a href="{{ \Routing::slug('user/panel/myBills') }}" >{{ trans(\Config::get('app.theme').'-app.user_panel.my_bills') }}</a></li>
                    </ul>
                </div>
        </div>
    </div>
</div>


<section class="pendientes_pago">
    <div class="pendientes_pago_content">
        <div class="container">
            <div class="row">
                <!-- Contenerdo de productos y facturas -->
                <div class="col-sm-12 productos">
                    <!-- Producto transportable -->
                    <form id="pagar_fact">
                    <input name="_token" type="hidden" value="{{ csrf_token() }}" />
                    @foreach($data['pending'] as $key_bill => $pendiente)
                     <div class="factura col-md-6" data-anum="{{$pendiente->anum_pcob}}" data-num="{{$pendiente->num_pcob}}">
                        <div class="factura_check" style="position: relative">
                            <label for="checkFactura-{{$pendiente->anum_pcob}}-{{$pendiente->num_pcob}}-{{$pendiente->efec_pcob}}">
                                <input name="factura[{{$pendiente->anum_pcob}}][{{$pendiente->num_pcob}}]" id="checkFactura-{{$pendiente->anum_pcob}}-{{$pendiente->num_pcob}}-{{$pendiente->efec_pcob}}" type="checkbox" class="hide add_factura" checked>
                                <span class="checkmark"></span>

                            </label>
                        </div>
                        <div class="factura_box col-xs-12 col-md-12">
                            <div class="visible-md visible-lg factura_icon col-xs-2 ">
                                <img width="40" src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTkuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDUxMi4wMDEgNTEyLjAwMSIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgNTEyLjAwMSA1MTIuMDAxOyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSIgd2lkdGg9IjE2cHgiIGhlaWdodD0iMTZweCI+CjxnPgoJPGc+CgkJPHBhdGggZD0iTTQ2OS4wNzIsOTEuOTI5bC04OS04OUMzNzguMTk2LDEuMDU0LDM3NS42NTIsMCwzNzMuMDAxLDBoLTMwM2MtMTYuNTQyLDAtMzAsMTMuNDU4LTMwLDMwdjQ1Mi4wMDEgICAgYzAsMTYuNTQyLDEzLjQ1OCwzMCwzMCwzMGgzNzJjMTYuNTQyLDAsMzAtMTMuNDU4LDMwLTMwVjk5QzQ3Mi4wMDEsOTYuMzQ4LDQ3MC45NDcsOTMuODA0LDQ2OS4wNzIsOTEuOTI5eiBNMzgzLjAwMSwzNC4xNDMgICAgTDQzNy44NTgsODloLTQ0Ljg1OGMtNS41MTQsMC0xMC00LjQ4Ni0xMC0xMFYzNC4xNDN6IE00NTIuMDAxLDQ4Mi4wMDFjMCw1LjUxNS00LjQ4NywxMC0xMCwxMGgtMzcyYy01LjUxNCwwLTEwLTQuNDg2LTEwLTEwVjMwICAgIGMwLTUuNTE0LDQuNDg2LTEwLDEwLTEwaDI5M3Y1OWMwLDE2LjU0MiwxMy40NTgsMzAsMzAsMzBoNTlWNDgyLjAwMXoiIGZpbGw9IiMwMDAwMDAiLz4KCTwvZz4KPC9nPgo8Zz4KCTxnPgoJCTxwYXRoIGQ9Ik0xMTEuMTA5LDY2Ljk3Yy0xLjg1OS0xLjg2LTQuNDM5LTIuOTMtNy4wNjktMi45M2MtMi42NDEsMC01LjIxLDEuMDctNy4wNywyLjkzYy0xLjg2LDEuODYtMi45Myw0LjQ0LTIuOTMsNy4wNyAgICBzMS4wNjksNS4yMSwyLjkzLDcuMDdzNC40MjksMi45Myw3LjA3LDIuOTNjMi42MywwLDUuMjEtMS4wNyw3LjA2OS0yLjkzYzEuODYtMS44NiwyLjkzMS00LjQ0LDIuOTMxLTcuMDcgICAgUzExMi45Nyw2OC44MywxMTEuMTA5LDY2Ljk3eiIgZmlsbD0iIzAwMDAwMCIvPgoJPC9nPgo8L2c+CjxnPgoJPGc+CgkJPHBhdGggZD0iTTI2MS45OTksNjQuMDM5SDE0MS45NzNjLTUuNTIyLDAtMTAsNC40NzctMTAsMTBzNC40NzgsMTAsMTAsMTBoMTIwLjAyNmM1LjUyMiwwLDEwLTQuNDc3LDEwLTEwICAgIFMyNjcuNTIyLDY0LjAzOSwyNjEuOTk5LDY0LjAzOXoiIGZpbGw9IiMwMDAwMDAiLz4KCTwvZz4KPC9nPgo8Zz4KCTxnPgoJCTxwYXRoIGQ9Ik00MDguMDAxLDE2MGgtMzA0Yy01LjUyMiwwLTEwLDQuNDc3LTEwLDEwdjE5MmMwLDUuNTIzLDQuNDc4LDEwLDEwLDEwaDMwNGM1LjUyMiwwLDEwLTQuNDc3LDEwLTEwVjE3MCAgICBDNDE4LjAwMSwxNjQuNDc3LDQxMy41MjIsMTYwLDQwOC4wMDEsMTYweiBNMTU2LjAwMSwzNTJoLTQyYzAsMCwwLTQ0LDAtNDRoNDJWMzUyeiBNMTU2LjAwMSwyODhoLTQyYzAsMCwwLTQ0LDAtNDRoNDJWMjg4eiAgICAgTTE1Ni4wMDEsMjI0aC00MnYtNDRoNDJWMjI0eiBNMjg2LjAwMSwzNTJoLTExMHYtNDRoMTEwVjM1MnogTTI4Ni4wMDEsMjg4aC0xMTB2LTQ0aDExMFYyODh6IE0yODYuMDAxLDIyNGgtMTEwdi00NGgxMTBWMjI0eiAgICAgTTM5OC4wMDEsMzUyaC05MnYtNDRoOTJWMzUyeiBNMzk4LjAwMSwyODhoLTkydi00NGg5MlYyODh6IE0zOTguMDAxLDIyNGgtOTJ2LTQ0aDkyVjIyNHoiIGZpbGw9IiMwMDAwMDAiLz4KCTwvZz4KPC9nPgo8Zz4KCTxnPgoJCTxwYXRoIGQ9Ik0zMDMuMDY5LDQxMC45M2MtMS44NTktMS44Ni00LjQzOS0yLjkzLTcuMDY5LTIuOTNzLTUuMjEsMS4wNy03LjA3LDIuOTNzLTIuOTMsNC40NC0yLjkzLDcuMDdzMS4wNjksNS4yMSwyLjkzLDcuMDcgICAgYzEuODYxLDEuODYsNC40NCwyLjkzLDcuMDcsMi45M3M1LjIxLTEuMDcsNy4wNjktMi45M2MxLjg2LTEuODYsMi45MzEtNC40NCwyLjkzMS03LjA3UzMwNC45MzEsNDEyLjc5LDMwMy4wNjksNDEwLjkzeiIgZmlsbD0iIzAwMDAwMCIvPgoJPC9nPgo8L2c+CjxnPgoJPGc+CgkJPHBhdGggZD0iTTQwOC4wMDEsNDA4aC03MGMtNS41MjIsMC0xMCw0LjQ3Ny0xMCwxMHM0LjQ3OCwxMCwxMCwxMGg3MGM1LjUyMiwwLDEwLTQuNDc3LDEwLTEwUzQxMy41MjIsNDA4LDQwOC4wMDEsNDA4eiIgZmlsbD0iIzAwMDAwMCIvPgoJPC9nPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+Cjwvc3ZnPgo=" />
                            </div>
                            <div class="factura_datos col-xs-12 col-md-9">
                                <div class="factura_titulo">
                                    <div>{{ trans(\Config::get('app.theme').'-app.user_panel.n_bill') }} {{$pendiente->anum_pcob}}/{{$pendiente->num_pcob}}</div>
                                </div>
                                <div class="factura_gastos">
                                    @php($precio_total = 0)
                                    @foreach($data['inf_factura'] as $key_type => $inf_fact)

                                        @if(!empty($inf_fact[$pendiente->anum_pcob][$pendiente->num_pcob]))
                                            @foreach($inf_fact[$pendiente->anum_pcob][$pendiente->num_pcob] as $fact)

                                                @if($data['tipo_tv'][$pendiente->anum_pcob][$pendiente->num_pcob] == 'P')
                                                    @php($precio_total = $precio_total + ((round((($fact->basea_dvc1l*$fact->iva_dvc1l)/100),2)) + $fact->basea_dvc1l)- $fact->padj_dvc1l)
                                                @elseif($data['tipo_tv'][$pendiente->anum_pcob][$pendiente->num_pcob] == 'L')
                                                     @php($precio_total = $precio_total + $fact->padj_dvc1l + $fact->basea_dvc1l + round((($fact->basea_dvc1l*$fact->iva_dvc1l)/100),2))
                                                @elseif($data['tipo_tv'][$pendiente->anum_pcob][$pendiente->num_pcob] == 'T')
                                                     @php($precio_total = $precio_total + $fact->imp_dvc1 + round((($fact->imp_dvc1*$fact->iva_dvc1)/100),2))
                                                @endif

                                            @endforeach
                                        @endif
                                    @endforeach
                                    <div class="producto_resumen_info">
                                            <span>{{ trans(\Config::get('app.theme').'-app.user_panel.total_fact') }}</span>
                                            <span> <?= \Tools::moneyFormat($precio_total,false,2) ?> €</span>
                                        </div>
                                    <div class="separador"></div>
                                     <div class="producto_resumen_info">
                                         <strong><span>{{ trans(\Config::get('app.theme').'-app.user_panel.total_price_fact') }} </span> </strong>
                                         <strong><span>{{\Tools::moneyFormat($pendiente->imp_pcob,false,2)}}</span><span> €</span></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    </form>
                </div>
                <div class="col-md-12 ">
                    <div class="facturacion_info_data">

                        <div class="importe_total adj" style="font-size: 23px;">
                            <span>{{ trans(\Config::get('app.theme').'-app.user_panel.total_pay_fact') }} </span>
                            <span id="total_bills">00</span><span> €</span>
                        </div>
                        <button id="submit_fact" style="margin-left: 15px; " type="button" class="btn btn-primary btn-lg " >{{ trans(\Config::get('app.theme').'-app.user_panel.pay') }}</button>
                            <button  class="btn btn-custom hidden" type="button"><div class="loader"></div></button>

                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
<script>
    $( document ).ready(function() {
        reload_facturas();
    });


</script>
@stop
