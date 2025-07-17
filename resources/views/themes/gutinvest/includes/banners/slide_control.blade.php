@foreach ($banner->activeItems as $bannerItem)
    <div class='controls_slider'>
        <picture>
            <source srcset="{{ $bannerItem->images['desktop'] }}" media="(min-width: 600px)">
            <img src="{{ $bannerItem->images['mobile'] }}" alt="{{ $bannerItem->texto }}" class="img-responsive">
        </picture>
    </div>
@endforeach
