@extends('layouts.default')

@section('title')
    {{ $data['title'] }}
@stop

@section('content')
    <?php
    $bread[] = ['name' => $data['title']];
    ?>

    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 text-center color-letter">

                <h1 class="titlePage"> {{ $data['title'] }}</h1>
                @include('includes.breadcrumb')

            </div>
        </div>


        <div class="row">

            @if (isset($data['content']))
                <div class="col-xs-12">
                    <br><br>
                    {!! $data['content'] !!}
                </div>
                <div class="clearfix"></div>
            @endif


            <div class="col-xs-12">
                <div class="row form-group">
                    <form id="autoformulario" name="autoformulario" method="post">

						<input type="hidden" data-sitekey="{{ config('app.captcha_v3_public') }}" name="captcha_token" value="">
                        <input name="subject" type="hidden" value="{{ $data['title'] }}">

                        @foreach ($data['formulario'] as $k => $item)
                            @if ($data['formulario'][$k]['type'] == 'Hidden')
                                {!! $data['formulario'][$k]['formulario'] !!}
                            @elseif ($data['formulario'][$k]['type'] == 'TextArea')
                                <div class="input-effect col-xs-12">
                                    {!! $data['formulario'][$k]['formulario'] !!}
                                    <label><b
                                            class="red">*</b>{{ trans($theme . '-app.global.' . $k) }}</label>
                                </div>
                            @elseif ($data['formulario'][$k]['type'] == 'Image')
                                <div class="col-xs-12">
                                    {!! $data['formulario'][$k]['formulario'] !!}
                                </div>
                            @else
                                <div class="input-effect col-xs-12 col-md-4">
                                    {!! $data['formulario'][$k]['formulario'] !!}
                                    <label><b
                                            class="red">*</b>{{ trans($theme . '-app.global.' . $k) }}</label>
                                </div>
                            @endif
                        @endforeach

						<div class="col-xs-12">
							<div class="row mb-2">
								<div class="col-xs-12 col-md-8">
									<div class="check_term row">
										<div class="col-xs-2 col-md-1">
											<input class="newsletter form-control" id="bool__1__condiciones" name="condiciones"
												type="checkbox" value="on" autocomplete="off">
										</div>
										<div class="col-xs-10 col-md-11">
											<labelcheck_term
												for="accept_new"><?= trans($theme . '-app.emails.privacy_conditions') ?></label>
										</div>
									</div>
								</div>
								<div class="col-xs-12 mt-1">
									<p class="captcha-terms">
										{!! trans("$theme-app.global.captcha-terms") !!}
									</p>
								</div>
							</div>
						</div>
                        <br><br><br>
                        <div class="col-xs-12 text-center">
                            {!! $data['submit'] !!}
                        </div>

                    </form>
                </div>

            </div>
        </div>

	</div>

        <br><br><br>


    @stop
