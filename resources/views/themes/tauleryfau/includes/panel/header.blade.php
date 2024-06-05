<header class="layout_header">
    <nav class="navbar navbar-default navbar-panel">
        <div class="panel-container">

            <div class="navbar-header">
                <a class="navbar-brand" href="/{{ config('app.locale') }}" title="{{ config('app.name') }}">
                    <img class="img-responsive" src="/themes/{{ $theme }}/assets/img/logo-web.png"
                        alt="{{ config('app.name') }}" width="250" height="40">
                </a>
            </div>

            <div class="collapse navbar-collapse">
                @include('includes.header-nav')
            </div>

            <a class="btn btn-lb btn-lb-outline btn-lb-session"
                href="{{ \Routing::slug('logout') }}">{{ trans($theme . '-app.user_panel.exit') }}</a>

        </div>
    </nav>
</header>
