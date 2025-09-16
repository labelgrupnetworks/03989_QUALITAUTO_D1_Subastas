@if (!empty($tablaSubasta))
    <table class="table-bordered" style="width: 100%">

        <tbody>
            <tr>
                <td class="td-title">{{ trans('web.reports.auction_code') }}</td>
                <td>{{ data_get($tablaSubasta, trans('web.reports.auction_code')) }}</td>

                <td class="td-title">{{ trans('web.reports.lot_code') }}</td>
                <td>{{ data_get($tablaSubasta, trans('web.reports.lot_code')) }}</td>
            </tr>
            <tr>
                <td class="td-title">{{ trans('web.reports.date_start') }}</td>
                <td>{{ data_get($tablaSubasta, trans('web.reports.date_start')) }}</td>

                <td class="td-title">{{ trans('web.reports.hour_start') }}</td>
                <td>{{ data_get($tablaSubasta, trans('web.reports.hour_start')) }}</td>
            </tr>
            <tr>
                <td class="td-title">{{ trans('web.reports.date_end') }}</td>
                <td>{{ data_get($tablaSubasta, trans('web.reports.date_end')) }}</td>

                <td class="td-title">{{ trans('web.reports.hour_end') }}</td>
                <td>{{ data_get($tablaSubasta, trans('web.reports.hour_end')) }}</td>
            </tr>

        </tbody>
    </table>
@endif
