@extends('layouts.mail')

@section('content')
        <p class="user">{{trans(\Config::get('app.theme').'-app.emails.hello')}}  {{$emailOptions['user']}}</p>
        <p><?= trans(\Config::get('app.theme').'-app.emails.text_new_user') ?></p>
        <p>{{trans(\Config::get('app.theme').'-app.emails.access_data')}}</p>    
        <p><strong>{{trans(\Config::get('app.theme').'-app.emails.user')}}</strong> {{$emailOptions['email']}}<p>
            
        <p><?= trans(\Config::get('app.theme').'-app.emails.cordially') ?>
        
@stop