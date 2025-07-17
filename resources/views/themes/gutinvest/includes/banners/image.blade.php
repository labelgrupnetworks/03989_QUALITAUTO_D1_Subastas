@foreach ($banner->activeItems as $bannerItem)
    <picture>
        <source srcset="{{ $bannerItem->images['desktop'] }}" media="(min-width: 600px)">
        <img class="img-responsive" src="{{ $bannerItem->images['mobile'] }}" alt="{{ $bannerItem->texto }}">
    </picture>
@endforeach
