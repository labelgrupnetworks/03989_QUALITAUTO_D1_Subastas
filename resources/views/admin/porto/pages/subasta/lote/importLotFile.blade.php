@extends('admin::layouts.logged')
@section('content')
@php

	$type= request("type", "Excel")
@endphp
<section role="main" class="content-body">
        <header class="page-header">
                <div class="right-wrapper pull-right">
                        <ol class="breadcrumbs">
                                <li>
                                        <a href="/admin">
                                                <i class="fa fa-home"></i>
                                        </a>
                                </li>

                        </ol>

                        <a class="sidebar-right-toggle" ><i class="fa fa-chevron-left"></i></a>
                </div>
        </header>

    <h1>{{ trans("admin-app.button.import") }} {{ mb_strtolower(trans("admin-app.title.lots")) }}</h1>

	<a href="{{ url()->previous() }}" class="btn btn-primary right">{{ trans("admin-app.button.back") }}</a>

	<br><br>

	<form name="uploadLotFile" id="uploadLotFile" action="/admin/lote/fileImport/{{$type}}" method="post" enctype="multipart/form-data">

		@csrf

		<h3>{{ trans("admin-app.title.upload_file") }} {{$type}}</h3>
		<i class="fa fa-2x fa-info-circle" style="position:relative;top:6px;"></i>&nbsp;
		<span class="badge">
			{{ trans("admin-app.information.upload_image_size_quantity") }}
		</span>
		<br>


		<br><br><br>

		<div class="row">
			<div class="col-xs-12 col-md-4 text-center">
				<input type="file" id="file" name="file">
				<input type="hidden" id="subasta" name="subasta" value="{{$subasta}}">
				<br><br>
				<button class="btn btn-primary" type="submit">{{ trans("admin-app.button.load") }}</button>
			</div>
			<div class="col-xs-12 col-md-8 text-center">
				<div id="div_log" class="log">

				</div>
				<div class="progress">
					<div id="progressBarImg" class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
					  <span class="sr-only"><span id="progressBarValue">{{ trans("admin-app.general.zero_percent") }}</span> <span>{{ trans("admin-app.success.completed") }}</span></span>
					</div>
				</div>
			</div>
		</div>

	</form>

@stop
