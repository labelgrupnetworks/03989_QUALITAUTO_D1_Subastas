<!doctype html>
<html lang="{{ config('app.language_complete')[config('app.locale')] }}">

<head>
    @includeIf('includes.google_head')
    @include('includes.head')
    @yield('assets_components')
</head>

<body>

    @include('includes.google_body')

	@yield('content')

	@include('includes.modals')

</body>

</html>
