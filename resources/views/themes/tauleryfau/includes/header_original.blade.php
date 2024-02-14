<header>
    <?php
    $lang = Config::get('app.locale');
    ?>
	{{-- header mobile --}}
    <div class="header-responsive hidden-md hidden-lg">
        <div class="col-xs-3 dch">
            <div class="hamburguer text-center">
                <i class="fa fa-bars"></i>
            </div>

        </div>

        <div class="col-xs-6 visible-xs visible-sm" style="height: 100%">
            <div class="logo-responsive-adapted d-flex align-items-center h-100">

                <a title="{{ \Config::get('app.name') }}" href="/{{ $lang }}">
                    <img class="img-responsive" src="/themes/{{ $theme }}/assets/img/logo.png"
                        alt="{{ \Config::get('app.name') }}">
                </a>

            </div>

        </div>

        <div class="col-xs-3 izq">

            @if (!Session::has('user'))
                <div class="user">
                    <a data-toggle="modal" data-target="#modalLogin">
                        <i class="fa fa-user-circle"></i>
                    </a>

                </div>
            @elseif(Session::has('user'))
                <div class="user">
                    <a href="{{ \Routing::slug('user/panel/orders') }}">
                        <i class="fa fa-user-circle"></i>
                    </a>

                </div>
            @endif

            @if (Session::get('user.admin'))
                <div class="user">
                    <a class="btn" href="/admin"><i class="fab fa-buysellads"></i></a>
                </div>
            @endif






        </div>

    </div>
    <div class="search-bar-responsive hidden-md hidden-lg">
        <form role="search" action="{{ \Routing::slug('busqueda') }}" class="search-component-form">
            <input type="text">
            <button><i class="fa fa-search"></i></button>
        </form>
    </div>

	{{-- hedader descktop --}}
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="header-desktop inline-flex">
                    <div class="logo col-xs-12 col-md-3 visible-md visible-lg ">

                        <a title="{{ \Config::get('app.name') }}" href="/{{ $lang }}">
                            <img class="img-responsive"
                                src="/themes/{{ $theme }}/assets/img/logo.png"
                                alt="{{ \Config::get('app.name') }}">
                        </a>

                    </div>
                    <div class="col-xs-9 header-block flex valign hidden-xs hidden-sm">
                        <div class="search-bar hidden">
                            <form role="search" action="{{ \Routing::slug('busqueda') }}">
                                <div class="search-button-content flex">
                                    <input
                                        placeholder="{{ trans($theme . '-app.head.search_label') }}"
                                        type="text" name="texto" class="form-control">
                                    <button type="submit" class="btn">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="session">
                            <ul class="panel-principal flex">
                                @if (!Session::has('user'))
                                    <li class="prueba session-start">
                                        <a title="<?= trans($theme . '-app.login_register.login') ?>"
                                            class="btn btn-color flex valign" data-toggle="modal"
                                            data-target="#modalLogin"><?= trans($theme . '-app.login_register.login') ?></a>
                                    </li>
                                @else
                                    <li class="prueba myAccount">
                                        <a href="{{ \Routing::slug('user/panel/orders') }}"
                                            class="btn btn-color btn-account flex">{{ trans($theme . '-app.login_register.my_panel') }}</a>
                                    </li>
                                    @if (Session::get('user.admin'))
                                        <li class="prueba admin">
                                            <a class="btn btn-color" href="/admin" target="_blank">
                                                {{ trans($theme . '-app.login_register.admin') }}</a>
                                        </li>
                                    @endif

                                @endif
                            </ul>
                        </div>
                        <div class="lenguaje">
                            <div class="selector" onclick="javascript:$('#selector_lenguaje').toggle();">
                                <img class="img-responsive"
                                    src="/themes/{{ $theme }}/assets/img/flag_{{ \Config::get('app.locale') }}.png" />
                                {{ \Config::get('app.locales')[\Config::get('app.locale')] }}
                                <i class="fa fa-sort-down"></i>
                            </div>
                            <div id="selector_lenguaje">
                                @foreach (Config::get('app.locales') as $key => $value)
                                    @if ($key != \Config::get('app.locale'))
                                        <a title="<?= trans($theme . '-app.head.language_es') ?>"
                                            href="{{ "/$key" . \App\libs\TradLib::getRouteTranslate(substr($_SERVER['REQUEST_URI'], 4), \App::getLocale(), $key) }}">
                                            <div class="row">
                                                <div class="col-xs-4">
                                                    <img alt="<?= $key ?>" class="img-responsive"
                                                        src="/themes/{{ $theme }}/assets/img/flag_<?= $key ?>.png" />
                                                </div>
                                                <div class="col-xs-8">
                                                    {{ \Config::get('app.locales')[$key] }}
                                                </div>
                                            </div>
                                        </a>
                                    @endif
                                @endforeach

                                {{-- <a href="/{{\Config::get('app.locale')}}?#googtrans(es|ru)">
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <img alt="ru" class="img-responsive" src="/themes/{{$theme}}/assets/img/flag_ru.png"/>
                                        </div>
                                        <div class="col-xs-8">
                                            pусский
                                        </div>
                                    </div>
                                </a> --}}

                                <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit">
                                </script>

                                <script type="text/javascript">
                                    function googleTranslateElementInit() {
                                        new google.translate.TranslateElement({
                                            pageLanguage: '{{ \Config::get('app.locale') }}',
                                            includedLanguages: 'ru,zh',
                                            layout: google.translate.TranslateElement.InlineLayout.SIMPLE
                                        }, 'google_translate_element1');
                                    }
                                </script>



                            </div>
                        </div>
                        <div class="redes">
                            <ul>
                                <li>
                                    <a target="_blank" href="{{ \Config::get('app.facebook') }}">
                                        <i class="fab fa-facebook-square"></i>
                                    </a>
                                </li>
                                <li>
                                    <a target="_blank" href="https://www.instagram.com/tauleryfau_numismatics/?hl=es">
                                        <img src="/img/instagram.png" class="instagram">
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<nav class="menu top-bar">
    <span class="tablet hidden-lg hidden-md"><svg class="close-menu close-dims"><svg viewBox="0 0 43 43" id="close"
                xmlns="https://www.w3.org/2000/svg" width="100%" height="100%">
                <path fill-rule="evenodd"
                    d="M42.997 5.724L26.546 21.511l16.355 15.765-4.126 5.728L21.5 26.353 4.148 43.004.003 37.276l16.451-15.787L.099 5.724 4.225-.004 21.5 16.647 38.852-.004l4.145 5.728z">
                </path>
            </svg></svg></span>
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="menu-title hidden-lg hidden-md">{{ trans($theme . '-app.head.menu') }}
                </div>
                <div class="nav navbar">
                    <ul class="flex valign">

                        <li>
                            <a title="{{ trans($theme . '-app.home.home') }}"
                                href="/{{ $lang }}"><i class="fas fa-home"></i><?php /*trans($theme.'-app.home.home')*/ ?></a>
                        </li>

                        @if (Session::has('user'))
                            <li class="hidden-md hidden-lg">

                                <a href="{{ \Routing::slug('user/panel/orders') }}">
                                    {{ trans($theme . '-app.user_panel.orders') }}
                                </a>

                            </li>
                        @endif

                        <?php

                        $subastaObj = new \App\Models\Subasta();
                        $has_subasta = $subastaObj->auctionList('S', 'O');
                        $has_subasta = array_merge($has_subasta, $subastaObj->auctionList('S', 'W'));
                        if (Session::get('user.admin')) {
                            $has_subasta = array_merge($has_subasta, $subastaObj->auctionList('A', 'O'));
                            $has_subasta = array_merge($has_subasta, $subastaObj->auctionList('A', 'W'));
                        }

                        if (!empty($has_subasta) && count($has_subasta) >= 2) {
                            $url_subasta = \Routing::translateSeo('subastas-activas');
                        } elseif (!empty($has_subasta) && count($has_subasta) == 1) {
                            $url_subasta = \Routing::translateSeo('info-subasta') . $has_subasta[0]->cod_sub . '-' . str_slug($has_subasta[0]->name);
                        } else {
                            $url_subasta = '';
                        }

                        ?>
                        @if (!empty($has_subasta))
                            <li class="auctions">
                                <a
                                    href="{{ $url_subasta }}">{{ trans($theme . '-app.foot.auctions') }}</a>
                            </li>
                        @endif


                        <?php
                        $has_subasta = $subastaObj->auctionList('S', 'P');
                        if (empty($has_subasta) && Session::get('user.admin')) {
                            $has_subasta = $has_subasta = array_merge($has_subasta, $subastaObj->auctionList('A', 'P'));
                        }
                        ?>
                        @if (!empty($has_subasta))
                            <li>
                                <a
                                    href="{{ \Routing::translateSeo('subastas-permanentes') }}">{{ trans($theme . '-app.foot.online_auction') }}</a>
                            </li>
                        @endif

                        {{-- Tauler no debe mostrar las subastas historicas
                        @php
                            $has_subasta = $subastaObj->auctionList ('H');
                        @endphp
                        @if (!empty($has_subasta))
                            <li>
                                <a href="{{ \Routing::translateSeo('subastas-historicas') }}">{{ trans($theme.'-app.foot.historico')}}</a>
                            </li>
                        @endif --}}
                        <?php
                        $has_subasta = $subastaObj->auctionList('S', 'V');
                        if (empty($has_subasta) && Session::get('user.admin')) {
                            $has_subasta = $has_subasta = array_merge($has_subasta, $subastaObj->auctionList('A', 'V'));
                        }
                        if (!empty($has_subasta) && count($has_subasta) > 1) {
                            $url_lotes = \Routing::translateSeo('venta-directa');
                        } elseif (!empty($has_subasta) && count($has_subasta) == 1) {
                            $url_lotes = \Tools::url_auction(head($has_subasta)->cod_sub, head($has_subasta)->name, head($has_subasta)->id_auc_sessions, head($has_subasta)->reference);
                        } else {
                            $url_lotes = '';
                        }
                        ?>
                        @if (!empty($has_subasta))
                            <li>
                                <a
                                    href="{{ $url_lotes }}">{{ trans($theme . '-app.foot.direct_sale') }}</a>
                            </li>
                        @endif

                        <li>
                            <a
                                href="<?= \Routing::translateSeo(trans($theme . '-app.links.services')) ?>">{{ trans($theme . '-app.services.title') }}</a>
                        </li>

                        @php
                            $urlCalendar = 'https://www.tauleryfau.com/blog/proximas-subastas/';
                            if (config('app.locale') != 'es') {
                                $urlCalendar = 'https://www.tauleryfau.com/blog/en/upcoming-auctions/';
                            }
                        @endphp

                        <li>
                            <a
                                href="{{ $urlCalendar }}">{{ trans($theme . '-app.services.calendar') }}</a>
                        </li>
                        <li><a
                                href="{{ \Routing::translateSeo('blog') }} ">{{ trans($theme . '-app.blog.blogTitle') }}</a>
                        </li>
                        <?php /*
                                                    <li><a title="{{ trans($theme.'-app.foot.how_to_buy') }}" href="<?php echo Routing::translateSeo('pagina').trans($theme.'-app.links.how_to_buy')  ?>
                        ?>">{{ trans($theme . '-app.foot.how_to_buy') }}</a></li>
                        <li><a title="{{ trans($theme . '-app.foot.how_to_sell') }}"
                                href="<?php echo Routing::translateSeo('pagina') . trans($theme . '-app.links.how_to_sell'); ?>">{{ trans($theme . '-app.foot.how_to_sell') }}</a>
                        </li>
                        */ ?>
                        <li>
                            <a title="{{ trans($theme . '-app.foot.contact') }}"
                                href="<?= \Routing::translateSeo('pagina') . trans($theme . '-app.links.contact') ?>">{{ trans($theme . '-app.foot.contact') }}</a>
                        </li>

                    </ul>

                    <div class="hidden search-img-mobile ">
                        <i class="fa fa-search"></i>
                        <i class="fa fa-times-circle"></i>

                    </div>
                    <div class="search-img-lenguaje lenguaje hidden-lg hidden-md">

                        <div class="d-flex">
                            @foreach (Config::get('app.locales') as $key => $value)
                                <li>
                                    <a title="{{ trans($theme . '-app.head.language_' . $key) }}"
                                        href="{{ "/$key" . \App\libs\TradLib::getRouteTranslate(substr($_SERVER['REQUEST_URI'], 4), \App::getLocale(), $key) }}">
                                        <img alt="{{ $key }}" class="img-responsive"
                                            src="/themes/{{ $theme }}/assets/img/flag_<?= $key ?>.png" />
                                    </a>
                                </li>
                            @endforeach
                            </ul>
                        </div>
                        <div class="facebook-mobile">
                            <a target="_blank" href="{{ \Config::get('app.facebook') }}" class="icon-facebook-min-nav">
                                <i class="fab fa-2x fa-facebook-square"></i>
                            </a>
                            <a target="_blank" href="https://www.instagram.com/tauleryfau_numismatics/?hl=es">
                                <img src="/img/instagram.png" class="">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</nav>


<div id='calendar-screen' class="calendar-screen" style='display: none'>
    <div class="calendar-container">
        <div role="button" id="calendarClose" class="calendar-close">
            <i class="far fa-2x fa-times-circle"></i>
        </div>
        <div class="calendar-title">
            <?= trans($theme . '-app.home.calendar_title') ?>
        </div>
        <div class="calendar-year">
            <div class="button-calendar">
                <button onclick="viewMoreMonths('more')">
                    <i class="fas fa-arrow-up"></i>
                </button>
                <button onclick="viewMoreMonths()">
                    <i class="fas fa-arrow-down"></i>
                </button>
            </div>
            <div id='calendar'></div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalCalendar" tabindex="-1" role="dialog" aria-labelledby="modalCalendar">
    <div class="modal-dialog d-flex justify-content-center align-items-center" role="document" style='height:100%'>
        <div class="modal-content" style='position: relative'>

            <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                style='padding: 10px; background: white; position: absolute; top: 0; right: 0; z-index: 1;opacity: 1;'>
                <i class="fas fa-times"></i>
            </button>

            <div class="modal-body" style="padding: 0; position: relative">

                <a>
                    <img class="img-responsive">
                </a>
            </div>
        </div>
    </div>
</div>


<script>
    <?php

    $key = 'lotes_destacados';
    $replace = [
        'lang' => Config::get('app.language_complete')[Config::get('app.locale')],
        'emp' => Config::get('app.emp'),
    ];

    $keydate = 'banner_calendar_' . strtoupper(Config::get('app.locale'));
    $html = '';
    $content = \Tools::slider($keydate, $html);
    echo $content;
    ?>



    var replace = <?= json_encode($replace) ?>;
    var key = "<?= $key ?>";

    $(document).ready(function() {
        //Calendario
        //Variable dates se obtiene desde al blade de slider
        if (typeof dates !== "undefined") {
            for (var i = 0; i < dates.length; i++) {
                var date = new Date(dates[i].date)

                dates[i].startDate = new Date(date.getFullYear(), date.getMonth(), date.getDate())
                dates[i].endDate = new Date(date.getFullYear(), date.getMonth(), date.getDate())
            }

            var cal = $('#calendar').calendar({
                language: '{{ Config::get('app.locale') }}',
                enableContextMenu: true,
                enableRangeSelection: true,
                startMonth: <?= date('m') ?>,
                minDay: new Date().getDay(),
                maxDaysToChoose: false,
                startYear: <?= date('Y') ?>,
                dataSource: dates,
                style: 'custom',
                customDataSourceRenderer: function(a, b) {
                    $(a).addClass('calendar-event');
                },
                clickDay: function(el) {
                    if (el.events.length > 0) {
                        $('#modalCalendar').find('a').removeAttr('href')
                        $('#modalCalendar').modal('show')
                        var url = ''
                        if (el.events[0].url !== null) {
                            url = el.events[0].url
                            $('#modalCalendar').find('a').prop('href', url)
                        }
                        var img = el.events[0].img
                        $('#modalCalendar').find('img').prop('src', img)

                    }

                }


            });

        }

        $('#calendarClose').click(function() {
            $('#calendar-screen').hide()
        })

    });

    $('#openCalendar').click(function(ev) {
        ev.preventDefault();
        $('#calendar-screen').show()
        $('.menu.top-bar').removeClass('active')

    })

    function viewEvent(event) {
        var dataSource = $('#calendar').data('calendar').getDataSource();
    }


    function viewMoreMonths(action) {
        if (action !== 'more') {
            $('#calendar').addClass('translate')
        } else {
            $('#calendar').removeClass('translate')
        }


    }
</script>
