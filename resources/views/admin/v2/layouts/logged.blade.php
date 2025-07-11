<!DOCTYPE html>
<html data-bs-theme="light" data-topbar-color="dark" data-menu-color="light" data-menu-size="default"
    lang="{{ config('app.language_complete')[config('app.locale')] }}">

<head>
    @include('admin::layouts.partials/head2')
    @yield('assets_components')
    @stack('styles')
</head>

<body>
    @include('admin::layouts.partials/header')

    <div class="wrapper">

        @include('admin::layouts.partials/topbar')
        @include('admin::layouts.partials/main-nav')

        <div class="page-content">

            <div class="container-fluid">

				@includeWhen(!empty($layout), 'admin::layouts.partials.page-title')

                @yield('content')
            </div>

            @include('admin::layouts.partials/footer')

            @yield('modal')
        </div>
    </div>


    {{-- <script src="{{ URL::asset('js/webpush.js') }}"></script> --}}

    {{-- @if (!str_starts_with(Route::currentRouteName(), 'users'))
        @include('admin::layouts.partials.invited_floating-notification')
    @endif --}}
</body>

</html>
