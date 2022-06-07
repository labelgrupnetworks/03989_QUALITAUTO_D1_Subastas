@extends('layouts.mail')

@section('content')

        
        <p>Estimado  <?= $emailOptions['user'] ?>,</p>

        <p>Tenemos el placer de comunicarle que hoy presentamos nuestra nueva web: <a href="https://www.arcesubastas.com/">www.arcesubastas.com</a></p>

        <p>A continuación, le detallamos sus datos de usuario, para poder acceder por primera vez:</p>



          <p><strong>USUARIO:</strong> <?= $emailOptions['email'] ?></p>
          <p><strong>CONTRASEÑA: </strong><?= $emailOptions['pwd'] ?></p>

        <p>Una vez haya accedido, podrá modificar la contraseña entrando en "Mi cuenta".</p>

        <p>Ante cualquier consulta, por favor no dude en contactar con nosotros en info@arcesubastas.com o llamando al +34 93 202 10 00.</p>

        <p>Gracias por su confianza,</p>
        <p>Arce Subastas.</p>
        <br>
        <hr>
        <br>
        
        <p>Dear  <?= $emailOptions['user'] ?>,</p>

        <p>We are pleased to inform you that today we launch our new website: <a href="https://www.arcesubastas.com/">www.arcesubastas.com</a></p>

        <p>In order to log in for the first time, you will need to use the following credentials:</p>



          <p><strong>USER:</strong> <?= $emailOptions['email'] ?></p>
          <p><strong>PASSWORD: </strong><?= $emailOptions['pwd'] ?></p>

        <p>Once on your profile, on “My Account”, you will be able to modify your data.</p>

        <p>Do not hesitate to contact us If you have any questions. You could send an e-mail to info@arcesubastas.com or call us at +34 93 202 10 00.</p>

        <p>Thank you for your trust,</p>
        <p>Arce Subastas.</p>
@stop		