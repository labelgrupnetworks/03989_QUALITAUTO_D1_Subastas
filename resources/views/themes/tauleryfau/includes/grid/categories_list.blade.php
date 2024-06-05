



		@foreach($categories as $category)
			<?php
			$linOrtsec0 = $category["lin_ortsec0"];
			$numCategoryLots = Tools::showNumLots($numActiveFilters, $filters, "category", $linOrtsec0);
			?>

				<?php # si una categoria no tiene lotes no puede estar marcada ni buscar las subcategorias  ?>
				@if($linOrtsec0 == $filters["category"] && $numCategoryLots > 0)
					<?php /*
						<div class="category_level_01 d-flex align-items-center justify-content-space-between">
							<div class="radio">
								<input type="radio" name="category" id="category_{{$linOrtsec0}}" value="{{$linOrtsec0}}" class="filter_lot_list_js"  checked="checked" />
								<label for="category_{{$linOrtsec0}}" class="radio-label">{{$category["des_ortsec0"]}} ({{Tools::numberformat($numCategoryLots)}})</label>
							</div>
						</div>

					*/ ?>
					@include('includes.grid.sections_list')
				<?php /*

					@elseif($numCategoryLots > 0)

					<div class="input-category d-flex align-items-center">

						<div class="radio">
							<input type="radio" name="category" id="category_{{$linOrtsec0}}" value="{{$linOrtsec0}}" class="filter_lot_list_js" <?=  ($numCategoryLots > 0)? "" : 'disabled=disabled' ?>/>
							<label for="category_{{$linOrtsec0}}" class="radio-label <?=  ($numCategoryLots > 0)? "" : 'disabled-label' ?>">{{$category["des_ortsec0"]}} ({{Tools::numberformat($numCategoryLots)}})</label>
						</div>

					</div>
				*/ ?>
				@endif

		@endforeach



