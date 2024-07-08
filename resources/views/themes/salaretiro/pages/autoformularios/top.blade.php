@extends('layouts.default')

@section('title')
    {{ $data['title'] }}
@stop

@section('content')
    <?php
    $bread[] = ['name' => $data['title']];
    ?>
    <script src="https://www.google.com/recaptcha/api.js?hl={{ \Config::get('app.locale') }}" async defer></script>

    @include('includes.breadcrumb')

    <div class="container autoformulario">

        <h1 class="titlePage"> {{ $data['title'] }}</h1>

        <div class="row">

            @if (isset($data['content']))
                <div class="col-xs-12 mb-2">
                    {!! $data['content'] !!}
                </div>
                <div class="clearfix"></div>
            @endif



            <div class="col-xs-12 col-md-offset-2 col-md-8">
                <div class="row form-group">
                    <form id="autoformulario" name="autoformulario" method="post">

                        <input name="subject" type="hidden" value="{{ $data['title'] }}">

                        @foreach ($data['formulario'] as $k => $item)
                            @if ($data['formulario'][$k]['type'] == 'Hidden')
                                {!! $data['formulario'][$k]['formulario'] !!}
                            @elseif ($data['formulario'][$k]['type'] == 'TextArea')
                                <div class="input-group col-xs-12">
                                    <label>
                                        @if ($data['formulario'][$k]['mandatory'])
                                            <b class="red">*</b>
                                        @endif
                                        {{ trans($theme . '-app.global.' . $k) }}
                                    </label>
                                    {!! $data['formulario'][$k]['formulario'] !!}

                                </div>
                            @elseif ($data['formulario'][$k]['type'] == 'Image')
                                <div class="col-xs-12">
                                    {!! $data['formulario'][$k]['formulario'] !!}
                                </div>
                            @elseif($data['formulario'][$k]['type'] == 'File')
                                <div class="input-group col-xs-12">
                                    <label style="display: block">
                                        @if ($data['formulario'][$k]['mandatory'])
                                            <b class="red">*</b>
                                        @endif
                                        {{ trans($theme . '-app.global.' . $k) }}
                                    </label>
                                    {!! $data['formulario'][$k]['formulario'] !!}

                                </div>
                            @else
                                <div class="input-group input-effect col-xs-12">
                                    <label>
                                        @if ($data['formulario'][$k]['mandatory'])
                                            <b class="red">*</b>
                                        @endif
                                        {{ trans($theme . '-app.global.' . $k) }}
                                    </label>
                                    {!! $data['formulario'][$k]['formulario'] !!}

                                </div>
                            @endif
                        @endforeach

                        <div class="col-xs-12 input-group">
                            <div class="g-recaptcha" data-sitekey="{{ \Config::get('app.codRecaptchaEmailPublico') }}"
                                data-callback="onSubmit">
                            </div>
                        </div>

                        <div class="col-xs-12 input-group mb-2">
                            <div class="check_term">
                                <label for="condiciones">
                                    <input class="newsletter" id="bool__1__condiciones" name="condiciones" type="checkbox"
                                        value="on" autocomplete="off">
                                    <span>{!! trans($theme . '-app.emails.privacy_conditions') !!}</span>
                                </label>
                            </div>
                        </div>

                        <div class="col-xs-12 input-group">
                            {!! $data['submit'] !!}
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
@stop
