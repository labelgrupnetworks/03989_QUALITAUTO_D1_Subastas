@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop


@section('content')
 <?php

	use App\Models\Cookies;
    $styleLotSeeConfiguration = (new Cookies())->getLotConfiguration();

	$typeSub = '';
    if(empty($data['type']) && !empty($data['sub_data']) ){
        $sub_data = $data['sub_data'];
        $url_subasta=\Routing::translateSeo('info-subasta').$sub_data->cod_sub."-".str_slug($sub_data->des_sub);
        $bread = array();
        $bread[] = array("url" =>$url_subasta, "name" =>$sub_data->des_sub  );
        $bread[] = array( "name" =>"Lotes" );
        $typeSub = $data['sub_data']->tipo_sub;
    }elseif(!empty($data['seo']->webname)){

        $bread = array();
        if(!empty($data['seo']->subcategory)){
            $bread[] = array("url" =>$data['seo']->url, "name" =>$data['seo']->webname  );
            $bread[] = array( "name" =>$data['seo']->subcategory  );
        }else{
            $bread[] = array( "name" =>$data['seo']->webname  );
        }
    }

    /*
    $sub_data = $data['sub_data'];
    $url_subasta=\Routing::translateSeo('info-subasta').$sub_data->cod_sub."-".str_slug($sub_data->des_sub);
    $url_lotes=\Routing::translateSeo('subasta').$data['cod_sub']."-".str_slug($data['sub_data']->des_sub)."-".$data['id_auc_sessions'];
    $bread = array();
    $bread[] = array("url" =>$url_lotes, "name" =>$sub_data->des_sub  );
    $bread[] = array( "name" =>"Lotes" );
    */
    ?>

        <section class="all-aution-title title-content pb-1">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 h1-titl text-center">
                            @if(!empty($typeSub) && $typeSub == 'O')
                                <h1 class="page-title mb-0">{{ trans(\Config::get('app.theme').'-app.subastas.auctions') }}</h1>
                                {{-- <p class="mt-1 mb-1 page-description">{{ trans(\Config::get('app.theme').'-app.subastas.subtitle') }} --}}
                            @elseif(!empty($typeSub) && $typeSub == 'V')
                                <h1 class="page-title mb-0">{{ trans(\Config::get('app.theme').'-app.foot.direct_sale') }}</h1>
                                {{-- <p class="mt-1 mb-1 page-description">{{ trans(\Config::get('app.theme').'-app.subastas.subtitle_direct_sale') }} --}}
                            @endif
                        </div>
                    </div>
                </div>
            </section>
            <section class="hide">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12">
                                @include('includes.breadcrumb')
                        </div>
                    </div>
                </div>
            </section>

	<input type="hidden" name="lot_see_configuration" value="{{ $styleLotSeeConfiguration }}">

    @include('content.subasta')


<script>
var menuItems = $('.menu-principal-content').find('li')

menuItems.each(function () {
    $(this).find('a').removeClass('color-brand')
})

menuItems = $('.nav-item');

menuItems.each(function () {

    if (this.innerHTML == "{!! trans(\Config::get('app.theme').'-app.foot.online_auction')!!}" && "{!!$typeSub !!}" == "O") {
        $(this).addClass('color-brand');
    }
    else if (this.innerHTML == "{!! trans(\Config::get('app.theme').'-app.foot.direct_sale')!!}"  && "{!!$typeSub !!}" == "V") {
        $(this).addClass('color-brand');
    }
})
</script>
@stop

