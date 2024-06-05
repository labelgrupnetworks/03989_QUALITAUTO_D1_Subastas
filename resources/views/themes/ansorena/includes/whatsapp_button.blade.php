@if (!empty(config('app.whatsapp_num_button')))
	<a class="whatsapp-button" href="https://api.whatsapp.com/send?phone={{ config('app.whatsapp_num_button') }}" target="_blank">
		<i class="fa fa-whatsapp"></i>
		<span>{{ trans("$theme-app.foot.whatsapp_button") }}</span>
	</a>
@endif
