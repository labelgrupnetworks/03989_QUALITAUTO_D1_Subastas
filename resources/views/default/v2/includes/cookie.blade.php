@php
    $style = $style ?? 'popover'; // popover | bar
@endphp

<div class="cookies" data-style="{{ $style }}">
    <p class="cookies__title">{{ trans("$theme-app.cookies.cookies_policy") }}</p>

    <div class="cookies_content">
        <div class="cookies__messages">
            <p class="cookies__message">
                {{ trans("$theme-app.cookies.popover_description") }}
            </p>

            <p class="cookies__message">
                {{ trans("$theme-app.cookies.popover_description2") }}
            </p>
        </div>

        <div class="cookies_buttons">
            <button onclick="acceptAllCookies()">
                {{ trans("$theme-app.cookies.accept_all") }}
            </button>
            <button type="button" data-toggle="modal" data-bs-toggle="modal" data-target="#cookiesPersonalize" data-bs-target="#cookiesPersonalize">
                {{ trans("$theme-app.cookies.personalize") }}
            </button>
            <button onclick="rejectAllCookies()">
                {{ trans("$theme-app.cookies.reject_all") }}
            </button>
        </div>
    </div>

    <div class="cookies_links">
        @include('includes.cookies._cookies_links')
    </div>

</div>
