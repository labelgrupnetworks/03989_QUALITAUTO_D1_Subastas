@php
    $showSalesPanel = Config::get('app.userPanelMySales') && (new App\Models\User())->hasSales(session('user.cod'));

    $isActiveTabClass = function ($tabName) use ($tab) {
        return $tabName == $tab ? 'tab-active' : '';
    };

    $favoritesRoute = Config::get('app.new_favorites_panel', 'favorites');
@endphp

<div class="tabs-custom-responsive visible-xs visible-sm" id="button-open-user-menu">
    <i class="fa fa-align-left" aria-hidden="true"></i>
</div>
<div class="tabs-custom filters-auction-content">
    <ul class="ul-format color-letter" id="user-account-ul">

        <li class="text-uppercase {{ $isActiveTabClass('orders') }}" role="presentation">
            <a class="" data-title="{{ trans($theme . '-app.panel.my_orders') }}"
                href="{{ Routing::slug('user/panel/orders') }}">
                <img class="hammer-icon hammer-icon-standard" src="/themes/{{ $theme }}/assets/img/hammer.png"
                    alt="{{ trans($theme . '-app.panel.my_orders') }}" width="20px">
                <img class="hammer-icon-hover" src="/themes/{{ $theme }}/assets/img/hammer-hover.png"
                    alt="{{ trans($theme . '-app.panel.my_orders') }}" width="20px">
                {{ trans($theme . '-app.user_panel.orders') }}
            </a>
        </li>

        @if ($showSalesPanel)
            <li class="text-uppercase {{ $isActiveTabClass('sales') }}" role="presentation">
                <a data-title="{{ trans("$theme-app.user_panel.my_sale_title") }}"
                    href="{{ route('panel.sales', ['lang' => config('app.locale')]) }}">
                    <i class="fa fa-money" aria-hidden="true"></i>
                    {{ trans("$theme-app.user_panel.my_sale_title") }}
                </a>
            </li>
        @endif
        <li class="text-uppercase {{ $isActiveTabClass('favorites') }}" role="presentation">
            <a class="" data-title="{{ trans($theme . '-app.panel.my_adj') }}"
                href="{{ Routing::slug("user/panel/$favoritesRoute") }}">
                <i class="fa fa-star"></i>
                <span class="menu-option-text">{{ trans($theme . '-app.user_panel.favorites') }}</span>
            </a>
        </li>

        <li class="text-uppercase {{ $isActiveTabClass('allotments') }} {{ $isActiveTabClass('bills') }}"
            role="presentation">
            <a class="" data-toggle="collapse" data-title="{{ trans($theme . '-app.panel.my_adj') }}"
                href="#collapse_adj" href="{{ Routing::slug('user/panel/allotments/outstanding') }}" role="button"
                aria-expanded="false" aria-controls="collapse_adj">
                <i class="fa fa-trophy"></i>
                <span class="menu-option-text">{{ trans($theme . '-app.user_panel.allotments') }}</span>
            </a>

        </li>

        <div class="{{ $tab != 'allotments' && $tab != 'bills' ? 'collapse' : '' }}" id="collapse_adj">
            <ul class="sub_menu_adj">
                <a class="@if ($tab == 'allotments') active @endif"
                    data-title="{{ trans($theme . '-app.panel.my_adj') }}"
                    href="{{ Routing::slug('user/panel/allotments') }}"
                    style="text-transform: uppercase">{{ trans($theme . '-app.user_panel.lots') }}</a>
                <a class="@if ($tab == 'bills') active @endif"
                    data-title="{{ trans($theme . '-app.panel.pending_bills') }}"
                    href="{{ Routing::slug('user/panel/bills') }}"
                    style="text-transform: uppercase">{{ trans($theme . '-app.user_panel.pending_bills') }}</a>
            </ul>
        </div>

        @if (Config::get('app.shoppingCart'))
            <li class="text-uppercase {{ $isActiveTabClass('showShoppingCart') }}" role="presentation">
                <a class="" href="{{ route('showShoppingCart', ['lang' => config('app.locale')]) }}">
                    <span class="menu-option-text">{{ trans("$theme-app.foot.direct_sale") }}</span>
                </a>
            </li>
        @endif

        @if (Config::get('app.makePreferences'))
            <li class="text-uppercase {{ $isActiveTabClass('form-preferencias') }}" role="presentation">
                <a class="" href="{{ Routing::slug('user/panel/preferences') }}">
                    <i class="fa fa-bell-o" aria-hidden="true"></i>
                    <span class="menu-option-text">{{ trans("$theme-app.user_panel.preferences") }}</span>
                </a>
            </li>
        @endif

        <li class="text-uppercase {{ $isActiveTabClass('datos-personales') }}" role="presentation">
            <a class="" href="{{ Routing::slug('user/panel/info') }}">
                <i class="fa fa-user-circle"></i>
                <span class="menu-option-text">{{ trans($theme . '-app.user_panel.info') }}</span>
            </a>
        </li>

        <li class="text-uppercase"><a href="{{ Routing::slug('logout') }}">
                <i class="fa fa-sign-out" aria-hidden="true"></i>
                <span class="menu-option-text">{{ trans($theme . '-app.user_panel.exit') }}</span>
            </a>
        </li>
    </ul>
</div>
