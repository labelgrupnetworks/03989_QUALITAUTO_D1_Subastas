            <div class="auction__filters-type mt-1">
                <div class="auction__filters-collapse d-flex align-items-center justify-content-space-between" role="button" data-toggle="collapse" href="#auction_type" aria-expanded="false" aria-controls="auction_type">
                    <div>{{ trans($theme.'-app.lot_list.auction_type') }}</div>
                    <i class="fa fa-sort-down"></i>
                </div>
                <div class="input-category d-flex align-items-center hidden">
                    <input type="radio" name="typeSub" id="all_typesSub" value="" <?=  empty(request('typeSub'))? 'checked="checked"' : '' ?>  />
                </div>
                <div class="auction__filters-type-list collapse mt-1" id="auction_type" >

                    @foreach($tipos_sub as $typeSub =>$desType)
                        <?php
                                $numLots = Tools::showNumLots($numActiveFilters, $filters, "typeSub", $typeSub);
                        ?>
						@if($numLots)
                            <div class="input-type-auction d-flex align-items-center">
                                <div class="radio">
                                <?php //si no ha lotes no podrán marcar la opcion ?>

                                    <input  type="radio" name="typeSub" id="typeSub_{{$typeSub}}" value="{{$typeSub}}" class="filter_lot_list_js"  <?=  ($numLots > 0)? "" : 'disabled=disabled' ?> <?= ($typeSub == $filters['typeSub'])? 'checked=checked' : '' ?>    />
                                    <label for="typeSub_{{$typeSub}}" class="radio-label <?=  ($numLots > 0)? "" : 'disabled-label' ?>">{{$desType}} ({{Tools::numberformat($numLots) }} )</label>

                                </div>
                            </div>
						@endif
                    @endforeach

                </div>
            </div>
