@php
    /**
     * @var string $type
     * @var array $cookies
     * @var array $anulable
	 * @var bool $is_checked
     */
	$is_checked = $is_checked ?? false;
    $anulable = $anulable ?? false;
@endphp

<div class="cookie-type">

    <div class="cookie-type_header_wrap">
        <div class="cookie-type_header" role="button" data-toggle="collapse" href="#collapse{{ $type }}Cookies"
            aria-expanded="false" aria-controls="collapse{{ $type }}Cookies">
            <span class="cookie-type_header_arrow"><i class="fa fa-chevron-right" aria-hidden="true"></i></span>
            <span class="cookie-type_header_text">{{ trans("$theme-app.cookies.type_$type") }}</span>

        </div>

        @if (!$anulable)
            <span class="cookie-type_header_action text-success">Siempre activo</span>
		@elseif(empty($cookies))
			<span class="cookie-type_header_action text-success">No contiene</span>
        @else
            <input type="checkbox" name="permission_{{$type}}" id="switch_{{ $type }}" {{ $is_checked ? 'checked' : '' }} />
            <label for="switch_{{ $type }}">Toggle</label>
        @endif
    </div>

    <p class="cookie-type_description">{{ trans("$theme-app.cookies.description_$type") }}</p>

    <div class="cookie-type_content collapse" id="collapse{{ $type }}Cookies">
        @foreach ($cookies as $cookie)
            <div class="cookie-wrapper">
                <p>
                    <span>Cookie: </span>
                    <span>{{ trans("$theme-app.cookies.{$cookie}_name") }}</span>
                </p>
                <p>
                    <span>Duración: </span>
                    <span>{{ trans("$theme-app.cookies.{$cookie}_duration") }}</span>
                </p>
                <p>
                    <span>Descripción: </span>
                    <span>{{ trans("$theme-app.cookies.{$cookie}_description") }}</span>
                </p>
            </div>
        @endforeach
    </div>
</div>
