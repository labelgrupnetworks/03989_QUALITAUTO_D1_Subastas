@extends('layouts.mail')

@section('content')
        {{trans($theme.'-app.emails.account_details_in')}}  {{Config::get('app.name')}}						</p>
        {{trans($theme.'-app.emails.access_data')}}:<br />
        <p><strong>{{trans($theme.'-app.emails.email_address')}}: <a href="mailto:{{$email}}">{{$email}}</a></strong></p>
        <p><strong>{{trans($theme.'-app.emails.password')}}:</strong></span> {{$pwd}}</p>
 @stop
