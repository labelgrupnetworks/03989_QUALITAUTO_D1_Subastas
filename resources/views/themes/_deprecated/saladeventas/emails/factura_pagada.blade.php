@extends('layouts.mail')
@section('content')
<?php
 $imp_total = 0;
 $cod_fact = '';
 ?>

@foreach($emailOptions['content']['factura'] as $bills)
    @foreach($emailOptions['content']['informacio_factura'] as $key_type => $inf_fact)
        @if(!empty($inf_fact[$bills->serie_pcob1][$bills->numero_pcob1]))
            @foreach($inf_fact[$bills->serie_pcob1][$bills->numero_pcob1] as $cont => $fact)

            @if($cont == 0)
                <?php $cod_fact = $bills->serie_pcob1.'/'.$bills->numero_pcob1 ?>
            @endif

            @if($emailOptions['content']['tipo_tv'][$bills->serie_pcob1][$bills->numero_pcob1] == 'P')
                <?php $calc_precio =  ((round((($fact->basea_dvc1l*$fact->iva_dvc1l)/100),2)) + $fact->basea_dvc1l)- $fact->padj_dvc1l ?>
            @elseif($emailOptions['content']['tipo_tv'][$bills->serie_pcob1][$bills->numero_pcob1] == 'L')
                <?php $calc_precio = $fact->padj_dvc1l + $fact->basea_dvc1l + round((($fact->basea_dvc1l*$fact->iva_dvc1l)/100),2) ?>
            @elseif($emailOptions['content']['tipo_tv'][$bills->serie_pcob1][$bills->numero_pcob1] == 'T')
                <?php $calc_precio = $fact->imp_dvc1 + round((($fact->imp_dvc1*$fact->iva_dvc1)/100),2) ?>
            @endif

            @php($imp_total = $imp_total + $calc_precio)
            @endforeach
        @endif
    @endforeach
@endforeach

<?= trans_choice(\Config::get('app.theme').'-app.emails.invoice_pay_user', 1, ['name' => $emailOptions['user'], 'cod_fact' => $cod_fact, 'imp' => \Tools::moneyFormat($imp_total,false,2)]) ?>

@stop


