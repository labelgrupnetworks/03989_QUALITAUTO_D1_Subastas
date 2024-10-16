@php
    $categories = (new App\Models\V5\FgOrtsec0())->getAllFgOrtsec0()->whereNotNull('key_ortsec0')->get()->toarray();
@endphp
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

<script>
    $('.categories').slick({
        infinite: false,
        slidesToShow: 5,
        slidesToScroll: 1,
		responsive: homeBannersOptions,
    });
</script>
