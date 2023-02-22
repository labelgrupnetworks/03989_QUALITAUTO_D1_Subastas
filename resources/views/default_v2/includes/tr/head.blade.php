@php
    $isAdmin = !empty($data['js_item']['user']['is_gestor']) && $data['js_item']['user']['is_gestor'];
@endphp
<link href="{{ "/themes/$theme/img/favicon.ico" }}" rel="shortcut icon" />
<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.gstatic.com" />
<link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300..700&display=swap" rel="stylesheet">

<!-- Global Packages -->
@if ($isAdmin)
<link rel="stylesheet" type="text/css" href="{{ URL::asset('vendor/bootstrap/3.4.1/dist/css/bootstrap.min.css') }}" >
@else
<link type="text/css" href="{{ URL::asset('vendor/bootstrap/5.2.0/css/bootstrap.min.css') }}" rel="stylesheet">
@endif

<link href="{{ URL::asset('/vendor/bootstrap-select/css/bootstrap-select.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('/vendor/font-awesome/4.5.0/css/font-awesome.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('/vendor/IconHoverEffects/css/component.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('/vendor/datetimepicker/css/bootstrap-datetimepicker.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('/css/animate.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('vendor/tiempo-real/pnotify/pnotify.custom.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('vendor/tiempo-real/popup/magnific-popup.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('vendor/tiempo-real/autocomplete/jquery.auto-complete.css') }}" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href={{ URL::asset("/vendor/slick/slick.css") }} />
<link rel="stylesheet" type="text/css" href={{ URL::asset("/vendor/slick/slick-theme.css") }} />

@if ($isAdmin)
    <link href="{{ Tools::urlAssetsCache('/default_v2/css/tiempo-real-admin.css') }}" rel="stylesheet" />
    <link href="{{ Tools::urlAssetsCache("/themes/$theme/tiempo-real-admin.css") }}" rel="stylesheet" />
@else
    <link href="{{ Tools::urlAssetsCache('/default_v2/css/tiempo-real-user.css') }}" rel="stylesheet" />
    <link href="{{ Tools::urlAssetsCache("/themes/$theme/css/tiempo-real-user.css") }}" rel="stylesheet" />
@endif

@stack('stylesheets')

<script>
    var fecha_server = {{ getdate()[0] * 1000 }};
    var routing = {
        subastashome: "{{ \Routing::slug('subastashome') }}",
        login: "{{ \Routing::slug('login') }}",
        registro: "{{ \Routing::slug('registro') }}",
        subasta: "{{ \Routing::slug('subasta') }}",
    };
    var messages = {
        'error': @json(trans("$theme-app.msg_error")),
        'success': @json(trans("$theme-app.msg_success")),
        'neutral': @json(trans("$theme-app.msg_neutral"))
    };
</script>
<script src="{{ URL::asset('vendor/jquery/3.6.0/dist/jquery.min.js') }}"></script>
@if ($isAdmin)
<script src="{{ URL::asset('vendor/bootstrap/3.4.1/dist/js/bootstrap.min.js') }}"></script>
@else
<script src="{{ URL::asset('vendor/bootstrap/5.2.0/js/bootstrap.bundle.min.js') }}"></script>
@endif


<script src="{{ URL::asset('js/validator.js') }}"></script>
<script src="{{ URL::asset('vendor/bootstrap-select/js/bootstrap-select.min.js') }}"></script>
<script src="{{ Tools::urlAssetsCache('/js/common.js') }}"></script>

<script src="{{ URL::asset('vendor/IconHoverEffects/js/modernizr.custom.js') }}"></script>

<script src="{{ URL::asset('vendor/jquery-countdown/jquery.countdown.min.js') }}"></script>
<script src="{{ URL::asset('vendor/datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>

<!-- multi idioma plugin datetimepicker -->
<script
    src="{{ URL::asset('vendor/datetimepicker/js/locales/bootstrap-datetimepicker.' . \App::getLocale() . '.js') }}">
</script>
<script src="{{ URL::asset('vendor/zoom/jquery.imageLens.js') }}"></script>
<script src="{{ URL::asset('js/numeral.js') }}"></script>

@if (config('app.socket_v4', 0))
    <script src="{{ URL::asset('vendor/tiempo-real/node_modules/socket.io/client-dist/socket.io.js') }}"></script>
@else
    <script src="{{ URL::asset('vendor/tiempo-real/node_modules/socket.io/node_modules/socket.io-client/socket.io.js') }}">
    </script>
@endif

<script src="{{ URL::asset('vendor/tiempo-real/pnotify/pnotify.custom.js') }}"></script>
<script src="{{ URL::asset('vendor/tiempo-real/pnotify/modernizr.js') }}"></script>

@if ($isAdmin)
    <script src="{{ Tools::urlAssetsCache('/vendor/tiempo-real/tr_main_gestor.js') }}"></script>
@endif

<script src="{{ Tools::urlAssetsCache('/vendor/tiempo-real/tr_main.js') }}"></script>
<script src="{{ Tools::urlAssetsCache('/default_v2/js/customized_tr_main.js') }}"></script>
<script src="{{ Tools::urlAssetsCache("/themes/$theme/js/customized_tr_main.js") }}"></script>

<script src="{{ URL::asset('js/hmac-sha256.js') }}"></script>
<script src="{{ URL::asset('vendor/tiempo-real/popup/magnific-popup.js') }}"></script>
<script src="{{ URL::asset('vendor/tiempo-real/autocomplete/jquery.auto-complete.min.js') }}"></script>
<script src="{{ URL::asset('js/jquery.inputmask.js') }}"></script>
<script type="text/javascript">
    routing.node_url = '{{ Config::get('app.node_url') }}';
    routing.comprar = '{{ $data['node']['comprar'] }}';

    @if ($isAdmin)
        routing.pause_lot = '{{ $data['node']['pause_lot'] }}';
    @endif

    var auction_info = $.parseJSON('<?php echo str_replace('\u0022', "\\\\\"", json_encode($data['js_item'], JSON_HEX_QUOT)); ?>');
</script>

<script src="{{ URL::asset('vendor/timeago/timeago.js') }}"></script>


<script src="{{ URL::asset('vendor/slick/slick.js') }}"></script>

@stack('scripts')

{{-- solamente si utilizamos componentes de react --}}
{{-- @if(!$isAdmin)
<script defer src="{{ URL::asset('/default_v2/js/manifest.js') }}"></script>
<script defer src="{{ URL::asset('/default_v2/js/vendor.js') }}"></script>
<script defer src="{{ URL::asset('/default_v2/js/TiempoRealComponents.js') }}"></script>
@endif --}}
