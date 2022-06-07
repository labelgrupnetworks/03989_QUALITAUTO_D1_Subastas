@php
$img = Tools::url_img('lote_medium', $item->num_hces1, $item->lin_hces1);
@endphp


<div class="{{ $class_square }} square" {!! $codeScrollBack !!}>
    <div id="{{ $item->sub_asigl0 }}-{{ $item->ref_asigl0 }}" style="position: absolute;top:-180px"></div>
    <a title="{{ $titulo }}" class="lote-destacado-link secondary-color-text" {{-- < ?= $url?> --}}>

        {{-- las etiquetas van a parta para simplificar el código --}}
        <div class="item_lot item_lot_venta_destacada">
            <div class="item_img">
                <img class="img-responsive " src="{{ $img }}" alt="{{ $titulo }}">
            </div>



            <div class="data-container">
                <div class="item-info-lot-venta-destacada">
                    <div class="title_item">
                        <span class="seo_h4" style="text-align: center;">{{ $titulo }}</span>
                    </div>

                    {{-- indice de caracteristicas
						1 - Autor
						2 - Técnica
						3 - Medidas
						4 - Fechas del autor --}}
                    <p class="item-autor">{{ $caracteristicas[1]->value_caracteristicas_hces1 ?? '' }}</p>
                    <p class="item-titulo">{!! $item->descweb_hces1 !!}</p>
                    <p class="item-tecnica">{{ $caracteristicas[2]->value_caracteristicas_hces1 ?? '' }}</p>
                    <p class="item-medidas">{{ $caracteristicas[3]->value_caracteristicas_hces1 ?? '' }}</p>

                </div>

                <div class="data-ventas-destacadas">
                    <p>
                        <strong>{{ trans("$theme-app.lot_list.lot-price") }}</strong>
                        {{ \Tools::moneyFormat($item->impsalhces_asigl0, false, 0) }}
                        {{ trans("$theme-app.subastas.euros") }}
                    </p>
                    <p>
                        <strong>{{ trans("$theme-app.lot_list.lot-selled-price") }}</strong>
                        {{ \Tools::moneyFormat($item->max_puja, false, 0) }}
                        {{ trans("$theme-app.subastas.euros") }}
                    </p>
                    <p>
                        <strong>{{ trans("$theme-app.lot_list.lot-owner-auction") }}{{ $item->sub_asigl0 }}</strong>
                    </p>
                </div>

            </div>

        </div>
    </a>

</div>
