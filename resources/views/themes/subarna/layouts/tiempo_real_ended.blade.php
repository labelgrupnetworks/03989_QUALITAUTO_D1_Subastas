<!doctype html>
<html>
<head>
   <title>{{ trans($theme.'-app.head.title_app') }}</title>
   
    <style>
    html,body
	{
	  height: 100%;
          background-color: #2B373C;
          font-family: "Montserrat",Helvetica,Arial,sans-serif;
	}    

  
    </style>
 
</head>
    
<body>	
<div style="color: white;font-size: 64px;text-align: center;margin-top: 200px;">
                                <div><img style="max-width: 300px; display: block; margin: 0 auto; margin-top: 100px; margin-bottom: 50px; " src="/themes/{{$theme}}/assets/img/logofooter.png"  alt="{{(\Config::get( 'app.name' ))}}"></div>

    {{ trans($theme.'-app.msg_neutral.auction_end') }}
    
</div>	                    
</body>
</html>