

<div class="auction__filters-categories">
        <div class="auction__filters-collapse d-flex align-items-center justify-content-space-between" role="button" data-toggle="collapse" href="#auction_categories" aria-expanded="true" aria-controls="auction_categories">
            <div class="filters_titles">{{ trans(\Config::get('app.theme').'-app.lot_list.categories') }}</div>
            <i class="fa fa-sort-down"></i>
        </div>

        <div class="auction__filters-type-list mt-1 collapse " id="auction_categories" >

            <div class="input-category d-flex align-items-center hidden">
                <input type="radio" name="category" id="all_categories" value="" <?=  empty($filters["category"])? 'checked="checked"' : '' ?>  />

            </div>


            @foreach($categories as $category)
				<?php
				$linOrtsec0 = $category["lin_ortsec0"];
				$numCategoryLots = Tools::showNumLots($numActiveFilters, $filters, "category", $linOrtsec0);
				?>

                    <?php # si una categoria no tiene lotes no puede estar marcada ni buscar las subcategorias  ?>
                    @if($linOrtsec0 == $filters["category"] && $numCategoryLots > 0)

                        <div class="input-category auction__filters-collapse d-flex align-items-center justify-content-space-between" role="button" data-toggle="collapse" href="#sections_{{$category["key_ortsec0"]}}" aria-expanded="true" aria-controls="sections_{{$category["key_ortsec0"]}}">
                            <div class="category_level_01 d-flex align-items-center justify-content-space-between">
                                <div class="radio">
                                    <input type="radio" name="category" id="category_{{$linOrtsec0}}" value="{{$linOrtsec0}}" class="filter_lot_list_js"  checked="checked" />
                                    <label for="category_{{$linOrtsec0}}" class="radio-label">{{$category["des_ortsec0"]}} ({{Tools::numberformat($numCategoryLots)}})</label>
                                </div>
                            </div>
                            <i role="button" data-toggle="collapse" href="#sections_{{$linOrtsec0}}" aria-expanded="true" aria-controls="sections_{{$linOrtsec0}}" class="fa fa-sort-down"></i>
                        </div>

                        @include('includes.grid.sections_list')


                    @elseif($numCategoryLots > 0)

                    <div class="input-category d-flex align-items-center">

                        <div class="radio">
                            <input type="radio" name="category" id="category_{{$linOrtsec0}}" value="{{$linOrtsec0}}" class="filter_lot_list_js" <?=  ($numCategoryLots > 0)? "" : 'disabled=disabled' ?>/>
                            <label for="category_{{$linOrtsec0}}" class="radio-label <?=  ($numCategoryLots > 0)? "" : 'disabled-label' ?>">{{$category["des_ortsec0"]}} ({{Tools::numberformat($numCategoryLots)}})</label>
                        </div>

                    </div>
                    @endif

            @endforeach


        </div>
</div>
