<?php
    if (!isset($getValue)) {$getValue = array();}
?>

<div class="filters" style="@if(!empty(request('section')))display:block;@endif">
    <div  class="filters-wrapper sidebar_lot">

        <div class="filter-section">
            <div class="filter-section-head">
                <h4>{{ trans($theme.'-app.lot_list.search_placeholder') }}</h4>
            </div>
            <div class="filter-section-checks">
                <div class="input-search-text">
                            <input type="text" class="form-control text search-text"  placeholder="{{ trans($theme.'-app.lot_list.search_placeholder') }}" name="description" id="filterDescription" value="{{ app('request')->input('description') }}">
                        </div>
            </div>
            <div class="btn-filt">
                <button class="btn btn-filter btn-color" type="submit" id="filterDescriptionButton">{{ trans($theme.'-app.lot_list.search') }}</button>
            </div>
        </div>



         @if(count($data['categories']) > 0 )

 <div id="collapseTwo" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingTwo">

     <label class="filter-section-head"><h4>{{ trans($theme.'-app.lot_list.categories') }}  </h4></label>
     <div class="filter-section-checks">
        <ul style="width:100%">

            @foreach($data['categories'] as $k => $category)
           <?php

                if($data['category'] == $category->key_ortsec0){
                    $count_all_subcategories = $category->cuantos;
                }
                unset($_GET['subperiodo']);
           ?>
            <li style="width:100%">
                @if(!empty( $data['category']) && $category->key_ortsec0 == $data['category'])
					<a href="<?= \Routing::translateSeo('subastas') ?><?= $category->key_ortsec0 ?>{{\Tools::generateUrlGet($getValue)}}" class="gold">
						<span class="checkedafter" style="position: relative;"></span>
						<span style="padding-left:10px">{{ $category->des_ortsec0 }} ({{$category->cuantos}})</span>
					</a>
				@else
					<a href="<?= \Routing::translateSeo('subastas') ?><?= $category->key_ortsec0 ?>{{\Tools::generateUrlGet($getValue)}}">
						<span class="checkboxafter" style="position: relative;"></span>
						<span style="padding-left:10px">{{ $category->des_ortsec0 }} ({{$category->cuantos}})</span>
					</a>
                @endif


                @if(!empty( $data['category']) && $category->lin_ortsec1 != 15 && $category->key_ortsec0 == $data['category'])
                    @if (!empty($data['periodos']))
                        <div class="period">
							<ul>
                            	@foreach($data['periodos'] as $k => $item)
									<?php $getValue['subperiodo'] = $item->subperiod_1; ?>
									<li>
										<small>
											<a href="<?= \Routing::translateSeo('subastas') ?><?= $category->key_ortsec0 ?>{{\Tools::generateUrlGet($getValue)}}">
												<input type="checkbox" id="vehicle1" name="vehicle1" value="Bike" class="checkboxafter" style="position: relative;">
												@if (isset($data['periodoSeleccionado']) && $k == \Tools::Seo_Url($data['periodoSeleccionado']->subperiod_1))
													<span class="checkedafter" style="position: relative;"></span>
													<span class="gold" style="padding-left: 10px">{{ $item->subperiod_1 }} ({{$data['periodosCount'][$k]}})</span>
												@else
													<span class="checkboxafter" style="position: relative;"></span>
													<span style="padding-left: 10px">{{ $item->subperiod_1 }} ({{$data['periodosCount'][$k]}})</span>
												@endif
											</a>
										</small>
									</li>
									<?php unset($getValue['subperiodo']);?>
								@endforeach
							</ul>
                        </div>

                    @endif

                @endif
            </li>

           @endforeach
        </ul>
     </div>
   </div>
 @endif

 @if(!empty($data['sub_data']) && strtotime($data['sub_data']->start) < time())
        <div class="filter-section">
            <div class="filter-section-head">
                <h4>{{ trans($theme.'-app.lot_list.status_lots') }}</h4>
            </div>
            @if(empty($data['sub_data']) || $data['sub_data']->tipo_sub != 'V')

                <div class="filter-section-checks">
                    <ul>
                        <li>
                            <input type="checkbox" id="open" name="open" class="filled-in" onclick="javascript:checkfilter('open')" <?= !empty(app('request')->input('open'))? "checked='checked'" : "" ?>>

                            <label for="open">{{ trans($theme.'-app.lot_list.progress_lots') }}</label>
                        </li>
                        <li>
                            <input type="checkbox" id="no_award" name="no_award" class="filled-in " onclick="javascript:checkfilter('no_award')" <?= !empty(app('request')->input('no_award'))? "checked='checked'" : "" ?>>
                            <label for="no_award">{{ trans($theme.'-app.lot_list.dont_sold_lots') }}</label>
                        </li>
                        <li>
                            <input type="checkbox" id="award" name="award" class="filled-in" onclick="javascript:checkfilter('award')" <?= !empty(app('request')->input('award'))? "checked='checked'" : "" ?>>
                            <label for="award">{{ trans($theme.'-app.lot_list.sold_lots') }}</label>
                        </li>

                    </ul>
                </div>

            @else
            <div class="filter-section-checks">
                <ul>
                    <li>
                        <input type="checkbox" id="open" name="open" class="filled-in" onclick="javascript:checkfilter('open')" <?= !empty(app('request')->input('open'))? "checked='checked'" : "" ?>>
                        <label for="open">{{ trans($theme.'-app.lot_list.dont_sold_lots') }}</label>
                    </li>
                    <li>
                        <input type="checkbox" id="exclusive_offers" name="offers" value='2' class="filled-in " onclick="javascript:checkfilter('exclusive_offers')" <?= !empty(app('request')->input('offers') && app('request')->input('offers') == 2)? "checked='checked'" : "" ?>>
                        <label for="exclusive_offers">{{ trans($theme.'-app.lot_list.exclusive_offers') }}</label>
                    </li>
                    <li>
                        <input type="checkbox" id="discounts" name="offers" value='1' class="filled-in" onclick="javascript:checkfilter('discounts')" <?= !empty(app('request')->input('offers') && app('request')->input('offers') == 1)? "checked='checked'" : "" ?>>
                        <label for="discounts">{{ trans($theme.'-app.lot_list.discounts') }}</label>
                    </li>
                </ul>
            </div>
            @endif
        </div>
 @endif
    @if(Session::has('user'))
         <div class="filter-section">
            <div class="filter-section-head">
                <h4>{{ trans($theme.'-app.lot_list.my_lots') }}</h4>
            </div>

            <div class="filter-section-checks">
                <ul>
                    <li>
                        <input type="checkbox" id="my_lots_property" name="my_lots_property" class="filled-in " onclick="javascript:checkfilter('my_lots_property');" <?= !empty(app('request')->input('my_lots_property'))? "checked='checked'" : "" ?>>
                        <label for="my_lots_property">{{ trans($theme.'-app.lot_list.my_lots_property') }}</label>
                    </li>
                    <li>
                        <input type="checkbox" id="my_lots_client" name="my_lots_client" class="filled-in" onclick="javascript:checkfilter('my_lots_client');" <?= !empty(app('request')->input('my_lots_client'))? "checked='checked'" : "" ?>>
                        <label for="my_lots_client">{{ trans($theme.'-app.lot_list.my_lots_clients') }}</label>
                    </li>
                </ul>
            </div>
          </div>
    @endif
 <!--
              <div id="select_filters">
                    @include('includes.select_filters')
              </div>
              <div class="buttons-filt flex">
                <div class="btn-filt">
                  <button class="btn btn-filter btn-color" type="submit">{{ trans($theme.'-app.lot_list.filter') }}</button>

                </div>
                <div class="btn-filt">
                  <a class="btn btn-filter btn-danger" style="border-radius: 0" href="{{ $data['url'] }}<?= !empty(app('request')->input('s'))? "?s=" . app('request')->input('s'):"" ?> ">{{ trans($theme.'-app.lot_list.reset_filter') }}</a>

                </div>
              </div>
-->
        <?php
                $indices = App\Models\Amedida::indice($data['cod_sub'], $data['id_auc_sessions']);

        ?>

        @if(!isset($in_indice_subasta) || $in_indice_subasta == false)

            <?php
                $indices = App\Models\Amedida::indice($data['cod_sub'], $data['id_auc_sessions']);
            ?>

            @if(!empty($indices))
                <div class="block_filters text">
                    <label class="filter-section-head" for="input_description">{{ trans($theme.'-app.lot_list.indice_auction') }}</label>
                    <div class="text-center">
                          <a title="{{ trans($theme.'-app.lot_list.open_indice') }}" href="{{$data['url_indice']}}" class="btn btn-filter listaIndice btn-color" >{{ trans($theme.'-app.lot_list.open_indice') }}</a>
                    </div>
                </div>

                <?php // hacer que los filtros funcionen con el indice
                    /*
                        @if (app('request')->input('first_lot'))
                            <input type='hidden' name="first_lot" value="{{app('request')->input('first_lot')}}">
                        @endif
                        @if (app('request')->input('last_lot'))
                            <input type='hidden' name="last_lot" value="{{app('request')->input('last_lot')}}">
                        @endif
                    */
                ?>
            @endif
        @endif
        </form>

    </div>
</div>


 <script>
        @if(!empty($data["type"]) && $data["type"] == "category")
        $("#category").change(function(){
            //borrar los names de los selectores para que no se envien al cambiar de categoria
            $("[name$='_select']").attr("name","");
            $("#form_lotlist").attr("action","<?= \Routing::translateSeo($data['route_customize']) ?>"+$(this).val());
            $("#form_lotlist").submit();
        });


        $("#subcategory").change(function(){
            //borrar los names de los selectores para que no se envien al cambiar de categoria
            $("[name$='_select']").attr("name","");
            console.log(categs_key[$(this).val()]);
            var value_categ = categs_key[$(this).val()];

            if(typeof value_categ === 'undefined'){
                 $("#form_lotlist").attr("action","<?= \Routing::translateSeo($data['route_customize']).\Request::segment(3) ?>");
            }else{
                 $("#form_lotlist").attr("action","<?= \Routing::translateSeo($data['route_customize']) ?>"+ value_categ + "/" + $(this).val());

            }

           $("#form_lotlist").submit();
        });
        @endif

        function checkfilter(check_name)   {

            if(check_name == 'open'){
                $("#award").attr("checked",false);
                $("#no_award").attr("checked",false);
            }
            if(check_name == 'award'){
                $("#no_award").attr("checked",false);
                $("#open").attr("checked",false);
            }
            if(check_name != 'exclusive_offers'){
                $("#exclusive_offers").attr("checked",false);
            }
            if(check_name == 'no_award'){
                $("#award").attr("checked",false);
                $("#open").attr("checked",false);
            }
            if(check_name != 'discounts'){
                $("#discounts").attr("checked",false);
            }
            if(check_name == 'my_lots_property'){
                $("#my_lots_client").attr("checked",false)
            }
            if(check_name == 'my_lots_client'){
                $("#my_lots_property").attr("checked",false);
            }

            $("#form_lotlist").submit();
        }

</script>







