<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>En construccion</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
   </head>

   <style>
       body{
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-pack: center;
                -ms-flex-pack: center;
                    justify-content: center;
            height: 95vh;
            font-family: 'Montserrat', sans-serif;
       }
       .img-bk img{
           max-width: 100%;
       }
       .img-bk{
           position: absolute;
           -ms-flex-item-align: center;
               -ms-grid-row-align: center;
               align-self: center;
       }
       .text{
           position: relative;
           z-index: 1;
           text-align: center;
       }
       .text h2{
           text-transform: uppercase;
           font-size: 9vmin;
           color: gray;
           margin-bottom: 0;

       }
        .text p{
           text-transform: uppercase;
           font-size: 24px;
           font-weight: 900;
           color: rosybrown;


       }
       h3{
           margin: 0;
           font-size: 6vmin;
           line-height: 1;
           text-transform: uppercase;
           color: gray;
       }
       .text{
               display: -webkit-box;
               display: -ms-flexbox;
               display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
        -ms-flex-direction: column;
            flex-direction: column;
    -webkit-box-pack: justify;
        -ms-flex-pack: justify;
            justify-content: space-between;
       }


   </style>

   <body>
       <div class="text">
           <div>
           <h2>Web en mantenimiento</h2>
           <h3>Web in maintenance</h3>
           </div>
           <div>
           <p>Volvemos en unos minutos</p>
           <p>we come back in a few minutes</p>
           </div>
       </div>
       <div class="img-bk">
           <img src="/default/img/mantenimiento.png" />
       </div>
   </body>

</html>
