@include('includes.head.open_graph_head')
@include('includes.head.google_head')
@include('includes.head.meta_tags')

@include('includes.head.stylesheets')
@stack('styles')

@include('includes.head.scripts')
@stack('scripts')

@yield('assets_components')

@php
    Tools::personalJsCss();
@endphp
