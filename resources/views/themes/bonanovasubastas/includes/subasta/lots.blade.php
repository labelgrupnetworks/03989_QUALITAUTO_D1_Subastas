@foreach ($data['subastas'] as $key => $item)
    @php
        $url = '';
        //Si no esta retirado tendrá enlaces

        //Si impsalweb_asigl0 asignamos este como precio de salida
        $precio_salida = $item->impsalweb_asigl0 != 0 ? $item->formatted_impsalweb_asigl0 : $item->formatted_impsalhces_asigl0;

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
            $titulo = trans($theme . '-app.lot.lot-name') . ' ' . $item->ref_asigl0;
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

        if ($item->tipo_sub == 'W' && $item->subabierta_sub == 'O') {
            $winner = '';
            //si el usuario actual es el
            if (isset($data['js_item']['user']) && count($item->ordenes) > 0 && head($item->ordenes)->cod_licit == $data['js_item']['user']['cod_licit']) {
                $winner = 'winner';
            }
            //si hay usuario conectado pero no es el ganador.
            elseif (isset($data['js_item']['user'])) {
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

    @endphp

    @include('includes.subasta.lot')

@endforeach
