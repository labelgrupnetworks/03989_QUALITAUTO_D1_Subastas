
@extends('layouts.default')

@section('title')
	{{ trans($theme.'-app.head.title_app') }}
@stop

@section('content')
<div style="margin: 100px 0px 100px 0px;text-align:center">
	<h1 >{{ trans($theme.'-app.user_panel.redirect_redsys') }}</h1>
	<img src="/default/img/logos/redsys.jpg">
</div>
<form id="frmRedsys" name="frm" action="{{\Config::get("app.UrlRedsys")}}realizarPago" method="POST" >
<input type="hidden" name="Ds_SignatureVersion" value="{{ $version}}"/>
 <input type="hidden" name="Ds_MerchantParameters" value="{{ $params}}"/>
 <input type="hidden" name="Ds_Signature" value="{{ $signature}}"/>
</form>

<script>
$("#frmRedsys").submit();
</script>

@stop
