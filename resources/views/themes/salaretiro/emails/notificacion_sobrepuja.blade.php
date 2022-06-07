@extends('layouts.mail')

@section('content')
    <p>
        
            <?= trans_choice(\Config::get('app.theme').'-app.emails.email_overbid',
            1,
            ['name' => $emailOptions['user'],
            'bid' => $emailOptions['importe'],
            'lot_ref' => $emailOptions['lote']->ref_hces1,
            'link' => $emailOptions['link_lote'],
            'sub' => $emailOptions['sub']->name]) ?>
    </p>
@stop