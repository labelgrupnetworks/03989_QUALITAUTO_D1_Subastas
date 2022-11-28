<style>
	.ethical-code section {
		padding: 15px 0;
	}

	.ethical-code p {
		font-size: 16pt;
		padding: 5px 0px;
	}

	.ethical-code h2 {
		margin: 1.5rem 0 1.5rem 0;
	}

	.ethical-code .flex-align-center {
		display: flex;
		align-items: center;
		flex-direction: column;
	}

	.ethical-code .lb-blockquote {
		max-width: 50%;
	}

	.ethical-code .background-banner {
		background-image: url("{{ asset('/themes/jesusvico/img/ethical_code_photo_2.webp') }}");
		background-size: cover;
		width: 100%;
		height: auto;
	}

	.ethical-code .flex-align-center-column {
		display: flex;
		align-items: center;
		flex-direction: column;
	}

	.ethical-code .flex-align-center-row-mobile {
		display: flex;
		align-items: center;
		flex-direction: row;
	}

	.ethical-code .btn-lb-primary {
		padding: 8px 25px;
	}

	@media (min-width: 768px) {
		.ethical-code .flex-align-center-row {
			display: flex;
			align-items: center;
			flex-direction: row;
		}

	}

	@media (min-width: 1200px) {
		.ethical-code .margin-video {
			margin: 2rem;
			margin-top: 5rem;
		}

		.ethical-code .margin-arrow-svg {
			margin-top: 8rem;
			margin-left: 18px;
		}
	}
</style>


<div class="ethical-code">

	<div class="container">
		<section>
			<div class="row">
				<div class="col col-md-7">
					<p>Jesús Vico S.A. is a founding member of the AENP (‘Spanish Association of Professional Numismatists’) and a
						member of the IAPN (‘International Association of Professional Numismatists’) since 1991. Our membership in both
						associations implies the fulfilment of a strict code of ethics.</p>
				</div>
			</div>
		</section>
	</div>

	<section class="mb-5">
		<div class="background-banner flex-align-center-column">
			<h2 class="text-center" style="padding: 8rem 0px; color: var(--primary-color);">Our integration in both associations
				implies<br>compliance with a rigorous code of conduct</h2>
		</div>
	</section>

	<div class="container">
		<section>
			<div class="row">
				<div class="col-12 col-md-6 mb-5">
					<div class="flex-align-center-column">
						<div>
							<h3 class="bg-lb-color-primary-light px-2 pt-2 pb-1">AENP code of conduct</h3>
						</div>
						<div style="height: 118px;display: flex;align-items: center;">
							<img src="/themes/jesusvico/assets/img/aenp_img.png" alt="">
						</div>
						<div>
							<a class="btn btn-lb-primary" href="https://aenp.org/codigo-de-conducta-aenp/">Read</a>
						</div>
					</div>
				</div>
				<div class="col-12 col-md-6 mb-5">
					<div class="flex-align-center-column">
						<div>
							<h3 class="bg-lb-color-primary-light px-2 pt-2 pb-1">IAPN Code of Conduct</h3>
						</div>
						<div class="flex-align-center-row-mobile">
							<img src="/themes/jesusvico/assets/img/iapn_img.png" alt="">
						</div>
						<div>
							<a class="btn btn-lb-primary" href="https://iapn-coins.org/iapn/association/">Read</a>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>



</div>
