<!doctype html>
<html>
<head>
   <title>{{ trans(\Config::get('app.theme').'-app.head.title_app') }}</title>

    <style>
    html,body
	{
	  height: 100%;
          background-color: #0C2340;
	}


    </style>

</head>

<body>
<div style="color: white;font-size: 64px;text-align: center;margin-top: 200px;font-family: 'Titillium Web', sans-serif;">
        <div><img style="max-width: 440px; display: block; margin: 0 auto; margin-top: 100px; margin-bottom: 50px;" src="/themes/{{\Config::get('app.theme')}}/assets/img/logo_footer.png?a=1"  alt="{{(\Config::get( 'app.name' ))}}"></div>

    {{ trans(\Config::get('app.theme').'-app.msg_neutral.auction_end') }}

</div>
</body>
</html>
