@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')
<script>
    var info_fact = $.parseJSON('<?php echo str_replace("\u0022","\\\\\"",json_encode($data["js_item"],JSON_HEX_QUOT)); ?>');
</script>
<!-- titulo -->
<div class="color-letter">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                <h3 class="titlePage">{{ trans(\Config::get('app.theme').'-app.user_panel.mi_cuenta') }}</h3>
                </div>
            </div>
        </div>
    </div>
<!-- Menu -->



<div class="account-user color-letter  panel-user pendientes_pago">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-3 col-lg-3 account-user-menu">
                        <?php $tab="bills";?> 
                                            @include('pages.panel.menu_micuenta')
                </div>
                <div class="col-xs-12 col-md-9 col-lg-9 ">
                    <div class="user-account-title-content">
                        <div class="user-account-menu-title">{{ trans(\Config::get('app.theme').'-app.user_panel.pending_bills') }}</div>
                    </div>

                    <div class="user-accounte-titles-link" data-id="collapse_pays">
                        <ul class="ul-format d-flex justify-content-space-between align-items-center" role="tablist"  role="button" data-toggle="collapse" href="#collapse_pays" aria-expanded="false" aria-controls="collapse_pays">
                            <li role="pagar"class="active" ><a class="color-letter" href="{{ \Routing::slug('user/panel/pending_bills') }}" style="text-transform: uppercase">{{ trans(\Config::get('app.theme').'-app.user_panel.still_paid') }}</a></li>
                            <span>
                                    <span class="label-open" style="display: none" >{{ trans(\Config::get('app.theme').'-app.user_panel.open') }}</span>
                                    <span class="label-close" >{{ trans(\Config::get('app.theme').'-app.user_panel.hide') }}</span>
                                <img width=10 src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjI0cHgiIGhlaWdodD0iMjRweCIgdmlld0JveD0iMCAwIDk2LjE1NCA5Ni4xNTQiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDk2LjE1NCA5Ni4xNTQ7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4KPGc+Cgk8cGF0aCBkPSJNMC41NjEsMjAuOTcxbDQ1Ljk1MSw1Ny42MDVjMC43NiwwLjk1MSwyLjM2NywwLjk1MSwzLjEyNywwbDQ1Ljk1Ni01Ny42MDljMC41NDctMC42ODksMC43MDktMS43MTYsMC40MTQtMi42MSAgIGMtMC4wNjEtMC4xODctMC4xMjktMC4zMy0wLjE4Ni0wLjQzN2MtMC4zNTEtMC42NS0xLjAyNS0xLjA1Ni0xLjc2NS0xLjA1NkgyLjA5M2MtMC43MzYsMC0xLjQxNCwwLjQwNS0xLjc2MiwxLjA1NiAgIGMtMC4wNTksMC4xMDktMC4xMjcsMC4yNTMtMC4xODQsMC40MjZDLTAuMTUsMTkuMjUxLDAuMDExLDIwLjI4LDAuNTYxLDIwLjk3MXoiIGZpbGw9IiMwMDAwMDAiLz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8L3N2Zz4K" />
                                </span>
                        </ul>
                    </div>

                    
                    <div class="col-xs-12 no-padding panel-collapse in" id="collapse_pays"  aria-expanded="true">
                        <div class="col-xs-12" style="padding-left:39px;">
                            <div class="user-account-heading hidden-xs d-flex align-items-center justify-content-space-between">
                                    <div class="col-xs-12 col-sm-7 col-lg-8 col-one user-account-item">
                                            {{ trans(\Config::get('app.theme').'-app.user_panel.lot') }}
                                    </div>
                                    <div class="col-xs-12 col-sm-2 col-one user-account-fecha text-right">
                                            {{ trans(\Config::get('app.theme').'-app.user_panel.total_fact') }}
                                    </div>
                                    <div class="col-xs-12 col-sm-3 col-lg-2 col-one user-account-max-bid text-right">
                                            {{ trans(\Config::get('app.theme').'-app.user_panel.total_price_fact') }}
                                    </div>
                                </div>
                            </div>
                        <form  id="pagar_fact"> 
                            <input name="_token" type="hidden" value="{{ csrf_token() }}" />
                            @foreach($data['pending'] as $key_bill => $pendiente)

                             <div class="factura col-md-12 d-flex" data-anum="{{$pendiente->anum_pcob}}" data-num="{{$pendiente->num_pcob}}"  data-efec="{{$pendiente->efec_pcob}}">
                                <div class="factura_check" style="position: relative">
                                    @if ($pendiente->compraweb_sub != "N")
                                    <label for="checkFactura-{{$pendiente->anum_pcob}}-{{$pendiente->num_pcob}}-{{$pendiente->efec_pcob}}">
                                        <input name="factura[{{$pendiente->anum_pcob}}][{{$pendiente->num_pcob}}-{{$pendiente->efec_pcob}}]" id="checkFactura-{{$pendiente->anum_pcob}}-{{$pendiente->num_pcob}}-{{$pendiente->efec_pcob}}" type="checkbox" class="hide add_factura" checked>
                                        <span class="checkmark"></span>
        
                                    </label>
                                    @endif
                                </div>
                                <div class="factura_box d-flex flex-wrap align-items-center col-xs-12 col-md-12">
                                    <div class="visible-md visible-lg factura_icon col-xs-6 col-md-2">
                                        @if(!empty($pendiente->factura) && file_exists($pendiente->factura) )
                                            <a style="width: 100%" target="_blank" href="/factura/{{$pendiente->anum_pcob}}-{{$pendiente->num_pcob}}">
                                                <img width="40" src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTguMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDU4IDU4IiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCA1OCA1ODsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHdpZHRoPSI2NHB4IiBoZWlnaHQ9IjY0cHgiPgo8Zz4KCTxwYXRoIGQ9Ik01MC45NSwxMi4xODdsLTAuNzcxLTAuNzcxTDQwLjA4NCwxLjMyMUwzOS4zMTMsMC41NUMzOC45NjQsMC4yMDEsMzguNDgsMCwzNy45ODUsMEg4Ljk2M0M3Ljc3NywwLDYuNSwwLjkxNiw2LjUsMi45MjZWMzkgICB2MTYuNTM3VjU2YzAsMC44MzcsMC44NDIsMS42NTMsMS44MzgsMS45MWMwLjA1LDAuMDEzLDAuMDk4LDAuMDMyLDAuMTUsMC4wNDJDOC42NDQsNTcuOTgzLDguODAzLDU4LDguOTYzLDU4aDQwLjA3NCAgIGMwLjE2LDAsMC4zMTktMC4wMTcsMC40NzUtMC4wNDhjMC4wNTItMC4wMSwwLjEtMC4wMjksMC4xNS0wLjA0MkM1MC42NTgsNTcuNjUzLDUxLjUsNTYuODM3LDUxLjUsNTZ2LTAuNDYzVjM5VjEzLjk3OCAgIEM1MS41LDEzLjIxMSw1MS40MDgsMTIuNjQ1LDUwLjk1LDEyLjE4N3ogTTQ3LjkzNSwxMkgzOS41VjMuNTY1TDQ3LjkzNSwxMnogTTguOTYzLDU2Yy0wLjA3MSwwLTAuMTM1LTAuMDI2LTAuMTk4LTAuMDQ5ICAgQzguNjA5LDU1Ljg3Nyw4LjUsNTUuNzIxLDguNSw1NS41MzdWNDFoNDF2MTQuNTM3YzAsMC4xODQtMC4xMDksMC4zMzktMC4yNjUsMC40MTRDNDkuMTcyLDU1Ljk3NCw0OS4xMDgsNTYsNDkuMDM3LDU2SDguOTYzeiAgICBNOC41LDM5VjIuOTI2QzguNSwyLjcwOSw4LjUzMywyLDguOTYzLDJoMjguNTk1QzM3LjUyNSwyLjEyNiwzNy41LDIuMjU2LDM3LjUsMi4zOTFWMTRoMTEuNjA5YzAuMTM1LDAsMC4yNjQtMC4wMjUsMC4zOS0wLjA1OCAgIGMwLDAuMDE1LDAuMDAxLDAuMDIxLDAuMDAxLDAuMDM2VjM5SDguNXoiIGZpbGw9IiMwMDAwMDAiLz4KCTxwYXRoIGQ9Ik0yMi4wNDIsNDQuNzQ0Yy0wLjMzMy0wLjI3My0wLjcwOS0wLjQ3OS0xLjEyOC0wLjYxNWMtMC40MTktMC4xMzctMC44NDMtMC4yMDUtMS4yNzEtMC4yMDVoLTIuODk4VjU0aDEuNjQxdi0zLjYzN2gxLjIxNyAgIGMwLjUyOCwwLDEuMDEyLTAuMDc3LDEuNDQ5LTAuMjMyczAuODExLTAuMzc0LDEuMTIxLTAuNjU2YzAuMzEtMC4yODIsMC41NTEtMC42MzEsMC43MjUtMS4wNDZjMC4xNzMtMC40MTUsMC4yNi0wLjg3NywwLjI2LTEuMzg4ICAgYzAtMC40ODMtMC4xMDMtMC45MTgtMC4zMDgtMS4zMDZTMjIuMzc1LDQ1LjAxOCwyMi4wNDIsNDQuNzQ0eiBNMjEuNDIsNDguMDczYy0wLjEwMSwwLjI3OC0wLjIzMiwwLjQ5NC0wLjM5NiwwLjY0OSAgIGMtMC4xNjQsMC4xNTUtMC4zNDQsMC4yNjctMC41NCwwLjMzNWMtMC4xOTYsMC4wNjgtMC4zOTUsMC4xMDMtMC41OTUsMC4xMDNoLTEuNTA0di0zLjk5MmgxLjIzYzAuNDE5LDAsMC43NTYsMC4wNjYsMS4wMTIsMC4xOTggICBjMC4yNTUsMC4xMzIsMC40NTMsMC4yOTYsMC41OTUsMC40OTJjMC4xNDEsMC4xOTYsMC4yMzQsMC40MDEsMC4yOCwwLjYxNWMwLjA0NSwwLjIxNCwwLjA2OCwwLjQwMywwLjA2OCwwLjU2NyAgIEMyMS41Nyw0Ny40NTEsMjEuNTIsNDcuNzk1LDIxLjQyLDQ4LjA3M3oiIGZpbGw9IiMwMDAwMDAiLz4KCTxwYXRoIGQ9Ik0zMS45NTQsNDUuNGMtMC40MjQtMC40NDYtMC45NTctMC44MDUtMS42LTEuMDczcy0xLjM4OC0wLjQwMy0yLjIzNS0wLjQwM2gtMy4wMzVWNTRoMy44MTQgICBjMC4xMjcsMCwwLjMyMy0wLjAxNiwwLjU4OC0wLjA0OGMwLjI2NC0wLjAzMiwwLjU1Ni0wLjEwNCwwLjg3NS0wLjIxOWMwLjMxOS0wLjExNCwwLjY0OS0wLjI4NSwwLjk5MS0wLjUxMyAgIHMwLjY0OS0wLjU0LDAuOTIzLTAuOTM3czAuNDk5LTAuODg5LDAuNjc3LTEuNDc3czAuMjY3LTEuMjk3LDAuMjY3LTIuMTI2YzAtMC42MDItMC4xMDUtMS4xODgtMC4zMTQtMS43NTcgICBDMzIuNjk0LDQ2LjM1NSwzMi4zNzgsNDUuODQ3LDMxLjk1NCw0NS40eiBNMzAuNzU4LDUxLjczYy0wLjQ5MiwwLjcxMS0xLjI5NCwxLjA2Ni0yLjQwNiwxLjA2NmgtMS42Mjd2LTcuNjI5aDAuOTU3ICAgYzAuNzg0LDAsMS40MjIsMC4xMDMsMS45MTQsMC4zMDhzMC44ODIsMC40NzQsMS4xNjksMC44MDdzMC40OCwwLjcwNCwwLjU4MSwxLjExNGMwLjEsMC40MSwwLjE1LDAuODI1LDAuMTUsMS4yNDQgICBDMzEuNDk2LDQ5Ljk4OSwzMS4yNSw1MS4wMiwzMC43NTgsNTEuNzN6IiBmaWxsPSIjMDAwMDAwIi8+Cgk8cG9seWdvbiBwb2ludHM9IjM1LjU5OCw1NCAzNy4yNjYsNTQgMzcuMjY2LDQ5LjQ2MSA0MS40NzcsNDkuNDYxIDQxLjQ3Nyw0OC4zNCAzNy4yNjYsNDguMzQgMzcuMjY2LDQ1LjE2OCA0MS45LDQ1LjE2OCAgICA0MS45LDQzLjkyNCAzNS41OTgsNDMuOTI0ICAiIGZpbGw9IiMwMDAwMDAiLz4KCTxwYXRoIGQ9Ik0zOC40MjgsMjIuOTYxYy0wLjkxOSwwLTIuMDQ3LDAuMTItMy4zNTgsMC4zNThjLTEuODMtMS45NDItMy43NC00Ljc3OC01LjA4OC03LjU2MmMxLjMzNy01LjYyOSwwLjY2OC02LjQyNiwwLjM3My02LjgwMiAgIGMtMC4zMTQtMC40LTAuNzU3LTEuMDQ5LTEuMjYxLTEuMDQ5Yy0wLjIxMSwwLTAuNzg3LDAuMDk2LTEuMDE2LDAuMTcyYy0wLjU3NiwwLjE5Mi0wLjg4NiwwLjYzNi0xLjEzNCwxLjIxNSAgIGMtMC43MDcsMS42NTMsMC4yNjMsNC40NzEsMS4yNjEsNi42NDNjLTAuODUzLDMuMzkzLTIuMjg0LDcuNDU0LTMuNzg4LDEwLjc1Yy0zLjc5LDEuNzM2LTUuODAzLDMuNDQxLTUuOTg1LDUuMDY4ICAgYy0wLjA2NiwwLjU5MiwwLjA3NCwxLjQ2MSwxLjExNSwyLjI0MmMwLjI4NSwwLjIxMywwLjYxOSwwLjMyNiwwLjk2NywwLjMyNmgwYzAuODc1LDAsMS43NTktMC42NywyLjc4Mi0yLjEwNyAgIGMwLjc0Ni0xLjA0OCwxLjU0Ny0yLjQ3NywyLjM4My00LjI1MWMyLjY3OC0xLjE3MSw1Ljk5MS0yLjIyOSw4LjgyOC0yLjgyMmMxLjU4LDEuNTE3LDIuOTk1LDIuMjg1LDQuMjExLDIuMjg1ICAgYzAuODk2LDAsMS42NjQtMC40MTIsMi4yMi0xLjE5MWMwLjU3OS0wLjgxMSwwLjcxMS0xLjUzNywwLjM5LTIuMTZDNDAuOTQzLDIzLjMyNywzOS45OTQsMjIuOTYxLDM4LjQyOCwyMi45NjF6IE0yMC41MzYsMzIuNjM0ICAgYy0wLjQ2OC0wLjM1OS0wLjQ0MS0wLjYwMS0wLjQzMS0wLjY5MmMwLjA2Mi0wLjU1NiwwLjkzMy0xLjU0MywzLjA3LTIuNzQ0QzIxLjU1NSwzMi4xOSwyMC42ODUsMzIuNTg3LDIwLjUzNiwzMi42MzR6ICAgIE0yOC43MzYsOS43MTJjMC4wNDMtMC4wMTQsMS4wNDUsMS4xMDEsMC4wOTYsMy4yMTZDMjcuNDA2LDExLjQ2OSwyOC42MzgsOS43NDUsMjguNzM2LDkuNzEyeiBNMjYuNjY5LDI1LjczOCAgIGMxLjAxNS0yLjQxOSwxLjk1OS01LjA5LDIuNjc0LTcuNTY0YzEuMTIzLDIuMDE4LDIuNDcyLDMuOTc2LDMuODIyLDUuNTQ0QzMxLjAzMSwyNC4yMTksMjguNzU5LDI0LjkyNiwyNi42NjksMjUuNzM4eiAgICBNMzkuNTcsMjUuMjU5QzM5LjI2MiwyNS42OSwzOC41OTQsMjUuNywzOC4zNiwyNS43Yy0wLjUzMywwLTAuNzMyLTAuMzE3LTEuNTQ3LTAuOTQ0YzAuNjcyLTAuMDg2LDEuMzA2LTAuMTA4LDEuODExLTAuMTA4ICAgYzAuODg5LDAsMS4wNTIsMC4xMzEsMS4xNzUsMC4xOTdDMzkuNzc3LDI0LjkxNiwzOS43MTksMjUuMDUsMzkuNTcsMjUuMjU5eiIgZmlsbD0iIzAwMDAwMCIvPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+Cjwvc3ZnPgo=" />
                                            </a>
                                        @else
                                            <img width="40" src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTkuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDUxMi4wMDEgNTEyLjAwMSIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgNTEyLjAwMSA1MTIuMDAxOyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSIgd2lkdGg9IjE2cHgiIGhlaWdodD0iMTZweCI+CjxnPgoJPGc+CgkJPHBhdGggZD0iTTQ2OS4wNzIsOTEuOTI5bC04OS04OUMzNzguMTk2LDEuMDU0LDM3NS42NTIsMCwzNzMuMDAxLDBoLTMwM2MtMTYuNTQyLDAtMzAsMTMuNDU4LTMwLDMwdjQ1Mi4wMDEgICAgYzAsMTYuNTQyLDEzLjQ1OCwzMCwzMCwzMGgzNzJjMTYuNTQyLDAsMzAtMTMuNDU4LDMwLTMwVjk5QzQ3Mi4wMDEsOTYuMzQ4LDQ3MC45NDcsOTMuODA0LDQ2OS4wNzIsOTEuOTI5eiBNMzgzLjAwMSwzNC4xNDMgICAgTDQzNy44NTgsODloLTQ0Ljg1OGMtNS41MTQsMC0xMC00LjQ4Ni0xMC0xMFYzNC4xNDN6IE00NTIuMDAxLDQ4Mi4wMDFjMCw1LjUxNS00LjQ4NywxMC0xMCwxMGgtMzcyYy01LjUxNCwwLTEwLTQuNDg2LTEwLTEwVjMwICAgIGMwLTUuNTE0LDQuNDg2LTEwLDEwLTEwaDI5M3Y1OWMwLDE2LjU0MiwxMy40NTgsMzAsMzAsMzBoNTlWNDgyLjAwMXoiIGZpbGw9IiMwMDAwMDAiLz4KCTwvZz4KPC9nPgo8Zz4KCTxnPgoJCTxwYXRoIGQ9Ik0xMTEuMTA5LDY2Ljk3Yy0xLjg1OS0xLjg2LTQuNDM5LTIuOTMtNy4wNjktMi45M2MtMi42NDEsMC01LjIxLDEuMDctNy4wNywyLjkzYy0xLjg2LDEuODYtMi45Myw0LjQ0LTIuOTMsNy4wNyAgICBzMS4wNjksNS4yMSwyLjkzLDcuMDdzNC40MjksMi45Myw3LjA3LDIuOTNjMi42MywwLDUuMjEtMS4wNyw3LjA2OS0yLjkzYzEuODYtMS44NiwyLjkzMS00LjQ0LDIuOTMxLTcuMDcgICAgUzExMi45Nyw2OC44MywxMTEuMTA5LDY2Ljk3eiIgZmlsbD0iIzAwMDAwMCIvPgoJPC9nPgo8L2c+CjxnPgoJPGc+CgkJPHBhdGggZD0iTTI2MS45OTksNjQuMDM5SDE0MS45NzNjLTUuNTIyLDAtMTAsNC40NzctMTAsMTBzNC40NzgsMTAsMTAsMTBoMTIwLjAyNmM1LjUyMiwwLDEwLTQuNDc3LDEwLTEwICAgIFMyNjcuNTIyLDY0LjAzOSwyNjEuOTk5LDY0LjAzOXoiIGZpbGw9IiMwMDAwMDAiLz4KCTwvZz4KPC9nPgo8Zz4KCTxnPgoJCTxwYXRoIGQ9Ik00MDguMDAxLDE2MGgtMzA0Yy01LjUyMiwwLTEwLDQuNDc3LTEwLDEwdjE5MmMwLDUuNTIzLDQuNDc4LDEwLDEwLDEwaDMwNGM1LjUyMiwwLDEwLTQuNDc3LDEwLTEwVjE3MCAgICBDNDE4LjAwMSwxNjQuNDc3LDQxMy41MjIsMTYwLDQwOC4wMDEsMTYweiBNMTU2LjAwMSwzNTJoLTQyYzAsMCwwLTQ0LDAtNDRoNDJWMzUyeiBNMTU2LjAwMSwyODhoLTQyYzAsMCwwLTQ0LDAtNDRoNDJWMjg4eiAgICAgTTE1Ni4wMDEsMjI0aC00MnYtNDRoNDJWMjI0eiBNMjg2LjAwMSwzNTJoLTExMHYtNDRoMTEwVjM1MnogTTI4Ni4wMDEsMjg4aC0xMTB2LTQ0aDExMFYyODh6IE0yODYuMDAxLDIyNGgtMTEwdi00NGgxMTBWMjI0eiAgICAgTTM5OC4wMDEsMzUyaC05MnYtNDRoOTJWMzUyeiBNMzk4LjAwMSwyODhoLTkydi00NGg5MlYyODh6IE0zOTguMDAxLDIyNGgtOTJ2LTQ0aDkyVjIyNHoiIGZpbGw9IiMwMDAwMDAiLz4KCTwvZz4KPC9nPgo8Zz4KCTxnPgoJCTxwYXRoIGQ9Ik0zMDMuMDY5LDQxMC45M2MtMS44NTktMS44Ni00LjQzOS0yLjkzLTcuMDY5LTIuOTNzLTUuMjEsMS4wNy03LjA3LDIuOTNzLTIuOTMsNC40NC0yLjkzLDcuMDdzMS4wNjksNS4yMSwyLjkzLDcuMDcgICAgYzEuODYxLDEuODYsNC40NCwyLjkzLDcuMDcsMi45M3M1LjIxLTEuMDcsNy4wNjktMi45M2MxLjg2LTEuODYsMi45MzEtNC40NCwyLjkzMS03LjA3UzMwNC45MzEsNDEyLjc5LDMwMy4wNjksNDEwLjkzeiIgZmlsbD0iIzAwMDAwMCIvPgoJPC9nPgo8L2c+CjxnPgoJPGc+CgkJPHBhdGggZD0iTTQwOC4wMDEsNDA4aC03MGMtNS41MjIsMC0xMCw0LjQ3Ny0xMCwxMHM0LjQ3OCwxMCwxMCwxMGg3MGM1LjUyMiwwLDEwLTQuNDc3LDEwLTEwUzQxMy41MjIsNDA4LDQwOC4wMDEsNDA4eiIgZmlsbD0iIzAwMDAwMCIvPgoJPC9nPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+Cjwvc3ZnPgo=" />
                                        @endif                                    
                                    </div>
                                    <div class="factura_datos col-xs-6 col-md-6">
                                        <div class="factura_titulo">
                                            <div>{{ trans(\Config::get('app.theme').'-app.user_panel.n_bill') }} {{$pendiente->anum_pcob}}/{{$pendiente->num_pcob}} ( {{date('d-m-Y',strtotime ($pendiente->fec_pcob))}} )</div>
                                        </div>
                                        <div class="factura_gastos">
                                            @php($precio_total = 0)    
                                            @foreach($data['inf_factura'] as $key_type => $inf_fact)
                                                @if(!empty($inf_fact[$pendiente->anum_pcob][$pendiente->num_pcob]))
                                                    @foreach($inf_fact[$pendiente->anum_pcob][$pendiente->num_pcob] as $fact)
                  
                                                        @if($data['tipo_tv'][$pendiente->anum_pcob][$pendiente->num_pcob] == 'P')
                                                            @php($precio_total = $precio_total + ((round((($fact->basea_dvc1l*$fact->iva_dvc1l)/100),2)) + $fact->basea_dvc1l)- $fact->padj_dvc1l)
                                                        @elseif($data['tipo_tv'][$pendiente->anum_pcob][$pendiente->num_pcob] == 'L')
                                                             @php($precio_total = $precio_total + $fact->padj_dvc1l + $fact->basea_dvc1l + round((($fact->basea_dvc1l*$fact->iva_dvc1l)/100),2))
                                                        @elseif($data['tipo_tv'][$pendiente->anum_pcob][$pendiente->num_pcob] == 'T')
                                                             @php($precio_total = $precio_total + $fact->total_dvc1 + round((($fact->total_dvc1*$fact->iva_dvc1)/100),2))
                                                        @endif
              
                                                    @endforeach
                                                @endif
                                            @endforeach
                                            
        
                                        </div>
                                    </div>
                                    <div class="col-xs-6 h-100 col-sm-2 col-lg-2 account-item-border">
                                            <div class="user-account-item-date d-flex align-items-center">
                                                    <div class="producto_resumen_info w-100">
                                                            <div class="w-100 text-right"> {{\Tools::moneyFormat($precio_total ,false,2)}} €</div>
                                                            <div class="w-100 text-right hidden-md hidden-lg"><strong><span>{{\Tools::moneyFormat($pendiente->imp_pcob,false,2)}}</span><span> €</span></strong></div>
                                                        </div>
                                            </div>
                                        </div>
                                        <div class=" hidden-xs hidden-sm col-xs-12 h-100 col-sm-2 col-lg-2 account-item-border">
                                                <div class="user-account-item-date d-flex align-items-center">
                                                        <div class="producto_resumen_info w-100">
                                                                <div class="w-100 text-right"><strong><span>{{\Tools::moneyFormat($pendiente->imp_pcob,false,2)}}</span><span> €</span></strong></div>
                                                            </div>
                                                </div>
                                            </div>
                                        
                                    
                                </div>
                            </div>
                            @endforeach
                            
                            <div class="col-md-12  text-right ">
                                    <div class="facturacion_info_data">
                                            
                                        <div class="importe_total adj" style="">
                                            <span>{{ trans(\Config::get('app.theme').'-app.user_panel.total_pay_fact') }} </span>
                                            <span id="total_bills">00</span><span> €</span>
                                        </div>
                                        <div class="mt-1">
                                        <button id="submit_fact" style="" type="button" class="secondary-button " >{{ trans(\Config::get('app.theme').'-app.user_panel.pay') }}</button>
                                        <button  class="btn btn-custom hidden" type="button"><div class="loader"></div></button>
                                        </div>
                
                                    </div>
                                </div>
                        </form>
                    
                    </div>
            
                    <div class="user-accounte-titles-link col-xs-12 no-padding" data-id="collapse_fac_pag">
                            <ul class="ul-format d-flex justify-content-space-between align-items-center" style="text-transform: uppercase" role="tablist"  role="button" data-toggle="collapse" href="#collapse_fac_pag" aria-expanded="false" aria-controls="collapse_fac_pag">
                                    <li role="pagadas"  ><a class="color-letter" style="text-transform: uppercase">{{ trans(\Config::get('app.theme').'-app.user_panel.bills') }}</a></li>   
                                    <span>
                                        <span class="label-open"  >{{ trans(\Config::get('app.theme').'-app.user_panel.open') }}</span>
                                        <span class="label-close" style="display: none">{{ trans(\Config::get('app.theme').'-app.user_panel.hide') }}</span>
                                    <img width=10 src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjI0cHgiIGhlaWdodD0iMjRweCIgdmlld0JveD0iMCAwIDk2LjE1NCA5Ni4xNTQiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDk2LjE1NCA5Ni4xNTQ7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4KPGc+Cgk8cGF0aCBkPSJNMC41NjEsMjAuOTcxbDQ1Ljk1MSw1Ny42MDVjMC43NiwwLjk1MSwyLjM2NywwLjk1MSwzLjEyNywwbDQ1Ljk1Ni01Ny42MDljMC41NDctMC42ODksMC43MDktMS43MTYsMC40MTQtMi42MSAgIGMtMC4wNjEtMC4xODctMC4xMjktMC4zMy0wLjE4Ni0wLjQzN2MtMC4zNTEtMC42NS0xLjAyNS0xLjA1Ni0xLjc2NS0xLjA1NkgyLjA5M2MtMC43MzYsMC0xLjQxNCwwLjQwNS0xLjc2MiwxLjA1NiAgIGMtMC4wNTksMC4xMDktMC4xMjcsMC4yNTMtMC4xODQsMC40MjZDLTAuMTUsMTkuMjUxLDAuMDExLDIwLjI4LDAuNTYxLDIwLjk3MXoiIGZpbGw9IiMwMDAwMDAiLz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8L3N2Zz4K" />
                                    </span>
                            </ul>
                        </div>
                       
                        <div class="col-xs-12 no-padding panel-collapse collapse" id="collapse_fac_pag"  data-toggle="collapse" aria-expanded="false">
                                <div class="col-xs-12" style="padding-left:39px;">
                                    <div class="user-account-heading hidden-xs d-flex align-items-center justify-content-space-between">
                                            <div class="col-xs-12 col-sm-7 col-lg-8 col-one user-account-item">
                                                    {{ trans(\Config::get('app.theme').'-app.user_panel.lot') }}
                                            </div>
                                            <div class="col-xs-12 col-sm-2 col-one user-account-fecha text-right">
                                                    {{ trans(\Config::get('app.theme').'-app.user_panel.total_fact') }}
                                            </div>
                                            <div class="col-xs-12 col-sm-3 col-lg-2 col-one user-account-max-bid text-right">
                                                    {{ trans(\Config::get('app.theme').'-app.user_panel.total_price_fact') }}
                                            </div>
                                        </div>
                                    </div>
                                  
                     <form id="facturas_pag"> 
                                @foreach($data['bills'] as $key_bill => $bills)
              
                                 <div class="factura col-md-12 d-flex">
                                    <div class="factura_check" style="position: relative">
                                            <label for="checkFactura">
                                                   
                    
                                                </label>
                                    </div>
                                    <div class="factura_box d-flex justify-content-center align-items-center col-xs-12 col-md-12">
                                        <div class="visible-md visible-lg factura_icon col-xs-2 ">
                                                @if(!empty($bills->factura) && file_exists($bills->factura) && !empty($bills->afra_cobro1) && !empty($bills->nfra_cobro1))
                                                <a style="width: 100%" target="_blank" href="/factura/{{$bills->afra_cobro1}}-{{$bills->nfra_cobro1}}">
                                                    <img width="40" src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTguMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDU4IDU4IiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCA1OCA1ODsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHdpZHRoPSI2NHB4IiBoZWlnaHQ9IjY0cHgiPgo8Zz4KCTxwYXRoIGQ9Ik01MC45NSwxMi4xODdsLTAuNzcxLTAuNzcxTDQwLjA4NCwxLjMyMUwzOS4zMTMsMC41NUMzOC45NjQsMC4yMDEsMzguNDgsMCwzNy45ODUsMEg4Ljk2M0M3Ljc3NywwLDYuNSwwLjkxNiw2LjUsMi45MjZWMzkgICB2MTYuNTM3VjU2YzAsMC44MzcsMC44NDIsMS42NTMsMS44MzgsMS45MWMwLjA1LDAuMDEzLDAuMDk4LDAuMDMyLDAuMTUsMC4wNDJDOC42NDQsNTcuOTgzLDguODAzLDU4LDguOTYzLDU4aDQwLjA3NCAgIGMwLjE2LDAsMC4zMTktMC4wMTcsMC40NzUtMC4wNDhjMC4wNTItMC4wMSwwLjEtMC4wMjksMC4xNS0wLjA0MkM1MC42NTgsNTcuNjUzLDUxLjUsNTYuODM3LDUxLjUsNTZ2LTAuNDYzVjM5VjEzLjk3OCAgIEM1MS41LDEzLjIxMSw1MS40MDgsMTIuNjQ1LDUwLjk1LDEyLjE4N3ogTTQ3LjkzNSwxMkgzOS41VjMuNTY1TDQ3LjkzNSwxMnogTTguOTYzLDU2Yy0wLjA3MSwwLTAuMTM1LTAuMDI2LTAuMTk4LTAuMDQ5ICAgQzguNjA5LDU1Ljg3Nyw4LjUsNTUuNzIxLDguNSw1NS41MzdWNDFoNDF2MTQuNTM3YzAsMC4xODQtMC4xMDksMC4zMzktMC4yNjUsMC40MTRDNDkuMTcyLDU1Ljk3NCw0OS4xMDgsNTYsNDkuMDM3LDU2SDguOTYzeiAgICBNOC41LDM5VjIuOTI2QzguNSwyLjcwOSw4LjUzMywyLDguOTYzLDJoMjguNTk1QzM3LjUyNSwyLjEyNiwzNy41LDIuMjU2LDM3LjUsMi4zOTFWMTRoMTEuNjA5YzAuMTM1LDAsMC4yNjQtMC4wMjUsMC4zOS0wLjA1OCAgIGMwLDAuMDE1LDAuMDAxLDAuMDIxLDAuMDAxLDAuMDM2VjM5SDguNXoiIGZpbGw9IiMwMDAwMDAiLz4KCTxwYXRoIGQ9Ik0yMi4wNDIsNDQuNzQ0Yy0wLjMzMy0wLjI3My0wLjcwOS0wLjQ3OS0xLjEyOC0wLjYxNWMtMC40MTktMC4xMzctMC44NDMtMC4yMDUtMS4yNzEtMC4yMDVoLTIuODk4VjU0aDEuNjQxdi0zLjYzN2gxLjIxNyAgIGMwLjUyOCwwLDEuMDEyLTAuMDc3LDEuNDQ5LTAuMjMyczAuODExLTAuMzc0LDEuMTIxLTAuNjU2YzAuMzEtMC4yODIsMC41NTEtMC42MzEsMC43MjUtMS4wNDZjMC4xNzMtMC40MTUsMC4yNi0wLjg3NywwLjI2LTEuMzg4ICAgYzAtMC40ODMtMC4xMDMtMC45MTgtMC4zMDgtMS4zMDZTMjIuMzc1LDQ1LjAxOCwyMi4wNDIsNDQuNzQ0eiBNMjEuNDIsNDguMDczYy0wLjEwMSwwLjI3OC0wLjIzMiwwLjQ5NC0wLjM5NiwwLjY0OSAgIGMtMC4xNjQsMC4xNTUtMC4zNDQsMC4yNjctMC41NCwwLjMzNWMtMC4xOTYsMC4wNjgtMC4zOTUsMC4xMDMtMC41OTUsMC4xMDNoLTEuNTA0di0zLjk5MmgxLjIzYzAuNDE5LDAsMC43NTYsMC4wNjYsMS4wMTIsMC4xOTggICBjMC4yNTUsMC4xMzIsMC40NTMsMC4yOTYsMC41OTUsMC40OTJjMC4xNDEsMC4xOTYsMC4yMzQsMC40MDEsMC4yOCwwLjYxNWMwLjA0NSwwLjIxNCwwLjA2OCwwLjQwMywwLjA2OCwwLjU2NyAgIEMyMS41Nyw0Ny40NTEsMjEuNTIsNDcuNzk1LDIxLjQyLDQ4LjA3M3oiIGZpbGw9IiMwMDAwMDAiLz4KCTxwYXRoIGQ9Ik0zMS45NTQsNDUuNGMtMC40MjQtMC40NDYtMC45NTctMC44MDUtMS42LTEuMDczcy0xLjM4OC0wLjQwMy0yLjIzNS0wLjQwM2gtMy4wMzVWNTRoMy44MTQgICBjMC4xMjcsMCwwLjMyMy0wLjAxNiwwLjU4OC0wLjA0OGMwLjI2NC0wLjAzMiwwLjU1Ni0wLjEwNCwwLjg3NS0wLjIxOWMwLjMxOS0wLjExNCwwLjY0OS0wLjI4NSwwLjk5MS0wLjUxMyAgIHMwLjY0OS0wLjU0LDAuOTIzLTAuOTM3czAuNDk5LTAuODg5LDAuNjc3LTEuNDc3czAuMjY3LTEuMjk3LDAuMjY3LTIuMTI2YzAtMC42MDItMC4xMDUtMS4xODgtMC4zMTQtMS43NTcgICBDMzIuNjk0LDQ2LjM1NSwzMi4zNzgsNDUuODQ3LDMxLjk1NCw0NS40eiBNMzAuNzU4LDUxLjczYy0wLjQ5MiwwLjcxMS0xLjI5NCwxLjA2Ni0yLjQwNiwxLjA2NmgtMS42Mjd2LTcuNjI5aDAuOTU3ICAgYzAuNzg0LDAsMS40MjIsMC4xMDMsMS45MTQsMC4zMDhzMC44ODIsMC40NzQsMS4xNjksMC44MDdzMC40OCwwLjcwNCwwLjU4MSwxLjExNGMwLjEsMC40MSwwLjE1LDAuODI1LDAuMTUsMS4yNDQgICBDMzEuNDk2LDQ5Ljk4OSwzMS4yNSw1MS4wMiwzMC43NTgsNTEuNzN6IiBmaWxsPSIjMDAwMDAwIi8+Cgk8cG9seWdvbiBwb2ludHM9IjM1LjU5OCw1NCAzNy4yNjYsNTQgMzcuMjY2LDQ5LjQ2MSA0MS40NzcsNDkuNDYxIDQxLjQ3Nyw0OC4zNCAzNy4yNjYsNDguMzQgMzcuMjY2LDQ1LjE2OCA0MS45LDQ1LjE2OCAgICA0MS45LDQzLjkyNCAzNS41OTgsNDMuOTI0ICAiIGZpbGw9IiMwMDAwMDAiLz4KCTxwYXRoIGQ9Ik0zOC40MjgsMjIuOTYxYy0wLjkxOSwwLTIuMDQ3LDAuMTItMy4zNTgsMC4zNThjLTEuODMtMS45NDItMy43NC00Ljc3OC01LjA4OC03LjU2MmMxLjMzNy01LjYyOSwwLjY2OC02LjQyNiwwLjM3My02LjgwMiAgIGMtMC4zMTQtMC40LTAuNzU3LTEuMDQ5LTEuMjYxLTEuMDQ5Yy0wLjIxMSwwLTAuNzg3LDAuMDk2LTEuMDE2LDAuMTcyYy0wLjU3NiwwLjE5Mi0wLjg4NiwwLjYzNi0xLjEzNCwxLjIxNSAgIGMtMC43MDcsMS42NTMsMC4yNjMsNC40NzEsMS4yNjEsNi42NDNjLTAuODUzLDMuMzkzLTIuMjg0LDcuNDU0LTMuNzg4LDEwLjc1Yy0zLjc5LDEuNzM2LTUuODAzLDMuNDQxLTUuOTg1LDUuMDY4ICAgYy0wLjA2NiwwLjU5MiwwLjA3NCwxLjQ2MSwxLjExNSwyLjI0MmMwLjI4NSwwLjIxMywwLjYxOSwwLjMyNiwwLjk2NywwLjMyNmgwYzAuODc1LDAsMS43NTktMC42NywyLjc4Mi0yLjEwNyAgIGMwLjc0Ni0xLjA0OCwxLjU0Ny0yLjQ3NywyLjM4My00LjI1MWMyLjY3OC0xLjE3MSw1Ljk5MS0yLjIyOSw4LjgyOC0yLjgyMmMxLjU4LDEuNTE3LDIuOTk1LDIuMjg1LDQuMjExLDIuMjg1ICAgYzAuODk2LDAsMS42NjQtMC40MTIsMi4yMi0xLjE5MWMwLjU3OS0wLjgxMSwwLjcxMS0xLjUzNywwLjM5LTIuMTZDNDAuOTQzLDIzLjMyNywzOS45OTQsMjIuOTYxLDM4LjQyOCwyMi45NjF6IE0yMC41MzYsMzIuNjM0ICAgYy0wLjQ2OC0wLjM1OS0wLjQ0MS0wLjYwMS0wLjQzMS0wLjY5MmMwLjA2Mi0wLjU1NiwwLjkzMy0xLjU0MywzLjA3LTIuNzQ0QzIxLjU1NSwzMi4xOSwyMC42ODUsMzIuNTg3LDIwLjUzNiwzMi42MzR6ICAgIE0yOC43MzYsOS43MTJjMC4wNDMtMC4wMTQsMS4wNDUsMS4xMDEsMC4wOTYsMy4yMTZDMjcuNDA2LDExLjQ2OSwyOC42MzgsOS43NDUsMjguNzM2LDkuNzEyeiBNMjYuNjY5LDI1LjczOCAgIGMxLjAxNS0yLjQxOSwxLjk1OS01LjA5LDIuNjc0LTcuNTY0YzEuMTIzLDIuMDE4LDIuNDcyLDMuOTc2LDMuODIyLDUuNTQ0QzMxLjAzMSwyNC4yMTksMjguNzU5LDI0LjkyNiwyNi42NjksMjUuNzM4eiAgICBNMzkuNTcsMjUuMjU5QzM5LjI2MiwyNS42OSwzOC41OTQsMjUuNywzOC4zNiwyNS43Yy0wLjUzMywwLTAuNzMyLTAuMzE3LTEuNTQ3LTAuOTQ0YzAuNjcyLTAuMDg2LDEuMzA2LTAuMTA4LDEuODExLTAuMTA4ICAgYzAuODg5LDAsMS4wNTIsMC4xMzEsMS4xNzUsMC4xOTdDMzkuNzc3LDI0LjkxNiwzOS43MTksMjUuMDUsMzkuNTcsMjUuMjU5eiIgZmlsbD0iIzAwMDAwMCIvPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+Cjwvc3ZnPgo=" />
                                                </a>
                                            @else
                                                <img width="40" src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTkuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDUxMi4wMDEgNTEyLjAwMSIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgNTEyLjAwMSA1MTIuMDAxOyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSIgd2lkdGg9IjE2cHgiIGhlaWdodD0iMTZweCI+CjxnPgoJPGc+CgkJPHBhdGggZD0iTTQ2OS4wNzIsOTEuOTI5bC04OS04OUMzNzguMTk2LDEuMDU0LDM3NS42NTIsMCwzNzMuMDAxLDBoLTMwM2MtMTYuNTQyLDAtMzAsMTMuNDU4LTMwLDMwdjQ1Mi4wMDEgICAgYzAsMTYuNTQyLDEzLjQ1OCwzMCwzMCwzMGgzNzJjMTYuNTQyLDAsMzAtMTMuNDU4LDMwLTMwVjk5QzQ3Mi4wMDEsOTYuMzQ4LDQ3MC45NDcsOTMuODA0LDQ2OS4wNzIsOTEuOTI5eiBNMzgzLjAwMSwzNC4xNDMgICAgTDQzNy44NTgsODloLTQ0Ljg1OGMtNS41MTQsMC0xMC00LjQ4Ni0xMC0xMFYzNC4xNDN6IE00NTIuMDAxLDQ4Mi4wMDFjMCw1LjUxNS00LjQ4NywxMC0xMCwxMGgtMzcyYy01LjUxNCwwLTEwLTQuNDg2LTEwLTEwVjMwICAgIGMwLTUuNTE0LDQuNDg2LTEwLDEwLTEwaDI5M3Y1OWMwLDE2LjU0MiwxMy40NTgsMzAsMzAsMzBoNTlWNDgyLjAwMXoiIGZpbGw9IiMwMDAwMDAiLz4KCTwvZz4KPC9nPgo8Zz4KCTxnPgoJCTxwYXRoIGQ9Ik0xMTEuMTA5LDY2Ljk3Yy0xLjg1OS0xLjg2LTQuNDM5LTIuOTMtNy4wNjktMi45M2MtMi42NDEsMC01LjIxLDEuMDctNy4wNywyLjkzYy0xLjg2LDEuODYtMi45Myw0LjQ0LTIuOTMsNy4wNyAgICBzMS4wNjksNS4yMSwyLjkzLDcuMDdzNC40MjksMi45Myw3LjA3LDIuOTNjMi42MywwLDUuMjEtMS4wNyw3LjA2OS0yLjkzYzEuODYtMS44NiwyLjkzMS00LjQ0LDIuOTMxLTcuMDcgICAgUzExMi45Nyw2OC44MywxMTEuMTA5LDY2Ljk3eiIgZmlsbD0iIzAwMDAwMCIvPgoJPC9nPgo8L2c+CjxnPgoJPGc+CgkJPHBhdGggZD0iTTI2MS45OTksNjQuMDM5SDE0MS45NzNjLTUuNTIyLDAtMTAsNC40NzctMTAsMTBzNC40NzgsMTAsMTAsMTBoMTIwLjAyNmM1LjUyMiwwLDEwLTQuNDc3LDEwLTEwICAgIFMyNjcuNTIyLDY0LjAzOSwyNjEuOTk5LDY0LjAzOXoiIGZpbGw9IiMwMDAwMDAiLz4KCTwvZz4KPC9nPgo8Zz4KCTxnPgoJCTxwYXRoIGQ9Ik00MDguMDAxLDE2MGgtMzA0Yy01LjUyMiwwLTEwLDQuNDc3LTEwLDEwdjE5MmMwLDUuNTIzLDQuNDc4LDEwLDEwLDEwaDMwNGM1LjUyMiwwLDEwLTQuNDc3LDEwLTEwVjE3MCAgICBDNDE4LjAwMSwxNjQuNDc3LDQxMy41MjIsMTYwLDQwOC4wMDEsMTYweiBNMTU2LjAwMSwzNTJoLTQyYzAsMCwwLTQ0LDAtNDRoNDJWMzUyeiBNMTU2LjAwMSwyODhoLTQyYzAsMCwwLTQ0LDAtNDRoNDJWMjg4eiAgICAgTTE1Ni4wMDEsMjI0aC00MnYtNDRoNDJWMjI0eiBNMjg2LjAwMSwzNTJoLTExMHYtNDRoMTEwVjM1MnogTTI4Ni4wMDEsMjg4aC0xMTB2LTQ0aDExMFYyODh6IE0yODYuMDAxLDIyNGgtMTEwdi00NGgxMTBWMjI0eiAgICAgTTM5OC4wMDEsMzUyaC05MnYtNDRoOTJWMzUyeiBNMzk4LjAwMSwyODhoLTkydi00NGg5MlYyODh6IE0zOTguMDAxLDIyNGgtOTJ2LTQ0aDkyVjIyNHoiIGZpbGw9IiMwMDAwMDAiLz4KCTwvZz4KPC9nPgo8Zz4KCTxnPgoJCTxwYXRoIGQ9Ik0zMDMuMDY5LDQxMC45M2MtMS44NTktMS44Ni00LjQzOS0yLjkzLTcuMDY5LTIuOTNzLTUuMjEsMS4wNy03LjA3LDIuOTNzLTIuOTMsNC40NC0yLjkzLDcuMDdzMS4wNjksNS4yMSwyLjkzLDcuMDcgICAgYzEuODYxLDEuODYsNC40NCwyLjkzLDcuMDcsMi45M3M1LjIxLTEuMDcsNy4wNjktMi45M2MxLjg2LTEuODYsMi45MzEtNC40NCwyLjkzMS03LjA3UzMwNC45MzEsNDEyLjc5LDMwMy4wNjksNDEwLjkzeiIgZmlsbD0iIzAwMDAwMCIvPgoJPC9nPgo8L2c+CjxnPgoJPGc+CgkJPHBhdGggZD0iTTQwOC4wMDEsNDA4aC03MGMtNS41MjIsMC0xMCw0LjQ3Ny0xMCwxMHM0LjQ3OCwxMCwxMCwxMGg3MGM1LjUyMiwwLDEwLTQuNDc3LDEwLTEwUzQxMy41MjIsNDA4LDQwOC4wMDEsNDA4eiIgZmlsbD0iIzAwMDAwMCIvPgoJPC9nPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+Cjwvc3ZnPgo=" />
                                            @endif                                  </div>
                                        <div class="factura_datos col-xs-12 col-md-6">
                                            <div class="factura_titulo">
                                                <div>{{ trans(\Config::get('app.theme').'-app.user_panel.n_bill') }} {{$pendiente->anum_pcob}}/{{$pendiente->num_pcob}} ( {{date('d-m-Y',strtotime ($pendiente->fec_pcob))}} )</div>
                                            </div>
                                            <div class="factura_gastos">
                                                    @php($precio_total = 0)
                                                  
                                                    @foreach($data['inf_factura_pag'] as $key_type => $inf_fact)
                                                       
                                                         @if(!empty($inf_fact[$bills->afra_cobro1][$bills->nfra_cobro1]))
                                                             @foreach($inf_fact[$bills->afra_cobro1][$bills->nfra_cobro1] as $fact)
                          
                                                            @if($data['tipo_tv_pag'][$bills->afra_cobro1][$bills->nfra_cobro1] == 'P')
                                                                @php($precio_total = $precio_total + ((round((($fact->basea_dvc1l*$fact->iva_dvc1l)/100),2)) + $fact->basea_dvc1l)- $fact->padj_dvc1l)
                                                            @elseif($data['tipo_tv_pag'][$bills->afra_cobro1][$bills->nfra_cobro1] == 'L')
                                                                 @php($precio_total = $precio_total + $fact->padj_dvc1l + $fact->basea_dvc1l + round((($fact->basea_dvc1l*$fact->iva_dvc1l)/100),2))
                                                             @elseif($data['tipo_tv_pag'][$bills->afra_cobro1][$bills->nfra_cobro1] == 'T')
                                                                 @php($precio_total = $precio_total + $fact->total_dvc1 + round((($fact->total_dvc1*$fact->iva_dvc1)/100),2))
                                                            @endif
                                                                            
                                                             @endforeach
                                                         @endif
                                                        
                                                     @endforeach
                                                
            
                                            </div>
                                        </div>
                                        <div class="col-xs-12 h-100 col-sm-2 col-lg-2 account-item-border">
                                                <div class="user-account-item-date d-flex align-items-center">
                                                        <div class="producto_resumen_info w-100">
                                                                <div class="w-100 text-right"> {{\Tools::moneyFormat($precio_total ,false,2)}} €</div>
                                                            </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 h-100 col-sm-2 col-lg-2 account-item-border">
                                                    <div class="user-account-item-date d-flex align-items-center">
                                                            <div class="producto_resumen_info w-100">
                                                                    <div class="w-100 text-right"><strong><span>{{\Tools::moneyFormat($bills->imp_cobro1,false,2)}}</span><span> €</span></strong></div>
                                                                </div>
                                                    </div>
                                                </div>
                                            
                                        
                                    </div>
                                </div>
                                @endforeach
                                

                            </form> 
                        </div>
                    
                </div>
            </div>
        </div>
</div>


<script>
    $( document ).ready(function() {
        reload_facturas();   
    });
    
    
</script>
@stop
