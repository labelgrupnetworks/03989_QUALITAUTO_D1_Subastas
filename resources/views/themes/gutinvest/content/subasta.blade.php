<script>
    var cod_sub = '{{$data["cod_sub"]}}';
routing.node_url 	 = '{{ Config::get("app.node_url") }}';
routing.comprar		 = '{{ $data["node"]["comprar"] }}';
routing.ol		 = '{{ $data["node"]["ol"] }}';
</script>


<?php

$file_code = $data['sub_data']->emp_sub . '_' . $data['sub_data']->cod_sub . '_' .$data['sub_data']->reference;
$user = (Session::get('user'));
 $inf_subasta = new \App\Models\Subasta();
                if(!empty($data['sub_data'])){
                    $inf_subasta->cod = $data['sub_data']->cod_sub;
                }else{
                    $inf_subasta->cod = $data['cod_sub'];
                }
                $ficha_subasta=$inf_subasta->getInfSubasta();
				$url_flat = '';

				$aucFiles = \App\Models\V5\AucSessionsFiles::select('"description"', '"url"')
					->where([
						['"auction"', $data['sub_data']->auction],
						['"reference"', $data['sub_data']->reference],
						['"lang"', Config::get('app.language_complete')[Config::get('app.locale', 'es')]],
						['"type"', '5'],
					])
					->orderBy('"order"')->get();
?>

<section class="info-subasta">
    <!--<div class="info-subasta-image" style="background: url(/img/prueba_subasta.png) no-repeat center #234575;background-size: cover;"></div>-->
    <div class="info-subasta-image" style="background: url(/img/load/subasta_large/AUCTION_{{ $ficha_subasta->emp_sub }}_{{$ficha_subasta->cod_sub }}.jpg) no-repeat center #234575;background-size: cover;"></div>
    <div class="info-subasta-content">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="info-subasta-title">
                        {{$data['sub_data']->des_sub}}
                    </div>
                    <div class="info-subasta-subtitle">
                        <p style="font-size: 14px">{{ trans(\Config::get('app.theme').'-app.subastas.number_sale') }}: {{ $data['sub_data']->cod_sub }}</p>
                        {{ trans(\Config::get('app.theme').'-app.subastas.date_start') }}
                    </div>
                    <div class="info-subasta-data">
                        {{ date("d/m/Y H:i:s",strtotime($data['sub_data']->orders_start))}}
                    </div>
                    <div class="info-subasta-subtitle">
                        {{ trans(\Config::get('app.theme').'-app.subastas.date_end') }}
                    </div>
                    <div class="info-subasta-data">

                        {{ date("d/m/Y H:i:s",strtotime($data['sub_data']->orders_end))}}
                    </div>
                </div>
                @if($data['sub_data']->expolocal_sub || $data['sub_data']->expolocal_sub)
                <div class="col-md-2">
                    @if($data['sub_data']->expofechas_sub)
                        <div class="info-subasta-subtitle">
                            {{ trans(\Config::get('app.theme').'-app.subastas.inspection_days') }}
                        </div>
                    <div class="info-subasta-data">
                        {{$data['sub_data']->expofechas_sub}}
                    </div>
                    @endif
                    @if($data['sub_data']->expolocal_sub)
                    <div class="info-subasta-subtitle">
                        {{ trans(\Config::get('app.theme').'-app.subastas.inf_subasta_exposicion') }}
                    </div>
                    <div class="info-subasta-data">
                        {{$data['sub_data']->expolocal_sub}}
                    </div>
                    @endif
                </div>
                @endif
                <div class="col-md-4">
                    <?= $data['sub_data']->descdet_sub ?>
                    <div class="info-subasta-subtitle">
                        <?= trans(\Config::get('app.theme').'-app.subastas.mode_pay') ?>
                    </div>
                    <div class="info-subasta-data">
                        <img src="/themes/{{\Config::get('app.theme')}}/assets/img/transferencia-bancaria.png">
                        <img style="background: white" src="/themes/{{\Config::get('app.theme')}}/assets/img/visa.png">
                        <img style="background: white" src="/themes/{{\Config::get('app.theme')}}/assets/img/mastercard.png">
                    </div>

                </div>
                 <div class="col-md-3">
                    @if(Config::get('app.locale') =='es')
                        @if( $data['sub_data']->upmanualuso == 'S')
                        <a style="text-transform: uppercase;" target="_blank" class="cat-link " href="{{url('/files/'.$file_code.'_man_es.pdf')}}"  role="button"><i class="fa fa-file-pdf-o fa-2x"></i><span>{{ trans(\Config::get('app.theme').'-app.subastas.condicionesgenerales') }}</span></a>
                        @endif
                        @if( $data['sub_data']->uppreciorealizado == 'S')
                            <a style="text-transform: uppercase;" target="_blank" class="cat-link" href="/files/<?=$file_code?>_pre_es.pdf"  role="button"><i class="fa fa-file-pdf-o fa-2x"></i> <span>{{ trans(\Config::get('app.theme').'-app.subastas.condicionesespecificas') }}</span></a>
                        @endif
                        @if( $data['sub_data']->upcatalogo == 'S')
                            <a style="text-transform: uppercase;" target="_blank" class="cat-link" href="/files/<?=$file_code?>_cat_es.pdf"  role="button"><i class="fa fa-file-pdf-o fa-2x"></i> <span>{{ trans(\Config::get('app.theme').'-app.subastas.pdf_catalog') }}</span></a>
                        @endif
                    @endif
                    @if(Config::get('app.locale') =='en')
                        @if( $data['sub_data']->upmanualuso_lang == 'S')
                        <a style="text-transform: uppercase;" target="_blank" class="cat-link " href="{{url('/files/'.$file_code.'_man_en.pdf')}}"  role="button"><i class="fa fa-file-pdf-o fa-2x"></i><span>{{ trans(\Config::get('app.theme').'-app.subastas.condicionesgenerales') }}</span></a>
                        @endif
                        @if( $data['sub_data']->uppreciorealizado_lang == 'S')
                            <a style="text-transform: uppercase;" target="_blank" class="cat-link" href="/files/<?=$file_code?>_pre_en.pdf"  role="button"><i class="fa fa-file-pdf-o fa-2x"></i> <span>{{ trans(\Config::get('app.theme').'-app.subastas.condicionesespecificas') }}</span></a>
                        @endif
                        @if( $data['sub_data']->upcatalogo_lang == 'S')
                            <a style="text-transform: uppercase;" target="_blank" class="cat-link" href="/files/<?=$file_code?>_cat_en.pdf"  role="button"><i class="fa fa-file-pdf-o fa-2x"></i> <span>{{ trans(\Config::get('app.theme').'-app.subastas.pdf_catalog') }}</span></a>
                        @endif
					@endif
					@foreach ($aucFiles as $aucFile)
				 	<a style="text-transform: uppercase;" target="_blank" class="cat-link" href="{{$aucFile->url}}"  role="button"><i class="fa fa-2x fa fa-youtube-play"></i> <span>{{ $aucFile->description }}</span></a>
					@endforeach
                </div>

            </div>
        </div>
    </div>
</section>
<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-12 title-head-grid">
            <div class="col-xs-5 col-sm-3 col-md-3 col-lg-3">
               <?php //Si quieren mostrar nombre de la subasta o que se vea texto Lotes ?>
                @if(empty($data['subastas']))
                    <h1 class="titlePage-custom"> {{$data['name']}} </h1>
                @endif
            </div>
            <div class="col-xs-7 col-sm-5 col-md-4 col-lg-5 lot-count">
                @if(!empty( $data['subastas']) && $data['subastas'][0]->tipo_sub == 'W'  && ($data['subastas'][0]->subc_sub == 'A' ||$data['subastas'][0]->subc_sub == 'S' )  && strtotime($data['subastas'][0]->start_session) > time())
                    <div  class="text-right timeLeft">
                        <span data-countdown="{{ strtotime($data['subastas'][0]->start_session) - getdate()[0] }}"  data-format="<?= \Tools::down_timer($data['subastas'][0]->start_session); ?>" data-closed="{{ $data['subastas'][0]->cerrado_asigl0 }}" class="timer"></span>
                        <span class="clock"></span>
                    </div>
                @endif
            </div>
            <div class="col-xs-9 col-sm-4 col-md-3 col-lg-3 refresh text-right">
                <?php // si es uan subasta w y abierta o si es uan subasta tipo O o P ?>
                @if(!empty( $data['subastas']) && ( ($data['subastas'][0]->tipo_sub == 'W' && $data['subastas'][0]->subabierta_sub == 'S') || $data['subastas'][0]->tipo_sub == 'P'  || $data['subastas'][0]->tipo_sub == 'O' )  && ($data['subastas'][0]->subc_sub == 'A' ||$data['subastas'][0]->subc_sub == 'S' )  )

                    <a href=""> {{ trans(\Config::get('app.theme').'-app.lot_list.refresh_prices') }} <i class="fa fa-refresh" aria-hidden="true"></i></a>

                @endif
                 @if(!empty($data['sub_data']) && !empty($data['sub_data']->opcioncar_sub && !empty($data['subastas'][0])) && $data['sub_data']->opcioncar_sub == 'S' && strtotime($data['subastas'][0]->start_session) > time())
                    @if(Session::has('user'))
                       <i class="fa fa-gavel  fa-1x"></i> <a href="{{ \Routing::slug('user/panel/modification-orders') }}?sub={{$data['sub_data']->cod_sub}}" ><?= trans(\Config::get('app.theme').'-app.lot_list.ver_ofertas') ?></a>
                    @endif
                @endif

            </div>
            <div class="col-xs-3 col-md-2 col-lg-1 lot-grid text-right hidden-xs hidden-sm">

                <a id="large_square" href="javascript:;"><i class="fa fa-th-list fa-lg"></i></a>
                <a id="square" href="javascript:;"><i class="fa fa fa-th-large fa-lg"></i></a>

            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-4 col-md-3">
               @include('includes.subasta_filters')
        </div>
        <div class="col-xs-12 col-sm-8 col-md-9 lot-grid text-right hidden-md hidden-lg">

            <a id="large_square_mobile" href="javascript:;"><i class="fa fa-th-list fa-lg"></i></a>

        </div>
        <div class="col-xs-12 col-sm-8 col-md-9">

            <div class="list_lot">


                @foreach ($data['subastas'] as $key => $item)
                    <?php
                        $url = "";
                        //Si las facturas están devueltas no mostrará enlace
                        if($item->fac_hces1 != 'D' && $item->fac_hces1 != 'R'){
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

                        $winner = "";
                        //si el usuario actual es el
                        if(isset($data['js_item']['user']) && count($item->ordenes) > 0 && head($item->ordenes)->cod_licit == $data['js_item']['user']['cod_licit']){
                            $winner = "winner";
                        }
                        //si hay usuario conectado pero no es el ganador.
                        elseif(isset($data['js_item']['user'])){
                            $winner = "no_winner";
                        }


                        $class_square = 'col-xs-12 col-sm-6 col-lg-4';
                    ?>
                    @include('includes.lotlist')
                    <?php
                        $class_square = 'col-lg-12';
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
      .attr(style, "height:450px;width:450px;z-index:9999999;")
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
