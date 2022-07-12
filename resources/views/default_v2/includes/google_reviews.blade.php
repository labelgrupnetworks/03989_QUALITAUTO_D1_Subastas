<section class="google_reviews container">
	<link href="{{ Tools::urlAssetsCache('/themes/'.$theme.'/css/components/google_reviews.css') }}" rel="stylesheet"
		type="text/css">
	<?php
		$googleReviews = Tools::googleReviews(7);
	?>

	@if (!empty($googleReviews['reviews']))
	<div class="row d-flex align-items-center rating-title">
		<div class="col-xs-4 col-sm-3 col-md-2">
			<img class="img-responsive" src="{{ URL::asset('/img/icons/google_logo_png.png') }}" alt="google">
		</div>
		<h1>{{ trans(\Config::get('app.theme').'-app.home.google_rating') }}</h1>
	</div>

	<div class="row mb-3">
		<div class="col-xs-12 d-flex align-items-center rating-reviews">

			<h1>{{$googleReviews["rating"]}}</h1>
			<div class="rate d-flex">
				@for ($i = 1; $i <= ceil($googleReviews['rating']); $i++) <img class="img-responsive"
					src="{{ URL::asset('/img/icons/star_rate.png') }}">

					@endfor
					@while ($i <= 5) <img src="{{ URL::asset('/img/icons/star_empty.png') }}">

						@php
						$i++;
						@endphp
						@endwhile
			</div>


			<span class="">{{$googleReviews["user_rating_total"]}}
				{{ trans(\Config::get('app.theme').'-app.home.google_reviews') }}</span>
			<a class="btn btn-google-review" href="{{$googleReviews["url_write_review"]}}"
				target="_blank">{{ trans(\Config::get('app.theme').'-app.home.google_write_review') }}</a>

		</div>
	</div>





	<div class="row">
		<div class="col-xs-12">

			<div class="row">
				@php
				$reviewsFiltradas = collect($googleReviews["reviews"])->filter(function($review, $key){
					return $review['rating'] >= 4;
				})->take(3);
				@endphp

				@foreach ($reviewsFiltradas as $review)

				<div class="col-xs-12 col-sm-4 mb-1">
					<div class="google-card">
						<div class="google-card-header">

							<div class="row">
								<div class="col-xs-4 col-sm-3">
									<img class="img-responsive" title="{{$review['author_name']}}"
										src="{{$review['profile_photo_url']}}" alt="{{$review['author_name']}}">
								</div>
								<div class="col-xs-8 col-sm-9 p-0">
									<span class="author">{{$review['author_name']}}</span>
									<div class="rate d-flex">

										@for ($i = 1; $i <= $review['rating']; $i++)
											<img class="img-responsive" src="{{ URL::asset('/img/icons/star_rate.png') }}">
										@endfor
										@while ($i <= 5) <img src="{{ URL::asset('/img/icons/star_empty.png') }}">
											@php
												$i++;
											@endphp
										@endwhile

									</div>
									<span class="time_ago">{{$review['relative_time_description']}}</span>
								</div>
							</div>

						</div>
						<div class="google-card-body">
							<p class="google-card-text">{{ $review['text'] }}</p>
						</div>
						<div class="google-card-footer text-muted">
							<div class="row">
								<a class="d-flex align-items-center" href="{{$review['author_url']}}" target="_blank">
									<div class="col-xs-3 col-md-2">
										<img class="img-responsive" src="{{ URL::asset('/img/icons/google.svg') }}"
											alt="Google">
									</div>
									<div class="col-xs-3 p-0">
										<p class="m-0">{{ trans(\Config::get('app.theme').'-app.home.google_view') }}
										</p>
										<p class="m-0">Google</p>
									</div>
								</a>
							</div>
						</div>
					</div>
				</div>

				@endforeach


			</div>

		</div>

	</div>
	@endif


</section>
