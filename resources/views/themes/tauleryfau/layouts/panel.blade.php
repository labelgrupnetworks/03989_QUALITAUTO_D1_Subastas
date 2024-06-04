<!doctype html>
<html lang="{{ config('app.language_complete')[config('app.locale')] }}">

<head>
    @include('includes.google_head')
    @include('includes.head')
    <link type="text/css" href="{{ Tools::urlAssetsCache("/themes/$theme/css/panel.css") }}" rel="stylesheet">
	<script src="{{ Tools::urlAssetsCache("/themes/$theme/js/panel.js") }}"></script>
</head>

<body class="user-panel-body">
    @include('includes.google_body')

    @include('includes.panel.header')

    <div class="body-main">
        <aside>
            @include('includes.panel.menu')
        </aside>

        @if (Route::currentRouteName() === 'panel.summary')
			<div class="summary-banner hidden-md hidden-lg">
				{!! \BannerLib::bannersPorKey('summary_page', 'summary_page', [
					'arrows' => false,
					'dots' => false,
					'autoplay' => true,
					'autoplaySpeed' => 4000
				]) !!}
			</div>

        @endif

        <main>
            @yield('content')
        </main>
    </div>

    @include('includes.modals')

    {!! Tools::querylog() !!}
</body>

</html>
