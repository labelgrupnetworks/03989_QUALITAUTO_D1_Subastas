@php
    $categories = (new App\Models\V5\FgOrtsec0())->getAllFgOrtsec0()->whereNotNull('key_ortsec0')->get()->toarray();
@endphp

<div class="home-slider">
    {!! BannerLib::bannersPorKey(
        'home',
        'home-top-banner',
        ['dots' => false, 'autoplay' => true, 'autoplaySpeed' => 5000, 'slidesToScroll' => 1, 'arrows' => false],
        null,
        false,
        '',
        $page_settings,
    ) !!}
</div>

<section class="home-seo">
    <div class="container">
        <h2>
            En un mundo cambiante e interconectado, <span>Magna Art</span> responde a las nuevas necesidades del arte,
            que hoy traspasa fronteras y donde ofrecemos a nuestros clientes las mejores obras allá donde se ofrezcan.
        </h2>
    </div>
</section>

<section class="home-section home-categories">
    <div class="container">
        <p class="home-section_subtitle">Calidad en nuestra colecciones</p>
        <h2 class="home-section_title">Categorías</h2>
        <p class="home-section_desc">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Commodi deserunt distinctio
            ducimus asperiores
            suscipit, dolorum, quas maiores, repudiandae accusamus harum natus. Eum, assumenda a aliquam quae unde animi
            labore eligendi!</p>

        <div class="categories">
            @foreach ($categories as $category)
                <div class="category-card">
                    <img src="/themes/{{ $theme }}/assets/img/categories/{{ $category['lin_ortsec0'] }}.jpg"
                        alt="{{ $category['des_ortsec0'] }}">
                    <h3 class="category_name">{{ $category['des_ortsec0'] }}</h3>
                </div>
            @endforeach
            @foreach ($categories as $category)
                <div class="category-card">
                    <img src="/themes/{{ $theme }}/assets/img/categories/{{ $category['lin_ortsec0'] }}.jpg"
                        alt="{{ $category['des_ortsec0'] }}">
                    <h3 class="category_name">{{ $category['des_ortsec0'] }}</h3>
                </div>
            @endforeach
        </div>


    </div>
</section>

<script>
	const carrouselResponsive = [
		{
			breakpoint: 1200,
			settings: {
				slidesToShow: 4,
				slidesToScroll: 4,
				infinite: true,
				rows: 1,
				slidesPerRow: 4,
			}
		},
		{
			breakpoint: 1024,
			settings: {
				slidesToShow: 3,
				slidesToScroll: 3,
				infinite: true,
				rows: 1,
				slidesPerRow: 3,
			}
		},
		{
			breakpoint: 770,
			settings: {
				slidesToShow: 2,
				slidesToScroll: 2,
				rows: 1,
				slidesPerRow: 2,
				arrows: false,
			}
		},
		{
			breakpoint: 480,
			settings: {
				slidesToShow: 1,
				slidesToScroll: 1,
				rows: 1,
				slidesPerRow: 1,
				arrows: false,
			}
		}
	];

    $('.categories').slick({
        infinite: false,
        slidesToShow: 5,
        slidesToScroll: 1,
		responsive: carrouselResponsive,
    });
</script>


<section class="home-section home-destacados">
    <div class="container">
        <p class="home-section_subtitle">Remesa de nuestra subastas</p>
        <h2 class="home-section_title">
            {{ trans("$theme-app.lot_list.lotes_destacados") }}
        </h2>
        <p class="home-section_desc">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Commodi deserunt distinctio
            ducimus asperiores
            suscipit, dolorum, quas maiores, repudiandae accusamus harum natus. Eum, assumenda a aliquam quae unde animi
            labore eligendi!</p>


        <div class="lotes_destacados">
            <div class="loader"></div>
            <div class="carrousel-wrapper" id="lotes_destacados"></div>
        </div>

    </div>
</section>

@php
    $replace = ['lang' => Tools::getLanguageComplete(Config::get('app.locale')), 'emp' => Config::get('app.emp')];
@endphp

<script>
    var replace = @json($replace);


    $(document).ready(function() {
        ajax_newcarousel("lotes_destacados", replace, null, {
            autoplay: false,
            arrows: true,
            dots: false,
			slidesToShow: 5,
			responsive: carrouselResponsive,
        });
    });
</script>
