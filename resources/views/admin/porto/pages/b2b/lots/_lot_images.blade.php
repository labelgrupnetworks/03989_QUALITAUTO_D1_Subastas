<fieldset>
    <legend>{{ trans('admin-app.title.order_images') }}</legend>

    @if (!empty($formulario->imagen))
        <div>
            <label for="imagen">{{ trans('admin-app.fields.images') }}</label>
            <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right"
                data-original-title="{{ trans('admin-app.help_fields.imagen_sub') }}" aria-hidden="true"
                style="cursor: pointer; margin-left: 3px"></i>
            {!! $formulario->imagen['image'] !!}
        </div>
    @endif

    <div class="images-grid sortable mt-3" data-child="test" style="grid-template-columns: repeat(3, 1fr)">
        @foreach ($images as $image)
            <div class="img-wrapper" id="imagen{{ $loop->index }}"
                style="background-image: url({{ $image . '?a=' . rand() }})">
                <p class="images-grid-delete"><i class="fa fa-trash-o" aria-hidden="true"
                        onclick="javascript:borrarImagenLote('{{ $loop->index }}', '{{ $image }}')"></i></p>
                <input name="images_url[]" type="hidden" value="{{ $image }}">
            </div>
        @endforeach
    </div>
</fieldset>



<!-- Modal -->
<div class="modal fade" id="loadMe" role="dialog" aria-labelledby="loadMeLabel" tabindex="-1">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="custom-spinner"></div>
                <div clas="custom-spinner-text">
                    <p>{{ trans("$theme-app.information.processing_data") }} <br><br></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        /**
         * @api: https://api.jqueryui.com/sortable/
         * */
        $(".sortable").sortable({

            //containment: "parent", //delimita el movimieno al div padre
            opacity: 0.5,
            placeholder: "sortable-placeholder",
            tolerance: "pointer",
            items: $(this).data('child'),
            over: function(event, ui) {
                $(ui.helper).css('border', '2px dashed red');
                $(ui.placeholder).css('border', '2px dashed #000').css('border-radius', '10px');
                $(ui.item).css('border', '1px solid gray');

            }
        });

    });
</script>
