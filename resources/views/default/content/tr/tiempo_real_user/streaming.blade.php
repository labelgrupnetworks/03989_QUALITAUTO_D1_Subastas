@push('stylesheets')
	<link href='{{ URL::asset('vendor/web-rtc/css/player.css') }}' rel='stylesheet' />
	<link href='{{ URL::asset('vendor/web-rtc/css/external/video-js.css') }}' rel='stylesheet' />
@endpush

@push('scripts')
	<script src="{{ URL::asset('vendor/web-rtc/js/fetch.js') }}"></script>
	<script src="{{ URL::asset('vendor/web-rtc/js/promise.min.js') }}"></script>
	<script src="{{ URL::asset('vendor/web-rtc/js/adapter-latest.js') }}"></script>
	<script type="module" src="{{ URL::asset('js/default/streaming.js') }}"></script>
	<script src="{{ URL::asset('vendor/web-rtc/js/external/video.js') }}"></script>
@endpush

<div class="stream-block">

	<p class="delay">{{ trans(\Config::get('app.theme').'-app.sheet_tr.delay') }}</p>
	<div class="stream-wrapper">

		<div id='video-overlay'>
			<img src="/default/img/icons/stream_loading.gif" alt="loading image" />
		</div>

		<div id="video_info">
			No esta hay video<br>
			Stream will start playing automatically<br />when it is live
		</div>

		<!-- HLS Player -->
		<div id="video_container">
			<video id="video-player" class="video-js vjs-default-skin vjs-big-play-centered" controls preload="auto">
				<p class="vjs-no-js">
					To view this video please enable JavaScript, and consider upgrading
					to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports
						HTML5 video</a>
				</p>
			</video>
		</div>

		<!-- WebRTC Player -->
		<video data-id="{{config('app.streaming_id', '')}}" data-app-name="{{config('app.streaming_name', '')}}" id="remoteVideo" controls
			playsinline></video>
		{{-- <video data-id="656405455542697866348373" data-app-name="Stream_LabelTest" id="remoteVideo" controls
			playsinline></video> --}}

		<!-- 360 player is added dynamically -->
		<div id="networkWarning">Your connection isn't fast enough to play this stream!</div>
		<img id="play_button" src="/default/img/icons/stream_play.png" onclick="playWebRTCVideo()"
			style="position: absolute; top: 30px; left: 30px; display: none;" />

	</div>
</div>
