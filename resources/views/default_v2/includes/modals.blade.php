{{-- El data-to solamente funciona cuando esta en el div con clase modal-block --}}

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

<div class="container modal-block mfp-hide " id="newsletterModal">
    <section class="panel">
        <div class="modal-wrapper">
            <div class="modal-body">
                <p class="insert_msg"></p>
            </div>
            <div class="modal-footer">
                <button class="btn modal-dismiss btn-lb-primary">
                    {{ trans("$theme-app.home.confirm") }}
                </button>
            </div>
        </div>
    </section>
</div>

<div class="container modal-block mfp-hide " id="modalMensajeWeb">
    <section class="panel">
        <div class="modal-wrapper">
            <div class="modal-body">
                <p id="insert_msgweb"></p>
            </div>
            <div class="modal-footer">
                <button class="btn modal-dismiss btn-lb-primary">
                    {{ trans("$theme-app.lot.accept") }}
                </button>
            </div>
        </div>
    </section>
</div>

<div class="container modal-block mfp-hide" id="modalMensaje">
    <section class="panel">
        <div class="modal-wrapper">
            <div class="modal-body">
                <h5 class="class_h1" id="insert_msg_title"></h5>
                <p id="insert_msg_login_required"></p>
                <p>
                    <a class="btn_login" href="#" onclick="$.magnificPopup.close();">
                        <span id="insert_msg_log_in"></span>
                    </a>
                </p>
                <p><span id="insert_msg"></span></p>
            </div>
            <div class="modal-footer">
                <button class="btn modal-dismiss btn-lb-primary">
                    {{ trans("$theme-app.lot.accept") }}
                </button>
            </div>

        </div>
    </section>
</div>

{{-- @todo tengo dudas de que se este utilizando, ya hay uno en modals_ficha --}}
{{-- <div class="container modal-block mfp-hide " id="modalPujarFicha">
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
                            <li> {{trans(\Config::get('app.theme') . '-app.lot.tax_not_included')}} </li>

                        </ul>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
 --}}

 {{-- se utiliza en panel/orders --}}
<div class="container modal-block mfp-hide " id="modalMensajeDeleteOrder">
    <section class="panel">
        <div class="modal-wrapper">
            <div class="modal-body">
                <p id="msg_delete_order"></p>
            </div>
            <div class="modal-footer">
                <button class="btn modal-dismiss btn-lb-primary confirm_delete_order">
                    {{ trans("$theme-app.lot.accept") }}
                </button>
            </div>
        </div>
    </section>
</div>

{{-- Solamente en ficha shoppingCart --}}
<div class="container modal-block mfp-hide " id="modalShoppingCart">
    <section class="panel">
        <div class="modal-wrapper">
			<div class="modal-header">
				<h5 id="msg_title_ShoppingCart"></h5>
			</div>
            <div class="modal-body">
				<p id="insert_msgweb"></p>
            </div>
            <div class="modal-footer">
                <button class="btn modal-dismiss btn-lb-secondary">
                    {{ trans("$theme-app.lot.continue_buying") }}
                </button>
				<a href="{{ route('showShoppingCart', ['lang' => Config::get('app.locale')]) }}" class="btn btn-lb-primary">
                    {{ trans("$theme-app.lot.go_to_cart") }}
				</a>
            </div>
        </div>
    </section>
</div>

{{-- Solamente en ficha articles --}}
<div class="container modal-block mfp-hide " id="modalArticleCart">
    <section class="panel">
        <div class="modal-wrapper">
			<div class="modal-header">
				<h5 id="msg_title_ArticleCart"></h5>
			</div>
            <div class="modal-body">
				<p id="insert_msgweb"></p>
            </div>
            <div class="modal-footer">
                <a href="{{ route('articles') }}" class="btn modal-dismiss btn-lb-secondary">
                    {{ trans("$theme-app.lot.continue_buying") }}
                </a>
				<a href="{{ route('showArticleCart', ['lang' => Config::get('app.locale')]) }}" class="btn btn-lb-primary">
                    {{ trans("$theme-app.lot.go_to_cart") }}
				</a>
            </div>
        </div>
    </section>
</div>

{{-- Solamente lo usa el blog de ansoreana para ampliar las imagenes,
	se podría modificar por los mismos que utiliza vico en eventos/piezas --}}
<div class="d-none" id="imgPopUpModal">
    <span class="cursor">&times;</span>
    <br />
    <img id="img-pop-up-img">
    <div id="img-pop-up-label"></div>
</div>

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
