<script>
var cod_sub = '{{$data["cod_sub"]}}';
routing.node_url 	 = '{{ Config::get("app.node_url") }}';
routing.comprar		 = '{{ $data["node"]["comprar"] }}';
routing.ol		 = '{{ $data["node"]["ol"] }}';
</script>
<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-12 title-head-grid">
            <div class="col-xs-5 col-sm-3 col-md-3 col-lg-3">
               <?php //Si quieren mostrar nombre de la subasta o que se vea texto Lotes ?>
                @if(empty($data['subastas']))
                    <h1 class="titlePage-custom"> {{$data['name']}} </h1>
                @else
                     <h1 class="titlePage-custom">{{ trans(\Config::get('app.theme').'-app.lot_list.lots') }}</h1>
                @endif
            </div>
            <div class="col-xs-7 col-sm-5 col-md-4 col-lg-4 lot-count">
                @if(!empty( $data['subastas']) && $data['subastas'][0]->tipo_sub == 'W'  && ($data['subastas'][0]->subc_sub == 'A' ||$data['subastas'][0]->subc_sub == 'S' )  && strtotime($data['subastas'][0]->start_session) > time())
                    <div  class="text-right timeLeft">
                                                <span class="clock"></span>

                        <span data-countdown="{{ strtotime($data['subastas'][0]->start_session) - getdate()[0] }}"  data-format="<?= \Tools::down_timer($data['subastas'][0]->start_session); ?>" data-closed="{{ $data['subastas'][0]->cerrado_asigl0 }}" class="timer"></span>
                    </div>
                @endif
            </div>
            <div class="col-xs-9 col-sm-4 col-md-3 col-lg-4 refresh text-right">
                @if(!isset($in_indice_subasta) || $in_indice_subasta == false)

            <?php
                $indices = App\Models\Amedida::indice($data['cod_sub'], $data['id_auc_sessions']);
            ?>

            @if(!empty($indices))
            <a style="color:var(--primary-color);padding-right: 10px;" href="{{$data['url_indice']}}" ><?= trans(\Config::get('app.theme').'-app.lot_list.open_indice') ?></a>
            @endif
        @endif
                <?php // si es uan subasta w y abierta o si es uan subasta tipo O o P ?>
                @if(!empty( $data['subastas']) && ( ($data['subastas'][0]->tipo_sub == 'W' && $data['subastas'][0]->subabierta_sub != 'N') || $data['subastas'][0]->tipo_sub == 'P'  || $data['subastas'][0]->tipo_sub == 'O' )  && ($data['subastas'][0]->subc_sub == 'A' ||$data['subastas'][0]->subc_sub == 'S' )  )

                    <a href=""> {{ trans(\Config::get('app.theme').'-app.lot_list.refresh_prices') }} <i class="fa fa-refresh" aria-hidden="true"></i></a>

                @endif
                 @if(!empty($data['sub_data']) && !empty($data['sub_data']->opcioncar_sub) && !empty($data['subastas'][0]) && $data['sub_data']->opcioncar_sub == 'S' && strtotime("now") > strtotime($data['sub_data']->orders_start)  &&   strtotime("now") < strtotime($data['sub_data']->orders_end))
                    @if(Session::has('user'))
                       <i class="fa fa-gavel  fa-1x"></i> <a href="{{ \Routing::slug('user/panel/modification-orders') }}?sub={{$data['sub_data']->cod_sub}}" ><?= trans(\Config::get('app.theme').'-app.lot_list.ver_ofertas') ?></a>
                    @endif
                @endif

            </div>
            <div class="col-xs-3 col-md-2 col-lg-1 lot-grid text-right hidden-xs hidden-sm">

                <a id="large_square" href="javascript:;"><i class="fa fa-th-list fa-lg"></i></a>
                <a id="square" href="javascript:;"><i class="fa fa fa-th-large fa-lg"></i></a>
                <a id="small_square" href="javascript:;"><i class="fa fa-th fa-lg hidden-xs hidden-sm"></i></a>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-4 col-md-3">
            <?php
                $inf_subasta = new \App\Models\Subasta();
                if(!empty($data['sub_data'])){
                    $inf_subasta->cod = $data['sub_data']->cod_sub;
                    $inf_subasta->id_auc_sessions = $data['sub_data']->id_auc_sessions;
                }else{
                    $inf_subasta->cod = $data['cod_sub'];
                }
                $ficha_subasta=$inf_subasta->getInfSubasta();
            ?>
               @include('includes.img_auction_filters')
               @include('includes.subasta_filters')

                @include('includes.info_auction_filters')

        </div>
        <div class="col-xs-12 col-sm-8 col-md-9 lot-grid text-right hidden-md hidden-lg">

            <a id="large_square_mobile" href="javascript:;"><i class="fa fa-th-list fa-lg"></i></a>
            <a id="square_mobile" href="javascript:;"><i class="fa fa fa-th-large fa-lg"></i></a>
        </div>

		<div class="col-xs-12 col-sm-8 col-md-9 mb-2 form-top-grid-container d-flex align-items-stretch hidden-xs">
			<div class="col-xs-12 col-lg-6 align-self-end" style="flex: 1">
				@if($data['sub_data']->tipo_sub =='W' && strtotime($data['sub_data']->end) > time())
					<p class="text-center btn-color grid-buttons">
						<a style="color:#FFFFFF" href="{{ \Routing::translateSeo('api/subasta').$data['sub_data']->cod_sub."-".str_slug($data['sub_data']->name)."-".$data['sub_data']->id_auc_sessions }}"
							target="_blank">{{ trans("$theme-app.subastas.lot_subasta_online") }}</a>
					</p>
				@endif
				@if(!Session::has('user'))
				<p class="text-center btn-color grid-buttons">
					<a style="color:#FFFFFF"
						title="{{ trans(\Config::get('app.theme').'-app.login_register.register') }}"
						href="{{ \Routing::slug('login') }}">{{ trans(\Config::get('app.theme').'-app.login_register.register') }}
						</a>
				</p>
				@endif
			</div>
			<div class="col-xs-12 col-lg-6" style="flex: 1">
				<form class="form-top-grid" method="get" action="{{ $data['url'] }}">
					<input type="hidden" name="no_award" value="{{ request('no_award') }}">
					<input type="hidden" name="order" value="{{ request('order') }}">
					<input type="hidden" name="lin_ortsec" value="{{ request('lin_ortsec') }}">
					<div class="form-group">
					<div>
						<label for="input_description">{{ trans(\Config::get('app.theme').'-app.lot_list.search') }}</label>
						<input id="input_description" placeholder="{{ trans(\Config::get('app.theme').'-app.lot_list.search_placeholder') }}" name="description" type="text" class="form-control input-sm" value="{{ app('request')->input('description') }}">
					</div>
					<div>
						<label for="input_reference">{{ trans(\Config::get('app.theme').'-app.lot_list.reference') }}</label>
						<input id="input_reference" placeholder="{{ trans(\Config::get('app.theme').'-app.lot_list.reference') }}" name="reference" type="text" class="form-control input-sm" value="{{ app('request')->input('reference') }}">
					</div>

					<button type="submit" class="btn btn-custom-search"><i class="fa fa-search"></i><div class="loader search-loader" style="display:none;position: absolute;top: -62.50px;right:-1px;width: 25px;height: 25px;"></div></button>

					</div>
				</form>
			</div>
		</div>

		<div class="col-xs-12 col-sm-8 col-md-9"><hr class="grid-hr"></div>

		<div class="col-xs-12 col-sm-8 col-md-9 text-center">

			{!! $data['subastas.paginator'] !!}

		</div>

        <div class="col-xs-12 col-sm-8 col-md-9">

            <div class="list_lot">



                @foreach ($data['subastas'] as $key => $item)
                    <?php
						$url = "";
						//Si impsalweb_asigl0 asignamos este como precio de salida
						$precio_salida = $item->impsalweb_asigl0 != 0 ? $item->formatted_impsalweb_asigl0 : $item->formatted_impsalhces_asigl0;

                        //Si no esta retirado tendrá enlaces
                        if($item->retirado_asigl0 =='N' && $item->fac_hces1 != 'D' && $item->fac_hces1 != 'R'){
                            $webfriend = !empty($item->webfriend_hces1)? $item->webfriend_hces1 :  str_slug($item->titulo_hces1);
                            if($data['type'] == "theme"){
                                $url_vars = "?theme=".$data['theme'];
                            }else{
                                $url_vars ="";
                            }
                            $url_friendly = \Routing::translateSeo('lote').$item->cod_sub."-".str_slug($item->name).'-'.$item->id_auc_sessions."/".$item->ref_asigl0.'-'.$item->num_hces1.'-'.$webfriend.$url_vars;
                            $url = "href='$url_friendly'";
                        }
                         $titulo ="";
                        if(\Config::get('app.ref_asigl0') && \Config::get('app.titulo_hces1')){
                            $titulo ="$item->ref_asigl0  -  $item->titulo_hces1";
                        }elseif(!\Config::get('app.ref_asigl0') && \Config::get('app.titulo_hces1')){
                            $titulo = $item->titulo_hces1;
                        }elseif(\Config::get('app.ref_asigl0')){
                            $titulo = trans(\Config::get('app.theme').'-app.lot.lot-name') ." ".$item->ref_asigl0 ;
                        }

                        $precio_venta=NULL;
                        if (!empty($item->himp_csub)){
                                $precio_venta=$item->himp_csub;
                        }
                        //si es un histórico y la subasta del asigl0 = a la del hces1 es que no está en otra subasta y podemso coger su valor de compra de implic_hces1
                        elseif($item->subc_sub == 'H' && $item->cod_sub == $item->sub_hces1 && $item->lic_hces1 == 'S' and $item->implic_hces1 >0){
                                $precio_venta = $item->implic_hces1;
						}
						 //Si hay precio de venta y  impsalweb_asigl0 contiene valor, mostramos este como precio de venta
						 $precio_venta = (!empty($precio_venta) && $item->impsalweb_asigl0 != 0) ? $item->impsalweb_asigl0 : $precio_venta;

                       $winner = "";
                        if($item->tipo_sub == 'W' && $item->subabierta_sub == 'O'){
                            //si el usuario actual es el
                            if(isset($data['js_item']['user']) && count($item->ordenes) > 0 && head($item->ordenes)->cod_licit == $data['js_item']['user']['cod_licit']){
                                $winner = "winner";
                            }
                            //si hay usuario conectado pero no es el ganador, y hay ordenes
                            elseif(isset($data['js_item']['user']) && count($item->ordenes) > 0){
                                $winner = "no_winner";
                            }
                        }
                        else if (($item->tipo_sub == 'P' || $item->tipo_sub == 'O') || $item->subabierta_sub == 'P'){
                             if(isset($data['js_item']['user']) && !empty($item->max_puja) && $item->max_puja->cod_licit == $data['js_item']['user']['cod_licit']){
                                $winner = "winner";
                            }
                            //si hay usuario conectado pero no es el ganador, y hay ordenes
                            elseif(isset($data['js_item']['user']) && !empty($item->max_puja)){
                                $winner = "no_winner";
                            }
                        }

						$rarityLot = \App\Models\V5\FgHces1::getRarity()->addSelect('nvl(otv_lang."catalog_3_lang",otv."catalog_3") AS CATALOG_3')->where([ ['num_hces1', $item->num_hces1],['lin_hces1', $item->lin_hces1] ])->first();
						$rarity = null;
						if($rarityLot){
							$rarity = $rarityLot->catalog_3;
						}
                        $class_square = 'col-xs-12 col-sm-6 col-lg-4';
                    ?>
                    @include('includes.lotlist')
                    <?php
                        $class_square = 'col-xs-12';
                    ?>
                    @include('includes.lotlist_large')
                    <?php
                        $class_square = 'col-xs-4 col-sm-3 col-md-2';
                    ?>
                    @include('includes.lotlist_mini')
                @endforeach

            </div>
        </div>
        <div class="col-xs-12 col-md-8 col-md-offset-3 col-xs-offset-0">
            <?php echo $data['subastas.paginator']; ?>
        </div>
    </div>
</div>

@if(!empty($data['seo']->meta_content) && $data['subastas.paginator']->currentPage == 1)
<div class="container category">
	<div class="row">
		<div class="col-lg-12">
                <?= $data['seo']->meta_content?>
                </div>
        </div>
</div>
@endif


<script>

// Visor de imagen en grande en lot-list-large

$(function() {
  $(".thumbPop").thumbPopup({
    imgSmallFlag: "lote_medium",
    imgLargeFlag: "lote_large",
    cursorTopOffset: 0,
    cursorLeftOffset: 20

  });
});

(function($) {



  $.fn.thumbPopup = function(options) {

    //Combine the passed in options with the default settings
    settings = jQuery.extend({
      popupId: "thumbPopup",
      popupCSS: {
        'border': '1px solid #000000',
        'background': '#FFFFFF'
      },
      imgSmallFlag: "lote_medium",
      imgLargeFlag: "lote_large",
      cursorTopOffset: 15,
      cursorLeftOffset: 15,
      loadingHtml: "<span style='padding: 5px;'>Loading</span>"
    }, options);

    //Create our popup element
    popup =
      $("<div />")
    .css(settings.popupCSS)
    .attr("id", settings.popupId)
    .css("position", "absolute")
    .css('z-index', 99999999)
    .appendTo("body").hide();

    //Attach hover events that manage the popup
    $(this)
    .hover(setPopup)
    .mousemove(updatePopupPosition)
    .mouseout(hidePopup);

    function setPopup(event) {

      var fullImgURL = $(this).attr("src").replace(settings.imgSmallFlag, settings.imgLargeFlag);
      $(this).data("hovered", true);

      var style = "style";
      if ($(this).attr("src").indexOf("portada") > -1) {
        style = "styleX";
      }

      //Load full image in popup
      $("<img />")
      .attr(style, "height:450px;max-width:100%; padding: 5px;z-index:9999999;")
      .bind("load", {
        thumbImage: this
      }, function(event) {
        //Only display the larger image if the thumbnail is still being hovered
        if ($(event.data.thumbImage).data("hovered") == true) {
          $(popup).empty().append(this);
          updatePopupPosition(event, style);
          $(popup).show();
        }
        $(event.data.thumbImage).data("cached", true);
      })
      .attr("src", fullImgURL);

      //If no image has been loaded yet then place a loading message
      if ($(this).data("cached") != true) {
        $(popup).append($(settings.loadingHtml));
        $(popup).show();
      }

      updatePopupPosition(event);
    }

    function updatePopupPosition(event, style) {
      var windowSize = getWindowSize();
      var popupSize = getPopupSize(style);

      var rectificaY = 0;
      var rectificaX = 0;

      /*	if (windowSize.width + windowSize.scrollLeft < event.pageX + popupSize.width + settings.cursorLeftOffset){
				$(popup).css("left", event.pageX - popupSize.width - settings.cursorLeftOffset);
			} else {
				$(popup).css("left", event.pageX + settings.cursorLeftOffset);
			}
			if (windowSize.height + windowSize.scrollTop < event.pageY + popupSize.height + settings.cursorTopOffset){
				$(popup).css("top", event.pageY - popupSize.height - settings.cursorTopOffset);
			} else {
				$(popup).css("top", event.pageY + settings.cursorTopOffset);
			} */

      if (event.pageX + popupSize.width > screen.width) {
        rectificaX = -(popupSize.width + 40);
      }
      $(popup).css("left", event.pageX + settings.cursorLeftOffset + rectificaX);

      if (event.pageY + popupSize.height > windowSize.height + windowSize.scrollTop) {
        rectificaY = (windowSize.height + windowSize.scrollTop) - (event.pageY + popupSize.height + 10);
      }
      $(popup).css("top", event.pageY + settings.cursorTopOffset + rectificaY);

    }

    function hidePopup(event) {
      $(this).data("hovered", false);
      $(popup).empty().hide();
    }

    function getWindowSize() {
      return {
        scrollLeft: $(window).scrollLeft(),
        scrollTop: $(window).scrollTop(),
        width: $(window).width(),
        height: $(window).height()
      };
    }

    function getPopupSize(style) {
      if (style == "styleX") {
        return {
          width: $(popup).width(),
          height: $(popup).height()
        };
      }

      return {
        width: 450,
        height: 450
      };
    }

    //Return original selection for chaining
    return this;
  };
})(jQuery);


</script>
