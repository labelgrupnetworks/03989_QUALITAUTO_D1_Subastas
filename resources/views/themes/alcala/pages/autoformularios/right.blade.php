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

            <div class="col-xs-12 col-md-7">
                <div class="row form-group">
                    <form id="autoformulario" name="autoformulario" method="post">

                        <input name="captcha_token" data-sitekey="{{ config('app.captcha_v3_public') }}" type="hidden"
                            value="">
                        <input name="subject" type="hidden" value="{{ $data['title'] }}">

                        @foreach ($data['formulario'] as $k => $item)
                            @if ($data['formulario'][$k]['type'] == 'Hidden')
                                {!! $data['formulario'][$k]['formulario'] !!}
                            @elseif ($data['formulario'][$k]['type'] == 'TextArea')
                                <div class="input-effect col-xs-12">
                                    {!! $data['formulario'][$k]['formulario'] !!}
                                    <label><b class="red">*</b>{{ trans($theme . '-app.global.' . $k) }}</label>
                                </div>
                            @elseif ($data['formulario'][$k]['type'] == 'Image')
                                <div class="col-xs-12">
                                    {!! $data['formulario'][$k]['formulario'] !!}
                                </div>
                            @else
                                <div class="input-effect col-xs-12 col-md-6">
                                    {!! $data['formulario'][$k]['formulario'] !!}
                                    <label><b class="red">*</b>{{ trans($theme . '-app.global.' . $k) }}</label>
                                </div>
                            @endif
                        @endforeach
                        <div class="clearfix"></div>
                        <br>
                        <div class="row">

                            <div class="col-xs-12">
                                <div class="check_term row">
                                    <div class="col-xs-2 col-md-2">
                                        <input class="form-control" id="bool__1__condiciones" name="condiciones"
                                            type="checkbox" value="on" autocomplete="off">
                                    </div>
                                    <div class="col-xs-10 col-md-10">
                                        <label
                                            for="accept_new"><?= trans($theme . '-app.emails.privacy_conditions') ?></label>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <br><br>
                            <div class="col-xs-12">
                                <p class="captcha-terms">
                                    {!! trans("$theme-app.global.captcha-terms") !!}
                                </p>
                            </div>


                            <div class="clearfix"></div>
                        </div>
                        <br><br><br>

                        <div class="col-xs-12 text-center">
                            {!! $data['submit'] !!}
                        </div>

                    </form>
                </div>
            </div>

            @if (isset($data['content']))
                <div class="col-md-5 col-xs-12" style="padding:20px 0 0 40px;">
                    {!! $data['content'] !!}
                </div>
            @endif

        </div>
    </div>

    <br><br><br>


@stop
