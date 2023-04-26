<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="description" content="{{ trans(\Config::get('app.theme') . '-app.head.meta_description') }}">
    <meta name="author" content="{{ trans(\Config::get('app.theme') . '-app.head.meta_author') }}">
    <meta name="viewport" content="width=device-width, user-scalable=no">
    <title>@yield('title', '')</title>

    @include('includes.google_head')
    @include('includes.tr.head')
</head>

<body class="tiempo_real">

    <div class="tiempo_real_content">
        @yield('content')
    </div>

    @include('includes.tr.modals')

    @if (Session::has('user') && $data['js_item']['user']['is_gestor'])
        @include('includes.tr.tiempo_real_admin.modals')
    @endif

    <script type="text/javascript">
        $(document).ready(function() {
            $(".form_datetime").datetimepicker({
                //format: "dd/mm/yyyy hh:ii",
                startDate: "<?php echo date('Y/m/d H:i'); ?>",
                endDate: "<?php echo $hastah; ?> 23:59",
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
