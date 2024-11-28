@php
    $lang = config('app.locale');
    $fullname = Session::get('user.name');
    $name = $fullname;
    if (strpos($fullname, ',')) {
        $str = explode(',', $fullname);
        $name = $str[1];
    }

    $searchAction = config('app.gridLots', false) == 'new' ? route('allCategories') : \Routing::slug('busqueda');
    $pageName = Route::currentRouteName();
@endphp

<header>

    <nav class="navbar navbar-expand-xl">
        <div class="container-fluid">
            <a class="navbar-brand" href="/{{ $lang }}" title="{{ \Config::get('app.name') }}">
                <img class="img-responsive" src="/themes/{{ $theme }}/assets/img/logo.png"
                    alt="{{ \Config::get('app.name') }}" width="200">
            </a>

            <button class="navbar-toggler collapsed" data-bs-toggle="collapse" data-bs-target="#navbarHeader"
                type="button" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="navbar-collapse collapse" id="navbarHeader" style="">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 fw-bold">

                    @if ($global['subastas']->has('S') && $global['subastas']['S']->has('O'))
                        <li class="nav-item">
                            <a href="{{ route('subastas.online') }}" @class([
                                'nav-link',
                                'lb-text-primary' => $pageName === 'subastas.online',
                            ])>
                                <span>{{ trans($theme . '-app.foot.online_auction') }}</span>
                            </a>
                        </li>
                    @endif

                    <li class="nav-item">
                        <a href="{{ route('contact_page') }}" title="{{ trans($theme . '-app.foot.contact') }}"
                            @class([
                                'nav-link',
                                'lb-text-primary' => $pageName === 'contact_page',
                            ])>
                            <span>{{ trans($theme . '-app.foot.contact') }}</span>
                        </a>
                    </li>
                </ul>

                @include('components.search', ['classes' => ''])

                @if (!Session::has('user'))
                    <button class="btn btn-lb-primary btn_login">
                        {{ trans($theme . '-app.login_register.login') }}
                    </button>
                @else
                    <div class="d-flex align-items-center gap-3">
                        <a class="btn btn-lb-primary"
                            href="{{ \Routing::slug('user/panel/orders') }}">{{ trans($theme . '-app.login_register.my_panel') }}</a>

                        @if (Session::get('user.admin'))
                            <a class="btn btn-lb-primary" href="/admin" target = "_blank">
                                {{ trans($theme . '-app.login_register.admin') }}</a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </nav>

</header>
