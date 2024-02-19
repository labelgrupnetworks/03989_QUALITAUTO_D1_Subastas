@extends('layouts.default')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-xs-12">
					<p class="consent_container">
						<span class="consent_ico consent_ico_{{$res}}"><i class="{{$res == 0 ? "fa fa-check" : "fa fa-times"}} consent_icon_size" aria-hidden="true"></i></span>
						<span class="consent_msg consent_msg_{{$res}}">{!! trans($theme . '-app.user_panel.resp_'.$res.'_wb_consentimiento') !!}</span>
					</p>
            </div>
        </div>
    </div>

@stop
