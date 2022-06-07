@extends('layouts.mail')

@section('content')
    <p>
        
            <?= trans_choice(\Config::get('app.theme').'-app.emails.email_overbid',
            1,
            ['name' => $emailOptions['user'],
            'bid' => $emailOptions['importe'],
            'lot_name' => $emailOptions['lote']->titulo_hces1,
            'link' => $emailOptions['link_lote'],
            'ref' => $emailOptions['lote']->ref_asigl0]) ?>
    </p>
@stop