@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
            <div class="col-md-5">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                    <div class="card-header bg-primary text-white text-center py-4 position-relative overflow-hidden">
                        <div class="position-absolute top-0 start-0 w-100 h-100 bg-gradient-overlay"></div>
                        <h4 class="mb-0 fw-bold position-relative">Reset Password</h4>
                        <p class="small text-white-50 mb-0 position-relative">Enter your new password</p>
                    </div>

                    <div class="card-body p-4 p-md-5">
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf

                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="mb-4">
                                <label for="email"
                                    class="form-label text-muted small fw-bold text-uppercase">{{ __('Email Address') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i
                                            class="bi bi-envelope text-muted"></i></span>
                                    <input id="email" type="email"
                                        class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror"
                                        name="email" value="{{ $email ?? old('email') }}" required autocomplete="email"
                                        autofocus placeholder="name@example.com">
                                </div>
                                @error('email')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password"
                                    class="form-label text-muted small fw-bold text-uppercase">{{ __('New Password') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i
                                            class="bi bi-lock text-muted"></i></span>
                                    <input id="password" type="password"
                                        class="form-control border-0 ps-0 @error('password') is-invalid @enderror"
                                        name="password" required autocomplete="new-password" placeholder="••••••••">
                                    <span class="input-group-text bg-light border-start-0 cursor-pointer"
                                        id="togglePassword">
                                        <i class="bi bi-eye text-muted" id="passwordIcon"></i>
                                    </span>
                                </div>
                                @error('password')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password-confirm"
                                    class="form-label text-muted small fw-bold text-uppercase">{{ __('Confirm Password') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i
                                            class="bi bi-lock-fill text-muted"></i></span>
                                    <input id="password-confirm" type="password" class="form-control border-0 ps-0"
                                        name="password_confirmation" required autocomplete="new-password"
                                        placeholder="••••••••">
                                    <span class="input-group-text bg-light border-start-0 cursor-pointer"
                                        id="togglePasswordConfirm">
                                        <i class="bi bi-eye text-muted" id="passwordConfirmIcon"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-primary py-2 fw-bold rounded-pill shadow-sm">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>

                            <div class="text-center small text-muted">
                                Remember your password? <a href="{{ route('login') }}"
                                    class="fw-bold text-primary text-decoration-none">Login</a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ url('/') }}" class="text-muted text-decoration-none small">
                        <i class="bi bi-arrow-left me-1"></i> Back to Home
                    </a>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-gradient-overlay {
            background: linear-gradient(45deg, rgba(0, 0, 0, 0.2), rgba(255, 255, 255, 0.1));
        }

        .input-group-text {
            color: #6c757d;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: none;
        }

        .form-control:focus+.input-group-text,
        .input-group:focus-within .input-group-text {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .input-group:focus-within .form-control {
            border-color: var(--primary-color);
        }

        /* Dark mode adjustments */
        body.dark-mode .input-group-text {
            background-color: #334155 !important;
            border-color: #475569;
            color: #94a3b8;
        }

        body.dark-mode .input-group:focus-within .input-group-text {
            border-color: #60a5fa;
            color: #60a5fa;
        }

        body.dark-mode .form-control::placeholder {
            color: #64748b;
        }

        /* Password Toggle Cursor */
        .cursor-pointer {
            cursor: pointer;
        }

        .cursor-pointer:hover i {
            color: var(--primary) !important;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Password Visibility Toggle
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('passwordIcon');

            if (togglePassword && passwordInput && passwordIcon) {
                togglePassword.addEventListener('click', function () {
                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        passwordIcon.classList.remove('bi-eye');
                        passwordIcon.classList.add('bi-eye-slash');
                    } else {
                        passwordInput.type = 'password';
                        passwordIcon.classList.remove('bi-eye-slash');
                        passwordIcon.classList.add('bi-eye');
                    }
                });
            }

            // Password Confirm Visibility Toggle
            const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
            const passwordConfirmInput = document.getElementById('password-confirm');
            const passwordConfirmIcon = document.getElementById('passwordConfirmIcon');

            if (togglePasswordConfirm && passwordConfirmInput && passwordConfirmIcon) {
                togglePasswordConfirm.addEventListener('click', function () {
                    if (passwordConfirmInput.type === 'password') {
                        passwordConfirmInput.type = 'text';
                        passwordConfirmIcon.classList.remove('bi-eye');
                        passwordConfirmIcon.classList.add('bi-eye-slash');
                    } else {
                        passwordConfirmInput.type = 'password';
                        passwordConfirmIcon.classList.remove('bi-eye-slash');
                        passwordConfirmIcon.classList.add('bi-eye');
                    }
                });
            }
        });
    </script>
@endsection