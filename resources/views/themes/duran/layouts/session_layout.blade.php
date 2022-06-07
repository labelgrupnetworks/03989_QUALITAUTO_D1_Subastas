<!doctype html>
<html>
<head>
    @include('includes.google_head')
    @include('includes.head')
</head>

<body>

    <?php \Tools::personalJsCss();?>

    @if(!env('APP_DEBUG') && !empty(\Config::get('app.login_acces_web')) && !Session::has('user') )
        @include('includes.loggin_web')
    @else
        @include('includes.google_body')
            @yield('content')
        @include('includes.modals')
    @endif

<div class="button-up">
    <i class="fas fa-arrow-up"></i>
</div>
 <?php //Tools::querylog(); ?>
</body>

</html>
