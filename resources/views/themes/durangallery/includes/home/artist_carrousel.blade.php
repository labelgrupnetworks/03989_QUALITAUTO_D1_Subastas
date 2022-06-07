@php
/**
 * @todo pendiente quitar el without
 **/
$artists = App\Models\V5\Web_Artist::withoutGlobalScope('emp')
	->select('id_artist', 'name_artist')
	->where('active_artist', 1)
	->get();
$imagePath = "/img/autores/";
@endphp


@if($artists)
<div class="artists-block mt-2 mb-2">

	<div>
		<p class="title-30 color-pink">Nuestros</p>
		<p class="title-40 color-black">Artistas</p>
	</div>
	<div class="d-flex artists-block-buttons">
		<a href="{{ route('artists') }}" class="custom-button">VER TODOS</a>

		<div class="custom-arrows-wrapper">
			<button class="custom-arrow prev" aria-label="Previous" type="button"><i class="fa fa-long-arrow-left"></i></button>
			<button class="custom-arrow next" aria-label="Next" type="button"><i class="fa fa-long-arrow-right"></i></button>
		</div>
	</div>



	<div class="artists-carroulsel-wrapper mt-2">
		@foreach ($artists as $artist)
			@if (file_exists(public_path("$imagePath{$artist->id_artist}.jpg")))
				@include("includes.artists.artist")
			@endif
		@endforeach
	</div>


	<script type="text/javascript">
		$(document).ready(function(){
      $('.artists-carroulsel-wrapper').slick({
		infinite: true,
  		slidesToShow: 3,
  		slidesToScroll: 1,
		dots: true,
		arrows: true,
		autoplay: false,
		prevArrow: $('.artists-block .prev'),
      	nextArrow: $('.artists-block .next'),
		responsive: [
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 3,
        infinite: true,
        dots: true
      }
    },
    {
      breakpoint: 600,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 2
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1,
		dots: false,
		arrows: false
      }
    }
    // You can unslick at a given breakpoint now by adding:
    // settings: "unslick"
    // instead of a settings object
  ]
	  });
    });
	</script>
</div>
@endif
