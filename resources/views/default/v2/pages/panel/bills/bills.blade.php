@php
$precio_total = 0;

foreach ($info_factura as $key_type => $inf_fact) {
    if (!empty($inf_fact[$anum][$num])) {
        foreach ($inf_fact[$anum][$num] as $fact) {
            if ($tipo_tv[$anum][$num] == 'P') {
                $precio_total = $precio_total + (round(($fact->basea_dvc1l * $fact->iva_dvc1l) / 100, 2) + $fact->basea_dvc1l) - $fact->padj_dvc1l;
            } elseif ($tipo_tv[$anum][$num] == 'L') {
                $precio_total = $precio_total + $fact->padj_dvc1l + $fact->basea_dvc1l + round(($fact->basea_dvc1l * $fact->iva_dvc1l) / 100, 2);
            } elseif ($tipo_tv[$anum][$num] == 'T') {
                $precio_total = $precio_total + $fact->total_dvc1 + round(($fact->total_dvc1 * $fact->iva_dvc1) / 100, 2);
            }
        }
    }
}
@endphp
<tr>
    <td class="td-check">
        @if (Config::get('app.pasarela_web') && !empty($efec) && $bill->compraweb_sub != 'N')
            <input class="form-check-input" name="factura[{{ $anum }}][{{ $num }}]"
                id="checkFactura-{{ $anum }}-{{ $num }}-{{ $efec }}" type="checkbox" checked
				onchange="reload_facturas()"/>
        @endif
    </td>
    <td class="td-img">
        <a target="_blank" href="/factura/{{ $anum }}-{{ $num }}"
            class="h-100 p-0 btn {{ !empty($bill->factura) && file_exists($bill->factura) ? '' : 'disabled' }}">
            <img class="img-responsive" src="/default/img/icons/pdf.png">
        </a>
    </td>
    <td data-title="{{ trans("web.user_panel.pending_bills") }}">

        {{ trans("web.user_panel.n_bill") }} {{ $anum }}/{{ $num }}
        {{-- texto de la factura de texto --}}
        @if (!empty($info_factura['T'][$anum][$num]))
            <br>
            @foreach ($info_factura['T'][$anum][$num] as $dvc2t)
                {{ $dvc2t->des_dvc2t ?? '' }}
            @endforeach
        @endif
    </td>

	<td data-title="{{ trans("web.user_panel.date") }}">
		{{ date('d-m-Y',strtotime ($fec)) }}
	</td>

	<td data-title="{{ trans("web.user_panel.total_fact") }}">
		{{Tools::moneyFormat($precio_total, trans("web.subastas.euros"), 2)}}
	</td>

	<td data-title="{{ trans("web.user_panel.total_price_fact") }}">
		{{ \Tools::moneyFormat($imp, trans("web.subastas.euros"), 2) }}
	</td>

</tr>
