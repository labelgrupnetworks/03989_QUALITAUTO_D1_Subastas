@extends('layouts.mail')

@section('content')        
        
        <p>Estimado  <?= $emailOptions['user'] ?>,</p>

        <p>Tenemos el placer de comunicarle que hoy presentamos nuestra nueva web: <a href="https://www.subarna.net/">www.suberna.net</a></p>

        <p>A continuación, le detallamos sus datos de usuario, para poder acceder por primera vez:</p>



          <p><strong>USUARIO:</strong> <?= $emailOptions['email'] ?></p>
          <p><strong>CONTRASEÑA: </strong><?= $emailOptions['pwd'] ?></p>

        <p>Una vez haya accedido, podrá modificar la contraseña entrando a su CUENTA.</p>

        <p>Ante cualquier consulta, por favor no dude en contactar con nosotros en subarna@subarna.net o llamando al +34 932 15 65 18.</p>



        <p>Gracias por su confianza,</p>
        <p>SUBARNA, SUBHASTES DE BARCELONA SL.</p>
@stop		