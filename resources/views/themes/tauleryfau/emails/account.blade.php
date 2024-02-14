@extends('layouts.mail')

@section('content')
        <p class="user">{{trans($theme.'-app.emails.hello')}}  {{$emailOptions['user']}}</p>
        <p><?= trans($theme.'-app.emails.text_new_user') ?></p>
        <p>{{trans($theme.'-app.emails.access_data')}}</p>
        <p><strong>{{trans($theme.'-app.emails.user')}}</strong> {{$emailOptions['email']}}<p>
        <p><strong>{{trans($theme.'-app.emails.password')}}</strong> {{$emailOptions['pwd']}} </p>
        <p><?= trans($theme.'-app.emails.cordially') ?>

@stop
