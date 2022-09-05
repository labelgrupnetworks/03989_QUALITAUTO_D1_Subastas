/**
	* This page accepts following arguments.
	* 1. "id": the stream id to play.It is mandatory
	* 2. "token": the token to play stream. It's mandatory if token security is enabled on server side.
	* 3. "autoplay": To start playing automatically if streams is available. Optional. Default value is true
	* 4. "mute": To start playing with mute if streams is available. Optional. Default value is true
	* 5. "playOrder": the order which technologies is used in playing. Optional. Default value is "webrtc,hls".
	*     possible values are "hls,webrtc","webrtc","hls","vod","dash"
	* 6. "playType": the order which play type is used in playing. Optional. Default value is "mp4,webm".
	*     possible values are "webm,mp4"","mp4","webm","mov"
	* 7. "targetLatency": To define target latency for the DASH player. Optional. Default value is 3.
	* 8. "is360": To play the stream in 360. Default value is false.
	*/
	//URL::asset('vendor/web-rtc/js/webrtc_adaptor.js'
	import { WebRTCAdaptor } from '/vendor/web-rtc/js/webrtc_adaptor.js';
	import { isMobile, tryToPlay } from '/vendor/web-rtc/js/fetch.stream.js';

	/**
	* Elements
	*/
	const placeHolder = document.getElementById("video_info");
	const player = document.getElementById("remoteVideo");

	/**
	* Config
	*/
	const streamId = player?.dataset.id;
	const isDebug = false;

	//let playOrder = ["webrtc", "hls"];
	let playOrder = ["webrtc"];
	//make play order global to let the other module access it
	window.playOrder = playOrder;

	const is360 = false;
	window.is360 = is360;

	var handleConnectivityCallDateTimeMs = 0;

	//Id de conexión con el cliente;

	const playType = ["mp4", "webm"];
	var token = undefined;
	const autoPlay = isMobile() ? false : true;
	const mute = false;
	const targetLatency = 3;
	var hlsExtension = "m3u8";
	var dashExtension = "mpd";
	var subscriberId = undefined;
	var subscriberCode = undefined;
	var iceConnected = false;
	var webRTCAdaptor = null;

	window.initializePlayer = initializePlayer
	window.playWebRTCVideo = playWebRTCVideo

	function log(message){
		if(isDebug) {
			console.log(message);
		}
	}

	function genericCallback(currentTech) {

		placeHolder.innerHTML = "Stream will start playing automatically<br/>when it is live";
		setTimeout(function () {

			(webRTCAdaptor == null)
				? initializeWebRTCPlayer(streamId, token, webrtcNoStreamCallback)
				: webRTCAdaptor.getStreamInfo(streamId);

		}, 3000);
	}

	function webrtcNoStreamCallback() {
		/**
	 * If HLS is in the play order then try to play HLS, if not wait for WebRTC stream
	 * In some cases user may want to remove HLS from the order and force to play WebRTC only
	 * in these cases player only waits for WebRTC streams
	 */
		genericCallback("webrtc");
	}

	function hlsNoStreamCallback() {
		genericCallback("hls");
	}

	function setHLSElementsVisibility(show) {
		document.getElementById("video_container").style.display = show == true ? "block" : "none";
	}

	function hideWebRTCElements() {
		setWebRTCElementsVisibility(false);
		document.getElementById("play_button").style.display = "none";
	}

	function setWebRTCElementsVisibility(show) {
		document.getElementById("remoteVideo").style.display = show == true ? "block" : "none";
	}

	function setPlaceHolderVisibility(show) {
		placeHolder.style.display = show == true ? "block" : "none";
	}

	function playWebRTCVideo() {
		setWebRTCElementsVisibility(true);

		if (mute) {
			document.getElementById("remoteVideo").muted = true;
		}
		else {
			document.getElementById("remoteVideo").muted = false;
		}

		if (autoPlay) {
			document.getElementById("remoteVideo").play().then(function (value) {
				//autoplay started
				document.getElementById("play_button").style.display = "none";

			}).catch(function (error) {
				document.getElementById("play_button").style.display = "block";
				log("User interaction needed to start playing");
			});
		}
	}


	function initializePlayer(streamId, extension, token, subscriberId, subscriberCode) {
		hideWebRTCElements();
		startPlayer(streamId, extension, token, subscriberId, subscriberCode)
	}

	function startPlayer(streamId, extension, token, subscriberId, subscriberCode) {

		var type;
		var liveStream = false;
		if (extension == "mp4") {
			type = "video/mp4";
			liveStream = false;
		}
		else if (extension == "webm") {
			type = "video/webm";
			liveStream = false;
		}
		else if (extension == "mov") {
			type = "video/mp4";
			alert("Browsers do not support to play mov format");
			liveStream = false;
		}
		else if (extension == "avi") {
			type = "video/mp4";
			alert("Browsers do not support to play avi format");
			liveStream = false;
		}
		else if (extension == "m3u8") {
			type = "application/x-mpegURL";
			liveStream = true;
		}
		else if (extension == "mpd") {
			type = "application/dash+xml";
			liveStream = true;
		}
		else {
			log("Unknown extension: " + extension);
			return;
		}

		var preview = streamId;
		if (streamId.endsWith("_adaptive")) {
			preview = streamId.substring(0, streamId.indexOf("_adaptive"));
		}

		var player = document.getElementById('remoteVideo');

		// If it's not dash, play with videojs
		if (extension != dashExtension) {

			player = videojs('video-player', {
				poster: "previews/" + preview + ".png",
				liveui: true,
				liveTracker: {
					trackingThreshold: 0
				},
			});

			videojs.Hls.xhr.beforeRequest = function (options) {
				options.uri = options.uri + "?token=" + token + "&subscriberId=" + subscriberId + "&subscriberCode=" + subscriberCode;
				return options;
			};

			player.src({
				src: "streams/" + streamId + "." + extension,
				type: type,
				withCredentials: true,
			});


			player.poster("previews/" + preview + ".png");

			if (mute) {
				player.muted(true);
			}
			else {
				player.muted(false);
			}

			if (autoPlay) {
				player.ready(function () {
					player.play();
				});
			}
		}
		else {
			player = dashjs.MediaPlayer().create();

			player.updateSettings({ 'streaming': { 'lowLatencyEnabled': true } });

			player.updateSettings({
				'streaming': {
					'liveDelay': targetLatency,
					'liveCatchUpMinDrift': 0.05,
					'liveCatchUpPlaybackRate': 0.5,
					"liveCatchupLatencyThreshold": 30,
				}
			});

			player.initialize(document.querySelector("#video-player"), "streams/" + streamId + "." + extension + "?token=" + token, false);

			if (mute) {
				player.setMute(true);
			}
			else {
				player.setMute(false);
			}

			if (autoPlay && player.isReady()) {
				player.play();
			}

			setInterval(function () {
				log("live latency: " + player.getCurrentLiveLatency());
			}, 2000);
		}

		setHLSElementsVisibility(true);
		setWebRTCElementsVisibility(false);
		if (typeof player.ready != "undefined") {
			player.ready(function () {
				var player = this;
				player.on('ended', function () {
					log("is360: " + is360 + " Playing has been finished");
					hideWebRTCElements();
					setHLSElementsVisibility(false);
					setPlaceHolderVisibility(true);
					tryToPlay(streamId, token, extension, subscriberId, subscriberCode, hlsNoStreamCallback);
				});
			});
		}

		setPlaceHolderVisibility(false);
	}

	function handleWebRTCConnectivity(noStreamCallback)
	{
		var currentTimeMs = (new Date()).getTime();
		//call if it's more than 3 seconds older
		if (currentTimeMs >  (handleConnectivityCallDateTimeMs + 3000))
		{
			handleConnectivityCallDateTimeMs = currentTimeMs;
			if (iceConnected)
			{
				//webrtc connection was successful and try to play again with webrtc
				setTimeout(function () {
					webRTCAdaptor.getStreamInfo(streamId);
				}, 3000);
				log("Trying to play with webrtc again");
			}
			else
			{
				//webrtc connection was not succesfull, switch the next play type(playOrder) if available
				if (typeof noStreamCallback != "undefined") {
					noStreamCallback();
					log("Trying to play with other tech than webrtc if available");
				}
			}
			//make the flag false to try other technologies
			iceConnected = false;
		}
	}


	function initializeWebRTCPlayer(streamId, token, subscriberId, subscriberCode, noStreamCallback) {

		setHLSElementsVisibility(false);

		var pc_config = {
			'iceServers': [{
				'urls': 'stun:stun1.l.google.com:19302'
			}]
		};

		var sdpConstraints = {
			OfferToReceiveAudio: true,
			OfferToReceiveVideo: true

		};
		var mediaConstraints = {
			video: false,
			audio: false
		};

		const appName = player?.dataset.appName;
		let protocol = "wss://";
		let port = "5443";

		//para reproducir en local
		if (!location.protocol.startsWith("https")) {
			port = '5080';
			protocol = "ws://";
		}

		const websocketURL = `${protocol}stream01.labelgrup.com:${port}/${appName}/websocket`;
		iceConnected = false;

		//webRTCAdaptor is a global variable
		webRTCAdaptor = new WebRTCAdaptor({
			websocket_url: websocketURL,
			mediaConstraints: mediaConstraints,
			peerconnection_config: pc_config,
			sdp_constraints: sdpConstraints,
			remoteVideoId: "remoteVideo",
			isPlayMode: true,
			debug: false,
			callback: function (info, description) {
				if (info == "initialized") {
					log("initialized");
					iceConnected = false;
					webRTCAdaptor.getStreamInfo(streamId);
					//metodo personalizado
					if(typeof initializedStreaming != 'undefined'){
						initializedStreaming();
					}
				}
				else if (info == "streamInformation") {
					log("stream information");
					webRTCAdaptor.play(streamId, token, "",[] ,subscriberId, subscriberCode);
				}
				else if (info == "play_started") {
					//joined the stream
					log("play started");
					setPlaceHolderVisibility(false);
					setHLSElementsVisibility(false);
					playWebRTCVideo();

					//metodo personalizado
					if(typeof playStreaming != 'undefined'){
						playStreaming();
					}

				} else if (info == "play_finished") {
					//leaved the stream
					log("play finished");
					setHLSElementsVisibility(false);
					hideWebRTCElements();
					setPlaceHolderVisibility(true);
					//if play_finished event is received, it has two meanings
					//1. stream is really finished
					//2. ice connection cannot be established and server reports play_finished event
					//check that publish may start again
					//below method handle the cases above
					handleWebRTCConnectivity(noStreamCallback);

					//metodo personalizado
					if(typeof finishStreaming != 'undefined'){
						finishStreaming();
					}
				}
				else if (info == "closed")
				{
					setHLSElementsVisibility(false);
					hideWebRTCElements();
					setPlaceHolderVisibility(true);

					log("Websocket connecton closed: " + (typeof description != "undefined" ?
								JSON.stringify(description) : ""));

					handleWebRTCConnectivity(noStreamCallback);

					//metodo personalizado
					if(typeof closeStreaming != 'undefined'){
						closeStreaming();
					}
				}
				else if (info == "bitrateMeasurement") {

					if (!document.getElementById("remoteVideo").paused) {
						document.getElementById("play_button").style.display = "none";
					}

					console.debug(description);
					if (description.audioBitrate + description.videoBitrate > description.targetBitrate) {
						document.getElementById("networkWarning").style.display = "block";
						setTimeout(function () {
							document.getElementById("networkWarning").style.display = "none";
						}, 3000);
					}
				}
				else if (info == "ice_connection_state_changed") {
					console.debug("ice connection state changed to " + description.state);
					if (description.state == "connected" || description.state == "completed") {
						//it means the ice connection has been established
						iceConnected = true;
					}
				}
				else if (info == "resolutionChangeInfo") {
					log("Resolution is changed to " + description["streamHeight"]);
					let getVideo = document.getElementById("remoteVideo");
					let overlay = document.getElementById('video-overlay');
					getVideo.pause();
					overlay.style.display = "block";
					setTimeout(function () { getVideo.play(); overlay.style.display = "none"; }, 2000);
				}
				else if (info == "server_will_close") {
					log("Server will close soon");
				}

			},
			callbackError: function (error) {
				//some of the possible errors, NotFoundError, SecurityError,PermissionDeniedError

				log("error callback: " + JSON.stringify(error));

				if (error == "no_stream_exist" || error == "WebSocketNotConnected"
						|| error == "not_initialized_yet" || error == "data_store_not_available")
				{
					handleWebRTCConnectivity(noStreamCallback);
				}
				if (error == "notSetRemoteDescription") {
					/*
					* If getting codec incompatible or remote description error, it will redirect HLS player.
					*/
					tryToPlay(streamId, token, hlsExtension, subscriberId, subscriberCode, hlsNoStreamCallback);
				}

			}
		});
	}

	function main() {

		if(typeof streamId === "undefined") {
			console.error("No stream specified. Please add ?id={STREAM_ID}  to the url");
			return;
		}

		initializeWebRTCPlayer(streamId, token, subscriberId, subscriberCode, webrtcNoStreamCallback);
	}

	window.addEventListener('load', function() {
		//run main method after everything is loaded
		main();
	});
