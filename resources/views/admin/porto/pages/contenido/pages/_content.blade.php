<div class="col-xs-12">
	<div id="gjs"></div>
	{{-- <div><button id="store-data">Guardar</button></div> --}}
</div>
@csrf

<script type="text/javascript">

	let editor;

	$(document).ready(function () {

		editor = grapesjs.init({
		...defaultGrapeOptions,
		storageManager: {
			...defaultSotreManager,
			//urlStore: 'http://larablog.test/save-page',
			//urlLoad: '{{ route('static-pages.show', ['static_page' => 7]) }}',
			params: {
				"_token": document.querySelector('[name=_token]')?.value
			}
		},
		assetManager: {
			...defaultAssetsManager,
			assets: @json($images),
			upload: '{{ route('static-pages.upload_image') }}',
			params: {
				"_token": document.querySelector('[name=_token]')?.value
			}
		}
		});

		editor.setComponents(`{!! $webPage->content_web_page !!}`);

		grapeOnLoad(editor);

	});


</script>
