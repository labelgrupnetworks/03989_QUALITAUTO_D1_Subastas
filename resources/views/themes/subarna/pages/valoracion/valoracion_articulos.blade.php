@extends('layouts.default')

@section('title')
    {{ trans($theme . '-app.head.title_app') }}
@stop

@section('content')
    <?php
    $bread[] = ['name' => $data['title']];
    ?>
    @include('includes.breadcrumb')

	<script src="https://www.google.com/recaptcha/api.js?hl={{ \Config::get('app.locale') }}" async defer></script>
    <div id="">
        <div class="container" id="return-valoracion">
            <div class="">
                <h1 class="titleSingle_corp">{{ trans($theme . '-app.valoracion_gratuita.solicitud_valoracion') }}</h1>
            </div>
            <br>
            <?= trans($theme . '-app.valoracion_gratuita.desc_assessment') ?>
            <form class="form" id="form-valoracion-adv">
                <input name="_token" type="hidden" value="{{ csrf_token() }}">
                <br>
                <textarea class="form-control" id="exampleTextarea" name="descripcion" rows="3" required
                    placeholder="{{ trans($theme . '-app.valoracion_gratuita.description') }}"></textarea>
                <br>
                <div clas="row">
                    <div class="col-md-4">
                        <br>
                        <input id="files" name="imagen[]" type="file" accept="image/png, image/jpeg" multiple
                            required>
                    </div>
                    <div class="col-md-8">
                        <?= trans($theme . '-app.valoracion_gratuita.desc_img') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 form-group">
                        <br>
                        <br>
                        <?= trans($theme . '-app.valoracion_gratuita.name') ?>
                        <input class="form-control" name="name" type="text" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <?= trans($theme . '-app.valoracion_gratuita.email') ?>
                        <input class="form-control" name="email" type="email" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <?= trans($theme . '-app.valoracion_gratuita.telf') ?>
                        <input class="form-control" name="telf" type="text" required>
                    </div>
                </div>
                <input name="email_category" type="hidden" value="tasaciones@subarna.net">

				<div class="row">
					<div class="col-xs-12">
						<div class="g-recaptcha" data-sitekey="{{\Config::get('app.codRecaptchaEmailPublico')}}" data-callback="onSubmit">
						</div>
					</div>
				</div>

                <div clas="row">
                    <div class="col-md-12">
                        <br>
                        <button class="btn-valoracion  btn btn-primary" id="valoracion-adv" type="submit">
                            <div class='loader hidden'></div>{{ trans($theme . '-app.valoracion_gratuita.send') }}
                        </button>
                        <br><br>
                        <h4 class="valoracion-h4 hidden msg_valoracion">{{ trans($theme . '-app.valoracion_gratuita.error') }}
                        </h4>
                        <br>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop
