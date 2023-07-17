<!doctype html>
<html>
	<?php $cookiesState = \Tools::cookies();?>
<head>
	@include('includes.open_graph_head')
    @include('includes.google_head')
    @include('includes.head')
</head>

<body>

	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TD77BLM"
		height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->

    <?php Tools::PersonalJsCss();?>

        <?php
        $route_customize =\Request::segment(2);
        ?>
     @include('includes.google_body')
        @include('includes.header')

        @yield('content')
        @if($route_customize != 'login' && $route_customize != 'register')
            @include('includes.newsletter')
        @endif

        @include('includes.footer')

        @include('includes.modals')

<div class="button-up">
    <i class="fa fa-chevron-up" aria-hidden="true"></i>

</div>

<?php Tools::querylog(); ?>


</body>

</html>
