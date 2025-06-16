@php
    use App\libs\TradLib;

    $lang = Config::get('app.locale');
    $langName = Config::get('app.locales')[$lang];
    $flagsLanguage = [
        'es' =>
            'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAALCAMAAABBPP0LAAAAflBMVEX/AAD9AAD3AADxAADrAAD/eXn9bGz8YWH8WVn6UVH5SEj5Pz/3NDT0Kir9/QD+/nL+/lT18lDt4Uf6+j/39zD39yf19R3n5wDxflXsZ1Pt4Y3x8zr0wbLs1NXz8xPj4wD37t3jmkvsUU/Bz6nrykm3vJ72IiL0FBTyDAvhAABEt4UZAAAAX0lEQVR4AQXBQUrFQBBAwXqTDkYE94Jb73+qfwVRcYxVQRBRToiUfoaVpGTrtdS9SO0Z9FR9lVy/g5c99+dKl30N5uxPuviexXEc9/msC7TOkd4kHu/Dlh4itCJ8AP4B0w4Qwmm7CFQAAAAASUVORK5CYII=',
        'en' =>
            'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAALCAMAAABBPP0LAAAAt1BMVEWSmb66z+18msdig8La3u+tYX9IaLc7W7BagbmcUW+kqMr/q6n+//+hsNv/lIr/jIGMnNLJyOP9/fyQttT/wb3/////aWn+YWF5kNT0oqz0i4ueqtIZNJjhvt/8gn//WVr/6+rN1+o9RKZwgcMPJpX/VFT9UEn+RUX8Ozv2Ly+FGzdYZrfU1e/8LS/lQkG/mbVUX60AE231hHtcdMb0mp3qYFTFwNu3w9prcqSURGNDaaIUMX5FNW5wYt7AAAAAjklEQVR4AR3HNUJEMQCGwf+L8RR36ajR+1+CEuvRdd8kK9MNAiRQNgJmVDAt1yM6kSzYVJUsPNssAk5N7ZFKjVNFAY4co6TAOI+kyQm+LFUEBEKKzuWUNB7rSH/rSnvOulOGk+QlXTBqMIrfYX4tSe2nP3iRa/KNK7uTmWJ5a9+erZ3d+18od4ytiZdvZyuKWy8o3UpTVAAAAABJRU5ErkJggg==',
    ];

    $urlToOtherLanguage = TradLib::getRouteTranslate(
        substr($_SERVER['REQUEST_URI'], 4),
        $lang,
        $lang == 'es' ? 'en' : 'es',
    );
    if (!function_exists('wpLink')) {
        function wpLink($code)
        {
            $wpDomain = 'https://www.tauleryfau.com/';
            return $wpDomain . trans(config('app.theme') . "-app.links.$code");
        }
    }
@endphp
<header class="header-web" id="main-header">
    <div class="topbar">
        @if (!Session::has('user'))
            <a class="text-uppercase" data-toggle="modal" data-target="#modalLogin"
                title="{{ trans('web.login_register.login') }}" role="button">
                {{ trans('web.login_register.login') }}
            </a>
        @else
            <a
                href="{{ route('panel.summary', ['lang' => config('app.locale')]) }}">{{ trans('web.login_register.my_panel') }}</a>

            @if (Session::get('user.admin'))
                <a href="/admin" target="_blank">{{ trans('web.login_register.admin') }}</a>
            @endif
        @endif

        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
                aria-expanded="false">
                <img src="{{ $flagsLanguage[$lang] }}" alt="{{ $langName }}" style="width: 16px; height: 11px;"
                    width="16" height="11">
                {{ $langName }}
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu dropdown-location">
                @foreach (Config::get('app.locales') as $key => $value)
                    @if ($key != $lang)
                        <li>
                            <a href="{{ "/$key" . $urlToOtherLanguage }}">
                                <img src="{{ $flagsLanguage[$key] }}" alt="{{ $key }}"
                                    style="width: 16px; height: 11px;" width="16" height="11">
                                {{ $value }}
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </li>
    </div>

    <nav class="navbar navbar-default navbar-tauler">
        <div class="container-fluid">
            <div class="navbar-header">
                <button class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" type="button"
                    aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">
                    <img src="/themes/{{ $theme }}/assets/img/logo-web.png"
                        alt="{{ \Config::get('app.name') }}">
                </a>
            </div>
            <div class="navbar-collapse collapse" id="navbar" aria-expanded="false" style="height: 1px;">

                <ul class="nav navbar-nav navbar-right">

                    <li class="visible-xs visible-sm">
                        @if (!Session::has('user'))

                            <a class="text-uppercase" data-toggle="modal" data-target="#modalLogin"
                                title="{{ trans('web.login_register.login') }}" role="button">
                                {{ trans('web.login_register.login') }}
                            </a>
                        @else
                            <a
                                href="{{ route('panel.summary', ['lang' => config('app.locale')]) }}">{{ trans('web.login_register.my_panel') }}</a>

                            @if (Session::get('user.admin'))
                                <a href="/admin" target="_blank">{{ trans('web.login_register.admin') }}</a>
                            @endif
                        @endif
                    </li>

                    <li class="visible-xs visible-sm dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                            aria-haspopup="true" aria-expanded="false">
                            <img src="{{ $flagsLanguage[$lang] }}" alt="{{ $langName }}"
                                style="width: 16px; height: 11px;" width="16" height="11">
                            {{ $langName }}
                            <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            @foreach (Config::get('app.locales') as $key => $value)
                                @if ($key != $lang)
                                    <li>
                                        <a href="{{ "/$key" . $urlToOtherLanguage }}">
                                            <img src="{{ $flagsLanguage[$key] }}" alt="{{ $key }}"
                                                style="width: 16px; height: 11px;" width="16" height="11">
                                            {{ $value }}
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </li>

                    <li>
                        <a href="{{ wpLink('wp_home') }}">
                            {{ trans('web.home.home') }}
                        </a>
                    </li>
                    <li class="dropdown active">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                            aria-haspopup="true" aria-expanded="false">{{ trans('web.foot.auctions') }} <span
                                class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ wpLink('wp_auctions') }}">
                                    {{ trans('web.foot.auctions') }}
                                </a>
                            </li>
                            <li><a href="#">Histórico</a></li>
                            <li><a href="{{ wpLink('wp_events') }}">{{ trans('web.foot.events') }}</a></li>

                        </ul>
                    </li>

                    <li class="dropdown">
                        <a class="dropdown-toggle" href="{{ wpLink('wp_services') }}" role="button"
                            aria-haspopup="true" aria-expanded="false">{{ trans('web.services.title') }} <span
                                class="caret"></span></a>

                        <ul class="dropdown-menu">
                            <li>
                                <a href="{{ wpLink('wp_sell_coins') }}">
                                    {{ trans('web.foot.how_to_sell') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ wpLink('wp_buy_coins') }}">
                                    {{ trans('web.foot.how_to_buy') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ wpLink('wp_valuations') }}">
                                    {{ trans('web.foot.valuations') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ wpLink('wp_photography') }}">
                                    {{ trans('web.foot.photography') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ wpLink('wp_coin_grading') }}">
                                    {{ trans('web.foot.coin_grading') }}
                                </a>
                            </li>

                        </ul>
                    </li>

                    <li class="dropdown">
                        <a class="dropdown-toggle" href="{{ wpLink('wp_about_us') }}" role="button"
                            aria-haspopup="true" aria-expanded="false">{{ trans('web.foot.about_us') }} <span
                                class="caret"></span></a>

                        <ul class="dropdown-menu">
                            <li>
                                <a href="{{ wpLink('wp_faq') }}">
                                    {{ trans('web.foot.faq') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ wpLink('wp_blog') }}">
                                    {{ trans('web.foot.blog') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ wpLink('wp_term_condition') }}">
                                    {{ trans('web.foot.auctions_conditions') }}
                                </a>
                            </li>

                        </ul>
                    </li>

                    <li><a href="{{ wpLink('wp_contact') }}"
                            title="{{ trans('web.foot.contact') }}">{{ trans('web.foot.contact') }}</a></li>
                </ul>

            </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
    </nav>

</header>
<div class="sentinel" id="sentinel"></div>


