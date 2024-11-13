@php
    $imagePath = "/themes/$theme/assets/img/categories";
    $categories = (new App\Models\V5\FgOrtsec0())->getAllFgOrtsec0()->whereNotNull('key_ortsec0')->get();
    $categoriesWithImage = $categories
        ->filter(function ($category) use ($imagePath) {
            return file_exists(public_path("$imagePath/{$category['lin_ortsec0']}.jpg"));
        })
        ->values();

@endphp

<div class="container">
    <p class="home-section_subtitle">{{ trans("$theme-app.home.seo_home_subtitle") }}</p>
    <h2 class="home-section_title">{{ trans("$theme-app.home.seo_home_title") }}</h2>
    <p class="home-section_desc">{{ trans("$theme-app.home.seo_home_text") }}</p>

    <div class="categories">
        @foreach ($categoriesWithImage as $category)
            <a class="category-card" href="{{ route('category', ['keycategory' => $category['key_ortsec0']]) }}">
                <img src="{{ $imagePath }}/{{ $category['lin_ortsec0'] }}.jpg" alt="{{ $category['des_ortsec0'] }}">
                <h3 class="category_name">{{ $category['des_ortsec0'] }}</h3>
            </a>
        @endforeach
    </div>
</div>

<script>
    $('.categories').slick({
        infinite: false,
        slidesToShow: 5,
        slidesToScroll: 1,
        responsive: homeBannersOptions,
        prevArrow: '<svg xmlns="http://www.w3.org/2000/svg" class="slick-prev" viewBox="0 0 512 512" fill="currentColor"><path d="M177.5 414c-8.8 3.8-19 2-26-4.6l-144-136C2.7 268.9 0 262.6 0 256s2.7-12.9 7.5-17.4l144-136c7-6.6 17.2-8.4 26-4.6s14.5 12.5 14.5 22l0 72 288 0c17.7 0 32 14.3 32 32l0 64c0 17.7-14.3 32-32 32l-288 0 0 72c0 9.6-5.7 18.2-14.5 22z"/></svg>',
        nextArrow: '<svg xmlns="http://www.w3.org/2000/svg" class="slick-next" viewBox="0 0 512 512" fill="currentColor"><path d="M334.5 414c8.8 3.8 19 2 26-4.6l144-136c4.8-4.5 7.5-10.8 7.5-17.4s-2.7-12.9-7.5-17.4l-144-136c-7-6.6-17.2-8.4-26-4.6s-14.5 12.5-14.5 22l0 72L32 192c-17.7 0-32 14.3-32 32l0 64c0 17.7 14.3 32 32 32l288 0 0 72c0 9.6 5.7 18.2 14.5 22z"/></svg>',
    });
</script>
