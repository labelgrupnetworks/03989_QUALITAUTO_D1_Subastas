<div id="modal-full-page-element">

<style>
	.swiper-container {
		width: 100%;
		height: 100%;
	}

	.swiper-slide {
		overflow: hidden;
	}

	.swiper-container .swiper-wrapper {
		height: 100%;
	}

	.swiper-container .swiper-slide {
		max-height: 100%;
		width: 100%;
		height: auto;
	}

	.modal-fullscreen {
		padding: 0 !important;
	}

	.modal-fullscreen .modal-dialog {
		width: 100%;
		height: 100%;
		margin: 0;
		padding: 0;
	}

	.modal-fullscreen .modal-content {
		height: auto;
		min-height: 100%;
		border: 0 none;
		border-radius: 0;
		box-shadow: none;
		background: black;
	}
</style>

<!-- Modal Fullscreen -->
<div class="modal fade modal-fullscreen" id="modal-fullscreen" tabindex="-1" role="dialog"
	aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header" style="position: absolute; left:0; right: 0; top: 0; z-index: 5">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>

				<div class="row d-flex align-items-center" style="height: 100vh">

					<div class="col-xs-12 h-100">
						<!-- Slider main container -->
						<div class="swiper-container">
							<!-- Additional required wrapper -->
							<div class="swiper-wrapper">
								<!-- Slides -->
								@foreach($imagesUrl as $key => $imagen)
								<div class="swiper-slide">
									<div class="swiper-zoom-container">
										<img style="max-height: 100%; max-width: 100%" class="img-responsive"
											data-pos="{{ $key }}" src="{{ $imagen }}">
									</div>
								</div>
								@endforeach
							</div>
							<!-- If we need pagination -->
							{{-- <div class="swiper-pagination"></div> --}}

							<!-- If we need navigation buttons -->
							<div class="swiper-button-prev"></div>
							<div class="swiper-button-next"></div>

							<!-- If we need scrollbar -->
							{{-- <div class="swiper-scrollbar"></div> --}}
						</div>
					</div>

				</div>

		</div>
	</div>
</div>


<script>
	var swiper;

	$('#modal-fullscreen').modal('show');

	$('#modal-fullscreen').on('shown.bs.modal', function (event) {

		swiper = new Swiper('.swiper-container', {
			//autoHeight: true,
			zoom: true,
			navigation: {
				nextEl: ".swiper-button-next",
			  	prevEl: ".swiper-button-prev",
			}
			/* pagination: {
				  el: ".swiper-pagination",
			  	clickable: true,
			} */

		});

		swiper.slideTo({{ $page }});

	});


	$('#modal-fullscreen').on('hidden.bs.modal', function (event) {
		$('#modal-full-page-element').remove();
	});



</script>
</div>
