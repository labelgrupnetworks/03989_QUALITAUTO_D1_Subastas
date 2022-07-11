

@if($typePuja == 'C' ){{-- contraoferta  --}}
<div class="ml-1 mr-1" style="text-align: justify;font-size: 16px;" >
	<p style="text-align: left;">¡Enhorabuena! </p>

	<p>Nos complace informarte que el vendedor <strong>ha aceptado tu oferta</strong> para comprar el vehículo <strong>{{$lot_descweb}},</strong> ID {{$lot_ref}}, <strong>por {{$price_counteroffer}} (IVA incluido).</strong></p>

	<p>Como acabamos de indicarte por e-mail, para reservarlo a tu nombre, poder financiar su compra o pagarlo al contado y coordinar su entrega, por favor, <strong>procede a depositar la señal de {{$importe_reserva}} vía Redsys pulsando el botón de abajo.</strong></p>

	<p>Hasta que no se reciba dicha señal, el vehículo podrá ser vendido a otro comprador.</p>
	<p >La señal se aplicará al pago del vehículo cuando se formalice su compraventa y se te entregue. De lo contrario, la señal te será restituida.</p>
	<p >Pincha <a href="/es/pagina/info-adjudicacion" target="_blank"  ><u>aquí</u></a> para ver más detalles.</p>

	<div class="icons-modal mt-1 mb-5">
		<p class="m-0" style="font-size: 16px;"><strong>¿Necesitas ayuda?</strong></p>
		<a href="tel:{{config('app.phoneNumber')}}" class="phone-icon">
			<i class="fa fa-phone" aria-hidden="true"></i>
		</a>
		<a href="mailto:carlandia@calandia.es" class="mail-icon">
			<i class="fa fa-envelope-o" aria-hidden="true"></i>
		</a>
		<a href="https://api.whatsapp.com/send?phone=+34{{config('app.whatsAppNumer')}}&text=" target="_blank" class="whatsapp-icon hidden-sm hidden-md hidden-lg">
			<i class="fa fa-whatsapp" aria-hidden="true"></i>
		</a>
		<a href="https://api.whatsapp.com/send?phone=0034{{config('app.whatsAppNumer')}}&text=" target="_blank" class="whatsapp-icon hidden-xs">
			<i class="fa fa-whatsapp" aria-hidden="true"></i>
		</a>
	</div>
<img src="" alt="" width="" height="">

	<center><a id="btn-modal-deposit" class="btn-return btn button-principal" href={{$pay_link}}>DEPOSITAR SEÑAL</a></center>
</div>

@else{{-- compra ya de un vehículo online if($typePuja == 'Y' || $typePuja == 'B' )  --}}
<div class="ml-1 mr-1" style="text-align: justify;font-size: 16px;" >
	<p style="text-align: left;">¡Enhorabuena!</p>

	<p>Nos complace informarte que el vendedor <strong>ha aceptado tu oferta</strong> para comprar el vehículo <strong>{{$lot_descweb}},</strong> ID {{$lot_ref}}, <strong>por {{$price}} (IVA incluido).</strong></p>

	<p>Para reservarlo a tu nombre, poder financiar su compra o pagarlo al contado y coordinar su entrega, por favor,
		<strong> procede a depositar la señal de {{$importe_reserva}} vía Redsys pulsando el botón de abajo.</strong>
	</p>

	<p>Hasta que no se reciba dicha señal, el coche podrá ser vendido a otro comprador.</p>

	<p>La señal se aplicará al pago del coche cuando se formalice la compraventa y se te entregue el vehículo. De lo contrario, la señal te será restituida.</p>

	<p>Pincha <a href="/es/pagina/info-adjudicacion" target="_blank" ><u>aquí</u></a> para ver más detalles. En cualquier caso, te hemos proporcionado toda la información en tu e-mail.</p>

	<div class="icons-modal mt-1 mb-5">
		<p class="m-0" style="font-size: 16px;"><strong>¿Necesitas ayuda?</strong></p>
		<a href="tel:{{config('app.phoneNumber')}}" class="phone-icon">
			<i class="fa fa-phone" aria-hidden="true"></i>
		</a>
		<a href="mailto:carlandia@calandia.es" class="mail-icon">
			<i class="fa fa-envelope-o" aria-hidden="true"></i>
		</a>
		<a href="https://api.whatsapp.com/send?phone=+34{{config('app.whatsAppNumer')}}&text=" target="_blank" class="whatsapp-icon hidden-sm hidden-md hidden-lg">
			<i class="fa fa-whatsapp" aria-hidden="true"></i>
		</a>
		<a href="https://api.whatsapp.com/send?phone=0034{{config('app.whatsAppNumer')}}&text=" target="_blank" class="whatsapp-icon hidden-xs">
			<i class="fa fa-whatsapp" aria-hidden="true"></i>
		</a>
	</div>

	<center><a class="btn-return btn button-principal" id="depositarSeñalComprar_JS" href={{$pay_link}}>DEPOSITAR SEÑAL</a></center>

@endif
