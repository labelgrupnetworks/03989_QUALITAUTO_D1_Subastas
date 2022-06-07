<!doctype html>
<html>
<head>
    @include('includes.google_head')
    @include('includes.head')
	<?php Tools::PersonalJsCss();?>
	@yield('assets_components')
</head>

<body>

     @include('includes.google_body')
        @include('includes.header')

        @yield('content')

        @include('includes.newsletter')

        @include('includes.footer')

        @include('includes.modals')

<div class="button-up">
    <i class="fa fa-chevron-up" aria-hidden="true"></i>

</div>
 <?php Tools::querylog(); ?>
</body>

</html>
