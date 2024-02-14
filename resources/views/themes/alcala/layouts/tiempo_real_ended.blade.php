<!doctype html>
<html>
<head>
   <title>{{ trans($theme.'-app.head.title_app') }}</title>

    <style>
    @import url('https://fonts.googleapis.com/css?family=Rubik:300,300i,400,400i,500,700,700i,900,900i');
    html,body
	{
	  height: 100%;
          background-color: white;
          font-family: 'Rubik', sans-serif;
          color: #414145;

    }

    .center-body{
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
            -ms-flex-align: center;
                align-items: center;
        -webkit-box-pack: center;
            -ms-flex-pack: center;
                justify-content: center;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
            -ms-flex-direction: column;
                flex-direction: column;
        height: 100%;
    }

    .center-body p{
        font-size: 4vw;
        font-weight: 100;
        text-transform: uppercase;
    }




    </style>

</head>

<body>

<div style="" class="center-body">
   <img style="max-width: 100%" src="/themes/{{$theme}}/assets/img/logo.png"  alt="{{(\Config::get( 'app.name' ))}}">
    <p>{{ trans($theme.'-app.msg_neutral.auction_end') }}</p>

</div>
</body>
</html>
