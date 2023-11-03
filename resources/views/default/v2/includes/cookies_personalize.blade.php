@php
	use App\Models\Cookies;
	$cookiesPreferences = new Cookies();

	$analyisi = $cookiesPreferences->getAnalysisCookies();
	$advertising = $cookiesPreferences->getAdvertisingCookies();
@endphp

<div class="modal fade modal-cookies" id="cookiesPersonalize" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">

				<h4 class="modal-title">{{ trans("$theme-app.cookies.modal_title") }}</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

            </div>
            <div class="modal-body">
                {!! trans("$theme-app.cookies.modal_description") !!}

                @include('includes.cookies._cookie_type', [
                    'type' => 'technical',
                    'cookies' => ['session', 'token'],
                ])

                @include('includes.cookies._cookie_type', [
                    'type' => 'preferences',
                    'cookies' => ['cookie_preferences'],
                ])

                @include('includes.cookies._cookie_type', ['type' => 'analysis', 'cookies' => $analyisi, 'anulable' => true, 'is_checked' => $cookiesPreferences->isAnalysisAllowed()])

                @include('includes.cookies._cookie_type', ['type' => 'advertising', 'cookies' => $advertising, 'anulable' => true, 'is_checked' => $cookiesPreferences->isAdvertisingAllowed()])

            </div>
            <div class="modal-footer">
                <div class="cookies_buttons">
                    <button onclick="acceptAllCookies()">
						{{ trans("$theme-app.cookies.accept_all") }}
                    </button>

                    <button type="button" onclick="savePreferencesCookies()">
						{{ trans("$theme-app.cookies.save_settings") }}
                    </button>

                    <button onclick="rejectAllCookies()">
						{{ trans("$theme-app.cookies.reject_all") }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('#cookiesPersonalize').on('show.bs.modal', function(event) {
        $('.cookies').fadeOut();
    });

    $('.cookie-type_content').on('show.bs.collapse', function(event) {
        let id = event.target.id;
        let control = document.querySelector(`[aria-controls="${id}"]`);
        control.querySelector('.cookie-type_header_arrow').classList.add('active');
    })

    $('.cookie-type_content').on('hide.bs.collapse', function(event) {
        let id = event.target.id;
        let control = document.querySelector(`[aria-controls="${id}"]`);
        control.querySelector('.cookie-type_header_arrow').classList.remove('active');
    })
</script>
