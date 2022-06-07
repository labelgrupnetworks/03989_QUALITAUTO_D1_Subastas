<!doctype html>
<html>
<head>
   <title>{{ trans(\Config::get('app.theme').'-app.head.title_app') }}</title>
   
    <style>
    html,body
	{
	  height: 100%;
          background-color: #fff;
          font-family: 'Rubik', sans-serif;
	}    
        
        

  
    </style>
 
</head>
    
<body>	

<div style="color: #101010;font-size: 64px;text-align: center;margin-top: 200px;" class="center-body">
   <img style="max-width: 100%" src="/themes/{{\Config::get('app.theme')}}/assets/img/logo.png"  alt="{{(\Config::get( 'app.name' ))}}">
    <p>{{ trans(\Config::get('app.theme').'-app.msg_neutral.auction_end') }}</p>
    
</div>	                    
</body>
</html>