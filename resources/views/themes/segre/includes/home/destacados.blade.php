<div class="container">
    <h2 class="mb-5 text-center">{{ trans("web.home.featured_items") }}</h2>

    <div class="lotes_destacados my-2">
        <div class="loader"></div>
        <div class="carrousel-wrapper" id="lotes_destacados" data-container="section-destacados"></div>
    </div>

    <div class="text-center mt-3">
        <a class="btn btn-outline-lb-primary px-md-5" href="{{ route('allCategories') }}">
            {{ trans("web.global.see_more") }}
        </a>
    </div>
</div>

@php
    $replace = [
        'lang' => Tools::getLanguageComplete(Config::get('app.locale')),
        'emp' => Config::get('app.emp'),
    ];
@endphp

<script>
    const replace = @json($replace);
    $(document).ready(function() {
        ajax_newcarousel("lotes_destacados", replace, null, {
            autoplay: false,
            arrows: false,
            dots: false
        });
    });
</script>
