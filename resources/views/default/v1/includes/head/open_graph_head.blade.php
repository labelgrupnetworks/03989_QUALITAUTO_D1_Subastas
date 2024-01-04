<!--Open Graph-->
<meta property="og:locale" content="{{ config('app.locale') }}" />
<meta property="og:type" content="website" />
<meta property="og:site_name" content="{{\Config::get("app.name")}}" />
<meta property="og:title" content="{{ $data['seo']->meta_title ?? trans("$theme-app.head.title_app") }}" />
<meta property="og:description" content="{{ strip_tags(str_replace('"', "'", $data['seo']->meta_description ?? trans("$theme-app.head.meta_description"))) }}" />
<meta property="og:url" content="{{str_replace("http:","https:",Request::url())}}" />
<meta property="og:image" content="{{ $data['seo']->openGraphImagen ?? URL::asset("/themes/$theme/assets/img/logo.png")  }}" />
