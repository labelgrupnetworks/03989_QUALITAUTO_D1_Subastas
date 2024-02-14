@extends('layouts.tasacion')
@include('includes.google_head')
@section('title')
{{ trans($theme.'-app.head.title_app') }}
@stop

@section('content')

<link href="/themes/{{$theme}}/css/landing.css" rel="stylesheet" type="text/css">
<section>
    <!-- barra azul -->
    <div class="custom-bar-logo">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-4 text-center"></div>
                <div class="col-xs-12 col-md-4 text-center">
                    <a title="{{(\Config::get( 'app.name' ))}}" href="/">
                        <img class="img-responsive" src="/themes/{{$theme}}/img/landing/logo.png"  alt="{{(\Config::get( 'app.name' ))}}">
                    </a>
                </div>
                <div class="col-xs-12 col-md-4 text-center"></div>
            </div>
        </div>
    </div>
    <!-- barra azul -->
    <div class="custom-bar-title">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 text-center">
                    <h1>Numismática Madrid</h1>
                </div>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="img-cabecera">
        <div class="container-fluid">
            <img class="img-responsive" src="/themes/{{$theme}}/img/landing/monedas-antiguas.jpg">
        </div>
    </div>
</section>

<section class="especialistas" style="margin-top: 3rem;">
    <div class="container">

        <div class="container-fluid">
            <div class="col-xs-12 text-center">
                <div style="text-align: justify">Si buscas expertos en <strong>Numismática Madrid</strong> podemos ayudarte. En Tauler & Fau llevamos más de 50 años trabajando en el sector de la numismática.
                    Nuestra sede se encuentra en Madrid donde realizamos subastas de <strong>monedas antiguas</strong>, además ahora también <a href="/es"><strong>realizamos subastas online</strong></a> para que puedas
                    participar en ellas estés donde estés. Somos una de las casas de Numismática en Madrid y ofrecemos diferentes servicios como: tasación de monedas antiguas, venta
                    directa y también ofrecemos servicios para encapsular tus monedas antiguas.</div>
            </div>
        </div>

    </div>
</section>

<section style="margin-top:2rem; margin-bottom: 2rem;">
    <div class="container">

        <div class="row">

            <div class="col-xs-12 col-md-5" style="float:left;margin-bottom: 1rem;">

                <img class="img-responsive" src="/themes/{{$theme}}/img/landing/moneda-espanola.jpg">
                <div class="container col-xs-12">
                    <div class="titles-border">
                        <h2>Moneda española</h2>
                    </div>
                    <div style="text-align: justify">
                        Existen muchas variedades de monedas españolas: 8 escudos, 8 reales, macuqinas, 1 peseta. En Tauler & Fau realizamos subastas de una gran variedad de
                        estas monedas. Visita nuestra página web y descubre las próximas subastas que tenemos programadas.
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-md-5" style="float:right;margin-bottom: 1rem;">

                <img class="img-responsive" src="/themes/{{$theme}}/img/landing/subasta-monedas-madrid.jpg">
                <div class="container col-xs-12">
                    <div class="titles-border">
                        <h2>Subastas Madrid</h2>
                    </div>
                    <div style="text-align: justify">
                        Si buscas una casa de subastas de monedas en Madrid, estás en el lugar adecuado. Nuestra trayectoria nos convierte en una de las casas de numismática en
                        Madrid con más experiencia y con un equipo de profesionales que estarán dispuestos a ayudarte y asesorarte en todo lo que necesites. Si lo que buscas es donde
                        vender monedas en Madrid podemos ayudarte en tasar tus monedas antiguas.
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>


<section style="margin-bottom: 3rem;" class="custom-bar-title">

    <div class="container">
        <div class="col-xs-12 text-center title-b-container">
            <div class="titles-border">
                <h2 style="color:white">Especialistas en monedas antiguas</h2>
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-3"></div>
                <div class="col-xs-12 col-md-6">
                    <hr>
                </div>
                <div class="col-xs-12 col-md-3"></div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-md-2"></div>
                <div class="col-xs-12 col-md-8">

                    <div class="col-xs-12 coltext-center">
                        <div style="text-align: justify; font-size: 15px; font-weight: normal">
                            Sin duda una de las ventajas que ofrecemos a nuestros clientes es la profesionalidad y experiencia de nuestro equipo. Combinamos la experiencia
                            de los profesionales de la venta con la pasión de los coleccionistas. Nos avala una larga trayectoria en el sector, y además seguimos creciendo
                            y apostando por la innovación y la creacion de nuevas oportunidades de venta. Combinamos la esencia de las subastas presenciales con las nuevas subastas
                            online en nuestra web. Asi podrás pujar por nuestros lotes donde quieras que estés.
                        </div>
                    </div>

                </div>
                <div class="col-xs-12 col-md-2"></div>
            </div>

            <br><br>
        </div>

    </div>

</section>


<section style="margin-top: 1rem;">

    <div class="container">
        <div class="col-xs-12 text-center title-b-container">
            <div class="titles-border">
                <ul><h2>Servicios</h2></ul>
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-3"></div>
                <div class="col-xs-12 col-md-6">
                    <hr>
                </div>
                <div class="col-xs-12 col-md-3"></div>
            </div>

        </div>
    </div>


    <div class="container-fluid">

        <div class="row">
            <div class="col-xs-12 col-md-1"></div>

            <div class="col-xs-12 col-md-10">

                <div class="tarjeta container">

                    <div class="row">
                        <div class="col-xs-12 col-md-12 col-lg-4">
                            <img class="img-responsive" src="/themes/{{$theme}}/img/landing/tasacion-monedas.jpg">
                        </div>

                        <div class="col-xs-12 col-md-12 col-lg-8">
                            <div class="titles-border" style="text-align: left; margin: 0px; padding: 0px;"><li style="list-style: none"><h3>Tasaciones o valoraciones</h3></li></div>
                            <div style="margin-top:2rem; text-align: justify; font-size: 15px; font-weight: normal">
                                Sin duda una de las ventajas que ofrecemos a nuestros clientes es la <strong>profesionalidad y experiencia de nuestro equipo.</strong>
                                Combinamos la experiencia de los profesionales de la venta con la pasión de los coleccionistas. Nos avala una larga trayectoria en el sector,
                                y además seguimos creciendo y apostando por la innovación y la creación de nuevas oportunidades de venta. Combinamos la esencia de las subastas
                                presenciales con las nuevas subastas online en nuestra web. Así podrás pujar por nuestros lotes donde quieras que estés.
                            </div>
                        </div>
                    </div>

                </div>

                <div class="tarjeta container">

                    <div class="row">
                        <div class="col-xs-12 col-md-4"><img class="img-responsive" src="/themes/{{$theme}}/img/landing/subasta-monedas-antiguas.jpg"></div>

                        <div class="col-xs-12 col-md-8">
                            <div class="titles-border" style="text-align: left; margin: 0px; padding: 0px;"><li style="list-style: none"><h3>Fotografiamos tu colección</h3></li></div>
                            <div style="margin-top:2rem; text-align: justify; font-size: 15px; font-weight: normal">
                                Con Tauler & Fau <strong>puedes fotografiar su colección de forma presencial</strong>, y conseguir resultados profesionales.
                                Rellena este formulario señalando el tipo de monedas que forman su colección, la cantidad de piezas que desea fotografiar,
                                el servicio que quiere solicitar, en que formato desea recibirlas le enviaremos un presupuesto personalizado.
                                Disponemos de <strong>servicios de recogida y entrega</strong> con todas las garantías de seguridad para que podamos realizar las fotografías de su colección.
                            </div>
                        </div>
                    </div>

                </div>

                <div class="tarjeta container">

                    <div class="row">
                        <div class="col-xs-12 col-md-4"><img class="img-responsive" src="/themes/{{$theme}}/img/landing/encapsular-monedas.jpg"></div>

                        <div class="col-xs-12 col-md-8">
                            <div class="titles-border" style="text-align: left; margin: 0px; padding: 0px;"><li style="list-style: none"><h3>Servicio de encapsulado</h3></li></div>
                            <div style="margin-top:2rem; text-align: justify; font-size: 15px; font-weight: normal">
                                Tauler & Fau te ofrece un servicio de encapsulado para monedas o billetes de forma profesional.
                                <strong>Rellena el formulario</strong> ofreciéndonos información su colección y le enviaremos un presupuesto personalizado.
                                Trabajamos con las empresas de certificación más importantes del mundo como son: NGC y PCGS.
                            </div>
                        </div>
                    </div>

                </div>



            </div>

            <div class="col-xs-12 col-md-1"></div>
        </div>

    </div>

</section>



<section style="margin-top: 1rem;margin-bottom: 5rem;">

    <div class="container-fluid">
        <div class="col-xs-12 text-center title-b-container" style="font-size: 1.7em;">
            <div class="titles-border">
                <h2>Encuéntranos</h2>
            </div>
        </div>
    </div>


    <div class="custom-bar-title" style="font-size: 14px;">

        <div class="container-fluid" style="padding: 1rem;">

            <div class="row">

                <div class="col-xs-12 col-md-12 col-lg-4">
                    <br><br>

                    <div class="elements">
                        <div class="row">
                            <div class="col-xs-3"></div>

                            <div class="col-xs-6">

                                <div class="row">
                                    <div class="col-xs-1" style="padding: 0px;"><span style="font-size: 2.5em; color: white;">
                                            <img class="img-responsive" src="/themes/{{$theme}}/img/landing/icon_1.png">
                                        </span></div>
                                    <div class="col-xs-10">
                                        Calle Marqués de Urquijo, 34<br>
                                        2ºExt. Dcha<br>
                                        28008 - Madrid, España
                                    </div>
                                </div>

                            </div>

                            <div class="col-xs-3"></div>
                        </div>
                    </div>
                    <br>

                    <div class="elements">

                        <div class="row">
                            <div class="col-xs-3"></div>

                            <div class="col-xs-6">

                                <div class="row">
                                    <div class="col-xs-1" style="padding: 0px;"><span style="font-size: 2.5em; color: white;">
                                            <img class="img-responsive" src="/themes/{{$theme}}/img/landing/icon_2.png">
                                        </span></div>
                                    <div class="col-xs-10"  style="margin-left: 5px;">
                                        <a style="text-decoration: none;color:white" href="mailto:Info@tauleryfau.com">Info@tauleryfau.com</a>
                                    </div>
                                </div>

                            </div>

                            <div class="col-xs-3"></div>
                        </div>

                    </div>
                    <br>

                    <div class="elements">

                        <div class="row">
                            <div class="col-xs-3"></div>
                            <div class="col-xs-6">
                                <div class="row">
                                    <div class="col-xs-1" style="padding: 0px;"><span style="font-size: 2.5em; color: white;">
                                            <img class="img-responsive" src="/themes/{{$theme}}/img/landing/icon_3.png">
                                        </span></div>
                                    <div class="col-xs-10"  style="margin-left: 5px;">
                                        <a style="text-decoration: none;color:white" href="tel:+34914221444">+34 914 221 444</a>
                                    </div>
                                </div>

                            </div>
                            <div class="col-xs-3"></div>
                        </div>


                    </div>
                    <br><br>

                </div>

                <div class="col-xs-12 col-md-12 col-lg-8">

                    <div class="maps" style="width: 98%">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3037.064255548779!2d-3.7222706843288655!3d40.42957606278159!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd4228f72e43ffff%3A0x2514ed48e18685f9!2sTauler%26Fau+Subastas!5e0!3m2!1ses!2ses!4v1520440273659" height="400" frameborder="0" style="border:0;width:100%" allowfullscreen></iframe>
                    </div>
                </div>

            </div>


        </div>


    </div>
</div>


</section>


<section class="contact-info">
    <div class="container">
        <div class="row">
            <div class="contact-info-content">
                <div class="col-xs-12 col-sm-2">

                </div>
                <div class="col-xs-12 col-sm-8 col-sm-offset-1" id="contact">
                    <div class="col-xs-12 col-lg-6">
                        <div class="title-contact">
                            <h1>¿Tienes Dudas?</h1>
                        </div>
                    </div>
                    <div class="col-xs-12 ">
                        <form action="/api-ajax/mail" method="post">
                            <div class="input-group">
                                <input type="text" name="name" placeholder="Nombre" required>
                                <input type="email" name="email" placeholder="Email" required>
                                <input type="text" name="telf" placeholder="Teléfono" required>
                            </div>
                            <div class="text-area">
                                <textarea placeholder="Asunto" required="" name="comentario" ></textarea>
                            </div>
                            <div class="checkbox" style="text-align: left;">
                                <label>

                            </div>
                            <div id="html_element" style="position:absolute"></div>

                            <div class="send-button">
                                <button id="buttonSend"  class="btn btn-color" type="submit" disabled>Enviar <i class="fa fa-paper-plane" disabled></i></button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>
<br><br><br>






<script type="text/javascript">

    var verifyCallback = function(response) {
    $('#buttonSend').attr('disabled', false)
    };

    var onloadCallback = function() {
    grecaptcha.render('html_element', {
    'sitekey' : '6LfKUJsUAAAAAOtdXBZE9VEKFcd-npZp_ycesbSd',
    'callback' : verifyCallback,
    'theme' : 'light'
    });
    };
</script>





@stop






