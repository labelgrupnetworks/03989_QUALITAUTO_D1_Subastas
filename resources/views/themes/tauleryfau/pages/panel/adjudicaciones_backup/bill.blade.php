{{-- Facturas --}}
@php
//Tauler solo recibe facturas por pagar, pero me sirve como componente para otro cliente
$anum = ($isPayed) ? $bill->afra_cobro1 : $bill->anum_pcob;
$num = ($isPayed) ? $bill->nfra_cobro1 : $bill->num_pcob;
$efec = ($isPayed) ? null : $bill->efec_pcob;
$fec = ($isPayed) ? $bill->fec_cobro1 : $bill->fec_pcob;
$imp = ($isPayed) ? $bill->imp_cobro1 : $bill->imp_pcob;
@endphp

@if ($loop->first)

    {{-- Cabeceras Facturas --}}
    <div class="custom-head-wrapper bill-head">
        <div class="bill-pdf"></div>
        <div class="bill-description">
            <p>{{ trans("$theme-app.user_panel.factura") }}</p>
        </div>
        <div class="bill-date">
            <p>{{ trans("$theme-app.user_panel.date") }}</p>
        </div>
        <div class="bill-price text-right">
            <p>{{ trans("$theme-app.user_panel.total_fact") }}</p>
        </div>
        <div class="bill-pending-price text-right">
            <p>{{ trans("$theme-app.user_panel.total_price_fact") }}</p>
        </div>
    </div>
@endif

<div class="bill-wrapper" data-anum="{{ $anum }}" data-num="{{ $num }}" data-efec="{{ $efec ?? '' }}">

    <div class="factura_check hidden">
        @if (!empty($efec) && $bill->compraweb_sub != 'N')
			<input type="hidden" name="factura[{{ $anum }}][{{ $num }}]" value="1">
        @endif
    </div>

    <div class="bill-image h-100">
		<a target="_blank" href="/factura/{{ $anum }}-{{ $num }}" class="h-100 p-0 btn {{!empty($bill->factura) && file_exists($bill->factura) ? '' : 'disabled' }}">
			<img class="img-responsive" src="/img/icons/pdf.png">
		</a>
    </div>

    <div class="description" data-label="{{ trans("$theme-app.user_panel.factura") }}">
        {{-- texto de la factura de texto --}}
        @if (!empty($bill->inf_fact['T'][$anum][$num]))

            {{ trans("$theme-app.user_panel.n_bill") }}
            {{ $anum }}/{{ $num }}
            <br>

            @foreach ($bill->inf_fact['T'][$anum][$num] as $dvc2t)
                {{ $dvc2t->des_dvc2t ?? '' }}
            @endforeach
        @else
            {{ trans("$theme-app.user_panel.n_bill") }}
            {{ $anum }}/{{ $num }}
        @endif
    </div>
    <div class="description" data-label="{{ trans("$theme-app.user_panel.date") }}">
        {{ date('d-m-Y', strtotime($fec)) }}
    </div>

    <div class="price" data-label="{{ trans("$theme-app.user_panel.total_fact") }}">
        <div class="w-100 text-right">
            {{ \Tools::moneyFormat($bill->total_price, trans("$theme-app.subastas.euros"), 2) }}
			&nbsp;|&nbsp;<span value="{{$bill->total_price}}" class="js-divisa"></span>
		</div>
    </div>

    <div class="price" data-label="{{ trans("$theme-app.user_panel.total_price_fact") }}">
        <div class="w-100 text-right">
			<strong>
				{{ \Tools::moneyFormat($imp, trans("$theme-app.subastas.euros"), 2) }}
				&nbsp;|&nbsp;<span value="{{$imp}}" class="js-divisa"></span>
			</strong>
        </div>
    </div>
</div>
