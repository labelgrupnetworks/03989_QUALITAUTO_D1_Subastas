@php
    $factAddress = "$user->nom_cli<br>$user->dir_cli $user->dir2_cli<br>$user->cp_cli, $user->pro_cli-{$countries[$user->codpais_cli]}";
@endphp

{{-- Fact address --}}
<div class="detail">
    <div class="detail_header" style="--lb-bw: 50%">
        <h4>{{ trans("$theme-app.user_panel.billing_address") }}</h4>
        <p> {!! trans($theme . '-app.user_panel.billing_address_info') !!}</p>
    </div>

    <div class="detail_form">
        <label>
            <input type="radio" checked>
			<p class="detail-fact-address">
            	{!! mb_convert_case($factAddress, MB_CASE_TITLE, 'UTF-8') !!}
			</p>
        </label>
    </div>
</div>

{{-- 2. Direcciones de envio --}}
<div class="detail">
    <div class="detail_header detail_header_action">
        <h4>{{ trans("$theme-app.user_panel.title_envio") }}</h4>
        <button class="btn btn-sm btn-lb btn-lb-success" type="button" onclick="showModalAddNewAddress()">
            {{ trans("$theme-app.user_panel.add_addres_btn") }}
        </button>
    </div>

    <div class="detail_form">
        @foreach ($envio as $key => $address)
            <label>
                <input id="clidd_{{ $address->codd_clid }}_{{ $cod_sub }}" name="clidd" type="radio"
                    value="{{ $address->codd_clid }}" @checked($address->codd_clid == 'W1')>


                <p class="detail-fact-ship-address">
					<span>
						@if (!empty($address->obs_clid))
							<b>{{ mb_strtoupper($address->obs_clid) }}</b>
						@endif
						{{ $address->nomd_clid }} - {{ $address->dir_clid }}{{ $address->dir2_clid }} -
						{{ $address->cp_clid }}, {{ $address->pro_clid }} -
						{{ $countries[strtoupper($address->codpais_clid)] }}
					</span>
                </p>

            </label>
        @endforeach
    </div>
</div>

{{-- 3. Forma de Envío --}}
<div class="detail">
    <div class="detail_header">
        <h4>{{ trans("$theme-app.user_panel.shipping_form") }}</h4>
    </div>

    <div class="detail_form">
        @if ($user->envcorr_cli != 'N')
            <label>
                <input id="shipping_express" name="shipping" type="radio" value="express" checked="checked">
                <p>
                    {{ trans("$theme-app.user_panel.shipping_express") }}
					{{-- Para mostrar importe de gastos de envío --}}
					<span class="gasto-envio-express-{{ $cod_sub }}_JS"></span>
                </p>

            </label>
        @endif

        <label for="shipping_express_min">
            <input id="shipping_express_min" name="shipping" type="radio" value="min">

            <p class="payment-adj">
                {{ trans("$theme-app.user_panel.shipping_express_min") }}

				{{-- Para mostrar importe de gastos de envío --}}
				<span class="gasto-envio-min-{{ $cod_sub }}_JS"></span> €
            </p>

        </label>

        <label>
            <input id="shipping_express_recoger" name="shipping" type="radio" value="recoger"
                @checked($user->envcorr_cli == 'N')>
            <p class="payment-adj">
                {{ trans("$theme-app.user_panel.store_pickup") }}
            </p>
        </label>

    </div>
</div>

{{-- 4. Método de Pago --}}
<div class="detail">
    <div class="detail_header">
        <h4>{{ trans("$theme-app.user_panel.payment_method") }}</h4>
    </div>

    <div class="detail_form">
        <label>
            <input id="paycreditcard" name="paymethod" type="radio" value="creditcard" checked="checked">
            <p>
                {{ trans("$theme-app.user_panel.pay_creditcard") }}
            </p>
        </label>
        <label>
            <input id="paybizum" name="paymethod" type="radio" value="bizum">
            <p>
                {{ trans("$theme-app.user_panel.pay_bizum") }}
            </p>
        </label>
    </div>
</div>


{{-- modal to new address --}}
<div class="modal fade" id="modal_new_address" role="dialog" aria-labelledby="modal_new_address" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal" type="button" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"> {{ trans($theme . '-app.user_panel.new_address') }}</h4>
            </div>
            <div class="modal-body">
                <div id="ajax_shipping_add"></div>
            </div>
        </div>
    </div>
</div>
