

@if($typePuja == 'C' ){{-- contraoferta  --}}
<div class="ml-1 mr-1" style="text-align: justify;font-size: 16px;" >
	<p style="text-align: left;">¡Enhorabuena! </p>

	<p >La empresa  {{$prop_name}}, propietaria del vehículo, ha aceptado tu contraoferta para comprar el vehículo <strong>{{$lot_descweb}},</strong> Oferta Nº {{$lot_ref}}, <strong>por {{$price_counteroffer}} (IVA incluido).</strong> </p>

	<p >Para reservarlo a tu nombre, poder financiar su compra o pagarlo al contado y coordinar su entrega, por favor,<strong> procede a depositar la señal de {{$importe_reserva}} vía Redsys pulsando el botón de abajo.</strong></p>

	<p >Hasta que no se reciba dicha señal, el coche podrá ser vendido a otro comprador.</p>
	<p >La señal se aplicará al pago del coche cuando se formalice la compraventa y se te entregue el vehículo. De lo contrario, la señal te será restituida.</p>
	<p >Pincha <a href="/es/pagina/info-adjudicacion" target="_blank"  ><u>aquí</u></a> para ver más detalles. En cualquier caso, te hemos proporcionado toda la información en tu e-mail. </p>
	<p >Si tienes alguna duda, por favor, indícanoslo por email pinchando   <a href="/es/contacto" target="_blank"><u>aquí</u></a>.o si prefieres, contacta con nosotros por WhatsApp o teléfono en el 900 670 239.</p>

	<center><a class="btn-return btn button-principal"  href={{$pay_link}}>DEPOSITAR SEÑAL</a></center>
</div>

@else{{-- compra ya de un vehículo online if($typePuja == 'Y' || $typePuja == 'B' )  --}}
<div class="ml-1 mr-1" style="text-align: justify;font-size: 16px;" >
	<p style="text-align: left;">¡Enhorabuena! </p>

	<p >En nombre de   {{$prop_name}}, propietaria del vehículo, nos complace informarte que, te has adjudicado el vehículo <strong>{{$lot_descweb}},</strong> Oferta Nº {{$lot_ref}},<strong> por {{$price}} (IVA incluido). </strong> </p>

	<p >Para reservarlo a tu nombre, poder financiar su compra o pagarlo al contado y coordinar su entrega, por favor,<strong> procede a depositar la señal de  {{$importe_reserva}}  vía Redsys pulsando el botón de abajo.</strong></p>

	<p >Hasta que no se reciba dicha señal, el coche podrá ser vendido a otro comprador.</p>
		<p >La señal se aplicará al pago del coche cuando se formalice la compraventa y se te entregue el vehículo. De lo contrario, la señal te será restituida.</p>
		<p >Pincha <a href="/es/pagina/info-adjudicacion" target="_blank" ><u>aquí</u></a> para ver más detalles. En cualquier caso, te hemos proporcionado toda la información en tu e-mail. </p>
	<p >Si tienes alguna duda, por favor, indícanoslo por email pinchando  <a href="/es/contacto" target="_blank"><u>aquí</u></a> o si prefieres, contacta con nosotros por WhatsApp o teléfono en el 900 670 239.</p>

	<center ><a class="btn-return btn button-principal"  href={{$pay_link}}>DEPOSITAR SEÑAL</a></center>

@endif
