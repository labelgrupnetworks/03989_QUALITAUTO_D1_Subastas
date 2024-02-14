<!doctype html>
<html>
<head>
<link href="{{ Tools::urlAssetsCache('/css/default/style_up.css') }}" rel="stylesheet" type="text/css">

<style>
    *{
        margin: 0;
        padding: 0;
    }
    html{
        height: 100%;
    }
    body{
        margin: 0;
        background: #2b373a;
    }
    .header{
         display: -webkit-box;
         display: -ms-flexbox;
         display: flex;
    -webkit-box-align: center;
        -ms-flex-align: center;
            align-items: center;
    -webkit-box-pack: center;
        -ms-flex-pack: center;
            justify-content: center;
    padding: 10px;
    background: #2b373a;
    height: 70px;

    }

    .content{
        height: 450px;
        width: 100%;
        max-width: 400px;
        margin: 0 auto;

    }
</style>
</head>

<body>
<div class="header">
    <img src="/themes/{{$theme}}/assets/img/logo.png"  alt="{{(\Config::get( 'app.name' ))}}">
</div>
    <div class="content">
        @yield('content')
    </div>

</body>

</html>
