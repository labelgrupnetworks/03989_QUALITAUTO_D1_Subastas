@extends('layouts.default')

@section('title')
    {{ trans($theme . '-app.head.title_app') }}
@stop

@section('content')
    <main class="grid">

		<section class="grid-auction-info">
			@include('includes.grid.auction_info')
		</section>

		<section>
			@include('content.grid')
		</section>

		<section>
			<div class="container">
				<div class="row">
					<div class="col-lg-3"></div>
					<div class="col-lg-9">
						<div class="how-to-buy-section">
							<p>Compra o vende pintura, joyas, relojes, vintage, diseño y más cuando quieras</p>
							<div class="how-to-buy-action">
								<h2 class="how-to-buy-text ff-highlight">Compra ahora</h2>
								<a class="how-to-buy-link btn btn-outline-lb-primary rounded-5">Ir a venta directa</a>
							</div>
							<p>Envíenos la información y en 48 horas nuestros especialistas se pondrán en contacto con usted</p>

						</div>
					</div>
				</div>
			</div>
		</section>
    </main>
@stop
