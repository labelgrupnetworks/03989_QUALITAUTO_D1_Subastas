<?php 
    $filter = new \App\Models\Filters();
    $type = empty($data["type"])? null: $data["type"];
    $category = empty($data['category'])? NULL : $data['category'];
    $subcategory = empty($data['subcategory'])? NULL : $data['subcategory'];
    $filters = $filter->getAllFilterSelector($type, $data['cod_sub'],$data['id_auc_sessions'], $category, $subcategory);
    
    if(!empty($data["type"])){
        $cod_sub = 0;
    }else{
        $cod_sub =$data['cod_sub'];
    }
    
    $filters_name = $filter->getFiltersDescription($cod_sub);
    $lang = \Tools::getLanguageComplete(Config::get('app.locale'));
    $filters_name_lang = array();
    //si no está el idioma del usuario cargamos el primero
    if(isset($filters_name[$lang])){
        $filters_name_lang = $filters_name[$lang];
    }elseif(!empty($filters_name)){
        $filters_name_lang = head($filters_name);
    }
    
    $filtersforajax="";
?>

<style>
 

@-webkit-keyframes loadingAnimation {
0% {
    -webkit-box-shadow: 0px 0px 0px 0px #abb2b9;
    box-shadow: 0px 0px 0px 0px #abb2b9;
}
100% {
    -webkit-box-shadow: 0px 0px 0px 2rem rgba(255,187,0,0);
    box-shadow: 0px 0px 0px 2rem rgba(255,187,0,0);
}

}
</style>
@foreach($filters as $key_filter => $filter_fields)
    @if(count($filter_fields) > 0)
        <?php 
        //necesitamos enviar los valores de los selects a la funcion ajax
        $filtersforajax.= $key_filter.'_select:$("#'.$key_filter.'_select").val() , ' ?>
        <p class="ti"><?=  !empty($filters_name_lang[$key_filter])? $filters_name_lang[$key_filter] : '' ?>  </p>
           <div id="collapseTwo" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingTwo">    
               <select class="form-control filter_recharge_on_change" id="{{$key_filter}}_select" name="{{$key_filter}}_select" > 
                   <option value="" >{{ trans(\Config::get('app.theme').'-app.lot_list.no_filters') }} </option>                           
                   @foreach($filter_fields as $field=> $count_field)               
                        <option value="{{$field}}"  <?= app('request')->input($key_filter.'_select') == $field ? "selected='selected'" : "" ?> >{{ $field }} ({{$count_field}}) </option>
                   @endforeach
               </select>
           </div>
    @endif
@endforeach
 

<script>
    $(".filter_recharge_on_change").on('change', function(){
        var loadd = '<div class="loadd" style="background: rgba( 86, 101, 115 , .6);position: absolute;width: 100%;height: 100%;top: 0;left: 0;z-index: 5;display: -webkit-box;display: -ms-flexbox;display: flex;-webkit-box-align: center;-ms-flex-align: center;align-items: center;-webkit-box-pack: center;-ms-flex-pack: center;justify-content: center;"><div style="text-align: center;display: inline-block;color: transparent !important;border-radius: 4rem;cursor: pointer;-webkit-transition: all 0.4s cubic-bezier(0.9, 0.1, 0.1, 0.9);-o-transition: all 0.4s cubic-bezier(0.9, 0.1, 0.1, 0.9);transition: all 0.4s cubic-bezier(0.9, 0.1, 0.1, 0.9);width: 30px !important;-webkit-animation: loadingAnimation 2s 0.48s infinite;opacity:.7;animation: loadingAnimation 2s 0.48s infinite;border-radius: 50% !important;height: 30px;background: #555;z-index: 10000;margin: 0 auto;"></div></div>'
        $('.sidebar_lot').css('position', 'relative')
        $('.sidebar_lot').append(loadd);   
            $.ajax({
                type: "GET",
                url: "/{{Config::get('app.locale')}}/rechargefilters",
                data:{<?= $filtersforajax; ?>type:'{{$type}}',cod_sub:'{{$data['cod_sub']}}', id_auc_sessions: '{{$data['id_auc_sessions']}}', category: '{{$category}}', subcategory: '{{$subcategory}}' },
                beforeSend: function () {
                        
                },
                success: function( html ) {
                    $("#select_filters").html(html);
                    $('.sidebar_lot').find('.loadd').remove()

                }
            });

    });
    $('#form_lotlist').submit(function(e){
        var loadd = '<div class="loadd" style="background: transparent;position: absolute;width: 100%;height: 100%;top: 0;left: 0;z-index: 5;display: -webkit-box;display: -ms-flexbox;display: flex;-webkit-box-align: center;-ms-flex-align: center;align-items: flex-start;-webkit-box-pack: center;-ms-flex-pack: center;justify-content: center;"><div style="text-align: center;display: inline-block;color: transparent !important;border-radius: 4rem;cursor: pointer;-webkit-transition: all 0.4s cubic-bezier(0.9, 0.1, 0.1, 0.9);-o-transition: all 0.4s cubic-bezier(0.9, 0.1, 0.1, 0.9);transition: all 0.4s cubic-bezier(0.9, 0.1, 0.1, 0.9);width: 30px !important;-webkit-animation: loadingAnimation 2s 0.48s infinite;opacity:.7;animation: loadingAnimation 1s 0.48s infinite;border-radius: 50% !important;height: 30px;background: #555;z-index: 10000;margin: 0 auto;position:relative; top: 50vh;position:fixed;"></div></div>'
        $('.list_lot').parent().css('position', 'relative');
        $('.list_lot').parent().css('height', '64vh');
        $('.pagination').hide();
        $('.list_lot').fadeOut();  
        $('.list_lot').parent().append(loadd);   
    
    })
 </script>
