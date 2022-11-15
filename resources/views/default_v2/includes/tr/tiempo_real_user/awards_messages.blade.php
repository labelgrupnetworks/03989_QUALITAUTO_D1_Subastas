<div class="tr_user_awards_messages border">

    <ul class="nav nav-pills" id="nav-tab" role="tablist">
        @if (Session::has('user'))
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="nav-adjudicaciones-tab" data-bs-toggle="pill"
                    data-bs-target="#nav-adjudicaciones" type="button" role="tab" aria-controls="nav-adjudicaciones"
                    aria-selected="true">
                    {{ trans("$theme-app.sheet_tr.your_adjudications") }}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="nav-mensajes-tab" data-bs-toggle="pill" data-bs-target="#nav-mensajes"
                    type="button" role="tab" aria-controls="nav-mensajes" aria-selected="false">
                    {{ trans("$theme-app.sheet_tr.room_msg") }}
                </button>
            </li>
        @else
            <button class="nav-link active" id="nav-mensajes-tab" data-bs-toggle="tab" data-bs-target="#nav-mensajes"
                type="button" role="tab" aria-controls="nav-mensajes" aria-selected="true">
                {{ trans("$theme-app.sheet_tr.room_msg") }}
            </button>
        @endif
    </ul>

    <div class="tab-content p-1 lb-scroll tab-awards-messages" id="nav-tabContent">
        @if (Session::has('user'))
            <div class="tab-pane fade show active p-3" id="nav-adjudicaciones" role="tabpanel" aria-labelledby="nav-adjudicaciones-tab"
                tabindex="0">
                @include('content.tr.tiempo_real_user.adjudicaciones')
            </div>
        @endif
        <div @class(['tab-pane fade p-3', 'show active' => !Session::has('user')]) id="nav-mensajes" role="tabpanel" aria-labelledby="nav-mensajes-tab"
            tabindex="0">
            @include('content.tr.tiempo_real_user.msg_sala')
        </div>
    </div>

</div>
