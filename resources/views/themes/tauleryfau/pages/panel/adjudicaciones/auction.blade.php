{{-- al acabar comprobar el numero de consultas total del nuevo metodo y el antiguo --}}

@php
$withAuction = !empty($auction);
$codSub = $withAuction ? $auction->cod_sub : 'withoutsub';
$name = $withAuction ? $auction->name : 'Sin subasta';
$totalBillsImport = 0;
$pdfBills = [];
@endphp

<a aria-expanded="true" data-toggle="collapse" href="#{{ $codSub }}_{{ $isPayed }}">
    <div class="panel-heading">
        <h4 class="panel-title">
            {{ $name }}
        </h4>
        <i class="fas fa-sort-down"></i>
    </div>
</a>

<div id="{{ $codSub }}_{{ $isPayed }}"
    class="table-responsive-custom panel-collapse collapse js-auction-block">

    <form class="js-pay-bill" action="/gateway/pagarFacturasWeb" method="POST">

		@foreach ($bills as $bill)
            @php
                $totalBillsImport += $bill->imp_pcob;
				if(!empty($bill->factura)){
					$pdfBills[] = "/factura/$bill->anum_pcob-$bill->num_pcob";
				}
            @endphp

            @include('pages.panel.adjudicaciones.bill', ['bill' => $bill, 'isPayed' => $isPayed])

			{{-- Bótones --}}
			@if($loop->last && $totalBillsImport > 0)
			<div class="text-right factura-buttons">
                <input type="hidden" name="paymethod" value="creditcard">

				@foreach ($pdfBills as $url)
				<a href="{{ $url }}" download
					class="btn btn-color factura-button mb-1">{{ trans($theme . '-app.user_panel.invoice_pdf') }}</a>
				@endforeach

                <a class="btn btn-color btn-gold mb-1" data-toggle="modal" data-target="#largeModal" data-type="bill"
                    data-codsub="{{ $codSub }}" data-value={{$totalBillsImport}}
                    data-concept="{{ explode('-', $name)[0] }}-{{ \Session::get('user.cod') }}">{{ trans("$theme-app.user_panel.bank_transfer") }}</a>

                <button type="submit"
                    class="btn btn-color btn-blue mb-1">{{ trans("$theme-app.user_panel.pay_now") }}</a>

            </div>
			@endif

        @endforeach

    </form>

    @foreach ($allotments as $allotment)
        @include('pages.panel.adjudicaciones.allotment', ['lot' => $allotment, 'isPayed' => $isPayed])
    @endforeach

</div>
