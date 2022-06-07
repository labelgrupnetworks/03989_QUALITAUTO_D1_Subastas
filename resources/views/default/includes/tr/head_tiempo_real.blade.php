<link rel="shortcut icon" href="<?='/themes/'.$theme.'/img/favicon.ico'?>" />
<link rel="stylesheet" type="text/css" href="{{ URL::asset('vendor/bootstrap/3.4.1/dist/css/bootstrap.min.css') }}" >
<!-- Selects -->
<link rel="stylesheet" href="{{ URL::asset('/vendor/bootstrap-select/css/bootstrap-select.min.css') }}" />
<!-- Font Awesome -->
<link rel="stylesheet" href="{{ URL::asset('/vendor/font-awesome/4.5.0/css/font-awesome.min.css') }}" />
<!--<link rel="stylesheet" href="{{ URL::asset('/vendor/IconHoverEffects/css/default.css') }}" />-->
<link rel="stylesheet" href="{{ URL::asset('/vendor/IconHoverEffects/css/component.css') }}" />
<!-- datetimepicker -->
<link rel="stylesheet" href="{{ URL::asset('/vendor/datetimepicker/css/bootstrap-datetimepicker.css') }}" />
<!-- Animate.css -->
<link rel="stylesheet" href="{{ URL::asset('/css/animate.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('vendor/tiempo-real/pnotify/pnotify.custom.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('vendor/tiempo-real/popup/magnific-popup.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('vendor/tiempo-real/autocomplete/jquery.auto-complete.css') }}" />

@if (config('app.socket_v4', 0))
<script src="{{ URL::asset('vendor/tiempo-real/node_modules/socket.io/client-dist/socket.io.js') }}"></script>
@else
<script src="{{ URL::asset('vendor/tiempo-real/node_modules/socket.io/node_modules/socket.io-client/socket.io.js') }}"></script>
@endif

<script src="{{ URL::asset('vendor/tiempo-real/pnotify/pnotify.custom.js') }}"></script>
<script src="{{ URL::asset('vendor/tiempo-real/pnotify/modernizr.js') }}"></script>
@if(!empty($data['js_item']['user']['is_gestor']) && $data['js_item']['user']['is_gestor'])
<script src="{{ Tools::urlAssetsCache('/vendor/tiempo-real/tr_main_gestor.js') }}"></script>
@endif
<script src="{{ Tools::urlAssetsCache('/vendor/tiempo-real/tr_main.js') }}"></script>
<script src="{{ Tools::urlAssetsCache('/js/default/customized_tr_main.js') }}" ></script>
<script src="{{ Tools::urlAssetsCache('/themes/'.$theme.'/customized_tr_main.js') }}" ></script>

<script src="{{ URL::asset('js/hmac-sha256.js') }}"></script>
<script src="{{ URL::asset('vendor/tiempo-real/popup/magnific-popup.js') }}"></script>
<script src="{{ URL::asset('vendor/tiempo-real/autocomplete/jquery.auto-complete.min.js') }}"></script>
<script src="{{ URL::asset('js/jquery.inputmask.js') }}"></script>
<script type="text/javascript">

    routing.node_url = '{{ Config::get("app.node_url") }}';
    routing.comprar = '{{ $data["node"]["comprar"] }}';

    @if (!empty($data['js_item']['user']['is_gestor']) && $data['js_item']['user']['is_gestor'])
        routing.pause_lot = '{{ $data["node"]["pause_lot"] }}';
    @endif

    var auction_info = $.parseJSON('<?php echo str_replace("\u0022", "\\\\\"", json_encode($data["js_item"], JSON_HEX_QUOT)); ?>');
</script>

<script src="{{ URL::asset('vendor/timeago/timeago.js') }}"></script>
