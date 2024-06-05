<!doctype html>
<html lang="{{ config('app.language_complete')[config('app.locale')] }}">

<head>
    @include('includes.head')
</head>

<body>
    @php
        $withNewsletters = Config::get('app.newsletter_in_all_pages', true);
    @endphp

    @if (!env('APP_DEBUG') && !empty(\Config::get('app.login_acces_web')) && !Session::has('user'))
        @include('includes.loggin_web')
    @else
        @include('includes.google_body')
        @include('includes.header')

        @yield('content')

        @includeWhen($withNewsletters, 'includes.newsletter')

        @include('includes.footer')
        @include('includes.modals')
    @endif

    <div class="button-up">
        <i class="fa fa-chevron-up" aria-hidden="true"></i>
    </div>

    

    @if (request('openLogin') == 'S' && !Session::has('user'))
        <script>
            openLogin();
        </script>
    @endif
</body>

</html>
