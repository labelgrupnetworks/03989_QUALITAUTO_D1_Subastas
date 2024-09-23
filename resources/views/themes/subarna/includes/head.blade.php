@include('includes.head.open_graph_head')
@include('includes.head.google_head')
@include('includes.head.meta_tags')

@include('includes.head.stylesheets')

@include('includes.head.scripts')

@yield('assets_components')

@php
    Tools::personalJsCss();
@endphp
