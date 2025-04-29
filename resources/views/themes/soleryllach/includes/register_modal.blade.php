
{{-- Modal for showing banner on scroll --}}
@if (!Session::has('user'))
    <div class="modal fade" id="bannerModal" role="dialog" aria-labelledby="bannerModalLabel" tabindex="-1">
        <div class="modal-dialog modal-lg" role="document"
            style="margin: 0; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
            <div class="modal-content p-0">
                <div class="modal-body banner-modal-lotlist">
                    <button class="close" data-dismiss="modal" type="button" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>

                    {!! \BannerLib::bannersPorKey('grid-banner', 'grid-banner') !!}
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            showLotListModal({{$whenScroll}});
        });
    </script>
@endif
