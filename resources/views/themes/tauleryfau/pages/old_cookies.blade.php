@extends('layouts.default')

@section('content')
<?php
$clientsCookies = json_decode(\Config::get('app.config_cookies', '{"google":["_ga", "_gid", "_gat"]}'), true);
$bread[] = array("name" => trans($theme.'-app.cookies.bread') );
$page = \App\Models\V5\Web_Page::select('CONTENT_WEB_PAGE')->where('KEY_WEB_PAGE', 'cookies')->where('upper(LANG_WEB_PAGE)', mb_strtoupper(\Config::get('app.locale')))->first();
?>
@include('includes.breadcrumb')

<div class="contenido">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 config-cookie-content">
				<h1 class="titleSingle_corp">{{ trans($theme.'-app.cookies.title') }}</h1>
				{!! $page->content_web_page ?? '' !!}
			</div>
		</div>

		<div class="row block-cookie advertising_cookies mt-3 mb-5">
			<form class="form-inline" method="POST" action="{{route('cookieConfig', ['lang' => Config::get('app.locale')])}}">
				@csrf
				<div class="col-xs-12">
					<h4>{{ trans($theme.'-app.cookies.advertising_cookies') }}</h4>
					<ul class="cookies-list">
						@foreach (array_keys($clientsCookies) as $value)

						<li>
							<div class="row">
								<div class="col-xs-4"><span>{{trans($theme."-app.cookies.cookie_name_$value")}}</span></div>
								<div class="col-xs-4"></div>
								<div class="col-xs-4">
									<label class="radio-inline">
										<input type="radio" name="{{ $value }}" value="1" @if(empty($cookiesState) || $cookiesState['all'] == 1 || !empty($cookiesState[$value]) && $cookiesState[$value] == 1) checked="checked" @endif>
										{{ trans($theme.'-app.cookies.activate') }}
									</label>
									<label class="radio-inline">
										<input type="radio" name="{{ $value }}" value="0" @if(!empty($cookiesState) && empty($cookiesState['all']) && empty($cookiesState[$value])) checked="checked" @endif>
										{{ trans($theme.'-app.cookies.desactivate') }}
									</label>
								</div>
							</div>

							@foreach ($clientsCookies[$value] as $cookie)
							<div class="row mt-2">
								<div class="col-xs-1">{{$cookie}}</div>
								<div class="col-xs-6">{{ trans($theme.'-app.cookies.cookie_description'.$cookie) }}</div>
							</div>
							@endforeach

						</li>

						@endforeach
					</ul>

				</div>
				<div class="col-xs-12">
					<input type="submit" class="btn btn-color" value="Guardar">
				</div>
			</form>
		</div>

	</div>
</div>


@stop

