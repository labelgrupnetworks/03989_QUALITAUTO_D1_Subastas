<div class="tr_user_streaming border">

    <ul class="nav nav-pills" id="nav-tab" role="tablist">
        @if (!empty(\Config::get('app.tr_show_streaming')))
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="nav-pujas-tab" data-bs-toggle="pill" data-bs-target="#nav-pujas"
                    type="button" role="tab" aria-controls="nav-pujas" aria-selected="true">
                    {{ trans($theme . '-app.sheet_tr.last_bids') }}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="nav-streaming-tab" data-bs-toggle="pill" data-bs-target="#nav-streaming"
                    type="button" role="tab" aria-controls="nav-streaming" aria-selected="false">
                    {{ trans($theme . '-app.sheet_tr.streaming') }}
                </button>
            </li>
        @else
            <button class="nav-link active" id="nav-pujas-tab" data-bs-toggle="tab" data-bs-target="#nav-pujas"
                type="button" role="tab" aria-controls="nav-pujas" aria-selected="true">
                {{ trans($theme . '-app.sheet_tr.last_bids') }}
            </button>
        @endif
    </ul>


    <div class="tab-content p-1 tab-streming-bids lb-scroll" id="nav-tabContent">
        <div class="tab-pane fade show active p-3" id="nav-pujas" role="tabpanel" aria-labelledby="nav-pujas-tab"
            tabindex="0">
            @include('content.tr.tiempo_real_user.ultimas_pujas')
        </div>
        @if (!empty(\Config::get('app.tr_show_streaming')))
            <div class="tab-pane fade" id="nav-streaming" role="tabpanel" aria-labelledby="nav-streaming-tab"
                tabindex="0">
                @include('content.tr.tiempo_real_user.streaming')
            </div>
        @endif
    </div>

</div>
