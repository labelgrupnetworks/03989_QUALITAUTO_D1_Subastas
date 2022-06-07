<div class="tr_user_streaming">
    <div>
        <ul class="nav nav-tabs">
            @if(!empty(\Config::get('app.tr_show_streaming')))
            <li class="active">
                <a data-toggle="tab" href="#ultimas_pujas">{{ trans(\Config::get('app.theme').'-app.sheet_tr.last_bids') }}</a>
            </li>
            <li>
                <a data-toggle="tab" href="#streaming">Streaming</a>
            </li>
            @else
            <li class="active" style="width: 100%; margin-bottom: 1px;">
                <a data-toggle="tab">{{ trans(\Config::get('app.theme').'-app.sheet_tr.last_bids') }}</a>
            </li>
            @endif

        </ul>

        <div class="tab-content">
            <div id="ultimas_pujas" class="tab-pane fade in active ultimas_pujas">
                @include('content.tr.tiempo_real_user.ultimas_pujas')
            </div>
            @if(!empty(\Config::get('app.tr_show_streaming')))
            <div id="streaming" class="tab-pane fade">
                @include('content.tr.tiempo_real_user.streaming')
            </div>
            @endif
        </div>
    </div>
</div>
