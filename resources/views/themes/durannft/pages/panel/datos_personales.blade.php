@extends('layouts.default')

@section('title')
    {{ trans(\Config::get('app.theme') . '-app.head.title_app') }}
@stop

@section('content')

    <div class="color-letter">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 text-center">
                    <h1 class="titlePage">{{ trans(\Config::get('app.theme') . '-app.user_panel.mi_cuenta') }}</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="account-user color-letter panel-user">

		<div class="container">

			<div class="row">

				<div class="col-xs-12 col-md-3 col-lg-3 account-user-menu">
					@php($tab = "datos-personales")
					@include('pages.panel.menu_micuenta')
				</div>

				@if (config('app.useNft', false))
				<div class="col-xs-12 col-md-9">

					<div class="user-account-title-content">
						<div class="user-account-menu-title">
							{{ trans("$theme-app.user_panel.wallet") }}
						</div>
					</div>

					<div class="col-xs-12 pt-2">
						<form method="post" name="save-wallet" id="save-wallet" class="form-wallet">
							@csrf
							<div class="inputs-custom-group d-flex justify-content-space-between flex-wrap align-items-end">
								<div class="form-group input-group col-xs-12 col-md-6">
									<label for="wallet_direction">{{ trans("$theme-app.user_panel.wallet_direction") }}</label>
									<input type="text" class="form-control" name="wallet_dir"
										placeholder="Dirección pública la wallet" value="{{ $data['user']->wallet_cli ?? '' }}">
								</div>

								<div class="form-group col-xs-12 col-md-6 d-flex align-items-end" style="gap: 5px">
									<button class="button-principal" type="submit" for="save-wallet">{{ trans("$theme-app.user_panel.save") }}</button>
									<button class="button-principal" id="create-wallet">{{ trans("$theme-app.user_panel.wallet_new") }}</button>
								</div>
							</div>
						</form>
						<div id="wallet-call-result"></div>
					</div>

				</div>
				@endif

            </div>

        </div>

	</div>
    @stop
