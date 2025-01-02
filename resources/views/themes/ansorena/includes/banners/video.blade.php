@if ($banner)
    @foreach ($banner->activeItems as $bannerItem)
        <div class="video-banner video-banner-{{ $bannerItem->id }}">
            @if (!empty($content['title']))
                <div class="video-banner-content">
                    <h2 class="video-banner-title">{{ $content['title'] }}</h2>
                    <h3 class="video-banner-subtitle">
                        {{ trans("$theme-app.subastas.lot_subasta_online") }}
                    </h3>
                    <a class="btn btn-medium btn-outline-lb-translucent video-banner-btn" href="{{ route('subasta.actual-online') }}">
						{{ trans("$theme-app.lot_list.go_to_auction") }}
					</a>
                </div>
            @endif

            <video class="w-100" src="{{ $bannerItem->texto }}" controls autoplay muted loop playsinline>
                Tu navegador no admite el elemento <code>video</code>.
            </video>
        </div>
    @endforeach
@endif
