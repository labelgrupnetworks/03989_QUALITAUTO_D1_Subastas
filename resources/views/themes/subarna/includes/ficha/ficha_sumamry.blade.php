<div class="ficha-details">
    <details>
        <summary>{{ trans("$theme-app.lot.need_a_shipment") }}</summary>
        <p>
			{!! trans("$theme-app.lot.contact_our_logistics", ['lote' => strip_tags($lote_actual->descweb_hces1)]) !!}
        </p>
    </details>
    <details>
        <summary>{{ trans("$theme-app.lot.purchase_conditions_summary") }}</summary>
        <p>
            {{ trans("$theme-app.lot.purchase_conditions_p1") }}
        </p>
        <p>
            {{ trans("$theme-app.lot.purchase_conditions_p2") }}
        </p>
    </details>
    <details>
        <summary>{{ trans("$theme-app.lot.want_to_sell_summary") }}</summary>
        <p>
            {!! trans("$theme-app.lot.want_to_sell_p1") !!}
        </p>
    </details>
</div>
