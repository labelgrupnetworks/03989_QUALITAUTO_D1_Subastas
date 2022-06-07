@extends('layouts.default')

@section('title')
{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop


@section('content')


			<div class="container static-page-container">

					<div class="col-xs-12 ml-2 mt-2 mb-2">

						<p>En nombre de {{$prop_name}}, propietario del vehículo, nos complace informarte que has comprado el vehículo {{$lot_descweb}}, Oferta Nº {{$lot_ref}}   por  {{$price}}  (IVA incluido).</p>
						<p><strong>Para que el coche quede reservado a tu nombre y formalices su compra</strong>, el propietario indica que, por favor:
						<p><strong>Primero, pagues, a la mayor brevedad</strong>, dentro de las siguientes 24 horas, la se&ntilde;al de  {{$importe_reserva}}.
						<strong>Hasta que no se reciba dicha se&ntilde;al, el coche podr&aacute; ser vendido a otro comprador.</strong></p>
						<p><strong>Segundo</strong>, contactes con {{$prop_contact}}   de {{$prop_name}}   en el teléfono {{$prop_tel}}  o email {{$prop_email}} para:</p>
						<ol class="ml-2" style="list-style-type: lower-alpha;">
						<li>Tramitar <strong>la financiaci&oacute;n</strong> del veh&iacute;culo o el pago restante, si vas a comprarlo al <strong>contado</strong>,  en cuyo caso deber&aacute;s hacerlo en el plazo m&aacute;ximo de siete d&iacute;as desde la recepci&oacute;n de este correo <br> <br></li>
						<li>Coordinar <strong>su entrega</strong> y tr&aacute;mites administrativos (cambio de titularidad&hellip;).</li>
						</ol>
						<p><strong>Informaci&oacute;n sobre la se&ntilde;al.</strong><br />El importe de la se&ntilde;al se deducir&aacute; del precio del veh&iacute;culo indicado arriba.
						<br />Hasta que te entreguen el veh&iacute;culo, la se&ntilde;al ser&aacute; custodiada por Carlandia Ib&eacute;rica S.L. en una cuenta a su nombre en CaixaBank, dedicada exclusivamente al dep&oacute;sito de se&ntilde;ales de compra de veh&iacute;culos.
						<br />Cuando el veh&iacute;culo se te haya entregado, la se&ntilde;al le ser&aacute; abonada al vendedor como parte del pago del coche.
						De lo contrario, la se&ntilde;al te ser&aacute; devuelta.</p>
						<p><strong>Pago de la se&ntilde;al y reserva del vehículo</strong><br />A trav&eacute;s de CaixaBank Redsys, <strong>pincha</strong> <a   href={{$pay_link}}>aqu&iacute;.</a></strong> </p>

						<p>Si tienes alguna duda, por favor, ind&iacute;canoslo pinchando  <a href="/es/contacto" target="_blank">aquí</a>.</p>
						<p>&iexcl;Enhorabuena por tu compra! &iexcl;Disfruta mucho tu nuevo veh&iacute;culo!</p>
						<p>Muchas gracias por tu confianza. </p>
						<p>Saludos,</p>

						</div>
</div>

@stop
