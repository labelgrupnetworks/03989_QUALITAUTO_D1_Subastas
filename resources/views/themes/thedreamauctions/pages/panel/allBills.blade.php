@extends('layouts.default')

@section('title')
	{{ trans($theme.'-app.head.title_app') }}
@stop

@section('content')
<script>
    var pendientes =  @json($data["pending"])
</script>
<!-- titulo -->
<div class="color-letter">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 text-center">
                <h1 class="titlePage">{{ trans($theme.'-app.user_panel.mi_cuenta') }}</h1>
                </div>
            </div>
        </div>
    </div>




<div class="account-user color-letter  panel-user pendientes_pago">
        <div class="container">
            <div class="row">

				<!-- Menu -->
                <div class="col-xs-12 col-md-3 col-lg-3 account-user-menu">
                        <?php $tab="bills";?>
                                            @include('pages.panel.menu_micuenta')
                </div>

                <div class="col-xs-12 col-md-9 col-lg-9 ">

					<!-- Facturas -->
                    <div class="user-account-title-content">
                        <div class="user-account-menu-title">{{ trans($theme.'-app.user_panel.pending_bills') }}</div>
                    </div>

					<!-- Pendientes -->
                    <div class="user-accounte-titles-link" data-id="collapse_pays">
                        <ul class="ul-format d-flex justify-content-space-between align-items-center" role="tablist"  role="button" data-toggle="collapse" href="#collapse_pays" aria-expanded="false" aria-controls="collapse_pays">
                            <li role="pagar"class="active" ><a class="color-letter" href="{{ \Routing::slug('user/panel/pending_bills') }}" style="text-transform: uppercase">{{ trans($theme.'-app.user_panel.still_paid') }}</a></li>
                            <span>
                                    <span class="label-open" style="display: none" >{{ trans($theme.'-app.user_panel.open') }}</span>
                                    <span class="label-close" >{{ trans($theme.'-app.user_panel.hide') }}</span>
                                <img width="10" src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjI0cHgiIGhlaWdodD0iMjRweCIgdmlld0JveD0iMCAwIDk2LjE1NCA5Ni4xNTQiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDk2LjE1NCA5Ni4xNTQ7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4KPGc+Cgk8cGF0aCBkPSJNMC41NjEsMjAuOTcxbDQ1Ljk1MSw1Ny42MDVjMC43NiwwLjk1MSwyLjM2NywwLjk1MSwzLjEyNywwbDQ1Ljk1Ni01Ny42MDljMC41NDctMC42ODksMC43MDktMS43MTYsMC40MTQtMi42MSAgIGMtMC4wNjEtMC4xODctMC4xMjktMC4zMy0wLjE4Ni0wLjQzN2MtMC4zNTEtMC42NS0xLjAyNS0xLjA1Ni0xLjc2NS0xLjA1NkgyLjA5M2MtMC43MzYsMC0xLjQxNCwwLjQwNS0xLjc2MiwxLjA1NiAgIGMtMC4wNTksMC4xMDktMC4xMjcsMC4yNTMtMC4xODQsMC40MjZDLTAuMTUsMTkuMjUxLDAuMDExLDIwLjI4LDAuNTYxLDIwLjk3MXoiIGZpbGw9IiMwMDAwMDAiLz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8L3N2Zz4K" />
                                </span>
                        </ul>
                    </div>


                    <div class="col-xs-12 no-padding panel-collapse in" id="collapse_pays"  aria-expanded="true">
                        <div class="col-xs-12" style="padding-left:39px;">
                            <div class="user-account-heading hidden-xs d-flex align-items-center justify-content-space-between">
                                <div class="col-xs-12 col-sm-8  col-one user-account-item">
                                    {{ trans($theme.'-app.user_panel.pending_bills') }}
                                </div>
                                <div class="col-xs-12 col-sm-2 col-one user-account-fecha text-right">
                                    {{ trans($theme.'-app.user_panel.total_fact') }}
                                </div>
                                <div class="col-xs-12 col-sm-2  col-one user-account-max-bid text-right">
                                    {{ trans($theme.'-app.user_panel.total_price_fact') }}
                                </div>
                            </div>
                        </div>

                        <form  id="pagar_fact">
                            <input name="_token" type="hidden" value="{{ csrf_token() }}" />
                            @foreach($data['pending'] as $key_bill => $pendiente)

							@include('pages.panel.bills.bills',
								['anum' => $pendiente->anum_pcob,
								'num' => $pendiente->num_pcob,
								'efec' => $pendiente->efec_pcob,
								'fec' => $pendiente->fec_pcob,
								'imp' => $pendiente->imp_pcob,
								'bill' => $pendiente,
								'info_factura' => $data['inf_factura'],
								'tipo_tv' => $data['tipo_tv']
								])

                            @endforeach

                            <div class="col-xs-12   ">
                                    <div class="facturacion_info_data">
										<div class=" d-flex">
												<div style="flex:1"></div>
												<div>
													<div class="importe_total adj" style="">
														<span>{{ trans($theme.'-app.user_panel.total_pay_fact') }} </span>
														<span id="total_bills">00</span> <span> {{ trans($theme.'-app.subastas.euros') }}</span>
													</div>
													@if(\Config::get("app.PayBizum") || \Config::get("app.PayTransfer") || \Config::get("app.paymentPaypal"))

															<div class="mt-1">

																@if(\Config::get("app.paymentUP2") )
																	<input id="paycreditcard"  type="radio" name="paymethod" value="creditcard" checked="checked">
																	<label for="paycreditcard"> {{ trans($theme.'-app.user_panel.pay_creditcard') }}  <?php /* <span class="fab fa-cc-visa" style="font-size: 20px;"></span>*/ ?>  </span></label>
																	<br>
																@endif

																@if(\Config::get("app.PayBizum") )
																	<input id="paybizum"    type="radio" name="paymethod" value="bizum">
																	<label for="paybizum" >  {{ trans($theme.'-app.user_panel.pay_bizum') }}  <?php /*<img src="/default/img/logos/bizum-blue.png" style="height: 20px;">*/ ?> </label>
																	<br>
																@endif

																@if(\Config::get("app.PayTransfer"))
																	<input id="paytransfer"    type="radio" name="paymethod" value="transfer">
																	<label for="paytransfer"> {{ trans($theme.'-app.user_panel.pay_transfer') }} </label>
																	<br>
																@endif

																@if(\Config::get("app.paymentPaypal"))
																	<input id="paypaypal" type="radio" name="paymethod" value="paypal" checked="checked">
																	<label for="paypaypal"><i class="fa fa-paypal" aria-hidden="true"></i> {{ trans($theme.'-app.user_panel.pay_paypal') }} </label>
																@endif

															</div>

													@endif
													<div class="mt-1 text-right">

													<button id="btoLoader"  class="btn btn-custom hidden" type="button"><div class="loader"></div></button>

													<button id="submit_fact" style="" type="button" class="secondary-button " >{{ trans($theme.'-app.user_panel.pay') }}</button>

													</div>
												</div>
										</div>
                                    </div>
                                </div>
                        </form>

                    </div>

                    <div class="user-accounte-titles-link col-xs-12 no-padding" data-id="collapse_fac_pag">
                            <ul class="ul-format d-flex justify-content-space-between align-items-center" style="text-transform: uppercase" role="tablist"  role="button" data-toggle="collapse" href="#collapse_fac_pag" aria-expanded="false" aria-controls="collapse_fac_pag">
                                    <li role="pagadas"  ><a class="color-letter" style="text-transform: uppercase">{{ trans($theme.'-app.user_panel.bills') }}</a></li>
                                    <span>
                                        <span class="label-open"  >{{ trans($theme.'-app.user_panel.open') }}</span>
                                        <span class="label-close" style="display: none">{{ trans($theme.'-app.user_panel.hide') }}</span>
                                    <img width="10" src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjI0cHgiIGhlaWdodD0iMjRweCIgdmlld0JveD0iMCAwIDk2LjE1NCA5Ni4xNTQiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDk2LjE1NCA5Ni4xNTQ7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4KPGc+Cgk8cGF0aCBkPSJNMC41NjEsMjAuOTcxbDQ1Ljk1MSw1Ny42MDVjMC43NiwwLjk1MSwyLjM2NywwLjk1MSwzLjEyNywwbDQ1Ljk1Ni01Ny42MDljMC41NDctMC42ODksMC43MDktMS43MTYsMC40MTQtMi42MSAgIGMtMC4wNjEtMC4xODctMC4xMjktMC4zMy0wLjE4Ni0wLjQzN2MtMC4zNTEtMC42NS0xLjAyNS0xLjA1Ni0xLjc2NS0xLjA1NkgyLjA5M2MtMC43MzYsMC0xLjQxNCwwLjQwNS0xLjc2MiwxLjA1NiAgIGMtMC4wNTksMC4xMDktMC4xMjcsMC4yNTMtMC4xODQsMC40MjZDLTAuMTUsMTkuMjUxLDAuMDExLDIwLjI4LDAuNTYxLDIwLjk3MXoiIGZpbGw9IiMwMDAwMDAiLz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8L3N2Zz4K" />
                                    </span>
                            </ul>
                        </div>

                        <div class="col-xs-12 no-padding panel-collapse collapse" id="collapse_fac_pag"   aria-expanded="false">
                                <div class="col-xs-12" style="padding-left:39px;">
                                    <div class="user-account-heading hidden-xs d-flex align-items-center justify-content-space-between">
                                            <div class="col-xs-12 col-sm-8  col-one user-account-item">
                                                    {{ trans($theme.'-app.user_panel.pending_bills') }}
                                            </div>
                                            <div class="col-xs-12 col-sm-2 col-one user-account-fecha text-right">
                                                    {{ trans($theme.'-app.user_panel.total_fact') }}
                                            </div>
                                            <div class="col-xs-12 col-sm-2 col-one user-account-max-bid text-right">
                                                    {{ trans($theme.'-app.user_panel.paid_out') }}
                                            </div>
                                        </div>
                                </div>

								@foreach($data['bills'] as $key_bill => $bill)

								@include('pages.panel.bills.bills',
								['anum' => $bill->afra_cobro1,
								'num' => $bill->nfra_cobro1,
								'fec' => $bill->fec_cobro1,
								'imp' => $bill->imp_cobro1,
								'bill' => $bill,
								'info_factura' => $data['inf_factura_pag'],
								'tipo_tv' => $data['tipo_tv_pag']
								])

                                @endforeach

                        </div>

                </div>
            </div>
        </div>
</div>


<script>
    $( document ).ready(function() {
        reload_facturas();
    });


</script>
@stop
