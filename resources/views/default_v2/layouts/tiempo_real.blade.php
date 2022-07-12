<!doctype html>
<html>
    <head>

        <meta charset="utf-8">
        <meta name="description" content="{{ trans(\Config::get('app.theme').'-app.head.meta_description') }}">
        <meta name="author" content="{{ trans(\Config::get('app.theme').'-app.head.meta_author') }}">
        <meta name="viewport" content="width=device-width, user-scalable=no">

        <title>@yield('title', '')</title>

        @include('includes.google_head')
        <!-- Common Javascript -->
        <script>
            var fecha_server = <?= getdate()[0] * 1000 ?>;
            var routing = {
            subastashome: 	"{{ \Routing::slug('subastashome') }}",
                    login: 			"{{ \Routing::slug('login') }}",
                    registro: 		"{{ \Routing::slug('registro') }}",
                    subasta: 		"{{ \Routing::slug('subasta') }}",
            };
            var messages = {
            'error': {
<?php foreach (trans(\Config::get('app.theme') . "-app.msg_error") as $key => $value) : ?>
                '<?php echo $key; ?>': '<?php echo $value; ?>',
<?php endforeach; ?>
            },
                    'success': {
<?php foreach (trans(\Config::get('app.theme') . "-app.msg_success") as $key => $value) : ?>
                        '<?php echo $key; ?>': '<?php echo $value; ?>',
<?php endforeach; ?>
                    },
                    'neutral': {
<?php foreach (trans(\Config::get('app.theme') . "-app.msg_neutral") as $key => $value) : ?>
                        '<?php echo $key; ?>': '<?php echo $value; ?>',
<?php endforeach; ?>
                    }

            };
        </script>
        <script src="{{ URL::asset('vendor/jquery/3.6.0/dist/jquery.min.js') }}"></script>
        <script src="{{ URL::asset('vendor/bootstrap/3.4.1/dist/js/bootstrap.min.js') }}"></script>
        <script src="{{ URL::asset('js/validator.js') }}"></script>
        <script src="{{ URL::asset('vendor/bootstrap-select/js/bootstrap-select.min.js') }}"></script>
        <script src="{{ Tools::urlAssetsCache('/js/common.js') }}"></script>

        <script src="{{ URL::asset('vendor/IconHoverEffects/js/modernizr.custom.js') }}"></script>

        <script src="{{ URL::asset('vendor/jquery-countdown/jquery.countdown.min.js') }}"></script>
        <script src="{{ URL::asset('vendor/datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>

        <!-- multi idioma plugin datetimepicker -->
        <script src="<?php echo URL::asset('vendor/datetimepicker/js/locales/bootstrap-datetimepicker.' . \App::getLocale() . '.js') ?>"></script>

        <script src="{{ URL::asset('vendor/zoom/jquery.imageLens.js') }}"></script>
        <script src="{{ URL::asset('js/numeral.js') }}"></script>

        @include('includes.tr.head_tiempo_real')

    </head>

    <body class="tiempo_real">

        <div class="tiempo_real_content">
            @yield('content')
        </div>

        @include('includes.tr.modals')

        @if(Session::has('user') && $data['js_item']['user']['is_gestor'])
            @include('includes.tr.tiempo_real_admin.modals')
        @endif

        <script type="text/javascript">
            $(document).ready(function(){
            $(".form_datetime").datetimepicker({
            //format: "dd/mm/yyyy hh:ii",
            startDate: "<?php echo date('Y/m/d H:i') ?>",
                    endDate:   "<?php echo $hastah; ?> 23:59",
                    todayHighlight: true,
                    language: auction_info.lang_code.toLowerCase(),
                    todayBtn: true,
            });
            });
        </script>




        <audio id="new_bid">
            <source src="{{ URL::asset('vendor/tiempo-real/sounds/ding.mp3') }}" type="audio/ogg">
        </audio>
        <audio id="new_ol">
            <source src="{{ URL::asset('vendor/tiempo-real/sounds/orden.mp3') }}" type="audio/ogg">
        </audio>
        <audio id="end_lot">
            <source src="{{ URL::asset('vendor/tiempo-real/sounds/fin_lote.mp3') }}" type="audio/ogg">
        </audio>
        <audio id="alarm_fav_lot">
            <source src="{{ URL::asset('vendor/tiempo-real/sounds/alarm_fav_lot.ogg') }}" type="audio/ogg">
        </audio>
        <audio id="alarm_end_lot">
            <source src="{{ URL::asset('vendor/tiempo-real/sounds/alarm_end_lot.ogg') }}" type="audio/ogg">
        </audio>
        <audio id="alarm_notification">
            <source src="{{ URL::asset('vendor/tiempo-real/sounds/alarm_notification.ogg') }}" type="audio/ogg">
        </audio>

    </body>

</html>

<script>

    $('.icon-responsive-live').click(function(){
    $('.menu').addClass('active')
    })
            $('.close-menu').click(function(){
    $('.menu').removeClass('active')
    })


            function login_web(){
            $.ajax({
            type: "POST",
                    url: '/login_post_ajax',
                    data: $('.ob_disp #accerder-user-form').serialize(),
                    success: function(response)
                    {
                    if (response.status == 'success'){
                    location.reload();
                    } else{
                    $(".ob_disp #accerder-user-form .message-error-log").text('').append(messages.error[response.msg]);
                    }

                    }
            });
            }

</script>
