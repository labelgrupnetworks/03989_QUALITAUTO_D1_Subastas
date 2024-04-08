<link rel="stylesheet" href="{{ URL::asset('vendor/tiempo-real/pnotify/pnotify.custom.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('vendor/tiempo-real/popup/magnific-popup.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('vendor/tiempo-real/autocomplete/jquery.auto-complete.css') }}" />

<link rel="stylesheet" href="{{ URL::asset('/themes/'.$theme.'/tiempo-real.css') }}?a={{rand()}}" />

@stack('stylesheets')

<script src="{{ URL::asset('vendor/tiempo-real/node_modules/socket.io/node_modules/socket.io-client/socket.io.js') }}"></script>
<script src="{{ URL::asset('vendor/tiempo-real/pnotify/pnotify.custom.js') }}"></script>
<script src="{{ URL::asset('vendor/tiempo-real/pnotify/modernizr.js') }}"></script>
@if(!empty($data['js_item']['user']['is_gestor']) && $data['js_item']['user']['is_gestor'])
<script src="{{ URL::asset('vendor/tiempo-real/tr_main_gestor.js') }}?a={{rand()}}"></script>
@endif
<script src="{{ URL::asset('vendor/tiempo-real/tr_main.js') }}?a={{rand()}}"></script>
<script src="{{ URL::asset('/themes/'.$theme.'/customized_tr_main.js') }}?a={{rand()}}" ></script>

<script src="{{ URL::asset('js/hmac-sha256.js') }}"></script>
<script src="{{ URL::asset('vendor/tiempo-real/popup/magnific-popup.js') }}"></script>
<script src="{{ URL::asset('vendor/tiempo-real/autocomplete/jquery.auto-complete.min.js') }}"></script>
<script src="{{ URL::asset('js/jquery.inputmask.js') }}"></script>
<script type="text/javascript">

	routing.node_url 	 ='{{ Config::get("app.node_url") }}';
	routing.comprar		 = '{{ $data["node"]["comprar"] }}';
        @if(!empty($data['js_item']['user']['is_gestor']) && $data['js_item']['user']['is_gestor'])
	routing.pause_lot	 = '{{ $data["node"]["pause_lot"] }}';

        @endif

	var auction_info = $.parseJSON('<?php echo str_replace("\u0022","\\\\\"",json_encode($data["js_item"],JSON_HEX_QUOT)); ?>');



</script>

<script src="{{ URL::asset('vendor/timeago/timeago.js') }}"></script>

@stack('scripts')
