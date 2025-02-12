@php
	$pageService = new App\Models\Page;
    $euroSymbol = trans("$theme-app.lot.eur");
	$transferContent = $pageService->getPagina(Config::get('app.locale'), 'transfer-pay');
@endphp

@if ($isCorrCli)
    <p>{{ trans("$theme-app.user_panel.contact_tauler") }}</p>
@endif

@if (!$isCorrCli)
    <div class="summary-section">
        <div class="summary-header">
            <h4>{{ trans("$theme-app.user_panel.summary") }}</h4>
            <p>{{ $title }}</p>
        </div>

        <div class="summary-body" data-paymethod="creditcard">
            <div class="summary_line summary_line-lot">
                <span>{{ trans("$theme-app.user_panel.lots") }}</span>
                <span class="summary_separator"></span>
                <span>{{ $lots_count }}</span>
            </div>

            <div class="summary_line">
                <span>{{ trans("$theme-app.user_panel.award_price") }}</span>
                <span class="summary_separator"></span>
                <span>{{ Tools::moneyFormat($total_remate, $euroSymbol, 2) }}</span>
            </div>

            <div class="summary_line">
                <span>{{ trans("$theme-app.user_panel.base") }}</span>
                <span class="summary_separator"></span>
                <span>{{ Tools::moneyFormat($total_base, $euroSymbol, 2) }}</span>
            </div>

            <div class="summary_line">
                <span>{{ trans("$theme-app.user_panel.summary_commision_vat") }}</span>
                <span class="summary_separator"></span>
                <span>{{ Tools::moneyFormat($total_iva, $euroSymbol, 2) }}</span>
            </div>

            <div class="summary_line">
                <span>{{ trans("$theme-app.user_panel.export_license") }}</span>
                <span class="summary_separator"></span>
                <span>{{ Tools::moneyFormat($total_licencia_exportacion, $euroSymbol, 2) }}</span>
            </div>

            <div class="summary_line">
                <span>{{ trans("$theme-app.user_panel.shipping_and_insurance") }}</span>
                <span class="summary_line-vat">{{ trans("$theme-app.user_panel.vat_included") }}</span>
                <span class="summary_separator"></span>
                <span class="fw-bold text-gasto-envio-{{ $cod_sub }}"></span>
                <span>{{ trans("$theme-app.lot.eur") }}</span>
            </div>

            <div class="summary_line">
                <span>{{ trans("$theme-app.user_panel.financial_fee_text") }}</span>
                <span class="summary_separator"></span>
                <span class="fw-bold text-gastos-extra-{{ $cod_sub }}"></span>
                <span>{{ trans("$theme-app.lot.eur") }}</span>
            </div>

            <div class="summary_total">
                <span>{{ trans("$theme-app.user_panel.total_to_pay") }}</span>
                <div class="summary_total_imports">
                    <div>
                        <span class="precio_final_{{ $cod_sub }}"></span>
                        <span>{{ trans("$theme-app.lot.eur") }}</span>
                    </div>
                    <div>
                        <span class="js-divisa precio_final_{{ $cod_sub }}"></span>
                    </div>
                </div>
            </div>

            <div class="summary_buttons">
                <a class="btn btn-lb btn-lb-outline w-100" href="#auction-details-{{ $cod_sub }}">
                    {{ trans("$theme-app.user_panel.see_detail") }}
                </a>

                @if ($isCompraWeb)
                    <button class="btn btn-lb btn-lb-secondary w-100 btn-step-reg submit_carrito" type="button"
                        cod_sub="{{ $cod_sub }}" disabled>
                        {{ trans("$theme-app.user_panel.pay_now") }}
                    </button>
                @endif
            </div>

        </div>

        <div class="summary-body" data-paymethod="transfer" style="display: none;">
            <div class="summary-tranfer">
				<h4>{{ trans("$theme-app.user_panel.pay_transfer") }}</h4>

				{!! $transferContent->content_web_page !!}
            </div>

            <div class="summary_total">
                <span>{{ trans("$theme-app.user_panel.total_to_pay") }}</span>
                <div class="summary_total_imports">
                    <div>
                        <span class="precio_final_{{ $cod_sub }}"></span>
                        <span>{{ trans("$theme-app.lot.eur") }}</span>
                    </div>
                    <div>
                        <span class="js-divisa precio_final_{{ $cod_sub }}"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
