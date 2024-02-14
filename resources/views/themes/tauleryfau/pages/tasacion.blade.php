@extends('layouts.tasacion')
@include('includes.google_head')

@section('title')
	{{ trans($theme.'-app.head.title_app') }}
@stop


@section('content')

<body class="landing">

<header>


    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="header-desktop inline-flex">
                    <div class="logo col-xs-12 col-md-12 text-center">

                        <a title="Tauler y Fau" href="/es">
                            <img class="img-responsive" src="/themes/<?= $theme ?>/img/comprar-monedas/logo-simple.png"  alt="Tauler y Fau">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<section class="super-slogan">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="slogan-principal text-center">
                    <h1>¿Quiere vender sus monedas?</h1>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="ventajas-landing">
    <div class="background-title"></div>
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-6 no-padding">
                    <div class="ventajas-title col-xs-12">
                        <h2>¿Tiene monedas y quiere conocer su valor?</h2></div>
                    <div class="ventajas-box col-xs-12">
                        <div class="ventajas-box-title text-center">
                            <div class="box-align">
                                <span>VALORACIONES GRATUITAS</span>
                            </div>
                        </div>
                        <div class="ventajas-box-ventajas">
                            <div class="box-align">
                            <p>Diferentes formas de venta:</p>
                            <ul class="ventajas-box-lista">
                                <li>
                                    <span><img style="margin-right: 4px; position:relative; top: -1px" width="10px" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACQAAAAiCAYAAAA3WXuFAAAACXBIWXMAAAsSAAALEgHS3X78AAABkklEQVRYhe2Y222EMBBFb1IBHWRTQegglOAOQjqghC2BEiiBDuJ0wHaQ7YBU4MjSfCAbzwOItB8cCWmFX9fjmfGwTyEEPBLPD6XmFKRgj6AaQA/AAwjJ46mtNs8andr4NCGEKejxNEa1jkVMFUIYDEJS+iMFVUarlJhoruJa2jw0AXjb4mgrfANoSo0ap+4PFBN5B3AtNUoWijv5OlDMklcAP+lLyULdP4lB0UqMg12UjhrD2i3GOUMAZA7OCWoVEw7MeE2KcOk47siKkUDcAbRMe0d9OLJMzgm6CJP1QvsMYBD6ZJvec5dNij7eOukeQZWij2TlDE5QNDmHU8wv+aEpD0lH8iGUFw314cjXYMI2lgwSc6G0cNQmUVsv12jSF2GXkRuAkX475d13X/Uxoey4KjPuFtot5UdF56yxkoVbyf+ksJ+V0WThl8vwmjwULfR5oKCOjWBDTd0qI6fEvHaZ7inyQWHqN4jxVM4c+tWR5plRIWS0fAJZivwSFUXL8oqYyUcmxfWTcf77IXEKYgHwBwWJFuLR+wdFAAAAAElFTkSuQmCC">SUBASTAS MENSUALES</span></li>
                                <li><span><img style="margin-right: 4px; position:relative; top: -1px" width="10px" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACQAAAAiCAYAAAA3WXuFAAAACXBIWXMAAAsSAAALEgHS3X78AAABkklEQVRYhe2Y222EMBBFb1IBHWRTQegglOAOQjqghC2BEiiBDuJ0wHaQ7YBU4MjSfCAbzwOItB8cCWmFX9fjmfGwTyEEPBLPD6XmFKRgj6AaQA/AAwjJ46mtNs8andr4NCGEKejxNEa1jkVMFUIYDEJS+iMFVUarlJhoruJa2jw0AXjb4mgrfANoSo0ap+4PFBN5B3AtNUoWijv5OlDMklcAP+lLyULdP4lB0UqMg12UjhrD2i3GOUMAZA7OCWoVEw7MeE2KcOk47siKkUDcAbRMe0d9OLJMzgm6CJP1QvsMYBD6ZJvec5dNij7eOukeQZWij2TlDE5QNDmHU8wv+aEpD0lH8iGUFw314cjXYMI2lgwSc6G0cNQmUVsv12jSF2GXkRuAkX475d13X/Uxoey4KjPuFtot5UdF56yxkoVbyf+ksJ+V0WThl8vwmjwULfR5oKCOjWBDTd0qI6fEvHaZ7inyQWHqN4jxVM4c+tWR5plRIWS0fAJZivwSFUXL8oqYyUcmxfWTcf77IXEKYgHwBwWJFuLR+wdFAAAAAElFTkSuQmCC">TIENDA ONLINE</span></li>
                            </ul>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 no-padding">
                    <div class="ventajas-title col-xs-12">
                        <h2>Ventajas de vender con Tauler&Fau</h2></div>
                    <div class="ventajas-box col-xs-12">
                        <div class="ventajas-box-ventajas col-lg-8 col-lg-offset-2">
                            <ul class="ventajas-box-lista2">
                                <li>
                                    <p>
                                        <img style="margin-right: 10px;position: relative;width: 20px;" src="/themes/<?= $theme ?>/img/comprar-monedas/icon.png" >
                                    </p>
                                    <p>
                                        <b>Nuestros expertos valoran</b> el material de acuerdo a la situación actual del mercado
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        <img style="    margin-right: 10px;    position: relative;   width: 20px;"src="/themes/<?= $theme ?>/img/comprar-monedas/icon.png" ></p><p><b>Recogida</b> del material en su domicilio en <b>24h</b></p>
                                </li>
                                <li>
                                    <p>
                                        <img style="    margin-right: 10px;    position: relative;    top: -3px;    width: 20px;"src="/themes/<?= $theme ?>/img/comprar-monedas/icon.png" ></p><p>Casi un <b>90%</b> de lotes vendidos en nuestras subastas</p></li>
                                <li>
                                    <p><img style="    margin-right: 10px;    position: relative;    top: -3px;    width: 20px;"src="/themes/<?= $theme ?>/img/comprar-monedas/icon.png" ></p><p><b>Liquidación</b> a los <b>30 días</b>, descontando la comisión acordada previamente en el contrato</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="banner-landing">
            <div class="banner-landing-box text-center">
                <div class="banner-landing-title">
                    ¡LLÁMENOS AHORA!
                </div>
                <div class="banner-landing-cell">
                    <a class="href-cell" title="914221444" href="tel:914221444">+34 91 422 14 44</a>
                </div>
                <div class="banner-landing-email">
                    o rellene el siguiente <span style="text-decoration: underline"><a title="formulario vender monedas" href="#form-href">formulario</a></span>
                </div>
            </div>
        </div>
    </section>

<section class="no-principal-landing " id="form-href">
    <div class="container">
        <div class="row or">
            <div class="col-xs-12 col-sm-12">
                <div class="form-landing">

                    <div class="title-form-landing">Formulario de valoración</div>
                     <form class="form upload" id="form-valoracion-adv" style="width: 100%; position: relative;">
                            <div class="loader-container" style="
                                position:  absolute;
                                align-items:  center;
                                justify-content:  center;
                                height:  100%;
                                width:  100%;
                                z-index:  99;
                                background: white;
                                display: none;
                            ">
                                <div class="loader"></div>
                            </div>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="post" value="true">
                            <div class="form-group col-xs-12 ">
                                <input type="text" name="name" placeholder="Nombre y apellido" required class="form-control">
                            </div>
                            <div class="form-group col-xs-12">
                                <input type="text" name="telf" placeholder="Teléfono" required class="form-control">
                            </div>
                            <div class="form-group col-xs-12">
                                 <input type="email" name="email" placeholder="Email" required class="form-control">
                            </div>
                            <div class="col-xs-12">
                                <div class="text-area">
                                    <textarea class="form-control" id="exampleTextarea" name="descripcion" required placeholder="Descripci&oacute;n del objeto y de su estado actual. Intente darnos el m&aacute;ximo de informaci&oacute;n posible. Gracias."></textarea>
                                </div>

                            </div>
                            <input type="hidden" value="info@tauleryfau.com" name="email_category">

                            <div class="add-picture">
                                <div class="text-center files1 fill">
                                    <input id="files1" type="file" class="files"  accept="image/png, image/jpeg" name="imagen[]" style="display:none" multiple required>
                                    <label for="files1">
                                        <i class="fa fa-camera-retro"></i>
                                        <img class="img-responsive" style="max-height: 65px;" />
                                    </label>
                                    <span><strong></strong><i class="fa fa-minus-circle" style="display:none"></i></span>
                                </div>
                                <div class="text-center files2 fill">
                                    <input id="files2" type="file" class="files" accept="image/png, image/jpeg" name="imagen[]" style="display:none" multiple >
                                    <label for="files2">
                                        <i class="fa fa-camera-retro"></i>
                                        <img class="img-responsive" style="max-height: 65px;" />
                                    </label>
                                    <span><strong></strong><i class="fa fa-minus-circle" style="display:none"></i></span>
                                </div>
                                <div class="text-center files3 fill">
                                    <input id="files3" type="file" class="files" accept="image/png, image/jpeg" name="imagen[]" style="display:none" multiple >
                                    <label for="files3">
                                        <i class="fa fa-camera-retro"></i>
                                        <img class="img-responsive" style="max-height: 65px;" />
                                    </label>
                                    <span><strong></strong><i class="fa fa-minus-circle" style="display:none"></i></span>
                                </div>
                            </div>

                            <div class="col-xs-12 send-button" style="margin-top: 0;">
                                <button type="submit" id="valoracion-adv" class="btn btn-color" ><div class='loader hidden'></div>ENVIAR</button>
                                <h4 class="valoracion-h4 hidden msg_valoracion">Error, vuelva a enviar el formulario de valoraci&oacute;n. Gracias</h4>

                            </div>
                        </form>
                </div>

            </div>
            <div class="slogan-form col-xs-12">
                "Combinamos la experiencia de los profesionales con la pasión de los coleccionistas"
            </div>
            <div class="col-xs-12" style="    margin: 60px 0;padding: 0 20px;text-align: justify;">
                <p style="margin-bottom: 20px;">Si en algún momento se ha preguntado dónde vender sus monedas antiguas, ya tiene la respuesta: en Tauler&Fau tasamos su colección y la ponemos a la venta.
                        Más de 10 años de experiencia nos avalan. </p>

                <p style="margin-bottom: 20px;">Un grupo de expertos valorará las monedas de forma gratuita de acuerdo a la situación actual del mercado. Si decide vender con nosotros, pasaremos a recoger las monedas por su domicilio de manera gratuita y asegurada en un plazo de 24 horas.
    Enviamos una relación con las descripciones y precios de salida de las monedas consignadas.</p>
                <p style="margin-bottom: 20px;">Disponemos de diversos canales de venta para ofrecer la mayor difusión a sus monedas: subastas presenciales y subastas online, presentes en los portales más importantes del sector, o a través de nuestra tienda online.</p>
                <p style="margin-bottom: 20px;">Una vez finalizada la subasta, realizaremos la liquidación en 30 días, descontando la comisión previamente acordada en el contrato.</p>
<p style="margin-bottom: 20px;">Ahora ya puede vender su colección de monedas antiguas con nosotros.</p>
<p>¡El objetivo de Tauler & Fau es darle el mejor servicio de venta!</p>
            </div>
        </div>
    </div>
</section>

<style>
    body.landing header {
        min-height: 80px;
    }
    body.landing .slogan-principal{
        font-weight:900;
        font-size: 25px;
    }
    body.landing .super-slogan{
        background-color: #283847;
    }
    body.landing .super-slogan .slogan-principal{
        font-weight: 900;
        font-size: 32px;
        margin: 15px 0;
        color: white;
    }
    .slogan-principal h1{
        font-weight: 600;
        margin-top: 10px;
        font-size: 32px;
    }
    body.landing .header-desktop{
        margin: 0;
    }
    .ventajas-landing .ventajas-title{
        height: 60px;
        font-size: 20px;
        font-weight: 900;
        color: #283847;
        text-align: center;
        padding-top: 17px;
            background: white;
    }
    .ventajas-landing .ventajas-title h2{
        font-size: 24px;
        font-weight: 900;
        color: #283847;
        margin: 0;
    }

    .href-cell{
        color: white;
        text-decoration: underline;

    }

    .box-align{
        width: 315px;
        margin: 0 auto;
    }

     .logo{
        margin-top:0px;
    }
    body.landing header .logo a img{
        width: 260px;
    }
    .ventajas-landing{
        min-height: 375px;
        background-color: #283847;
        position: relative;
    }
    .banner-landing{
        display: -webkit-box;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: end;
        -webkit-justify-content: flex-end;
            -ms-flex-pack: end;
                justify-content: flex-end;
        padding: 18px 20px;
        background: #a88c69;
        width: 45%;
        position: absolute;
        bottom: -45px;
        color: white;
        padding-right: 30px;
    }
    .banner-landing-title,
    .banner-landing-cell{
        font-size: 30px;
        font-weight: 900;
    }
    .ventajas-landing .ventajas-box{
        background-color: #283847;
        color: white;
        margin-top: 30px;
    }
    .ventajas-box-title {
        margin-bottom: 10px;
    }
    .ventajas-landing .ventajas-box .ventajas-box-title span{
        font-size: 22px;
        color: white;
        font-weight: 700;
        text-align: center;
        position: relative;
        letter-spacing: 1px;
    }

    .ventajas-landing .ventajas-box .ventajas-box-title span:after{
        content: '';
        position: absolute;
        height: 2px;
        background: white;
        width: 100%;
        left: 0;
        bottom: -3px;
    }

    .ventajas-box-lista2 li{
        display: -webkit-inline-box;
        display: -webkit-inline-flex;
        display: -ms-inline-flexbox;
        display: inline-flex;
        margin-bottom: 20px;
    }
    .ventajas-box-lista2 li p{
        color: #fff;
        font-size: 16px;
        line-height: 20px;
    }
    .ventajas-box-ventajas p{
        color: #fff;
        font-size: 16px;
        font-weight: 400;
        line-height: 29px;
    }

    .slogan-form{
        text-align: center;
        font-size: 18px;
        font-weight: 900;
        margin-top: 21px;
    }

    .ventajas-box-lista{
        margin-left: 80px;
    }

    ul.ventajas-box-lista li{
       font-size: 17px;
        font-weight: 900;
        margin-bottom: 10px;
    }

    body.landing .no-principal-landing {
        background-color: #fff;
        margin-top: 50px;
        color: #283847;
    }


    body.landing .form-landing {
        background-color: #fff;
        border: 1px solid #283747;
        width: 460px;
        margin: 20px auto;
    }

    body.landing form.upload{
        padding: 35px 16px 87px;
    }
    .title-form-landing{
        padding: 13px;
        text-align: center;
        background: #283747;
        color: white;
        font-weight: 600;
        font-size: 20px;
    }
    body.landing .form-landing input {
        background-color: #f0ebe6;
    }
    body.landing .form-landing textarea {
        background-color: #f0ebe6;
        color: #000;
    }
    body.landing div.send-button {
        text-align: left !important;
    }
    body.landing #exampleTextarea {
        border: 1px solid #c6c6c6;
        padding: 5px 10px 25px;
        min-height: 110px;
    }
    body.landing div.text-landing {
        text-align: center;
    }
    body.landing div.text-landing hr {
        width: 40%;
        margin: 0 auto;
        border-color: #9f7a47;
        border-width: 2px;
        margin-top: 20px;
        margin-bottom: 40px;
    }
    body.landing div.text-landing span {
        color: #fff;
        font-size: 16px;
        position: relative;
        top: 32px;
        font-style: italic;
    }
    body.landing h1.landing {
        font-size: 39px;
        color: #fff;
        margin-bottom: 30px;
    }
    body.landing h2.slog {
        color: #fff;
        font-size: 23px;
        line-height: 29px;
    }
    body.landing p.txt {
        font-size: 17px;
        color: #fff;
        margin-bottom: 5px;
    }
    body.landing p.foot {
        font-size: 17px;
        color: #fff;
        margin-top: 30px;
    }
    body.landing div.send-button button {
        margin-top: 25px;
    }
    body.landing div.title {
        font-size: 32px;
        font-weight: bold;
        color: #263846;
        margin:30px 0;
        text-align: center;
    }
    body.landing p.txt_foot {
        font-size: 18px;
        text-align: center;
    }
    body.landing ul.items {
        margin: 0;
        padding: 0;
    }
    body.landing .add-picture {
        display: -webkit-inline-box;
        display: -ms-inline-flexbox;
        display: inline-flex;
        -webkit-box-pack: start;
            -ms-flex-pack: start;
                justify-content: flex-start;
        padding-left: 10px;
    }
    body.landing .add-picture label i {
        font-size: 30px;
    }
    body.landing .add-picture div.text-center {
        width: 60px;
        height: 50px;
    }
    body.landing .mb60 {
        margin-bottom: 60px;
    }
    body.landing ul.items {
        margin: 30px;
        padding: 0;
    }
    body.landing ul.items li {
        font-size: 15px;
        min-height: 34px;
        color:#283747;
        margin-bottom: 10px;
        width: 70%;
        margin: auto;
        margin-bottom: 40px;
        padding-left: 56px;
        background-image: url('/themes/<?= $theme ?>/assets/img/comprar-monedas/items.png');
        background-repeat: no-repeat;
        background-position: 0 11px;
    }
    body.landing .txt_fin {
        font-size: 22px;
        font-weight: bold;
        margin-bottom: 30px;
    }
    body.landing hr.land {
        width: 40%;
        margin: 0 auto;
        border-color: #9f7a47;
        border-width: 2px;
        margin-top: 20px;
        margin-bottom: 70px;
    }

    .banner-landing span a{
        color: white;

    }

    .background-title{
        content: '';
        position: absolute;
        width: 100%;
        height: 60px;
        top: 0;
        background: white;
        z-index: 0;
    }



    .banner-landing-title, .banner-landing-cell{
        font-size: 28px;
        font-weight: 900;
    }

    .href-cell{
       text-decoration: none;
    }
    ::-webkit-input-placeholder {
        color: black;
        font-style: normal;
        opacity: 1;
    }
    ::-ms-input-placeholder {
        color: black;
        font-style: normal;
        opacity: 1;
    }
        ::placeholder {
        color: black;
        font-style: normal;
        opacity: 1;
    }

    body.landing .form-landing input,
    body.landing #exampleTextarea{
        border: 0;
    }
    @media screen and (max-width: 991px){
        .ventajas-landing .ventajas-title {
            height: 60px;
            color: #283847;
            padding-top: 8px;

        }

        @media screen and (max-width: 1200px)
            .ventajas-landing .ventajas-title h2 {
                    font-size: 20px;
            }

            .ventajas-landing .ventajas-box {

                padding-bottom: 25px;
            }
            .ventajas-box-lista {
                margin-left: 0;
            }



    }

    @media screen and (width: 768px){
        body.landing header {
            margin-top: 0;
        }


        body.landing .form-landing {
            margin-top: 50px;
        }
        .banner-landing-title, .banner-landing-cell {
                  font-size: 20px;
        }
    }
    @media screen and (min-width: 1200px){
        .banner-landing {
            width: 30%;

        }
    }

    @media screen and (max-width: 1200px){
        .banner-landing {
            width: 44%;

        }
        .ventajas-landing .ventajas-title h2{
            font-size: 22px;
        }
    }
    @media screen and (max-width: 767px){
        .banner-landing-title, .banner-landing-cell {
            font-size: 20px;
            font-weight: 900;
        }
        .href-cell{
            text-decoration: underline;
        }


        .banner-landing {
            padding: 18px 0px;
            background: #a88c69;
            width: auto;
            position: absolute;
            bottom: 0%;
            color: white;
            padding-right: 30px;
            position: fixed;
            z-index: 9;
        }
        body.landing .form-landing {
            margin-top: 50px;
        }
        body.landing .or {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
                -ms-flex-direction: column;
                    flex-direction: column;
        }
        body.landing .or .col-sm-offset-1 {
            -webkit-box-ordinal-group: 0;
                -ms-flex-order: -1;
                    order: -1;
        }
        body.landing header {
            margin-top: 0;
        }
        body.landing .no-principal-landing .col-xs-12 {
            padding: 0;
        }

        body.landing div.text-landing {
            padding: 40px 0 50px;
        }

        body.landing ul.items li {
            width: 100%;
        }
    }
@media screen and (max-width: 480px){

    .box-align {
    width: 280px;
    margin: 0 auto;
}
.ventajas-landing .ventajas-title {

    padding-top: 6px;
}

        .banner-landing-title, .banner-landing-cell {
            font-size: 14px;
            font-weight: 900;
        }
        body.landing .form-landing {

            width: 95%;

        }
        .banner-landing {

            width: 70%;


        }

        .banner-landing-box{
            padding-left: 5px;
        }
        .banner-landing {
            width: 70%;
        }
        .ventajas-landing .ventajas-box .ventajas-box-title span::after {
            display: none;
        }
        .ventajas-landing .ventajas-box .ventajas-box-title span{
            text-decoration: underline;
        }
    }
</style>

</body>
@stop
