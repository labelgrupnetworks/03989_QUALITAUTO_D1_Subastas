@php
	$hasWatchStreaming = config('app.tr_show_streaming', false);
	$streamingByDefault = $hasWatchStreaming && true;
@endphp

<div class="tr_user_streaming">
    <div>
        <ul class="nav nav-tabs">
            @if($hasWatchStreaming)
            <li class="{{ $streamingByDefault ? '' : 'active' }}">
                <a data-toggle="tab" href="#ultimas_pujas">{{ trans(\Config::get('app.theme').'-app.sheet_tr.last_bids') }}</a>
            </li>
            <li class="{{ $streamingByDefault ? 'active' : '' }}">
                <a data-toggle="tab" href="#streaming">{{ trans(\Config::get('app.theme').'-app.sheet_tr.streaming') }}</a>
            </li>
            @else
            <li class="active" style="width: 100%; margin-bottom: 1px;">
                <a data-toggle="tab">{{ trans(\Config::get('app.theme').'-app.sheet_tr.last_bids') }}</a>
            </li>
            @endif

        </ul>

        <div class="tab-content" @if($hasWatchStreaming) style="z-index: 1001;" @endif>
            <div id="ultimas_pujas" class="tab-pane fade ultimas_pujas {{ $streamingByDefault ? '' : 'in active' }}">
                @include('content.tr.tiempo_real_user.ultimas_pujas')
            </div>
            @if($hasWatchStreaming)
            <div id="streaming" class="tab-pane fade {{ $streamingByDefault ? 'in active' : '' }}">
                @include('content.tr.tiempo_real_user.streaming')
            </div>
            @endif
        </div>
    </div>
</div>
