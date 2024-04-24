<!doctype html>
<html lang="{{ config('app.language_complete')[config('app.locale')] }}">

<head>
    @include('includes.google_head')
    @include('includes.head')
	<link href="{{ Tools::urlAssetsCache("/themes/$theme/css/panel.css") }}" rel="stylesheet" type="text/css">
</head>

<body class="panel-body">
    @include('includes.google_body')

    @include('includes.panel.header')

    <div class="body-main">
		<aside>
            @include('includes.panel.menu')
        </aside>

        <main>
            @yield('content')
        </main>
    </div>

	@include('includes.modals')

    {!! Tools::querylog() !!}
</body>

</html>
