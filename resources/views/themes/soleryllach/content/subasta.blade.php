<script>
    var cod_sub = '{{ $data['cod_sub'] }}';
    routing.node_url = '{{ Config::get('app.node_url') }}';
    routing.comprar = '{{ $data['node']['comprar'] }}';
    routing.ol = '{{ $data['node']['ol'] }}';
</script>

@php
	use App\Models\V5\AucSessions;
    $inf_subasta = new \App\Models\Subasta();
	$previousAuction = null;
	$nextAuction = null;
    if (!empty($data['sub_data'])) {
        $inf_subasta->cod = $data['sub_data']->cod_sub;
        $inf_subasta->id_auc_sessions = $data['sub_data']->id_auc_sessions;

		$previousAuction = AucSessions::previousReference($data['sub_data']->cod_sub, $data['sub_data']->reference);
		$nextAuction = AucSessions::nextReference($data['sub_data']->cod_sub, $data['sub_data']->reference);

    } else {
        $inf_subasta->cod = $data['cod_sub'];
    }
    $ficha_subasta = $inf_subasta->getInfSubasta();

    //mostrar cuenta atras
    $showCountdown = !empty($data['subastas']) && $data['subastas'][0]->tipo_sub == 'W' && in_array($data['subastas'][0]->subc_sub, ['A', 'S']) && strtotime($data['subastas'][0]->start_session) > time();
@endphp

<div class="container">
    <div class="row">
        <div class="col-xs-12">
			<div class="grid-title-wrapper">
				<h1 class="grid-title">
					@if (!empty($data['subastas']))
						{{ trans("$theme-app.subastas.inf_subasta_subasta") }} {{ $data['subastas'][0]->name }}
					@else
						{{ trans("$theme-app.lot_list.lots") }}
					@endif
				</h1>
				<div class="next">
					@if ($previousAuction)
						<a class="nextLeft" title="{{ trans("$theme-app.lot_list.previous_session") }}"
							href="{{ $previousAuction->url_session }}">
							<i class="fa fa-angle-left fa-angle-custom"></i>
							{{ trans("$theme-app.lot_list.previous_session") }}
						</a>
					@endif
					@if ($nextAuction)
						<a class="nextRight" title="{{ trans("$theme-app.lot_list.next_session") }}"
							href="{{ $nextAuction->url_session }}">
							{{ trans("$theme-app.lot_list.next_session") }}
							<i class="fa fa-angle-right fa-angle-custom"></i>
						</a>
					@endif
				</div>
			</div>
        </div>
    </div>

    @if (!empty($ficha_subasta))
		@include('includes.auction_cover', ['ficha_subasta' => $ficha_subasta, 'urlIndice' => $data['url_indice']])
    @endif

	@include('includes.subasta_top_filters', ['ficha_subasta' => $ficha_subasta, 'urlToForm' => $data['url']])

    <section class="grid-selects">

        <form id="form_lotlist" method="get" action="{{ $data['url'] }}">
            <div class="form-group">

                <select id="order_selected" name="order" class="form-control submit_on_change">
                    <option value="name" @if (app('request')->input('order') == 'name') selected @endif>
                        {{ trans(\Config::get('app.theme') . '-app.lot_list.order') }}:
                        {{ trans(\Config::get('app.theme') . '-app.lot_list.name') }}
                    </option>
                    <option value="price_asc" @if (app('request')->input('order') == 'price_asc') selected @endif>
                        {{ trans(\Config::get('app.theme') . '-app.lot_list.order') }}:
                        {{ trans(\Config::get('app.theme') . '-app.lot_list.price_asc') }}
                    </option>
                    <option value="price_desc" @if (app('request')->input('order') == 'price_desc') selected @endif>
                        {{ trans(\Config::get('app.theme') . '-app.lot_list.order') }}:
                        {{ trans(\Config::get('app.theme') . '-app.lot_list.price_desc') }}
                    </option>
                    <option value="ref" @if (empty(app('request')->input('order')) || app('request')->input('order') == 'ref') selected @endif>
                        {{ trans(\Config::get('app.theme') . '-app.lot_list.order') }}:
                        {{ trans(\Config::get('app.theme') . '-app.lot_list.reference') }}
                    </option>
                    @if (!empty($data['subastas']) && ($data['subastas'][0]->tipo_sub == 'O' || $data['subastas'][0]->tipo_sub == 'P'))
                        <option value="ffin" @if (app('request')->input('order') == 'ffin') selected @endif>
                            {{ trans(\Config::get('app.theme') . '-app.lot_list.order') }}: <b>
                                {{ trans(\Config::get('app.theme') . '-app.lot_list.more_near') }} </b>
                        </option>

                        <option value="mbids" @if (app('request')->input('order') == 'mbids') selected @endif>
                            {{ trans(\Config::get('app.theme') . '-app.lot_list.order') }}: <b>
                                {{ trans(\Config::get('app.theme') . '-app.lot_list.more_bids') }} </b>
                        </option>

                        <option value="hbids" @if (app('request')->input('order') == 'hbids') selected @endif>
                            {{ trans(\Config::get('app.theme') . '-app.lot_list.order') }}: <b>
                                {{ trans(\Config::get('app.theme') . '-app.lot_list.higher_bids') }} </b>
                        </option>

                        <option value="fecalta" @if (app('request')->input('order') == 'fecalta') selected @endif>
                            {{ trans(\Config::get('app.theme') . '-app.lot_list.order') }}:
                            {{ trans(\Config::get('app.theme') . '-app.lot_list.more_recent') }}
                        </option>
                    @endif
                </select>
            </div>
        </form>

        <div class="grid-selects_pagination">
            {!! $data['subastas.paginator'] !!}
        </div>

        <div class="grid-selects_squares hidden-xs">
            <a id="large_square" href="javascript:;"><i class="fa fa-th-list fa-lg"></i></a>
            <a id="square" href="javascript:;"><i class="fa fa fa-th-large fa-lg"></i></a>
            <a id="small_square" href="javascript:;"><i class="fa fa-th fa-lg"></i></a>
        </div>
    </section>

</div>

<div class="container">

	<div class="list_lot row">

		@foreach ($data['subastas'] as $key => $item)
			<?php
			$url = '';
			//Si impsalweb_asigl0 asignamos este como precio de salida
			$precio_salida = $item->impsalweb_asigl0 != 0 ? $item->formatted_impsalweb_asigl0 : $item->formatted_impsalhces_asigl0;

			//Si no esta retirado tendrá enlaces
			if ($item->retirado_asigl0 == 'N' && $item->fac_hces1 != 'D' && $item->fac_hces1 != 'R') {
				$webfriend = !empty($item->webfriend_hces1) ? $item->webfriend_hces1 : str_slug($item->titulo_hces1);
				if ($data['type'] == 'theme') {
					$url_vars = '?theme=' . $data['theme'];
				} else {
					$url_vars = '';
				}
				$url_friendly = \Routing::translateSeo('lote') . $item->cod_sub . '-' . str_slug($item->name) . '-' . $item->id_auc_sessions . '/' . $item->ref_asigl0 . '-' . $item->num_hces1 . '-' . $webfriend . $url_vars;
				$url = "href='$url_friendly'";
			}
			$titulo = '';
			if (\Config::get('app.ref_asigl0') && \Config::get('app.titulo_hces1')) {
				$titulo = "$item->ref_asigl0  -  $item->titulo_hces1";
			} elseif (!\Config::get('app.ref_asigl0') && \Config::get('app.titulo_hces1')) {
				$titulo = $item->titulo_hces1;
			} elseif (\Config::get('app.ref_asigl0')) {
				$titulo = trans(\Config::get('app.theme') . '-app.lot.lot-name') . ' ' . $item->ref_asigl0;
			}

			$precio_venta = null;
			if (!empty($item->himp_csub)) {
				$precio_venta = $item->himp_csub;
			}
			//si es un histórico y la subasta del asigl0 = a la del hces1 es que no está en otra subasta y podemso coger su valor de compra de implic_hces1
			elseif ($item->subc_sub == 'H' && $item->cod_sub == $item->sub_hces1 && $item->lic_hces1 == 'S' and $item->implic_hces1 > 0) {
				$precio_venta = $item->implic_hces1;
			}
			//Si hay precio de venta y  impsalweb_asigl0 contiene valor, mostramos este como precio de venta
			$precio_venta = !empty($precio_venta) && $item->impsalweb_asigl0 != 0 ? $item->impsalweb_asigl0 : $precio_venta;

			$winner = '';
			if ($item->tipo_sub == 'W' && $item->subabierta_sub == 'O') {
				//si el usuario actual es el
				if (isset($data['js_item']['user']) && count($item->ordenes) > 0 && head($item->ordenes)->cod_licit == $data['js_item']['user']['cod_licit']) {
					$winner = 'winner';
				}
				//si hay usuario conectado pero no es el ganador, y hay ordenes
				elseif (isset($data['js_item']['user']) && count($item->ordenes) > 0) {
					$winner = 'no_winner';
				}
			} elseif ($item->tipo_sub == 'P' || $item->tipo_sub == 'O' || $item->subabierta_sub == 'P') {
				if (isset($data['js_item']['user']) && !empty($item->max_puja) && $item->max_puja->cod_licit == $data['js_item']['user']['cod_licit']) {
					$winner = 'winner';
				}
				//si hay usuario conectado pero no es el ganador, y hay ordenes
				elseif (isset($data['js_item']['user']) && !empty($item->max_puja)) {
					$winner = 'no_winner';
				}
			}

			$rarityLot = \App\Models\V5\FgHces1::getRarity()
				->addSelect('nvl(otv_lang."catalog_3_lang",otv."catalog_3") AS CATALOG_3')
				->where([['num_hces1', $item->num_hces1], ['lin_hces1', $item->lin_hces1]])
				->first();
			$rarity = null;
			if ($rarityLot) {
				$rarity = $rarityLot->catalog_3;
			}
			$class_square = 'col-xs-12 col-sm-6 col-lg-4';

			$isFavorite = false;
			if(in_array($item->ref_asigl0, $favorites)) {
				$isFavorite = true;
			}
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

	<div class="d-flex justify-content-center">
		{!! $data['subastas.paginator'] !!}
	</div>

</div>

@if (!empty($data['seo']->meta_content) && $data['subastas.paginator']->currentPage == 1)
    <div class="container category">
        <div class="row">
            <div class="col-lg-12">
                <?= $data['seo']->meta_content ?>
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
                    rectificaY = (windowSize.height + windowSize.scrollTop) - (event.pageY + popupSize.height +
                        10);
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
