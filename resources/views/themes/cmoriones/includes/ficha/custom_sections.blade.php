
@if(in_array($lote_actual->cod_sub, ['CEREMATE', 'COMDEUDA', 'REOS']))
<section class="ficha-login">
    <p class="mb-2">
        Si quieres tener una VALORACIÓN REAL de este activo a fecha de hoy por el que muestras interés de forma
        TOTALMENTE GRATUITA, ve a <button class="btn btn-link btn_login p-0">login</button> completa los datos y solicita más información. Uno de nuestros expertos
        valorará el activo con el máximo detalle y precisión y se pondrá en contacto contigo para transmitirte el
        resultado de la valoración.
    </p>
	@if(Session::has('user'))
	<button class="btn btn-lb-primary w-100">
		Solicitar valoración
	</button>
	@endif
</section>
@endif

<section class="ficha-contact">
	<a class="btn btn-success" href="https://wa.me/34602252061?text=Estoy%20interesado%20es%20el%20siguiente%20activo%20{{ URL::current() }}" target="_blank" style="--lb-border-radius:.375rem">
		<svg class="bi" width="24" height="24" fill="currentColor">
			<use xlink:href="/bootstrap-icons.svg#whatsapp"></use>
		</svg>
		Contacta con nosotros
	</a>
</section>
