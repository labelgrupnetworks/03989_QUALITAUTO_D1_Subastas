<!-- Facebook Meta Tags -->
<meta property="og:locale" content="{{ config('app.locale') }}" />
<meta property="og:type" content="website" />
<meta property="og:site_name" content="{{ \Config::get('app.name') }}" />
<meta property="og:title" content="{{ $data['seo']->meta_title ?? trans("web.head.title_app") }}" />
<meta property="og:description"
    content="{{ strip_tags(str_replace('"', "'", $data['seo']->meta_description ?? trans("web.head.meta_description"))) }}" />
<meta property="og:url" content="{{ str_replace('http:', 'https:', Request::url()) }}" />
<meta property="og:image"
    content="{{ $data['seo']->openGraphImagen ?? URL::asset("/themes/$theme/assets/img/logo.png") }}" />


<!-- Twitter Meta Tags -->
<meta name="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="{{ str_replace('http:', 'https:', Request::url()) }}" />
<meta name="twitter:title" content="{{ $data['seo']->meta_title ?? trans("web.head.title_app") }}" >
<meta name="twitter:description" content="{{ strip_tags(str_replace('"', "'", $data['seo']->meta_description ?? trans("web.head.meta_description"))) }}" >
<meta name="twitter:image" content="{{ $data['seo']->openGraphImagen ?? URL::asset("/themes/$theme/assets/img/logo.png") }}">
