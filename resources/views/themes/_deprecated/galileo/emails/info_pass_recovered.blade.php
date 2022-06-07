@extends('layouts.mail')

@section('content')
        <p><?= trans_choice(\Config::get('app.theme').'-app.emails.new_pass', 1, ['app_url' => $emailOptions['url'], 'password'=> $emailOptions['new_pass']]) ?> </p>
@stop