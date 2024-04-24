@extends('layouts.default')

@section('title')
    {{ $data['data']->name_web_page }}
@stop

@section('content')
    <main class="page-static">
        <script
            src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit&hl={{ config('app.locale') }}"
            async defer></script>

        <div id="pagina-{{ $data['data']->id_web_page }}" class="contenido">
            <div class="container">
				{!! $data['data']->content_web_page !!}
            </div>
        </div>

        @if ($data['data']->key_web_page == 'como-vender' || $data['data']->key_web_page == 'como-comprar')
            <script>
                var goog = getCookie('googtrans');
                $(document).ready(function() {

                    @if ($data['data']->key_web_page == 'como-vender')
                        change_video_vender(goog);
                    @elseif ($data['data']->key_web_page == 'como-comprar')
                        change_video_comprar(goog);
                    @endif


                    setInterval(timer, 3000);
                });

                function timer() {
                    var goog_temp = getCookie('googtrans');
                    if (goog_temp != goog) {
                        @if ($data['data']->key_web_page == 'como-vender')
                            change_video_vender(goog_temp);
                        @elseif ($data['data']->key_web_page == 'como-comprar')
                            change_video_comprar(goog_temp);
                        @endif
                        goog = getCookie('googtrans');
                    };
                    // change_video_vender();
                }

                function change_video_vender(goog) {

                    if (goog && goog != '/es/es') {
                        if (goog == '/es/ca') {
                            $("video").attr('src', '/themes/soleryllach/assets/como_vender_CA.mp4')
                        } else {
                            $("video").attr('src', '/themes/soleryllach/assets/como_vender_EN.mp4')
                        }
                    } else {
                        $("video").attr('src', '/themes/soleryllach/assets/como_vender_ES.mp4')
                    }

                }

                function change_video_comprar(goog) {
                    if (goog && goog != '/es/es') {
                        if (goog == '/es/ca') {
                            $("video").attr('src', '/themes/soleryllach/assets/como_comprar_CA.mp4')
                        } else {
                            $("video").attr('src', '/themes/soleryllach/assets/como_comprar_EN.mp4')
                        }
                    } else {
                        $("video").attr('src', '/themes/soleryllach/assets/como_comprar_ES.mp4')
                    }
                }
            </script>
        @endif
    </main>
@stop
