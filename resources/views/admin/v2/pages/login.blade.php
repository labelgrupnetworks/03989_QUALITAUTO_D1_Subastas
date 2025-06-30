@extends('admin::layouts.login')

@section('title', __('auth.login'))

@section('content')
<div class="col-xl-5">
    <div class="card auth-card">
        <div class="card-body px-3 py-5">
            <div class="mx-auto mb-4 text-center auth-logo">
                <a href="{{ route('home', [], false) }}" class="logo-dark">
                    <img class="img-fluid w-50"  src="/themes/{{$theme}}/assets/img/logo.png" alt="Subalia">
                </a>

                <a href="{{ route('home', [], false) }}" class="logo-light">
                    <img class="img-fluid w-50"  src="/themes/{{$theme}}/assets/img/logo.png" alt="Subalia">
                </a>
            </div>

            <h2 class="fw-bold text-uppercase text-center fs-18">{{ trans('web.login_register.login') }}</h2>
            <p class="text-muted text-center mt-1 mb-4">{{ trans('web.login_register.credentials') }}</p>

            <div class="px-4">
                <form class="authentication-form accerder-user-form" id="accerder-user-form" novalidate>
                    @csrf

                    <div class="alert alert-danger alert-dismissible fade show d-none message-error-log" role="alert">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <p class="text-danger mb-3">{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label" for="email">{{ trans('web.login_register.email') }}</label>
                        <input type="email" id="email" name="email"
                               class="form-control bg-light bg-opacity-50 border-light py-2 @error('email') is-invalid @enderror"
                               placeholder="demo@user.com"  required>
                        <div class="invalid-feedback">
                            {{ trans('web.msg_error.form_required' , ['attribute' => 'email']) }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <a href="{{ \Routing::slug('password_recovery') }}"
                           class="float-end text-muted text-unline-dashed ms-1">Restablecer contraseña</a>
                        <label class="form-label" for="password">{{ trans('web.login_register.contraseña') }}</label>
                        <input type="password" id="password"
                               class="form-control bg-light bg-opacity-50 border-light py-2 @error('password') is-invalid @enderror"
                               placeholder="************" name="password" required>
                        <div class="invalid-feedback">
                            {{ trans('web.msg_error.form_required' , ['attribute' => 'contaseña']) }}
                        </div>
                    </div>


                    <div class="mb-1 text-center d-grid">

                        <button class="btn btn-danger accerder-user py-2 fw-medium" type="submit" id="accerder-user">
                            <div class="spinner-border d-none text-white spinner-border-sm me-3" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            {{ trans('web.login_register.generic_name') }}</button>
                    </div>
                </form>
            </div> <!-- end col -->
        </div> <!-- end card-body -->
    </div> <!-- end card -->

    {{-- No tendremos página de registro --}}
    <p class="mb-0 text-center text-white">
        {{ Config::get('app.name')}} {{ date('Y') }}. Todos los derechos reservados.
    </p>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Obtener el formulario
        const form = document.getElementById('accerder-user-form');

        // Validación en el evento submit
        form.addEventListener('submit', function(event) {
            let isValid = true;

            // Validar email
            const emailInput = document.getElementById('email');
            const emailValue = emailInput.value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!emailValue || !emailRegex.test(emailValue)) {
                emailInput.classList.add('is-invalid');
                isValid = false;
            } else {
                emailInput.classList.remove('is-invalid');
                emailInput.classList.add('is-valid');
            }

            // Validar contraseña
            const passwordInput = document.getElementById('password');
            const passwordValue = passwordInput.value.trim();

            if (!passwordValue || passwordValue.length < 6) {
                passwordInput.classList.add('is-invalid');
                isValid = false;
            } else {
                passwordInput.classList.remove('is-invalid');
                passwordInput.classList.add('is-valid');
            }

            // Si no es válido, prevenir el envío del formulario
            if (!isValid) {
                event.preventDefault();
            }
        });

        // Validación en tiempo real al escribir
        const inputs = [
            document.getElementById('email'),
            document.getElementById('password')
        ];

        inputs.forEach(input => {
            input.addEventListener('input', function() {
                // Eliminar las clases de validación al escribir
                input.classList.remove('is-invalid');
                input.classList.remove('is-valid');
            });

            input.addEventListener('blur', function() {
                // Validar email
                if (input.id === 'email') {
                    const emailValue = input.value.trim();
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                    if (!emailValue || !emailRegex.test(emailValue)) {
                        input.classList.add('is-invalid');
                    } else {
                        input.classList.remove('is-invalid');
                        input.classList.add('is-valid');
                    }
                }

                // Validar contraseña
                if (input.id === 'password') {
                    const passwordValue = input.value.trim();

                    if (!passwordValue || passwordValue.length < 6) {
                        input.classList.add('is-invalid');
                    } else {
                        input.classList.remove('is-invalid');
                        input.classList.add('is-valid');
                    }
                }
            });
        });
    });
</script>
@endpush
@endsection
