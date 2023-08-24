@if ($banner)
    @foreach ($banner->activeItems as $bannerItem)
        <div class="video-banner video-banner-{{ $bannerItem->id }}">
            <video class="w-100" src="{{ $bannerItem->texto }}" controls autoplay muted loop playsinline>
                Tu navegador no admite el elemento <code>video</code>.
            </video>
        </div>
    @endforeach
@endif
