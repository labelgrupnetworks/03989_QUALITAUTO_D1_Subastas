@php
    use App\libs\TradLib;

	$locales = Config::get('app.locales');
    $stringLangs = [
        'es' => 'ESP',
        'en' => 'ENG',
    ];
@endphp

@if (count($locales) > 1)
    {{-- Con dropdown --}}
    <div class="btn-group d-none d-lg-inline-flex">
        <button class="btn btn-link dropdown-toggle" data-bs-toggle="dropdown" data-bs-display="static" type="button"
            aria-expanded="false">

            {{ $stringLangs[$lang] }}
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
            @foreach ($locales as $key => $value)
                @php
                    $route = '';
                    //get current route url without domain
                    $routePath = request()->path();
                    if ($key != $lang) {
                        $route =
                            "/$key" .
                            TradLib::getRouteTranslate(substr($routePath, 3), Config::get('app.locale'), $key);
                    }
                @endphp
                <li>
                    <a href="{{ $route }}" title="{{ trans("$theme-app.head.language_$key") }}" aria-current="true"
                        @class(['dropdown-item', 'disabled' => $key == $lang]) translate="no">
						{{ $stringLangs[$key] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
@endif
