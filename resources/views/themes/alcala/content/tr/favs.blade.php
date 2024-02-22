<div class="aside hidden started" id="favs_box">
	<h2 class="msj">{{ trans($theme.'-app.sheet_tr.your_favs') }}</h2>
	<div class="row fav_list">
		<?php foreach ($data['js_item']['user']['favorites'] as $key => $value) : ?>
			<div class="col-sm-3">
				<div class="bordered" data-key="<?php echo $key; ?>" >
					<div class="img">
						<img class="img-responsive" src="{{ $value->imagen }}">
					</div>
					<div class="lot">
						<?php echo $value->ref_asigl0; ?>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
	<div class="col-sm-3 hidden" id="model_fav_box">
		<div class="bordered" data-key="" >
			<div class="img">
				<img class="img-responsive" src="{{ $img_url }}/test.jpg">
			</div>
			<div class="lot">

			</div>
		</div>
	</div>

</div>
