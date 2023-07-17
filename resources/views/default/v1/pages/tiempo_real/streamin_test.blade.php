@extends('layouts.default')

@section('content')
    <main style="min-height: 70vh">
        <div class="container pt-4">

            <div class="row">
                <div class="col-xs-12 col-md-8" style="float: none; margin: 0 auto">
					<h1>Stream</h1>

                    <div id="streaming">
                        @include('content.tr.tiempo_real_user.streaming')
                    </div>
                </div>
            </div>
        </div>
    </main>

    @push('stylesheets')
        <style>
            .stream-block {
                height: 100%;
                display: flex;
                flex-direction: column;
            }

            .stream-wrapper {
                position: relative;
                flex: 1;
            }

            #streaming {
                display: block;
            }
        </style>
    @endpush
@stop
