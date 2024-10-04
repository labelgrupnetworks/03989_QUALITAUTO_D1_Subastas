<div id="loginResponsive">
    <div class="contentLogin">
        <a class="closeBtn" id="closeResponsive" href="javascript:;" title="Cerrar">
            <img src="/themes/{{ $theme }}/assets/img/shape.png" alt="Cerrar">
        </a>
        <div class="title_login">
            <?= trans($theme . '-app.login_register.login') ?>
        </div>
        <form id="accerder-user-form-responsive">
            <input name="_token" type="hidden" value="{{ csrf_token() }}">
            <div class="form-group">
                <input class="form-control" name="email" type="text"
                    placeholder="{{ trans($theme . '-app.login_register.user') }}">
            </div>
            <div class="form-group">
                <input class="form-control" name="password" type="password"
                    placeholder="{{ trans($theme . '-app.login_register.contraseña') }}" maxlength="20">
            </div>
            <p><a class="c_bordered" id="p_recovery" data-ref="{{ \Routing::slug('password_recovery') }}"
                    data-title="{{ trans($theme . '-app.login_register.forgotten_pass_question') }}" data-toggle="modal"
                    data-target="#modalAjax" href="javascript:;"
                    onclick="close_modal_session();">{{ trans($theme . '-app.login_register.forgotten_pass_question') }}</a>
            </p>
            <h5 class="message-error-log text-danger"></h5>
            </p>
            <button class="btn btn-registro" id="accerder-user-responsive" type="button">Enviar</button>
        </form>
        <div class="title_login mt30">
            {{ trans($theme . '-app.login_register.no_account') }}
        </div>
        <a class="btn btn-registro-responsive"
            href="{{ \Routing::slug('login') }}">{{ trans($theme . '-app.login_register.register') }}</a>
    </div>
</div>
<div class="modal fade " id="modalAjax" role="dialog" aria-labelledby="myModalLabel" tabindex="-1">
    <div class="modal-dialog modal-lg" role="document">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal" type="button">&times;</button>
                <h4 class="modal-title">Modal Header</h4>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal" type="button">Close</button>
            </div>
        </div>

    </div>
</div>
<div class="modal-block mfp-hide" id="newsletterModal">
    <section class="panel">
        <div class="panel-body">
            <div class="modal-wrapper">
                <div class="modal-text text-center maxw">
                    <p class="insert_msg"></p>
                    <button class="btn btn-lb-outline btn-lb-gray modal-dismiss">{{ trans($theme . '-app.home.confirm') }}</button>
                </div>
            </div>
        </div>
    </section>
</div>
<div class="modal fade" id="mediaModal" role="dialog" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal" type="button">&times;</button>
            </div>
            <div class="modal-body">
                <!-- content dynamically inserted -->
            </div>
        </div>
    </div>
</div>
<div class="container modal-block mfp-hide " id="modalMensajeWeb">
    <div class="modal-sub-w" data-to="pujarLoteFicha">
        <section class="panel">
            <div class="panel-body">
                <div class="modal-wrapper">
                    <div class=" text-center single_item_content_">
                        <span class="class_h1">
                            <p id="insert_msg_title"></p>
                        </span>
						<br>
                        <p id="insert_msgweb"></p>
						<br>
                        <button class="btn btn-lb-outline btn-lb-gray button_modal_confirm modal-dismiss">
							{{ trans($theme . '-app.lot.accept') }}
						</button>

                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<div class="container modal-block mfp-hide " id="modalMensaje">
    <div class="modal-sub-w" data-to="pujarLoteFicha">
        <section class="panel">
            <div class="panel-body">
                <div class="modal-wrapper">
                    <div class=" text-center single_item_content_">
                        <span class="class_h1">
                            <p id="insert_msg_title"></p>
                        </span>
						<br>
                        <p id="insert_msg"></p>
						<br>
                        <button class="btn btn-lb-outline btn-lb-gray button_modal_confirm modal-dismiss">
							{{ trans($theme . '-app.lot.accept') }}
						</button>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<div class="container modal-block mfp-hide " id="modalPujarFicha">
    <div class="modal-sub-w" data-to="pujarLoteFicha">
        <section class="panel">
            <div class="panel-body">
                <div class="modal-wrapper">
                    <div class=" text-center single_item_content_">
                        <p class="class_h1">{{ trans($theme . '-app.lot.confirm_bid') }}</p><br />
                        <span class='desc_auc' for="bid">{{ trans($theme . '-app.lot.you_are_bidding') }} </span>
                        <strong><span class="precio_orden"></span> €</strong><br />
                        <span class="ref_orden hidden"></span>
                        </br>
                        <button class="btn button_modal_confirm btn-lb-outline btn-lb-gray"
                            id="confirm_orden_lotlist">{{ trans($theme . '-app.lot.confirm') }}</button>
                        <div class='mb-10'></div>
                        <div class='mb-10'></div>
                        <ul class="items_list">
                            <li><?= trans($theme . '-app.lot.tax_not_included') ?> </li>

                        </ul>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
