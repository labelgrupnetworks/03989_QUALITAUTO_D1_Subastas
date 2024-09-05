@extends('layouts.default')

@section('content')
    @php
        $bread[] = ['name' => 'Newsletters'];
    @endphp

    <div class="container">
        @include('includes.breadcrumb')
        <h2 class="h2 mb-4 page-title">{{ trans("$theme-app.foot.newsletters") }}</h2>
    </div>

    <section class="newsletters-page container mb-3">
        <div class="row d-flex flex-column justify-content-center">
            <div class="col-xs-12 col-md-10 col-lg-8 mx-auto mb-5">
                <h2 class="h3 mb-2 page-title">{{ trans("$theme-app.foot.subscriptions") }}</h2>
                <p>{{ trans("$theme-app.foot.agree_notifications") }}</p>

                <div class="my-3">
                    <form id="newsletterForm" method="POST">
                        <input type="hidden" id="lang-newsletter" name="lang" value="{{ config('app.locale') }}">
						<input type="hidden" data-sitekey="{{ config('app.captcha_v3_public') }}" name="captcha_token" value="">
                        <input type="hidden" name="email" value="{{ request('email') }}">
                        <input type="hidden" name="isMultiCompany" value="{{ $isMultiCompany }}">

                        <div class="list-group shadow">
                            @foreach ($newsletters as $id_newsletters => $name_newsletters)
                                @php
                                    $isChecked = in_array($id_newsletters, $suscriptions);
                                @endphp
                                <div class="list-group-item">
                                    <label class="d-flex align-items-center justify-content-space-between">
                                        <strong class="mb-0 newsletter_label">{{ $name_newsletters }}</strong>
                                        {{-- <p class="text-muted mb-0">Donec in quam sed urna bibendum tincidunt quis mollis mauris.</p> --}}
                                        <input type="checkbox" class="newsletter" name="families[{{ $id_newsletters }}]"
                                            value="{{ $id_newsletters }}"
                                            @if ($isChecked) checked="checked" @endif>
                                    </label>
                                </div>
                            @endforeach
                            <div class="list-group-item">
                                <label class="d-flex align-items-center justify-content-space-between">
                                    <strong class="mb-0 newsletter_label">{!! trans("$theme-app.login_register.read_conditions_politic") !!}</strong>
                                    <input name="condiciones" type="checkbox" type="checkbox" class="form-check-input"
                                        @if (count($suscriptions)) checked="checked" @endif>
                                </label>
                            </div>

                        </div>
                        <button type="submit"
                            class="btn button-principal">{{ trans("$theme-app.foot.newsletter_button") }}</button>
                    </form>
                </div>

            </div>

            <div class="col-xs-12 col-md-10 col-lg-8 mx-auto d-none">
                <p>{{ trans("$theme-app.foot.unsuscribe_from") }} <a class="popup-modal" id="unsuscribeMailChimp"
                        href="#confirm-unsuscribe"
                        data-href="{{ route('newsletter.unsuscribe', ['lang' => config('app.locale'), 'email' => request('email'), 'hash' => md5(request('email'))]) }}">{{ trans("$theme-app.foot.unsuscribe_link") }}</a>
                </p>
            </div>
        </div>

    </section>

    <!-- Simple pop-up dialog box, containing a form -->
    <dialog class="newsletter-dialog" id="favDialog">
        <div class="dialog-content">
            <p>¿Seguro que quiere cancelar la suscripción?</p>
            <div class="">
                <button class="btn btn-default dialog-accept" onclick="unsuscribe()">Aceptar</button>
                <button class="btn btn-default dialog-cancel" onclick="closeDialog()">Cancelar</button>
            </div>
        </div>
    </dialog>

    <script>
        const dialog = document.getElementById("favDialog");
        const linkUnsuscribe = document.getElementById("unsuscribeMailChimp");

        linkUnsuscribe.addEventListener('click', showDialog);

        function showDialog(event) {
            event.preventDefault();
            dialog.showModal();
        }

        function closeDialog() {
            dialog.close();
        }

        function unsuscribe() {
            const href = linkUnsuscribe.dataset.href;
			location.href = href;
        }
    </script>

@stop
