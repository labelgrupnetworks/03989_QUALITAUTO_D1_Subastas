@php
	$settings = $page_settings->getSettings();
@endphp

@if (count($settings) > 0)
	<div id="admin_settings_box" class="">
		<div class="gestor_buttons">
			@foreach ($settings as $setting)
				<a class="btn btn-lb-primary w-100 mb-1" href="{{ $setting['url'] }}" title="{{ $setting['name'] }}" target="_blank">
					<span>
						{{ trans("$theme-app.foot.admin_" . $setting['name'], $setting['name_val'] ? $setting['name_val'] : []) }}
					</span>
				</a>
			@endforeach
		</div>
		<div class="desplegable">
			<div data-id="right">
				<svg width="20" height="20" fill="currentColor" class="bi bi-arrow-right-short m-auto d-block" viewBox="0 0 16 16">
					<path fill-rule="evenodd" d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8"/>
				</svg>
			</div>
			<div data-id="left" class="hidden">
				<svg width="20" height="20" fill="currentColor" class="bi bi-arrow-left-short m-auto d-block" viewBox="0 0 16 16">
					<path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5"/>
				</svg>
			</div>
		</div>
	</div>
@endif
