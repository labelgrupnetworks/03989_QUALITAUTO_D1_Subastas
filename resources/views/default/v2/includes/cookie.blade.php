@php
    $style = $style ?? 'popover'; // popover | bar
@endphp

<div class="cookies" data-style="{{ $style }}">
    <p class="cookies__title">{{ trans("web.cookies.cookies_policy") }}</p>

    <div class="cookies_content">
        <div class="cookies__messages">
            <p class="cookies__message">
                {{ trans("web.cookies.popover_description") }}
            </p>

            <p class="cookies__message">
                {{ trans("web.cookies.popover_description2") }}
            </p>
        </div>

        <div class="cookies_buttons">
            <button onclick="acceptAllCookies()">
                {{ trans("web.cookies.accept_all") }}
            </button>
            <button type="button" data-toggle="modal" data-bs-toggle="modal" data-target="#cookiesPersonalize" data-bs-target="#cookiesPersonalize">
                {{ trans("web.cookies.personalize") }}
            </button>
            <button onclick="rejectAllCookies()">
                {{ trans("web.cookies.reject_all") }}
            </button>
        </div>
    </div>

    <div class="cookies_links">
        @include('includes.cookies._cookies_links')
    </div>

</div>
