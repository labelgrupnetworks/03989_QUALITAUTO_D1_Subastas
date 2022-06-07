<!doctype html>
<html>
<head>
   <title>{{ trans(\Config::get('app.theme').'-app.head.title_app') }}</title>
   
    <style>
    html,body
	{
	  height: 100%;
          background-color: #2B373C;
          font-family: 'Montserrat', sans-serif;
	}    

  
    </style>
 
</head>
    
<body>	
<div style="color: white;font-size: 64px;text-align: center;margin-top: 200px;">
                                                                <div><img style="max-width: 100%; display: block; margin: 0 auto; margin-top: 100px; margin-bottom: 50px;    border: 4px solid grey;" src="/themes/{{\Config::get('app.theme')}}/assets/img/logo.jpg"  alt="{{(\Config::get( 'app.name' ))}}"></div>

    {{ trans(\Config::get('app.theme').'-app.msg_neutral.auction_end') }}
    
</div>	                    
</body>
</html>