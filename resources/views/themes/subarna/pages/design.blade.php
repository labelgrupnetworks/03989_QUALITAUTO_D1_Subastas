@extends('layouts.default')

@php
    $fws = [100, 200, 300, 400, 500, 600, 700, 800, 900];
    $fontSizes = [
        '--fs-xsmall' => '16px',
        '--fs-small' => '18px',
        '--fs-default' => '24px',
        '--fs-large' => '28px',
        '--fs-xxxlarge' => '40px',
    ];
@endphp

@section('content')
    <main class="design-page">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    @foreach ($fontSizes as $varFs => $fs)
						<p style="font-size: var({{ $varFs }}); margin-top:3rem;">Font Size: {{ $fs }}</p>
						@foreach ($fws as $fw)
                            <p style="font-size: var({{ $varFs }}); font-weight: {{ $fw }}" class="max-line-1">
                                {{ $fw }} - Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed a nulla
                                sit amet leo venenatis feugiat a id augue.
                            </p>
                        @endforeach
                    @endforeach


                </div>
            </div>
        </div>
    </main>
@stop
