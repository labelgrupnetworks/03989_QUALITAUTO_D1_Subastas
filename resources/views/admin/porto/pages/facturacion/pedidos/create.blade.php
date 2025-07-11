@extends('admin::layouts.logged')
@section('content')

<section role="main" class="content-body">

	<div class="row well header-well d-flex align-items-center">
		<div class="col-xs-9">
			<h1>{{ trans_choice("admin-app.title.venta", 1) }}</h1>
		</div>
		<div class="col-xs-3">
			<a href="{{ route('pedidos.index') }}" class="btn btn-primary right">{{ trans("admin-app.button.return") }}</a>
		</div>
	</div>



	<div class="row well" style="font-size:15px">

		<form  id="pedidosForm" >
			@csrf
			<div class="col-xs-12 col-md-9 mb-3 ">
				<div class="row d-flex flex-wrap">
					<div class="col-xs-12">	<h1> {{ trans_choice("admin-app.fields.buyer", 1) }}</h1> </div>

					<div class="col-xs-12 col-sm-6">
						<label class="mt-1" for="client">{{ trans("admin-app.fields.buyer") }}</label>
						<i class="fa fa-info-circle" style="cursor: pointer; margin-left: 3px" aria-hidden="true"
							data-toggle="tooltip" data-placement="right"
							data-original-title="{{ trans("admin-app.help_fields.buyer") }}"></i>
						{!! $formulario->client !!}
					</div>


				</div>
				<div class="row d-flex flex-wrap mt-2">
					<div  class="col-xs-12">	<h2>Obras vendidas:</h2></div>




						<div class="col-xs-12  ">

							<div class="col-xs-6" >
								<label class="mt-1" for="obras">{{ trans("admin-app.fields.agregarObrasVenta") }}</label>
								<label class="mt-1 error" >

									En el buscador sólo aparecerán obras que tengan precio  y precio de coste
								</label>
								{!! $formulario->obras !!}
							</div>
							<div class="col-xs-6 mt-4" >
								<input id="addObra" type="button" class="btn btn-success  mt-2" value="{{ trans("admin-app.fields.agregarObraVenta") }}">
								<br>

									<label id="errorAddObra" class="error hidden">  </label>

							</div>

							<div class="col-xs-12 mt-3" >
								Listado de obras vendidas.

							</div>

						</div>





					<div id="obrasList"	 class="ml-2 mt-1" style="background-color: white;padding:20px;min-width: 100%;">
						<div id="obraClon" class="hidden">
							* <span> </span>	<input type="hidden" name="" > <label class="error"> - Eliminar </label>
						</div>
					</div>
					<div id="Descuento"	 class="ml-2 mt-1" >
						<br/>
						<p style="text-align:right">Importe Base Obras: <span id="importeBasePedidoLabel" ></span> €</p>
						<input type="hidden" id="importeBasePedido" value="0" >
						<input type="hidden" id="iva" value="0" >
						<br/>
						Descuento:
						<br/>
						<input type="number" name="descuento" value="0" min="0" max="100"> %
						<br/>

						<br/>
						Importe total con IVA:
						<br/>
						<input type="number" name="impTotalForzado" value="0" >

						<br/>
						Observaciones:
						<br/>
						<textarea  name="observaciones" cols="60" rows="6"> </textarea>
					</div>

				</div>




				<div class="row">
					<div class="col-xs-12">
						<input id="CrearPedidoBtn" type="button" class="btn btn-success mt-2" value="Crear Venta">

					</div>
					<div class="col-xs-12">
						<label  id="errorCreatePedido" class="error hidden">  </label>
					</div>
				</div>
		</div>
		</form>

	</div>




	@stop
