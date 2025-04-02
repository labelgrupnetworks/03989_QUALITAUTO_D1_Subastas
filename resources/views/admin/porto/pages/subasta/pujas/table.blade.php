<div class="col-xs-12 d-flex mb-1 pt-1 pb-1" style="background-color: #ffe7e7; gap:5px; flex-wrap: wrap">

    <a class="btn btn-success btn-sm" href="{{ route('admin.bids.index', Request::query() + ['export' => true]) }}">
        {{ trans('admin-app.button.download_excel') }}
    </a>

    <div style="margin-left: auto">
        @include('admin::includes.config_table', ['id' => 'tableBids', 'params' => ((array) $tableParams)])
    </div>

</div>
<table class="table table-striped table-condensed table-responsive" id="tableBids" data-order-name="order_bids"
    style="width:100%">
    <thead>
        <tr>
            @foreach ($tableParams as $param => $display)
                <th class="{{ $param }}" data-order="{{ $param }}"
                    style="cursor: pointer; @if (!$display) display: none; @endif">
                    {{ trans("admin-app.fields.$param") }}
                    @if (request()->order_bids == $param)
                        <span style="margin-left: 5px; float: right;">
                            @if (request()->order_bids_dir == 'asc')
                                <i class="fa fa-arrow-up" aria-hidden="true" style="color:green"></i>
                            @else
                                <i class="fa fa-arrow-down" aria-hidden="true" style="color:red"></i>
                            @endif
                        </span>
                    @endif
                </th>
            @endforeach
            <th>
                <span>{{ trans('admin-app.fields.actions') }}</span>
            </th>
        </tr>
    </thead>

    <tbody>
        <tr id="bidsfilters">
            <form class="form-group" action="">
                <input name="order_bids" type="hidden" value="{{ request('order_bids', 'sub_asigl0') }}">
                <input name="order_bids_dir" type="hidden" value="{{ request('order_bids_dir', 'desc') }}">

                @foreach ($tableParams as $param => $display)
                    <td class="{{ $param }}" @if (!$display) style="display: none" @endif>

                        @if (!empty($formulario->$param) && is_array($formulario->$param))
                            @foreach ($formulario->$param as $input)
                                {!! $input !!}
                            @endforeach
                        @else
                            {!! $formulario->$param ?? '' !!}
                        @endif

                    </td>
                @endforeach

                <td class="d-flex">
                    <input class="btn btn-info w-100" type="submit" value="{{ trans('admin-app.button.search') }}">
                    <a class="btn btn-warning w-100" href="{{ route('award.index') }}">
                        {{ trans('admin-app.button.restart') }}
                    </a>
                </td>

            </form>
        </tr>

        @foreach ($bids as $bid)
            <tr id="fila_award_{{ $bid->ref_asigl1 }}" style="max-height: 60px; overflow: hidden;">
                @foreach ($tableParams as $param => $display)
                    <td class="{{ $param }}" @if (!$display) style="display: none" @endif>
                        @if ($param == 'pujrep_asigl1')
                            {{ $pujrepsArray[$bid->pujrep_asigl1] ?? '' }}
                        @elseif($param == 'type_asigl1')
                            {{ $typesArray[$bid->type_asigl1] ?? '' }}
                        @elseif($param == 'imp_asigl1')
                            {{ Tools::moneyFormat($bid->imp_asigl1, trans('web.subastas.euros')) }}
                        @else
                            {{ $bid->{$param} ?? '' }}
                        @endif
                    </td>
                @endforeach
                <td></td>
            </tr>
        @endforeach


    </tbody>
</table>
{{ $bids->links() }}
