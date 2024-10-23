@php
    $categories = (new App\Models\V5\FgOrtsec0())->getAllFgOrtsec0()->whereNotNull('key_ortsec0')->get()->toarray();
@endphp
<div class="container">
    <p class="home-section_subtitle">{{ trans("$theme-app.home.seo_home_subtitle") }}</p>
    <h2 class="home-section_title">{{ trans("$theme-app.home.seo_home_title") }}</h2>
    <p class="home-section_desc">{{ trans("$theme-app.home.seo_home_text") }}</p>

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

<script>
    $('.categories').slick({
        infinite: false,
        slidesToShow: 5,
        slidesToScroll: 1,
		responsive: homeBannersOptions,
    });
</script>
