<?php
header("HTTP/1.0 404 Not Found");
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Error 404</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
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
                background-color: #000;
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
                font-weight: 800;
            }

            a {
                font-weight: 800;
            }

            @media (max-width: 530px) {
                img.logo {
                    margin-top: 30px;
                }
            }

            .error404{
                margin-top: 100px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12">
                    <div class="content error404">
                        <img class="logo" src="/themes/<?= $theme?>/assets/img/logo.png" alt="">
                        <div class="title">{{ trans($theme.'-app.global.page_not_found') }}</div>
                        <a href="/{{\App::getLocale()}}">{{ trans($theme.'-app.global.go_home') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
