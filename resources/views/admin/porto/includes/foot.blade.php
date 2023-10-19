
<script src="{{ $base_url }}/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
<script src="{{ $base_url }}/vendor/bootstrap/js/bootstrap.js"></script>
<script src="{{ $base_url }}/vendor/nanoscroller/nanoscroller.js"></script>
<script src="{{ $base_url }}/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="{{ $base_url }}/vendor/magnific-popup/magnific-popup.js"></script>
<script src="{{ $base_url }}/vendor/jquery-placeholder/jquery.placeholder.js"></script>
<script src="{{ $base_url }}/vendor/jquery-ui/js/jquery-ui-1.10.4.custom.js"></script>
<script src="{{ $base_url }}/vendor/jquery-ui-touch-punch/jquery.ui.touch-punch.js"></script>
<script src="{{ $base_url }}/vendor/jquery-appear/jquery.appear.js"></script>
<script src="{{ $base_url }}/vendor/jquery-maskedinput/jquery.maskedinput.js"></script>
<script src="{{ $base_url }}/vendor/jquery-easypiechart/jquery.easypiechart.js"></script>
<script src="{{ $base_url }}/vendor/jquery-nestable/jquery.nestable.js"></script>
<script src="{{ $base_url }}/vendor/flot/jquery.flot.js"></script>
<script src="{{ $base_url }}/vendor/flot-tooltip/jquery.flot.tooltip.js"></script>
<script src="{{ $base_url }}/vendor/flot/jquery.flot.pie.js"></script>
<script src="{{ $base_url }}/vendor/flot/jquery.flot.categories.js"></script>
<script src="{{ $base_url }}/vendor/flot/jquery.flot.resize.js"></script>
<script src="{{ $base_url }}/vendor/jquery-sparkline/jquery.sparkline.js"></script>
<script src="{{ $base_url }}/vendor/raphael/raphael.js"></script>
<script src="{{ $base_url }}/vendor/morris/morris.js"></script>
<script src="{{ $base_url }}/vendor/gauge/gauge.js"></script>
<script src="{{ $base_url }}/vendor/snap-svg/snap.svg.js"></script>
<script src="{{ $base_url }}/vendor/liquid-meter/liquid.meter.js"></script>
<script src="{{ $base_url }}/vendor/jqvmap/jquery.vmap.js"></script>
<script src="{{ $base_url }}/vendor/jqvmap/data/jquery.vmap.sampledata.js"></script>
<script src="{{ $base_url }}/vendor/jqvmap/maps/jquery.vmap.world.js"></script>
<script src="{{ $base_url }}/vendor/jqvmap/maps/continents/jquery.vmap.africa.js"></script>
<script src="{{ $base_url }}/vendor/jqvmap/maps/continents/jquery.vmap.asia.js"></script>
<script src="{{ $base_url }}/vendor/jqvmap/maps/continents/jquery.vmap.australia.js"></script>
<script src="{{ $base_url }}/vendor/jqvmap/maps/continents/jquery.vmap.europe.js"></script>
<script src="{{ $base_url }}/vendor/jqvmap/maps/continents/jquery.vmap.north-america.js"></script>
<script src="{{ $base_url }}/vendor/jqvmap/maps/continents/jquery.vmap.south-america.js"></script>
<script src="{{ $base_url }}/vendor/summernote/summernote.js"></script>
<script src="{{ $base_url }}/vendor/summernote/lang/summernote-es-ES.js"></script>
<script src="{{ $base_url }}/vendor/pnotify/pnotify.custom.js"></script>
{{--<script src="{{ $base_url }}/vendor/datatables/datatables.min.js"></script> --}}
{{-- <script src="{{ $base_url }}/vendor/owl-carousel/owl.carousel.js"></script> --}}
<script src="{{ $base_url }}/vendor/dropzone/dropzone.js"></script>
<script src="{{ $base_url }}/vendor/modernizr/modernizr.js"></script>
<script src="{{ $base_url }}/vendor/store-js/store.js"></script>
<script src="{{ $base_url }}/javascripts/theme.js?a={{rand()}}"></script>
<script src="{{ $base_url }}/javascripts/theme.custom.js?a={{rand()}}"></script>
<script src="{{ $base_url }}/javascripts/theme.init.js"></script>
<script src="{{ $base_url }}/javascripts/ui-elements/examples.notifications.js"></script>
<script src="{{ URL::asset('js/validator.js') }}"></script>
<script src="{{ $base_url }}/javascripts/nestable-menu.nestable.js"></script>
<script src="{{ $base_url }}/javascripts/ui-elements/examples.portlets.js"></script>
<script src="{{ $base_url }}/javascripts/V5/bootbox.min.js"></script>
<script src="{{ URL::asset('js/forms.js') }}"></script>
<script src="{{ URL::asset('vendor/slick/slick.js') }}"></script>
<script src="{{ URL::asset('vendor/datatables/datatables.js') }}"></script>
<script src="{{ URL::asset('vendor/datetimepicker/js/bootstrap-datetimepicker.js') }}"></script>
<script src="{{ $base_url }}/vendor/chart.js-3.4.1/package/dist/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>


<script type="text/javascript" src="{{ URL::asset('vendor/bootstrap-multiselect//bootstrap-multiselect.js') }}"></script>

{{-- <script src="{{ URL::asset('js/common.js') }}"></script> --}}
<?php \Tools::personalJsCss(2);?>

@stack('admin-js')
