@if (!empty(\Config::get('app.google_conversion_id')) && (!empty($cookiesState['google']) || !empty($cookiesState['all'])) )

<script type="text/javascript">

	var google_conversion_id = {{\Config::get('app.google_conversion_id') }};
    var google_custom_params = window.google_tag_params;
    var google_remarketing_only = true;

</script>

<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">

</script>

<noscript>

	<div style="display:inline;">
		<img height="1" width="1" style="border-style:none;" alt=""
			src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/<?= \Config::get('app.google_conversion_id') ?>/?guid=ON&amp;script=0" />
	</div>

</noscript>
@endif
