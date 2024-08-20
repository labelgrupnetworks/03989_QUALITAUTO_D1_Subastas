@php
    header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
    header('Pragma: no-cache'); // HTTP 1.0.
    header('Expires: 0 '); // Proxies.
    //header("Cache-Control: max-age=31536000");
@endphp

<meta charset="utf-8" http-equiv="content-type">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="initial-scale=1,minimum-scale=0.15, maximum-scale=2, user-scalable=yes">
<meta name="author" content="{{ trans($theme . '-app.head.meta_author') }}">

@yield('seo')

<title>
    @if (!empty($data['seo']->meta_title))
        {{ $data['seo']->meta_title }}
    @elseif (!empty($seo->meta_title))
        {{ $seo->meta_title }}
    @else
        {{ trans("$theme-app.head.title_app") }}
    @endif
</title>

<script>
    //fecha servidor
    var fecha_server = @json(getdate()[0] * 1000);
    //fecha del sistema del usuario
    var fecha_js = new Date().getTime();
</script>

<link href="<?= '/themes/' . $theme . '/img/favicon.ico' ?>" rel="shortcut icon" />
@if (env('APP_DEBUG'))
    <meta name="robots" content="noindex">
@elseif(!empty($data['seo']->noindex_follow) && $data['seo']->noindex_follow == true)
    <meta name="robots" content="noindex,follow">
@elseif(!empty($seo->noindex_follow) && $seo->noindex_follow == true)
    <meta name="robots" content="noindex,follow">
@endif

@if (!empty($data['seo']->meta_title))
    <meta name="title" content="{{ $data['seo']->meta_title }}">
@elseif (!empty($seo->meta_title))
    <meta name="title" content="{{ $seo->meta_title }}">
@else
    <meta name="title" content="{{ trans("$theme-app.head.title_app") }}">
@endif

@if (!empty($data['seo']->meta_description))
    <meta name="description" content="{{ $data['seo']->meta_description }}">
@elseif (!empty($seo->meta_description))
    <meta name="description" content="{{ $seo->meta_description }}">
@else
    <meta name="description" content="{{ trans("$theme-app.head.meta_description") }}">
@endif

@if (!empty($data['seo']->canonical))
    <link href="{{ $data['seo']->canonical }}" rel="canonical" />
@elseif(!empty($seo->canonical))
    <link href="{{ $seo->canonical }}" rel="canonical" />
@endif


<meta name="csrf-token" content="{{ csrf_token() }}">
