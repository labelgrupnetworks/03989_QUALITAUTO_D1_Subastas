
<div class="clearfix"></div>

<div class="categories-home">
	<div class="container">
		<div class="row">

			<div class="container">
		        <div class="row">
		            <div class="categories-title-container d-flex justify-content-space-between align-items-flex-end align-items-center">
		                <p style="margin: 0" class="categories-title">
		                	<?= trans(\Config::get('app.theme').'-app.home.ours-categories') ?>
	                	</p>
		                <div class="hidden-xs hidden-md">
							<a href="{{ \Routing::slug('subastas') }}">
								{{trans(\Config::get('app.theme').'-app.home.all_categories')}}
							</a>
						</div>
		            </div>

		        </div>
		    </div>


			<?php
				$fgortsec0 = new App\Models\V5\FgOrtsec0();
				$categories = $fgortsec0->GetAllFgOrtsec0()->get()->toarray();
			 ?>

			@foreach ($categories as $category)

					<div class="category-block-home">

						<div class="row">
							<div class="col-xs-8 text-left no-padding">
								<a title="{!! $category["des_ortsec0"] !!}" href='{{ route("category",array( "category" => $category["key_ortsec0"])) }}' class="title">
							   		{{$category["des_ortsec0"]}}
							   	</a>

							</div>
							<div class="col-xs-4 no-padding">
								<img src="/themes/{{Config::get("app.theme")}}/assets/category/category_{{$category["lin_ortsec0"]}}.svg" alt="{{$category["des_ortsec0"]}}" width="100%">
							</div>
						</div>

				   	</div>

			@endforeach

		</div>
	</div>
</div>
