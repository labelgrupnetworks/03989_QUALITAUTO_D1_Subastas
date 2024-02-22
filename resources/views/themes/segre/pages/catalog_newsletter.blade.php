@extends('layouts.default')

@section('title')
	{{ trans($theme . '-app.head.title_app') }}
@stop

@section('content')

	@include('includes.newsletter')


	<div class="cataog-newsletter-text-container">
		<div class="container">
			<div class="row">
				<div class="col-xs-12">
					<h2 class="text-center title-catalog">{{ trans($theme . '-app.home.welcome_segre') }}</h2>

					<p class="text-center subtitle-catalog">{!! trans($theme . '-app.foot.newsletter_desc') !!}</p>

					<div class="social-link-container text-center">
						@if (\Config::get('app.facebook'))
							<a class="facebook-social-link social-link" href="{{ \Config::get('app.facebook') }}" target="_blank">
								<i class="fa fa-facebook-square social-link-icon" aria-hidden="true"></i>
							</a>
						@endif
						@if (\Config::get('app.twitter'))
							<a class="twitter-social-link social-link" href="{{ \Config::get('app.twitter') }}" target="_blank">
								<i class="fa fa-twitter-square social-link-icon" aria-hidden="true"></i>
							</a>
						@endif
						@if (\Config::get('app.youtube'))
							<a class="youtube-social-link social-link" href="{{ \Config::get('app.youtube') }}" target="_blank">
								<i class="fa fa-youtube-square social-link-icon" aria-hidden="true"></i>
							</a>
						@endif
						@if (\Config::get('app.instagram'))
							<a class="instagram-social-link social-link" href="{{ \Config::get('app.instagram') }}" target="_blank">
								<i class="fa fa-instagram social-link-icon" aria-hidden="true"></i>
							</a>
						@endif
						@if (\Config::get('app.linkedin'))
							<a class="linkedin-social-link social-link" href="{{ \Config::get('app.linkedin') }}" target="_blank">
								<i class="fa fa-linkedin-square social-link-icon" aria-hidden="true"></i>
							</a>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>

@stop
