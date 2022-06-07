
<div class="modal fade " id="modalAjax" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <span class="modal-title seo_h4_modal">Modal Header</span>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="secondary-button" data-dismiss="modal">{{ trans(\Config::get('app.theme').'-app.head.close') }}</button>
      </div>
    </div>

  </div>
</div>
<div id="newsletterModal" class="modal-block mfp-hide">
	<section class="panel">
		<div class="panel-body">
			<div class="modal-wrapper">
				<div class="modal-text text-center maxw">
					<p class="insert_msg"></p>
					<button class="btn btn-default modal-dismiss">{{ trans(\Config::get('app.theme').'-app.home.confirm') }}</button>
				</div>
			</div>
		</div>
	</section>
</div>
<div class="modal fade" id="mediaModal" tabindex="-1" role="dialog" aria-hidden="true">
<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
    <div class="modal-body">
      <!-- content dynamically inserted -->
    </div>
  </div>
</div>
</div>
<div id="modalMensajeWeb" class="container modal-block mfp-hide ">
    <div   data-to="pujarLoteFicha" class="modal-sub-w">
            <section class="panel">
                    <div class="panel-body">
                            <div class="modal-wrapper">
                                    <div class=" text-center single_item_content_">
                                        <span class="class_h1"><p id="insert_msg_title"></p></span><br/>
                                       <p id="insert_msgweb"></p><br/>

                                            <button  class=" btn button_modal_confirm modal-dismiss btn-custom">{{ trans(\Config::get('app.theme').'-app.lot.accept') }}</button>

                                    </div>
                            </div>
                    </div>
            </section>
    </div>
</div>

<div id="modalMensaje" class="container modal-block mfp-hide ">
    <div   data-to="pujarLoteFicha" class="modal-sub-w">
            <section class="panel">
                    <div class="panel-body">
                            <div class="modal-wrapper">
                                    <div class=" text-center single_item_content_">
                                        <span class="class_h1"><p id="insert_msg_title"></p></span><br/>
                                        
                                        <!-- METODO NUEVO -->
                                        <p><span id="insert_msg_login_required"></span><a class="btn_login" href="#" onclick="$.magnificPopup.close();"><span id="insert_msg_log_in"></span></a><span id="insert_msg"></span></p><br/>
                                       
                                        <!-- METODO ORIGINAL EN EL QUE INICIAR SESION NO ES UN LINK -->
                                        <!-- <p id="insert_msg"></p><br/> -->

                                            <button  class=" btn button_modal_confirm modal-dismiss btn-custom">{{ trans(\Config::get('app.theme').'-app.lot.accept') }}</button>

                                    </div>
                            </div>
                    </div>
            </section>
    </div>
</div>
<div id="modalPujarFicha" class="container modal-block mfp-hide ">
            <div   data-to="pujarLoteFicha" class="modal-sub-w">
                    <section class="panel">
                            <div class="panel-body">
                                    <div class="modal-wrapper">
                                            <div class=" text-center single_item_content_">
                                                <p class="class_h1">{{ trans(\Config::get('app.theme').'-app.lot.confirm_bid') }}</p><br/>
                                                <span for="bid" class='desc_auc'>{{ trans(\Config::get('app.theme').'-app.lot.you_are_bidding') }} </span> <strong><span class="precio_orden"></span> €</strong><br/>
                                                <span class="ref_orden hidden"></span>    
                                                </br>
                                                    <button id="confirm_orden_lotlist" class="btn button_modal_confirm btn-custom">{{ trans(\Config::get('app.theme').'-app.lot.confirm') }}</button>
                                                    <div class='mb-10'></div>
                                                     <div class='mb-10'></div>
                                                    <ul class="items_list">
                                                        <li><?=trans(\Config::get('app.theme').'-app.lot.tax_not_included')?> </li>
                                                        
                                                    </ul>
                                            </div>
                                    </div>
                            </div>
                    </section>
            </div>
</div>

@if($errors->any())
  <script>
     $( document ).ready(function() {
        $("#modalMensaje #insert_msg_title").html("");
        $("#modalMensaje #insert_msg").html('<?= $errors->first() ?>');
        $.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
    });
  </script>
@endif