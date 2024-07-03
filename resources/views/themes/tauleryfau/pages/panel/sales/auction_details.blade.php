{{-- <div class="tab-pane" id="auction-details-{{ $id }}" role="tabpanel">
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

    <div class="panel-lots">
        <div class="panel-lots_header-wrapper">
            <div class="table-grid_header panel-lots_header">
                <p></p>
                <p>{{ trans("$theme-app.user_panel.lot") }}</p>
                <p>{{ trans("$theme-app.user_panel.description") }}</p>
                <p>{{ trans("$theme-app.user_panel.starting_price") }}</p>
                <p>{{ trans("$theme-app.user_panel.actual_price") }}</p>
                <p>{{ trans("$theme-app.user_panel.increase") }}</p>
                <p>{{ trans("$theme-app.user_panel.bids") }} / {{ trans("$theme-app.user_panel.bidders") }}</p>
            </div>
        </div>

        @foreach ($lots as $lot)
            @include('pages.panel.sales.lot', [
                'lot' => $lot,
            ])
        @endforeach
    </div>
</div> --}}

<!-- Modal -->
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
                <div id="auction-details-{{ $id }}">
                    <h4 class="auction-details_title">
                        <div class="auction-details_title__file">
                            <span>{{ $title }}</span>
                            @if ($invoice)
                                <a class="panel-pdf-icon" href="{{ $invoice }}" target="_blank">
                                    <img src="/themes/{{ $theme }}/assets/icons/file-pdf-solid.svg"
                                        alt="PDF file" width="18.75">
                                </a>
                            @endif
                        </div>
                    </h4>

                    <div class="panel-lots">
                        <div class="panel-lots_header-wrapper">
                            <div class="table-grid_header panel-lots_header">
                                <p></p>
                                <p>{{ trans("$theme-app.user_panel.lot") }}</p>
                                <p>{{ trans("$theme-app.user_panel.description") }}</p>
                                <p>{{ trans("$theme-app.user_panel.starting_price") }}</p>
                                <p>
									@if($finish)
									{{ trans("$theme-app.user_panel.awarded") }}
									@else
									{{ trans("$theme-app.user_panel.actual_price") }}
									@endif
								</p>
                                <p>{{ trans("$theme-app.user_panel.increase") }}</p>
                                <p>{{ trans("$theme-app.user_panel.bids") }} /
                                    {{ trans("$theme-app.user_panel.bidders") }}</p>
                            </div>
                        </div>

                        @foreach ($lots as $lot)
                            @include('pages.panel.sales.lot', [
                                'lot' => $lot,
								'finish' => $finish,
                            ])
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
