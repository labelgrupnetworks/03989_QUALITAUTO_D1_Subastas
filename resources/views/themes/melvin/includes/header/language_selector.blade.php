@php
    use App\libs\TradLib;
@endphp

@if (count(Config::get('app.locales')) > 1)
    @foreach (Config::get('app.locales') as $key => $value)
        @php
            $route = '';
            if ($key != $lang) {
                $route =
                    "/$key" . TradLib::getRouteTranslate(substr($_SERVER['REQUEST_URI'], 4), \App::getLocale(), $key);
            }
        @endphp
        <a href="{{ $route }}" title="{{ trans("web.head.language_$key") }}" @class(['selected' => $key == $lang])
            translate="no">
            <img src="/themes/melvin/assets/icons/{{ $key }}.svg" alt="{{ trans("web.head.language_$key") }}">
            <span translate="no">
				{{ Str::title(trans("web.home.$key")) }}
			</span>
        </a>
    @endforeach
@endif
