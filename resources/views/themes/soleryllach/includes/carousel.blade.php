<?php

$url = '';
//Si no esta retirado tendrá enlaces
if ($bann->retirado_asigl0 == 'N') {
    $url_friendly = str_slug($bann->webfriend_hces1);
    $url_friendly = \Routing::translateSeo('lote') . $bann->sub_asigl0 . '-' . str_slug($bann->name) . '-' . $bann->id_auc_sessions . '/' . $bann->ref_asigl0 . '-' . $bann->num_hces1 . '-' . $url_friendly;
    $url = "href='$url_friendly'";
}
$titulo = '';
if (\Config::get('app.ref_asigl0') && \Config::get('app.titulo_hces1')) {
    $titulo = "$bann->ref_asigl0  -  $bann->titulo_hces1";
} elseif (!\Config::get('app.ref_asigl0') && \Config::get('app.titulo_hces1')) {
    $titulo = $bann->titulo_hces1;
} elseif (\Config::get('app.ref_asigl0')) {
    $titulo = trans(\Config::get('app.theme') . '-app.lot.lot-name') . ' ' . $bann->ref_asigl0;
}

$subastaModel = new \App\Models\Subasta();
$subastaModel->cod = $bann->sub_asigl0;
$subastaModel->ref = $bann->ref_asigl0;
$ordenes = $subastaModel->getOrdenes();
$max_puja = $subastaModel->price_open_auction($bann->impsalhces_asigl0, $ordenes);
?>
<div>
    <div class="item_home">
        <a title="{{ $titulo }}" <?= $url ?>>
            <div>
                <div class="item_img">
                    <img src="{{ Tools::url_img('lote_medium', $bann->num_hces1, $bann->lin_hces1) }}"
                        alt="{{ $titulo }}">
                    @if ($bann->retirado_asigl0 != 'N')
                        <div class="retired">{{ trans(\Config::get('app.theme') . '-app.lot.retired') }}</div>
                    @elseif(\Config::get('app.awarded') && $bann->cerrado_asigl0 == 'S' && !empty($bann->max_puja))
                        <div class="retired" style ="background:#777777;text-transform: lowercase;">
                            {{ trans(\Config::get('app.theme') . '-app.subastas.buy') }}
                        </div>
                    @endif
                </div>
            </div>
        </a>
        @if (!empty($titulo))
            <div class="title_item">
                <a title="{{ $titulo }}" <?= $url ?>>
                    <h4>{{ $titulo }}</h4>
                </a>
            </div>
        @endif
        <div class="title_auction text-color">{{ $bann->name }}</div>
        @if (\Config::get('app.descweb_hces1') || \Config::get('app.desc_hces1'))
            <div class="desc_lot">
                @if (\Config::get('app.descweb_hces1'))
                    <?= $bann->descweb_hces1 ?>
                @elseif (\Config::get('app.desc_hces1'))
                    <?= $bann->desc_hces1 ?>
                @endif
            </div>
        @endif

        <div class="data-price">

            @if ($bann->retirado_asigl0 == 'N')
                @if (\Config::get('app.estimacion') || \Config::get('app.impsalhces_asigl0'))
                    <p class="salida">
                        @if (\Config::get('app.estimacion'))
                            {{ trans(\Config::get('app.theme') . '-app.lot.estimate') }} <span class="">
                                {{ $bann->imptas_asigl0 }} - {{ $bann->imptash_asigl0 }}
                                {{ trans(\Config::get('app.theme') . '-app.subastas.euros') }}</span>
                        @elseif(\Config::get('app.impsalhces_asigl0'))
                            {{ trans(\Config::get('app.theme') . '-app.lot.lot-price') }}

                            @if ($bann->cerrado_asigl0 == 'S' && !empty($bann->impsalweb_asigl0))
                                <span class="">{{ $bann->impsalweb_asigl0 }}
                                    {{ trans(\Config::get('app.theme') . '-app.subastas.euros') }}</span>
                            @else
                                @if ($bann->impsalhces_asigl0 == 0)
                                    {{ trans(\Config::get('app.theme') . '-app.lot.free') }}
                                @else
                                    <span class=""> {{ $bann->impsalhces_asigl0 }}
                                        {{ trans(\Config::get('app.theme') . '-app.subastas.euros') }}</span>
                                @endif

                            @endif

                        @endif
                    </p>
                @endif

                <p class="salida">
                    @if (\Config::get('app.awarded'))
                        @if ($bann->cerrado_asigl0 == 'D')
                            {{ trans(\Config::get('app.theme') . '-app.subastas.dont_available') }}
                        @elseif($bann->cerrado_asigl0 == 'S' && !empty($bann->max_puja) && $bann->remate_asigl0 == 'S')
                            {{ trans(\Config::get('app.theme') . '-app.subastas.buy_to') }}:
                            <span>{{ \Tools::moneyFormat($bann->max_puja) }}
                                {{ trans(\Config::get('app.theme') . '-app.subastas.euros') }}</span>
                        @elseif($bann->cerrado_asigl0 == 'S' && !empty($bann->max_puja) && $bann->remate_asigl0 != 'S')
                            {{ trans(\Config::get('app.theme') . '-app.subastas.buy') }}
                        @elseif($bann->cerrado_asigl0 == 'S' && empty($bann->max_puja))
                            {{ trans(\Config::get('app.theme') . '-app.subastas.dont_buy') }}
                        @elseif($bann->cerrado_asigl0 == 'N' && $bann->tipo_sub == 'W' && !empty($max_puja))
                            {{ trans("$theme-app.home.puja_actual") }}
                            @php
                                $pos = strpos($max_puja, '.');
                                if ($pos === false) {
                                    $max_puja = Tools::moneyFormat($max_puja);
                                }
                            @endphp
                            {{ $max_puja }} €
                        @endif
                    @endif
                </p>

                @if (($bann->tipo_sub == 'P' || $bann->tipo_sub == 'O') && $bann->cerrado_asigl0 == 'N')
                    <p class="salida text-center">
                        <i class="fa fa-clock-o"></i>
                        <span data-countdown="{{ strtotime($bann->close_at) - getdate()[0] }}"
                            data-format="<?= \Tools::down_timer($bann->close_at) ?>" class="timer">
                        </span>
                    </p>
                @endif
            @endif
        </div>
    </div>
</div>
