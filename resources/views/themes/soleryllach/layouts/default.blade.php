<!doctype html>
<html lang="{{config('app.locale')}}">
<head>
	@include('includes.open_graph_head')
    @include('includes.google_head')
    @include('includes.head')
</head>

<body>

	<?php Tools::PersonalJsCss();?>
     @include('includes.google_body')
        @include('includes.header')

        @yield('content')


        @include('includes.footer')

        @include('includes.modals')

<div class="button-up">
    <i class="fa fa-chevron-up" aria-hidden="true"></i>

</div>
 <?php Tools::querylog(); ?>
</body>

</html>
