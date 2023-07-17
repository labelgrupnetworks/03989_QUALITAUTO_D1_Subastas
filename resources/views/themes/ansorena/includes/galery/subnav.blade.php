@php
    $subObj = new App\Models\V5\FgSub();

    #Cojemos la exposicion/subasta tipo E  activa que empiece antes, si no quieren que aparezca esa que la pongan en histórico
    #Las subastas con opcion carrito son las que ellos llaman online
    $actual = $subObj
        ->select('DES_SUB, COD_SUB')
        ->where('SUBC_SUB', 'S')
        ->where('TIPO_SUB', 'E')
        ->where('OPCIONCAR_SUB', 'N')
        ->orderby('DFEC_SUB')
        ->first();

@endphp
<div class="section-nav d-flex">
    <ul class="ff-highlight">
        @if (!empty($actual))
            <li>
                <a href="{{ Tools::url_exposicion($actual->des_sub, $actual->cod_sub) }}">
                    {{ trans("$theme-app.galery.current_exhibition") }}
                </a>
            </li>
        @endif
        <li>
            <a href="{{ Route('exposiciones') }}?online=N">
                {{ trans("$theme-app.galery.exhibitions") }}
            </a>
        </li>

        <li>
            <a href="{{ Route('exposiciones') }}?online=S">
                {{ trans("$theme-app.galery.online_exhibitions") }}
            </a>
        </li>

        <li>
            <a href="{{ Route('artistasGaleria') }}">
                {{ trans("$theme-app.galery.artists") }}
            </a>
        </li>

        <li>
            <a href="{{ Route('fondoGaleria') }}">
                {{ trans("$theme-app.galery.gallery_collection") }}
            </a>
        </li>
    </ul>
</div>
