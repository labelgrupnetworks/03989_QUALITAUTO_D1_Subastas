@php
    $cur_lang = strtoupper(\App::getLocale());
    $mensajes = $data['js_item']['chat']['mensajes'];
    $mensajesPredefinidos = [];
    $mensajesNoPredefinidos = [];
    foreach ($mensajes as $id => $mensajePorIdioma) {
        # Si no encuentra el idioma del usuario le asignamos uno por defecto
        $mensaje = $mensajePorIdioma[$cur_lang] ?? array_values(array_slice($mensajePorIdioma, 0, 1))[0];
        if ($mensaje->predefinido) {
            $mensajesPredefinidos[] = $mensaje;
        } else {
            $mensajesNoPredefinidos[] = $mensaje;
        }
    }
@endphp

<div class="modal-block mfp-hide" id="mensajes_predefinidos">
    <section class="panel">
        <div class="panel-body">
            <div class="modal-wrapper">
                <div class="modal-text">
                    <ul class="chat-predefinidos" style="list-style:none; padding:0;">
                        @foreach ($mensajesPredefinidos as $item)
                            <li class="left clearfix chatline" id="predefinido-model-{{ $item->id_web_chat }}"
                                style="padding:5px;">
                                <div class="chat-body clearfix">
                                    <div class="header"></div>

                                    <div class="col-lg-8">
                                        <p>{{ $item->msg }}</p>
                                    </div>

                                    <div class="col-lg-4">
                                        <button class="btn-primary btn btn-warning btn-chat-pre btn-enviar"
                                            id_mensaje="{{ $item->id_web_chat }}" type="button">
                                            {{ trans("$theme-app.sheet_tr.chat-send") }}
                                            <button class="btn btn-danger btn-eliminar"
                                                id_mensaje="{{ $item->id_web_chat }}" type="button" predefinido="1">
                                                {{ trans("$theme-app.sheet_tr.chat-delete") }}
                                            </button>
                                    </div>

                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="panel-body">
    {{-- Model --}}
    <li class="list-group-item p-0 py-1 hidden" id="chatline_model">
        <small class="text-muted float-end">
			@include('components.boostrap_icon', ['icon' => 'clock'])
            <time class="timeago" datetime="2008-07-17T09:27:17Z"></time>
        </small>
        <p class="texto float-start"></p>
    </li>

    <ul class="chat list-group list-group-flush">
        @foreach ($mensajesNoPredefinidos as $item)
            @php
                $fecha = str_replace('/', '-', $item->fecha);
                $parte = explode(' ', $fecha);
                $fecha_final = $parte[0] . 'T' . $parte[1];
            @endphp
            <li class="list-group-item p-0 py-1 chatline" id="chatline_model_{{ $item->id_web_chat }}">
				<small class="text-muted float-end">
                    @include('components.boostrap_icon', ['icon' => 'clock'])
                    <time class="timeago" datetime="{{ $fecha_final }}"></time>
                </small>
                <p class="texto">
                    {{ $item->msg }}
                </p>
            </li>
        @endforeach
    </ul>
</div>
