<input type="hidden" name="_token" id="token" value="{{ Session::token() }}" />
<section class="title-ficha">
	<div class="row">
		<div class="col-xs-12">
			<div class="title-wrapper valign">
				<div class="title-lot">
					<h2>
						{{trans($theme.'-app.lot.lot-name')}} {{$ref_asigl0}} - {{$titulo ?? ''}}
					</h2>
				</div>
			</div>
			<p class="description-lot">
				{!!$descripcion!!}
			</p>
		</div>
	</div>
</section>

<section class="body-ficha">
	<div class="row">
		<div class="col-xs-12">
			<div class="single-lot">

				<div id="img_main" class="img-single-lot flex valign hidden-xs hidden-sm hidden-md embed-responsive embed-responsive-16by9"></div>

				<div class="owl-theme owl-carousel visible-xs visible-sm visible-md" id="owl-carousel-responsive">

					@foreach($imagenes as $key => $imagen)
					<div class="item_content_img_single" style="position: relative; height: 250px; overflow: hidden;">
						<img style="    max-width: 100%; max-height: 190px;top: 50%; transform: translateY(-50%); position: relative; width: auto !important;    display: inherit !important;    margin: 0 auto !important;"
							class="img-responsive"
							loading="lazy"
							src="{{ \Tools::url_img("lote_medium_large", $num_hces1, $lin_hces1, $key) }}"
							alt="{{$titulo}}">
					</div>
					@endforeach

				</div>

				<div class="single-lot-bar inline-flex valign hidden-xs hidden-sm hidden-md">

					<div class="slider-images-mini">

						<div class="btn-slider-mini">
							<div class="btn-left"><i class="fa fa-chevron-circle-left"></i></div>
							<div class="btn-right"><i class="fa fa-chevron-circle-right"></i></div>
						</div>

						<div class="carousel-img-btn inline-flex">
							<div class="img-thumnail inline-flex valign">

								@if(count($videos) > 0)
								@foreach($videos as $key => $video)

								<a class="img-openDragon img-thumb-item flex valign"
									href="javascript:loadVideoModalGrid('{{$video}}','{{$ref_asigl0}}','{{$cod_sub}}');">
									<img src="{{ asset('/themes/tauleryfau/assets/img/play.png') }}" />
								</a>

								@endforeach
								@endif

								@foreach($imagenes as $key => $imagen)
								<div class="col-sm-3-custom">
									<a href="javascript:loadSeaDragon('{{ $imagen }}', {{ $key }});">
										<div class="img-openDragon img-thumb-item flex valign">
											<img src="{{ \Tools::url_img("lote_small", $num_hces1, $lin_hces1, $key) }}"
												data-image="{{ $imagen }}">
										</div>
									</a>
								</div>
								@endforeach

							</div>
						</div>

					</div>

				</div>
			</div>
		</div>
	</div>
</section>

<script src="{{ URL::asset('js/openseadragon.min.js') }}"></script>
<script>
	$('.item_content_img_single').click(function() {

		var src = $(this).find('img').attr('src');
		var newsrc = "";

		for (var splitSrc of src.split('/')) {
			if(splitSrc.includes(".jpg") ||  splitSrc.includes(".png")){
				newsrc = splitSrc;
			}
		}

    	$('#zommImg img').attr('src', '/img/load/real/'+newsrc);
    	$('#zommImg img').attr('data-magnify-src', '/img/load/real/'+newsrc);
    	$('#zommImg').fadeIn();
	});

	$('.zoomImgClose').click(function(){
    	$('#zommImg').fadeOut()
    	$('#zommImg img').attr('src', '');
	});


function loadVideoModalGrid(video,ref,sub){
	addReproduccion(video,ref,sub);
    $("#img_main").html('');
	$("#img_main").append('<video height="100%" controls autoplay  id="elvideo" onplay="addReproduccion(\''+video+'\',\''+ref+'\',\''+sub+'\')"><source src="'+video+'" type="video/mp4"></video>');

	$('.mfp-close').off('click');
	$('.mfp-close').on('click', function(e){
		$("#elvideo")[0].pause();
		$("#elvideo")[0].remove();
	});
}

function loadSeaDragon(img, position){
		indexImg = position;
        var element = document.getElementById("img_main");
        while (element.firstChild) {
          element.removeChild(element.firstChild);
        }

		OpenSeadragon({
        	id:"img_main",
        	prefixUrl: "/img/opendragon/",
			showReferenceStrip:  true,
			tileSources: [{
                type: 'image',
                url:  '/img/load/real/'+img
            }],
        	showNavigator:false,
        });
}

@if(count($videos) > 0)
loadVideoModalGrid('{{$videos[0]}}','{{$ref_asigl0}}','{{$cod_sub}}');
@else
loadSeaDragon('{{ $imagenes[0] }}', 0);
@endif


	$(window).ready(function(){


(function ($) {
	var carouselThumnail = {
		init: function () {
			this.cache()
			this.bindEvents()
			this.move()

		},
		cache: function () {
			this.chevrons = $('.btn-carousel-thumnail')
			this.chLeft = $('.btn-slider-mini').find('.btn-left')
			this.chRight = $('.btn-slider-mini').find('.btn-right')
			this.carousel = $('.carousel-img-btn')
			this.scrollLeft = 0
			this.scrollTotal =  $('.img-thumnail').width() - $('.carousel-img-btn').width()

		},

		scroll: function(e){
			this.scrollTotal =  $('.img-thumnail').width() - $('.carousel-img-btn').width()
			if(this.scrollLeft >= 0 && this.scrollLeft <= this.scrollTotal){
				if((e.currentTarget.className === 'btn-right')){
					if(this.scrollLeft !== this.scrollTotal){
						this.scrollLeft = this.scrollLeft + 136
						if(this.scrollLeft > this.scrollTotal){
							this.scrollLeft = this.scrollTotal
						}
					}
				}

		if((e.currentTarget.className === 'btn-left')){
			if(this.scrollLeft !== 0){
				this.scrollLeft = this.scrollLeft - 136
				if(this.scrollLeft < 0){
					this.scrollLeft = 0
				}
			}
		}
		this.move()

		}
	},
		move: function () {
			this.carousel.animate({
				scrollLeft: this.scrollLeft
			},200)
		},


		bindEvents: function () {

			this.chLeft.on('click', this.scroll.bind(this))
			this.chRight.on('click', this.scroll.bind(this))
		}
}

carouselThumnail.init()
})($);

});

</script>
