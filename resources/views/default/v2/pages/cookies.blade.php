@extends('layouts.default')

@section('content')
<?php
$clientsCookies = json_decode(\Config::get('app.config_cookies', '{}'), true);
$bread[] = array("name" => trans(\Config::get('app.theme').'-app.cookies.bread') );
?>
@include('includes.breadcrumb')

<div class="contenido cookies-page">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12">
				<h1 class="titleSingle_corp">{{ trans(\Config::get('app.theme').'-app.cookies.title') }}</h1>
				<p>{{trans(\Config::get('app.theme').'-app.cookies.description') }}</p>
			</div>
		</div>

		<form class="form-inline" method="POST"
			action="{{route('cookieConfig', ['lang' => Config::get('app.locale')])}}">
			@csrf
			<div class="row block-cookie technical_cookies mt-3">
				<div class="col-xs-12">
					<h4>{{ trans(\Config::get('app.theme').'-app.cookies.technical_cookies') }}</h4>
					<p>{{ trans(\Config::get('app.theme').'-app.cookies.technical_cookies_description') }}</p>
					{!! trans(\Config::get('app.theme').'-app.cookies.technical_cookies_list') !!}


					<ul class="cookies-list">

						@foreach ($internalCookies as $typeCookie => $cookie)

						<li>
							<div class="row">
								<div class="col-xs-4"><span
										style="text-transform: capitalize">{{ trans(\Config::get('app.theme')."-app.cookies.cookie_name_$typeCookie") }}</span>
								</div>
								<div class="col-xs-4"></div>
								<div class="col-xs-4">
									<label class="radio-inline">
										<input type="radio" name="{{ $typeCookie }}" value="1" @if(empty($cookiesState)
											|| $cookiesState['all']==1 || !empty($cookiesState[$typeCookie]) &&
											$cookiesState[$typeCookie]==1) checked="checked" @endif
											@if($typeCookie=='esentials' ) disabled @endif>
										{{ trans(\Config::get('app.theme').'-app.cookies.activate') }}
									</label>
									<label class="radio-inline">
										<input type="radio" name="{{ $typeCookie }}" value="0" @if(!empty($cookiesState)
											&& empty($cookiesState['all']) && empty($cookiesState[$typeCookie]))
											checked="checked" @endif @if($typeCookie=='esentials' ) disabled @endif>
										{{ trans(\Config::get('app.theme').'-app.cookies.desactivate') }}
									</label>
								</div>
							</div>


							@foreach ($cookie as $cookieName => $description)
							<div class="row mt-2">
								<div class="col-xs-3">{{ $cookieName }}</div>
								<div class="col-xs-9">{{ $description }}</div>
							</div>
							@endforeach


						</li>

						@endforeach
					</ul>

				</div>
			</div>




			<div class="row block-cookie advertising_cookies mt-3 mb-5">
				<div class="col-xs-12">

					<h4>{{ trans(\Config::get('app.theme').'-app.cookies.advertising_cookies') }}</h4>
					@if(!empty($clientsCookies))
					<p>{{ trans(\Config::get('app.theme').'-app.cookies.advertising_cookies_description') }}</p>
					@else
					<p>{{ trans_choice(\Config::get('app.theme') . '-app.cookies.not_advertising_cookies', 1, ['theme' => trans(\Config::get('app.theme').'-app.head.title_app')]) }}</p>
					@endif

					<ul class="cookies-list">
						@foreach (array_keys($clientsCookies) as $value)

						<li>
							<div class="row">
								<div class="col-xs-4"><span style="text-transform: capitalize">{{$value}}</span></div>
								<div class="col-xs-4"></div>
								<div class="col-xs-4">
									<label class="radio-inline">
										<input type="radio" name="{{ $value }}" value="1" @if(empty($cookiesState) ||
											$cookiesState['all']==1 || !empty($cookiesState[$value]) &&
											$cookiesState[$value]==1) checked="checked" @endif>
										{{ trans(\Config::get('app.theme').'-app.cookies.activate') }}
									</label>
									<label class="radio-inline">
										<input type="radio" name="{{ $value }}" value="0" @if(!empty($cookiesState) &&
											empty($cookiesState['all']) && empty($cookiesState[$value]))
											checked="checked" @endif>
										{{ trans(\Config::get('app.theme').'-app.cookies.desactivate') }}
									</label>
								</div>
							</div>

							@foreach ($clientsCookies[$value] as $cookie)
							<div class="row mt-2">
								<div class="col-xs-1">{{$cookie}}</div>
								<div class="col-xs-6">
									{{ trans(\Config::get('app.theme').'-app.cookies.cookie_description'.$cookie) }}
								</div>
							</div>
							@endforeach

						</li>

						@endforeach
					</ul>

				</div>
				<div class="col-xs-12">
					<input type="submit" class="btn btn-color" value="Guardar">
				</div>

			</div>
		</form>

	</div>
</div>


@stop
