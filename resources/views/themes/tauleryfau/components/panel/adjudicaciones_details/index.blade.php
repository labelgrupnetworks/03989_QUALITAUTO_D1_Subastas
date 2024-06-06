@props(['id', 'title', 'invoice'])

<div class="tab-pane" id="auction-details-{{ $id }}" role="tabpanel">
    <h4 class="auction-details_title">
		<div class="auction-details_title__file">
            <span>{{ $title }}</span>
            @if ($invoice)
                <a class="panel-pdf-icon" href="{{ $invoice }}" target="_blank">
                    <img src="/themes/{{ $theme }}/assets/icons/file-pdf-solid.svg" alt="PDF file" width="18.75">
                </a>
            @endif
        </div>
	</h4>

    <div class="panel-lots allotments-lots">
        <div class="panel-lots_header-wrapper">
            <div class="panel-lots_header">
                <p></p>
                <p>{{ trans("$theme-app.user_panel.lot") }}</p>
                <p>{{ trans("$theme-app.user_panel.description") }}</p>
                <p>{{ trans("$theme-app.user_panel.starting_price") }}</p>
                <p>{{ trans("$theme-app.user_panel.awarded") }}</p>
                <p></p>
            </div>
        </div>

        {{ $slot }}

    </div>
</div>
