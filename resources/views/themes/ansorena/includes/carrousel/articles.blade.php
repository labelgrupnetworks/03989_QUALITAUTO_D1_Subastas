@php
    $iva = (new App\Http\Controllers\V5\CartController())->ivaAplicable();
    $relatedArticles = App\Models\articles\FgArt0::select('ID_ART0, MODEL_ART0, DES_ART0, PVP_ART0, SEC_ART0')
        ->activo()
        ->orderby(' DBMS_RANDOM.VALUE')
        ->take(6)
        ->get();
@endphp

<section class="recomendados relatedArticles">
    <h2 class="ff-highlight fs-24-48 mb-5 text-center">{{ trans("$theme-app.articles.we_highlight") }}</h2>

    <div class="Grid articles-container">
        @foreach ($relatedArticles as $relatedArticle)
            @include('includes.articles.relatedArticle')
        @endforeach
    </div>
</section>

<script>
	$('.articles-container').slick({
		dots: false,
		infinite: false,
		slidesToShow: 3,
		slidesToScroll: 1,
		responsive: [{
				breakpoint: 992,
				settings: {
					slidesToShow: 2,
					slidesToScroll: 1
				}
			},
			{
				breakpoint: 600,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1
				}
			}
		]
	})
</script>
