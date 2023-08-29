@php
    use App\libs\TradLib;
@endphp

@if (count(Config::get('app.locales')) > 1)
    {{-- Con dropdown --}}
    <div class="btn-group">
        <button class="btn btn-sm btn-outline-border-lb-primary dropdown-toggle" data-bs-toggle="dropdown"
            data-bs-display="static" type="button" aria-expanded="false">
            {{ config('app.locales')[config('app.locale')] }}
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
            @foreach (Config::get('app.locales') as $key => $value)
                @php
                    $route = '';
                    //get current route url without domain
                    $routePath = request()->path();
                    if ($key != $lang) {
                        $route = "/$key" . TradLib::getRouteTranslate(substr($routePath, 3), Config::get('app.locale'), $key);
                    }
                @endphp
                <li>
                    <a href="{{ $route }}" title="{{ trans("$theme-app.head.language_$key") }}" aria-current="true"
                        @class(['dropdown-item', 'disabled' => $key == $lang]) translate="no">
                        {{ trans("$theme-app.home.$key") }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
    {{-- Con enlaces --}}
    {{-- @foreach (Config::get('app.locales') as $key => $value)
		@php
			$route = '';
			if($key != $lang){
				$route = "/$key". TradLib::getRouteTranslate((substr($_SERVER["REQUEST_URI"], 4)), \App::getLocale(), $key);
			}
		@endphp
		<a @class(['selected' => $key == $lang]) translate="no" title="{{ trans("$theme-app.head.language_$key") }}" href="{{$route}}">
			<span translate="no">{{ trans("$theme-app.home.$key") }}</span>
		</a>
		@endforeach --}}
@endif
