@extends('layouts.session_layout')

@php
	$isNftPage = $back && $back == 'nft';
	$isGalleryPage = $back && $back == 'gallery';
	$isDuranPage = !$back;
@endphp

@push('styles')
@if($back == 'nft')
	<link rel="stylesheet" type="text/css" href="{{ Tools::urlAssetsCache('/themes/'.$theme.'/nft.css') }}">
@endif
@endpush


@section('title')
{{ trans($theme.'-app.head.title_app') }}
@stop

@section('content')
<div class="create-account color-letter mt-3">
	<div class="container register pb-5">
		<div class="row d-flex align-items-center justify-content-center">
			<div class="col-xs-12 general-container">

				<div class="text-center mb-5">

				@if($isGalleryPage)
					<a id="logo_link" title="{{(\Config::get( 'app.name' ))}}" href="{{ request('context_url', '') }}">
						<img class="logo-landing" src="/themes/{{$theme}}/assets/img/logo_gallery.png"  alt="{{(\Config::get( 'app.name' ))}}">
					</a>
				@elseif($isNftPage)
				<a id="logo_link" title="{{(\Config::get( 'app.name' ))}}" href="{{ request('context_url', '') }}">
					<img class="logo-landing" src="/themes/{{$theme}}/assets/img/logo_nft.png"  alt="{{(\Config::get( 'app.name' ))}}">
				</a>
				@else
					<a id="logo_link" title="{{(\Config::get( 'app.name' ))}}" href="/">
						<img class="logo-landing" src="/themes/{{$theme}}/assets/img/logo.png"  alt="{{(\Config::get( 'app.name' ))}}">
					</a>
				@endif
				</div>

				<h1 class="titlePage mb-3">{{ trans($theme.'-app.login_register.login') }}</h1>

				<form data-toggle="validator" id="accerder-user-form" class="flex-display justify-center align-items-center flex-column">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">

					<?php /* Input que determina si se realiza también login en página de prestashop */ ?>
                	@if(!empty(\Config::get('app.ps_activate')))
						<input type="hidden" id="presta" value="1">
						<input type="hidden" id="back" name="back" value="{{ $back }}">
						<input type="hidden" name="context_url" value="{{ request('context_url', '') }}">
                	@endif

					<div class="form-group">
						<div class="input-login-group">
							<i class="fa fa-user"></i>
							<input class="form-control" placeholder="{{ trans($theme.'-app.login_register.user') }}" type="email" name="email" type="text">
						</div>
					</div>
					<div class="form-group ">
						<div class="input-login-group">
							<i class="fa fa-key"></i>
							<input class="form-control" placeholder="{{ trans($theme.'-app.login_register.contraseña') }}" type="password" name="password">
							<img class="view_password eye-password" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAAASCAQAAAD8x0bcAAAAxUlEQVR4AcWQIQxBURSGvyF5EwiSINMDNlU3sxmaLtoMk5iIRhAFM8Vkm170LOgU4Ozu7D7P63vfH+79z/23c+4hSJK0GYo6lAiDnyJrnnysLjT5Y24eHsyoiGYa3+FgWZnSkzyQEkFBYwdCGFraYAlM5HwzAhZa7SPEuKqtk7ETZanr7U4cEtzU1kjbUFqcGxJ6bju993/ajTGE2PsGz/EytTNRFIeNXUFVNNW/nYjhocGFj2eZAxx8RCjRZcuRHWVxQfEFCcppAFXu2JUAAAAASUVORK5CYII=">
						</div>
					</div>

					<span class="message-error-log text-danger seo_h5"></span></p>
					<div class="pass-login-content">
						<div class="text-center">
						<button id="accerder-user" class="button-principal" type="button">
							<div>{{ trans($theme.'-app.login_register.acceder') }}</div>
						</button>
						</div>
						<a class="c_bordered pass_recovery_login" data-ref="{{ \Routing::slug('password_recovery') }}" id="p_recovery" data-title="{{ trans($theme.'-app.login_register.forgotten_pass_question')}}" href="javascript:;" data-toggle="modal" data-target="#modalAjax" >{{ trans($theme.'-app.login_register.forgotten_pass_question')}}</a>
					</div>
				</form>

				<div class="login-separator mb-3"></div>
				<p class="text-center">{{ trans($theme.'-app.login_register.not_account') }}</p>
				<div class="create-account-link">
					<a class="" title="{{ trans($theme.'-app.login_register.register') }}" href="{{ \Routing::slug('register') }}?back={{$back}}&context_url={{ request('context_url', '') }}">{{ trans($theme.'-app.login_register.register') }}</a>
				</div>

			</div>
		</div>
	</div>
</div>



