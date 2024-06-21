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

<div class="modal fade modal-lots-details" id="myModal-{{ $id }}" role="dialog"
    aria-labelledby="myModalLabel-{{ $id }}" tabindex="-1">
    <div class="modal-dialog modal-lot-detail" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal" type="button" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>


                <h4 class="modal-title" id="myModalLabel">{{ trans("$theme-app.user_panel.detail") }}</h4>

				<div class="modal-table-header"></div>
				<div class="modal-auction-header"></div>

            </div>
            <div class="modal-body">
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
            </div>
        </div>
    </div>
</div>
