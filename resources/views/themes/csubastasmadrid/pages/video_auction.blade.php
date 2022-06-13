@extends('layouts.default')

@section('title')
{{ trans(\Config::get('app.theme') . '-app.head.title_app') }}
@stop


@section('content')

<div class="container">
	<div class="row">
		<div class="col-xs-12 mb-4 titlePage">

			<h1>{{ trans("$theme-app.lot_list.auction_videos") }}</h1>
			<p class="mini-underline"></p>
		</div>
		<div class="col-xs-12 full-border-bottom">
			<div class="col-xs-12 col-md-4">
				<img src="{{ Tools::url_img_auction('subasta_medium', $subastaReciente->cod_sub) }}"
					alt="AUCTION {{ $subastaReciente->cod_sub }} IMAGE">
			</div>
			<div class="col-xs-12 col-md-8">
				<h2>{{ $subastaReciente->des_sub }}</h2>
				<p>{{ trans("$theme-app.lot_list.auction_video_text") }}</p>
				<div class="selector">
					<form action="" id="videoselector_{{ $subastaReciente->cod_sub }}">
						<select multiple class="form-control" name="videos" id="videos">
							@foreach ($videoSorted as $video)
							<option value="{{ $video }}">{{ last(explode('/', $video)) }}</option>
							@endforeach
						</select>
					</form>
				</div>
			</div>
		</div>
		<div id="videocontainer" class="col-xs-12 text-center mt-3 hidden">
			<video id="viewVideo" class="viewVideo" src="" autoplay="true" loop="true" controls="true"></video>
		</div>
	</div>

</div>


<script>
	$('#videos').change(function () {
			$('#videocontainer').removeClass('hidden');
			var src = $('#videos').val();
			$('#viewVideo').attr('src', src);
		});
</script>


@stop
