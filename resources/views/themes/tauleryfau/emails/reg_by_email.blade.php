@extends('layouts.mail')

@section('content')
        {{trans(\Config::get('app.theme').'-app.emails.account_details_in')}}  {{Config::get('app.name')}}						</p>
        {{trans(\Config::get('app.theme').'-app.emails.access_data')}}:<br />
        <p><strong>{{trans(\Config::get('app.theme').'-app.emails.email_address')}}: <a href="mailto:{{$email}}">{{$email}}</a></strong></p>
        <p><strong>{{trans(\Config::get('app.theme').'-app.emails.password')}}:</strong></span> {{$pwd}}</p>
 @stop
						