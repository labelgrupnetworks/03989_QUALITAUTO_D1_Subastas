<script>
    //fecha servidor
    var fecha_server = @json(getdate()[0] * 1000);
    //fecha del sistema del usuario
    var fecha_js = new Date().getTime();

    var traduction_large = {
        {!! trans("$theme-app.global.month_large") !!}
    };
    var traductions = {
        {!! trans("$theme-app.global.month") !!}
    };

    var routing = {
        subastashome: "{{ \Routing::slug('subastashome') }}",
        login: "{{ \Routing::slug('login') }}",
        registro: "{{ \Routing::slug('registro') }}",
        subasta: "{{ \Routing::slug('subasta') }}",
        usuario_registrado: "{{ \Routing::slug('usuario-registrado') }}",
    };

    var messages = {
        'error': @json(trans("$theme-app.msg_error")),
        'success': @json(trans("$theme-app.msg_success")),
        'neutral': @json(trans("$theme-app.msg_neutral"))
    };
</script>

<script src="{{ Tools::urlAssetsCache('/js/lang/' . \Config::get('app.locale') . "/$theme-app.js") }}"></script>

<script src="{{ URL::asset('vendor/jquery/3.6.0/dist/jquery.min.js') }}"></script>
<script src="{{ Tools::urlAssetsCache('/js/common.js') }}"></script>
<script defer src="{{ Tools::urlAssetsCache('/js/actions.js') }}"></script>
<script defer src="{{ URL::asset('vendor/tiempo-real/popup/magnific-popup.js') }}"></script>
<script defer src="{{ URL::asset('vendor/tiempo-real/pnotify/pnotify.custom.js') }}"></script>
<script defer src="{{ URL::asset('vendor/tiempo-real/pnotify/modernizr.js') }}"></script>
<script defer src="{{ URL::asset('vendor/jquery-countdown/jquery.countdown.min.js') }}"></script>
<script defer src="{{ URL::asset('vendor/timeago/timeago.js') }}"></script>
<script defer src="{{ URL::asset('js/validator.js') }}"></script>
<script defer src="{{ URL::asset('vendor/bootstrap/3.4.1/dist/js/bootstrap.min.js') }}"></script>
<script defer src="{{ URL::asset('vendor/bootstrap-select/js/bootstrap-select.min.js') }}"></script>
<script defer src='{{ URL::asset('vendor/year-calendar/jquery.bootstrap.year.calendar.js') }}'></script>
<script defer src='{{ URL::asset('vendor/year-calendar/bootstrap-year-calendar.es.js') }}'></script>
<script defer src="{{ URL::asset('vendor/jquery-ui/1.13.3/jquery-ui.min.js') }}"></script>

@stack('scripts')

<script defer src="{{ Tools::urlAssetsCache('/js/default/customized_tr_main.js') }}"></script>
<script defer src="{{ Tools::urlAssetsCache('/themes/' . $theme . '/customized_tr_main.js') }}"></script>
<script defer src="{{ URL::asset('js/owl.carousel.min.js') }}"></script>
<script defer src="{{ URL::asset('js/jquery.jqzoom-core.js') }}"></script>
<script defer type="text/javascript" src="{{ URL::asset('js/jquery.ui.touch-punch.min.js') }}"></script>
<script defer src="{{ Tools::urlAssetsCache('/js/default/custom.js') }}"></script>
<script defer src="{{ Tools::urlAssetsCache('/themes/' . $theme . '/custom.js') }}"></script>
<script defer src="{{ Tools::urlAssetsCache('/js/default/shopping_cart.js') }}"></script>
<script defer src="{{ URL::asset('vendor/zoom/jquery.imageLens.js') }}"></script>
<script defer src="{{ URL::asset('js/jquery.lazy.min.js') }}"></script>
<script defer src="{{ URL::asset('js/numeral.js') }}"></script>
<script defer src="{{ Tools::urlAssetsCache('js/forms.js') }}"></script>
<script src="{{ URL::asset('vendor/slick/slick.min.js') }}"></script>

<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
