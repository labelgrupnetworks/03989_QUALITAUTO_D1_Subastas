@php
    use App\Models\V5\FgAsigl0;
    #lotes de este artista
    $lotsTmp = FgAsigl0::select(' NUM_HCES1, LIN_HCES1, REF_ASIGL0, COD_SUB, "reference" ,WEBFRIEND_HCES1, DESCWEB_HCES1 ,"id_auc_sessions", "name" ')
        ->activeLotAsigl0()
        #join para saber el artista
        ->join('FGCARACTERISTICAS_HCES1 AUTOR', 'AUTOR.EMP_CARACTERISTICAS_HCES1 = FGASIGL0.EMP_ASIGL0 AND AUTOR.NUMHCES_CARACTERISTICAS_HCES1 = FGASIGL0.NUMHCES_ASIGL0 AND AUTOR.LINHCES_CARACTERISTICAS_HCES1 = FGASIGL0.LINHCES_ASIGL0')

        ->wherein('TIPO_SUB', ['E', 'F'])
        #usar solo el id que corresponde a artistas
        ->where('AUTOR.IDCAR_CARACTERISTICAS_HCES1', \Config::get('app.ArtistCode'))
        ->where('AUTOR.IDVALUE_CARACTERISTICAS_HCES1', request('artistaFondoGaleria'))
        #PARA QUE APAREZCA EN EL FONDO DE GALERIA DEBE SER NECESARIO QUE SE PONGA QUE SE PUEDE COMPRAR
        ->where('COMPRA_ASIGL0', 'S')
        #DEBE TENER STOCK PARA APARECER EN EL LISTADO DEL ARTISTA EN FONDO DE GALERIA
        ->where('STOCK_HCES1', '>=', '1')
        #ORDENAMOS POR DESTACADO DESC PARA QUE PONGA PRIMERO EL DESTACADO SI EXISTE, SI NO, COJERÁ EL QUE TENGA LA REFERENCIA MÁS PEQUEÑA
        ->orderby('DESTACADO_ASIGL0 desc, REF_ASIGL0')
        ->get();

    $previous = '';
    $next = '';
    $seleccionado = false;

    foreach ($lotsTmp as $key => $lotTmp) {
        #el orden de las condiciones es importante
        #si ya ha salido el seleccionado es que este es el siguinete
        if ($seleccionado) {
            $next = Tools::url_lot($lotTmp->cod_sub, $lotTmp->id_auc_sessions, $lotTmp->name, $lotTmp->ref_asigl0, $lotTmp->num_hces1, $lotTmp->webfriend_hces1, $lotTmp->descweb_hces1) . '?artistaFondoGaleria=' . request('artistaFondoGaleria');
            break;
        }

        if ($lotTmp->cod_sub == $lote_actual->cod_sub && $lotTmp->ref_asigl0 == $lote_actual->ref_asigl0) {
            $seleccionado = true;
        }
        #mientras no lleguemso al seleccionado vamos poniendo el actual como anterior
        if (!$seleccionado) {
            $previous = Tools::url_lot($lotTmp->cod_sub, $lotTmp->id_auc_sessions, $lotTmp->name, $lotTmp->ref_asigl0, $lotTmp->num_hces1, $lotTmp->webfriend_hces1, $lotTmp->descweb_hces1) . '?artistaFondoGaleria=' . request('artistaFondoGaleria');
        }
    }

@endphp

<div class="prev-next-buttons">

    @if (!empty($previous))
        <a class="swiper-button-prev" title="{{ trans("$theme-app.subastas.last") }}" href="{{ $previous }}">
        </a>
    @endif

    @if (!empty($next))
        <a class="swiper-button-next" title="{{ trans("$theme-app.subastas.next") }}" href="{{ $next }}">
        </a>
    @endif

</div>
