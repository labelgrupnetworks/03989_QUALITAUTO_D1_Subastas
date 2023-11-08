@php
    $inf_subasta = new \App\Models\Subasta();
    if (!empty($data['sub_data'])) {
        $inf_subasta->cod = $data['sub_data']->cod_sub;
    } else {
        $inf_subasta->cod = $data['cod_sub'];
    }
    $ficha_subasta = $inf_subasta->getInfSubasta();
@endphp

@include('includes.img_auction_filters')
@include('includes.subasta_filters')
@include('includes.info_auction_filters')
