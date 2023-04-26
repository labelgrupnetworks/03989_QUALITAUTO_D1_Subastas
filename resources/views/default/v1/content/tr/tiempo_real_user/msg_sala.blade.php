<div id="mensajes_predefinidos" class="modal-block mfp-hide">
    <section class="panel">
        @if(Session::has('user'))
        <div class="panel-body">
            <div class="modal-wrapper">
                <div class="modal-text">
                    <ul class="chat-predefinidos" style="list-style:none; padding:0;">
                        <?php
                        $cur_lang = strtoupper(\App::getLocale());

                        if (!empty($data['js_item']['chat'])) {

                            foreach ($data['js_item']['chat']['mensajes'] as $k => $item) {

                                # Si no encuentra el idioma del usuario le asignamos uno por defecto
                                if (!isset($item[$cur_lang])) {

                                    foreach ($data['js_item']['chat']['mensajes'][$k] as $index => $valor) {

                                        if (isset($data['js_item']['chat']['mensajes'][$k][$index])) {
                                            $item = $data['js_item']['chat']['mensajes'][$k][$index];
                                        }

                                        $item = $data['js_item']['chat']['mensajes'][$k][$index];
                                    }
                                } else {
                                    $item = $item[$cur_lang];
                                }

                                if ($item->predefinido == 1) {
                                    ?>
                                    <li class="left clearfix chatline" id="predefinido-model-<?php echo $item->id_web_chat ?>" style="padding:5px;">
                                        <div class="chat-body clearfix">
                                            <div class="header"></div>

                                            <div class="col-lg-8">
                                                <p><?php echo $item->msg; ?></p>
                                            </div>

                                            <div class="col-lg-4">
                                                <button type="button" class="btn-primary btn btn-warning btn-chat-pre btn-enviar" id_mensaje="<?php echo $item->id_web_chat ?>"> {{ trans(\Config::get('app.theme').'-app.sheet_tr.chat-send') }}</button>
                                                <button type="button" class="btn btn-danger btn-eliminar" id_mensaje="<?php echo $item->id_web_chat ?>" predefinido="1">{{ trans(\Config::get('app.theme').'-app.sheet_tr.chat-delete') }}</button>
                                            </div>

                                        </div>
                                    </li>
                                    <?php
                                }
                            }
                        }
                        ?>

                    </ul>
                </div>
            </div>
        </div>
        @endif
    </section>
</div>

@if(Session::has('user'))
<div class="panel-body">
    <?php $cur_lang = strtoupper(\App::getLocale()); ?>

    <!-- model -->
    <li class="left clearfix hidden" id="chatline_model">
        <div class="chat-body clearfix">
            <div class="header">
                <small class="pull-right text-muted">
                    <span class="glyphicon glyphicon-time"></span><time class="timeago" datetime="2008-07-17T09:27:17Z"></time>
                </small>
            </div>
            <p class="texto">
                <?php if (isset($data['js_item']['user']) && $data['js_item']['user']['is_gestor']) { ?>
                    <span class="glyphicon glyphicon-remove btn-eliminar" predefinido="0" id_mensaje=""></span>
                <?php } ?>
            </p>
        </div>
    </li>
    <!-- model -->

    <ul class="chat">
        <?php
        if (!empty($data['js_item']['chat'])) {

            foreach ($data['js_item']['chat']['mensajes'] as $k => $item) {

                if (!isset($item[$cur_lang])) {

                    foreach ($data['js_item']['chat']['mensajes'][$k] as $index => $valor) {

                        if (isset($data['js_item']['chat']['mensajes'][$k][$index])) {
                            $item = $data['js_item']['chat']['mensajes'][$k][$index];
                        }

                        $item = $data['js_item']['chat']['mensajes'][$k][$index];
                    }
                } else {
                    $item = $item[$cur_lang];
                }

                if ($item->predefinido == 0) {
                    $fecha = str_replace('/', '-', $item->fecha);
                    $parte = explode(' ', $fecha);
                    $fecha_final = $parte[0] . 'T' . $parte[1];
                    ?>
                    <li class="left clearfix chatline" id="chatline_model_<?php echo $item->id_web_chat; ?>">
                        <div class="chat-body clearfix">
                            <div class="header">
                                <small class="pull-right text-muted">
                                    <span class="glyphicon glyphicon-time"></span><time class="timeago" datetime="<?php echo $fecha_final; ?>"></time>
                                </small>
                            </div>

                            <p>
                                <?php echo $item->msg; ?>
                            </p>
                        </div>
                    </li>
                    <?php
                }
            }
        }
        ?>

    </ul>
</div>
@endif