@extends('layouts.default')

@section('title')
	{{ trans($theme.'-app.head.title_app') }}
@stop


@section('content')
    <?php
    $bread[] = array("name" => $data['name'] );
    ?>

	@php

		$redirect = function ($resource) {
			$openLogin = request()->has('openLogin') ? ['openLogin' => 'S'] : [];
			header("Location: " . "https://www.tauleryfau.com/".Config::get('app.locale').'/'.$resource , true, 302);
			exit();
		};

		$userIsNotAdmin = !session('user.admin');
		$redirectToTaulerSubastas = $userIsNotAdmin && $data['subc_sub'] == App\Models\V5\FgSub::SUBC_SUB_ACTIVO;
		if ($redirectToTaulerSubastas) {
			$redirect(Config::get('app.locale') == 'es' ?  'proximas-subastas' : 'upcoming-auctions');
		}
	@endphp

    <?php //@include('includes.breadcrumb')<?>

    @include('content.subastas')
@stop
