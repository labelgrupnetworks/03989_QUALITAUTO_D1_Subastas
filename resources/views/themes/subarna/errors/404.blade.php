@extends('layouts.default')

@section('title')
    {{ trans($theme . '-app.head.title_app') }}
@stop

@section('content')
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

		.content {
			text-align: center
		}

		h1 {
			font-size: 2em;
			margin-bottom: 2rem;
			text-wrap: balance;
		}
    </style>

    <main class="not-found-page">
        <div class="content">
            <h1>{{ trans("$theme-app.global.page_not_found") }}</h1>

            <a href="/{{ App::getLocale() }}">
                {{ trans("$theme-app.global.go_home") }}
            </a>
        </div>
    </main>
@stop
