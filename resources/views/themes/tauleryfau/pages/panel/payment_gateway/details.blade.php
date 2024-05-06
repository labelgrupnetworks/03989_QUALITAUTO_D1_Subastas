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
        <label for="">
            <input type="radio" checked>
            {!! mb_convert_case($factAddress, MB_CASE_TITLE, 'UTF-8') !!}
        </label>
    </div>
</div>

{{-- 2. Direcciones de envio --}}
<div class="detail">
    <div class="detail_header detail_header_action">
        <h4>{{ trans("$theme-app.user_panel.title_envio") }}</h4>
        <a class="btn btn-sm btn-lb btn-lb-success"
            {{-- href="{{ route('panel.addresses', ['lang' => Config::get('app.locale'), 'cod_sub' => $cod_sub]) }}" --}}>
            {{ trans($theme . '-app.user_panel.new_address') }}
        </a>
    </div>

    <div class="detail_form">
        @foreach ($envio as $key => $address)
            <label>
                <input id="clidd_{{ $address->codd_clid }}_{{ $cod_sub }}" name="clidd" type="radio"
                    value="{{ $address->codd_clid }}" @checked($address->codd_clid == 'W1')>

                <span>
                    {{ $address->nomd_clid }} - {{ $address->dir_clid }}{{ $address->dir2_clid }} -
                    {{ $address->cp_clid }}, {{ $address->pro_clid }} -
                    {{ $countries[strtoupper($address->codpais_clid)] }}
                </span>

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
                <span>
                    {{ trans("$theme-app.user_panel.shipping_express") }}
                </span>

                {{-- Para mostrar importe de gastos de envío --}}
                <span class="gasto-envio-express-{{ $cod_sub }}_JS"></span>
            </label>
        @endif

        <label for="shipping_express_min">
            <input id="shipping_express_min" name="shipping" type="radio" value="min">

            <span class="payment-adj">
                {{ trans("$theme-app.user_panel.shipping_express_min") }}
            </span>
            {{-- Para mostrar importe de gastos de envío --}}
            <span class="gasto-envio-min-{{ $cod_sub }}_JS"></span> €
        </label>

        <label>
            <input id="shipping_express_recoger" name="shipping" type="radio" value="recoger"
                @checked($user->envcorr_cli == 'N')>
            <span class="payment-adj">
                {{ trans("$theme-app.user_panel.store_pickup") }}
            </span>
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
            <span>
                {{ trans("$theme-app.user_panel.pay_creditcard") }}
            </span>
        </label>
        <label>
            <input id="paybizum" name="paymethod" type="radio" value="bizum">
            <span>
                {{ trans("$theme-app.user_panel.pay_bizum") }}
            </span>
        </label>
    </div>
</div>


{{-- modal to new address --}}
<div class="modal" id="modal_new_address">
	<div class="modal-content">
		<div class="modal-header">
			<h4>Nueva dirección</h4>
			<span class="close" onclick="closeModal('modal_new_address')">&times;</span>
		</div>
		<div class="modal-body">
			<div id="#ajax_shipping_add"></div>
		</div>
	</div>
</div>

<script>

</script>
