@php
/**
 * @var string $video_id
 * @var string $title
 * @var string $text
 */
@endphp

<div class="item">
    <div class="yt-iframe-container">
        <iframe src="https://www.youtube.com/embed/{{ $video_id ?? '' }}" title="YouTube video player"
            width="360" height="250" frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
            allowfullscreen=""></iframe>
    </div>

    <div class="text-banner-container pb-5">
        <h2 class="yt-video-title text-center">{{ $title ?? '' }}</h2>
        <h3 class="text-center">{{ $text ?? '' }}</h3>
    </div>
</div>
