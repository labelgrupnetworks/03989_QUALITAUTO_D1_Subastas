<?php
header("HTTP/1.0 404 Not Found");
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Error 404</title>

        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous" type="text/css">

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                color: #B0BEC5;
                display: table;
                font-weight: 100;
                font-family: 'Lato';
                background-color: white;
            }

            img.logo {
                margin-bottom: 40px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 32px;
                margin-bottom: 40px;
            }
            .error-btn{
                display: flex;
                justify-content: space-around;
                width: 60%;
                margin: 0 auto;
            }
            .go-home,
            .go-contact{
width: 200px;
color: white;
height: 40px;
border-radius: 15px;
font-size: 19px;
font-weight: 900;
padding-top: 6px;
                
            }
            .go-home{
                
                background: #283747;
                
                
            }
        .go-contact{
                
                background: #bc9c7e;
                
                
            }
            .text404{
                font-size: 26vh;
font-weight: 900;
            }
            @media (max-width: 530px) {
                img.logo {
                    margin-top: 30px;
                }
            }
        </style>
    </head>
    <body style="    
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
                align-items: center;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
                justify-content: center;"
    >
        <div class="container-had">
            <div class="row">
                <div class="col-xs-12 col-sm-12">
                    <div class="content">
                        <img 
                            class="logo" 
                            class="img-responsive" 
                            style="max-height: 200px;" 
                            src="/themes/<?= \Config::get('app.theme')?>/assets/img/logo.png" alt="Balclis">
                        
                        <div 
                            class="title" 
                            style="font-size: 32px;margin-bottom: 40px;font-weight: 400;color: black;">{{ trans(\Config::get('app.theme').'-app.global.page_not_found') }}
                        </div>
                        <div class="error-btn">
                            <a href="/{{\App::getLocale()}}" class="go-home">{{ trans(\Config::get('app.theme').'-app.global.go_home') }}</a>    
  
                            
                        </div>
                        
                        
                        <a href="/{{\App::getLocale()}}" style="font-size: 20px;font-weight: 900;"></a>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
