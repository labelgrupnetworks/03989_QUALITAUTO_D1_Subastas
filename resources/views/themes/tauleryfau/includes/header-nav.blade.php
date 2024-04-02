@php
    function wpLink($code)
    {
        $wpDomain = 'https://www.tauleryfau.com/';
        return $wpDomain . trans(config('app.theme') . "-app.links.$code");
    }
@endphp


<ul class="nav navbar-nav">
    <li>
        <a href="{{ wpLink('wp_home') }}" title="{{ trans($theme . '-app.home.home') }}"><i class="icon_house"></i></a>
    </li>

    <li class="auctions">
        <a href="{{ wpLink('wp_auctions') }}">{{ trans($theme . '-app.foot.auctions') }}</a>
    </li>

    <li class="dropdown">

        <a class="dropdown-toggle" data-toggle="dropdown" data-href="{{ wpLink('wp_calendar') }}" href="#"
            role="button" aria-haspopup="true" aria-expanded="false">
            {{ trans($theme . '-app.services.calendar') }}
            <span class="sub-arrow"><i class="fas fa-caret-down"></i></span>
        </a>

        <ul class="dropdown-menu">
            <li>
                <a href="{{ wpLink('wp_calendar') }}">{{ trans("$theme-app.subastas.next_auctions") }}</a>
            </li>

            <li>
                <a href="{{ wpLink('wp_events') }}">{{ trans("$theme-app.foot.events") }}</a>
            </li>
        </ul>
    </li>

    <li>
        <a href="{{ wpLink('wp_sell_coins') }}"
            title="{{ trans($theme . '-app.foot.how_to_sell') }}">{{ trans($theme . '-app.foot.how_to_sell') }}</a>
    </li>

    <li>
        <a href="{{ wpLink('wp_buy_coins') }}"
            title="{{ trans($theme . '-app.foot.how_to_buy') }}">{{ trans($theme . '-app.foot.how_to_buy') }}</a>
    </li>

    <li class="dropdown">

        <a class="dropdown-toggle" data-toggle="dropdown" data-href="{{ wpLink('wp_services') }}" href="#"
            role="button" aria-haspopup="true" aria-expanded="false">
            {{ trans($theme . '-app.services.title') }}
            <span class="sub-arrow"><i class="fas fa-caret-down"></i></span>
        </a>

        <ul class="dropdown-menu">
            <li>
                <a href="{{ wpLink('wp_valuations') }}">{{ trans("$theme-app.foot.valuations") }}</a>
            </li>

            <li>
                <a href="{{ wpLink('wp_photography') }}">{{ trans("$theme-app.foot.photography") }}</a>
            </li>

            <li>
                <a href="{{ wpLink('wp_coin_grading') }}">{{ trans("$theme-app.foot.coin_grading") }}</a>
            </li>
        </ul>
    </li>

    <li class="dropdown">

        <a class="dropdown-toggle" data-toggle="dropdown" data-href="{{ wpLink('wp_about_us') }}" href="#"
            role="button" aria-haspopup="true" aria-expanded="false">
            {{ trans($theme . '-app.foot.about_us') }}
            <span class="sub-arrow"><i class="fas fa-caret-down"></i></span>
        </a>

        <ul class="dropdown-menu">
            <li>
                <a href="{{ wpLink('wp_about_us') }}">{{ trans("$theme-app.foot.about_us") }}</a>
            </li>

            <li>
                <a href="{{ wpLink('wp_faq') }}">{{ trans("$theme-app.foot.faq") }}</a>
            </li>

            <li>
                <a href="{{ wpLink('wp_blog') }}">{{ trans("$theme-app.foot.blog") }}</a>
            </li>

            <li>
                <a href="{{ wpLink('wp_term_condition') }}">{{ trans("$theme-app.foot.auctions_conditions") }}</a>
            </li>
        </ul>
    </li>

    <li>
        <a href="{{ wpLink('wp_contact') }}"
            title="{{ trans($theme . '-app.foot.contact') }}">{{ trans($theme . '-app.foot.contact') }}</a>
    </li>

</ul>
