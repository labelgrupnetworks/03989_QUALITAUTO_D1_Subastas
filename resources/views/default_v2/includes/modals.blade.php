<!-- Modal -->
<div class="modal fade" id="modalAjax" aria-labelledby="myModalLabel" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button class="btn-close" data-bs-dismiss="modal" type="button" aria-label="Close"></button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button class="btn btn-lb-secondary" data-bs-dismiss="modal"
                    type="button">{{ trans(\Config::get('app.theme') . '-app.head.close') }}</button>
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
                    <button
                        class="btn btn-default modal-dismiss">{{ trans(\Config::get('app.theme') . '-app.home.confirm') }}</button>
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
                        </span><br />
                        <p id="insert_msgweb"></p><br />

                        <button
                            class=" btn button_modal_confirm modal-dismiss btn-custom">{{ trans(\Config::get('app.theme') . '-app.lot.accept') }}</button>

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

                        <!-- METODO NUEVO -->
                        <p>
                            <span id="insert_msg_login_required"></span>
                            <a class="btn_login" href="#" onclick="$.magnificPopup.close();">
                                <span id="insert_msg_log_in"></span>
                            </a>
                            <span id="insert_msg"></span>
                        </p>
                        <br>

                        <!-- METODO ORIGINAL EN EL QUE INICIAR SESION NO ES UN LINK -->
                        <!-- <p id="insert_msg"></p><br/> -->

                        <button class="btn btn-lb-secondary modal-dismiss">
                            {{ trans(\Config::get('app.theme') . '-app.lot.accept') }}
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
                        <p class="class_h1">{{ trans(\Config::get('app.theme') . '-app.lot.confirm_bid') }}</p><br />
                        <span class='desc_auc'
                            for="bid">{{ trans(\Config::get('app.theme') . '-app.lot.you_are_bidding') }} </span>
                        <strong><span class="precio_orden"></span>
                            {{ trans(\Config::get('app.theme') . '-app.subastas.euros') }}</strong><br />
                        <span class="ref_orden hidden"></span>
                        </br>
                        <button class="btn button_modal_confirm btn-custom"
                            id="confirm_orden_lotlist">{{ trans(\Config::get('app.theme') . '-app.lot.confirm') }}</button>
                        <div class='mb-10'></div>
                        <div class='mb-10'></div>
                        <ul class="items_list">
                            <li><?= trans(\Config::get('app.theme') . '-app.lot.tax_not_included') ?> </li>

                        </ul>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<div class="container modal-block mfp-hide " id="modalMensajeDelete">
    <div class="modal-sub-w" data-to="pujarLoteFicha">
        <section class="panel">
            <div class="panel-body">
                <div class="modal-wrapper">
                    <div class=" text-center single_item_content_">
                        <span class="class_h1"></span><br />
                        <p id="insert_msg_delete"></p><br />

                        <button class=" btn confirm_delete modal-dismiss btn-custom " ref=''
                            sub=''>{{ trans(\Config::get('app.theme') . '-app.lot.accept') }}</button>

                    </div>
                </div>
            </div>
        </section>
    </div>
</div>


<div class="container modal-block mfp-hide " id="modalMensajeDeleteOrder">
    <div class="modal-sub-w" data-to="pujarLoteFicha">
        <section class="panel">
            <div class="panel-body">
                <div class="modal-wrapper">
                    <div class=" text-center single_item_content_">
                        <span class="class_h1"></span><br />
                        <p id="msg_delete_order"></p><br />

                        <button class=" btn confirm_delete_order modal-dismiss btn-custom " ref=''
                            sub=''>{{ trans(\Config::get('app.theme') . '-app.lot.accept') }}</button>

                    </div>
                </div>
            </div>
        </section>
    </div>
</div>


<div class="container modal-block mfp-hide " id="modalShoppingCart">
    <div class="modal-sub-w">
        <section class="panel">
            <div class="panel-body">
                <div class="modal-wrapper">
                    <div class=" text-center single_item_content_">
                        <span class="class_h1">
                            <p id="msg_title_ShoppingCart"></p>
                        </span><br />
                        <p id="insert_msgweb"></p><br />
                        <a href="{{ trans(\Config::get('app.theme') . '-app.lot.href_continue_buying') }}">
                            <button
                                class=" btn modal-dismiss   btn-custom">{{ trans(\Config::get('app.theme') . '-app.lot.continue_buying') }}</button>
                        </a>
                        <a href="{{ route('showShoppingCart', ['lang' => \Config::get('app.locale')]) }}">
                            <button
                                class=" btn  btn-custom">{{ trans(\Config::get('app.theme') . '-app.lot.go_to_cart') }}</button>
                        </a>

                    </div>
                </div>
            </div>
        </section>
    </div>
</div>



<div class="container modal-block mfp-hide " id="modalArticleCart">
    <div class="modal-sub-w">
        <section class="panel">
            <div class="panel-body">
                <div class="modal-wrapper">
                    <div class=" text-center single_item_content_">
                        <span class="class_h1">
                            <p id="msg_title_ArticleCart"></p>
                        </span><br />
                        <p id="insert_msgweb"></p><br />
                        <a href="{{ Route('articles') }}">
                            <button
                                class=" btn   btn-custom">{{ trans(\Config::get('app.theme') . '-app.lot.continue_buying') }}</button>
                        </a>
                        <a href="{{ route('showArticleCart', ['lang' => \Config::get('app.locale')]) }}">
                            <button
                                class=" btn  btn-custom">{{ trans(\Config::get('app.theme') . '-app.lot.go_to_cart') }}</button>
                        </a>

                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<div class="d-none" id="imgPopUpModal">
    <span class="cursor">&times;</span>
    <br />
    <img id="img-pop-up-img">
    <div id="img-pop-up-label"></div>
</div>


@if (!empty($lote_actual->contextra_hces1))
    <div class="modal fade" id="modal360" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
        tabindex="-1">
        <div class="modal-dialog flex valign" role="document">
            <div class="modal-content">
                <a class="close-modal-360" data-dismiss="modal"><i class="fas fa-times"></i></a>
                <div class="modal-body">
                    <?= $lote_actual->contextra_hces1 ?>
                </div>
            </div>
        </div>
    </div>
@endif




@if ($errors->any())
    <script>
        $(document).ready(function() {
            $("#modalMensaje #insert_msg_title").html("");
            $("#modalMensaje #insert_msg").html('<?= $errors->first() ?>');
            $.magnificPopup.open({
                items: {
                    src: '#modalMensaje'
                },
                type: 'inline'
            }, 0);
        });
    </script>
@endif
