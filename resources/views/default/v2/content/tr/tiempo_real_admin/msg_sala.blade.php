<div class="hidden started" >

    <!-- inicio modelo clon predefinidos -->
    <li class="left clearfix chatline hidden" id="predefinido-model" style="padding:5px;">
        <div class="chat-body clearfix">
            <div class="header"></div>

                <div class="col-lg-8">
                    <p class="pull-left texto"></p>
                </div>

                <div class="col-lg-4">
                    <button type="button" class="btn-primary btn btn-warning btn-enviar btn-chat-pre" id_mensaje="">
                        {{ trans('web.sheet_tr.chat-send') }}
                    </button>
                    <button type="button" class="btn btn-danger btn-eliminar" id_mensaje="" predefinido="1">{{ trans('web.sheet_tr.chat-delete') }}</button>
                </div>

        </div>
    </li>
    <!-- fin objeto modelo clon -->
    <div id="mensajes_predefinidos" class="modal-block mfp-hide">
        <section class="panel">
            <div class="panel-body">
                <div class="modal-wrapper">
                    <div class="modal-text">
                       <ul class="chat-predefinidos" style="list-style:none; padding:0;">
                        <?php
                        $cur_lang = strtoupper(\App::getLocale());

                        if(!empty($data['js_item']['chat'])) {

                            foreach ($data['js_item']['chat']['mensajes'] as $k => $item) {

                                # Si no encuentra el idioma del usuario le asignamos uno por defecto
                                if(!isset($item[$cur_lang])) {

                                    foreach($data['js_item']['chat']['mensajes'][$k] as $index => $valor) {

                                        if(isset($data['js_item']['chat']['mensajes'][$k][$index])) {
                                            $item = $data['js_item']['chat']['mensajes'][$k][$index];
                                        }

                                        $item = $data['js_item']['chat']['mensajes'][$k][$index];
                                    }

                                } else {
                                    $item = $item[$cur_lang];
                                }

                                if($item->predefinido == 1) {
                        ?>
                                    <li class="left clearfix chatline" id="predefinido-model-<?php echo $item->id_web_chat ?>" style="padding:5px;">
                                        <div class="chat-body clearfix">
                                            <div class="header"></div>

                                                <div class="col-lg-8">
                                                    <p><?php echo $item->msg; ?></p>
                                                </div>

                                                <div class="col-lg-4">
                                                    <button type="button" class="btn-primary btn btn-warning btn-chat-pre btn-enviar" id_mensaje="<?php echo $item->id_web_chat ?>"> {{ trans('web.sheet_tr.chat-send') }}</button>
                                                    <button type="button" class="btn btn-danger btn-eliminar" id_mensaje="<?php echo $item->id_web_chat ?>" predefinido="1">{{ trans('web.sheet_tr.chat-delete') }}</button>
                                                </div>

                                        </div>
                                    </li>
                                    <?php
                                }
                            }
                        }
                        ?>

                    </ul>


                        <!--<button class="btn btn-primary modal-dismiss">{{ trans('web.tr.confirm') }}</button>-->
                    </div>
                </div>
            </div>
        </section>
    </div>


    <div class="row" style="margin-top:20px;">
        <div class="col-lg-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h2 class="msj">{{ trans('web.sheet_tr.room_msg') }} ({{ strtoupper(\App::getLocale()) }})</h2>
                </div>

                <div class="panel-body">

                    <!-- model -->
                    <li class="left clearfix hidden" id="chatline_model">
                        <div class="chat-body clearfix">
                            <div class="header">
                                <small class="pull-right text-muted">
                                    <span class="glyphicon glyphicon-time"></span><time class="timeago" datetime="2008-07-17T09:27:17Z"></time>
                                </small>
                            </div>
                            <p class="texto">
                                <?php if(isset($data['js_item']['user']) && $data['js_item']['user']['is_gestor']) { ?>
                                <span class="glyphicon glyphicon-remove btn-eliminar" predefinido="0" id_mensaje=""></span>
                                <?php } ?>
                            </p>
                        </div>
                    </li>
                    <!-- model -->

                    <ul class="chat">
                        <?php
                        if(!empty($data['js_item']['chat'])) {
                            foreach ($data['js_item']['chat']['mensajes'] as $k => $item) {

                                /*
                                if(!isset($item[$cur_lang])) {
                                    continue;
                                }
                                */

                                if(!isset($item[$cur_lang])) {

                                    foreach($data['js_item']['chat']['mensajes'][$k] as $index => $valor) {

                                        if(isset($data['js_item']['chat']['mensajes'][$k][$index])) {
                                            $item = $data['js_item']['chat']['mensajes'][$k][$index];
                                        }

                                        $item = $data['js_item']['chat']['mensajes'][$k][$index];
                                    }

                                } else {
                                    $item = $item[$cur_lang];
                                }

                                if($item->predefinido == 0) {
                                    $fecha = str_replace('/', '-', $item->fecha);
                                    $parte = explode(' ', $fecha);
                                    $fecha_final = $parte[0].'T'.$parte[1];
                        ?>
                                    <li class="left clearfix chatline" id="chatline_model_<?php echo $item->id_web_chat; ?>">
                                        <div class="chat-body clearfix">
                                            <div class="header">
                                                <small class="pull-right text-muted">
                                                    <span class="glyphicon glyphicon-time"></span><time class="timeago" datetime="<?php echo $fecha_final; ?>"></time>
                                                </small>
                                            </div>

                                            <p>
                                                <?php if(isset($data['js_item']['user']) && $data['js_item']['user']['is_gestor']) { ?>
                                                <span class="glyphicon glyphicon-remove btn-eliminar" predefinido="0" id_mensaje="<?php echo $item->id_web_chat; ?>"></span>
                                                <?php } ?>

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

                @if (isset($data['js_item']['user']) && $data['js_item']['user']['is_gestor'])
                <div class="panel-footer">
                    <form id="chat-frm">
                        <div>

                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs" role="tablist">
                                <?php
                                $contador = 1;

                                foreach (Config::get('app.locales') as $short => $long) {

                                    if ($contador == 1) {
                                        $class = 'class="active"';
                                    } else {
                                        $class = false;
                                    }
                                    ?>

                                    <li role="presentation" <?php echo $class; ?>><a href="#<?php echo strtoupper($short); ?>" aria-controls="<?php echo strtoupper($short); ?>" role="tab" data-toggle="tab"><?php echo $long; ?></a></li>
                                    <?php
                                    $contador++;
                                }
                                ?>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <?php
                                $contador = 1;
                                foreach (Config::get('app.locales') as $short => $long) {

                                    if ($contador == 1) {
                                        $class = 'active';
                                    } else {
                                        $class = false;
                                    }

                                ?>
                                <div role="tabpanel" class="tab-pane <?php echo $class; ?>" id="<?php echo strtoupper ($short); ?>">
                                    <input type="text" class="form-control input-sm" class="msg" name="mens" contador="<?php echo $contador; ?>" clave="<?php echo strtoupper ($short); ?>" placeholder="<?php echo $long; ?>" />
                                </div>
                                <?php
                                $contador++;
                                }
                                ?>
                            </div>

                            <br />
                            <button type="button" class="btn btn-warning btn-sm" id="btn-chat">{{ trans('web.sheet_tr.chat-send') }}</button>
                            <div class="col-lg-6 pull-right" style="text-align:right;">Predefinido: <input type="checkbox" name="predefinido" id="predefinido" autocomplete="off"></div>
                        </div>
                        <!--
                        <div class="input-group">
                            <input id="btn-input" type="text" class="form-control input-sm" placeholder="Escribe aquí el mensaje..." />
                            <span class="input-group-btn">
                                <button class="btn btn-warning btn-sm" id="btn-chat">{{ trans('web.tr.chat.send') }}</button>
                            </span>
                        </div>
                    -->

                    </form>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>
