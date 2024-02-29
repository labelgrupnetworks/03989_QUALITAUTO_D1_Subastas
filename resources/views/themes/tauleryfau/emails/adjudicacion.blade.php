@extends('layouts.mail')

@section('content')

<p class="title_adj">{{ trans($theme.'-app.emails.subject-adj') }}</p>


<p>
        {{ trans_choice($theme.'-app.emails.winner', 1, ['ref' => $emailOptions['ref_asigl1'], 'user_name' => $emailOptions['user'], 'lot_name' => $emailOptions['lot_name'], 'sub_name' =>$emailOptions['sub_name'], 'importe' => $emailOptions['importe']]) }}
</p>

<img src="{{ $emailOptions['img'] }}" alt="" />

@stop
