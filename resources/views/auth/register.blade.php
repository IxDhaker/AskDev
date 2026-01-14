@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
            <div class="col-md-6">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                    <div class="card-header bg-primary text-white text-center py-4 position-relative overflow-hidden">
                        <div class="position-absolute top-0 start-0 w-100 h-100 bg-gradient-overlay"></div>
                        <h4 class="mb-0 fw-bold position-relative">Join AskDev</h4>
                        <p class="small text-white-50 mb-0 position-relative">Create your account to start asking and
                            answering</p>
                    </div>

                    <div class="card-body p-4 p-md-5">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="mb-4">
                                <label for="name"
                                    class="form-label text-muted small fw-bold text-uppercase">{{ __('Full Name') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i
                                            class="bi bi-person text-muted"></i></span>
                                    <input id="name" type="text"
                                        class="form-control border-start-0 ps-0 @error('name') is-invalid @enderror"
                                        name="name" value="{{ old('name') }}" required autocomplete="name" autofocus
                                        placeholder="John Doe">
                                </div>
                                @error('name')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="email"
                                    class="form-label text-muted small fw-bold text-uppercase">{{ __('Email Address') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i
                                            class="bi bi-envelope text-muted"></i></span>
                                    <input id="email" type="email"
                                        class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror"
                                        name="email" value="{{ old('email') }}" required autocomplete="email"
                                        placeholder="name@example.com">
                                </div>
                                @error('email')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="password"
                                        class="form-label text-muted small fw-bold text-uppercase">{{ __('Password') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i
                                                class="bi bi-lock text-muted"></i></span>
                                        <input id="password" type="password"
                                            class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror"
                                            name="password" required autocomplete="new-password" placeholder="••••••••">
                                    </div>
                                    @error('password')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label for="password-confirm"
                                        class="form-label text-muted small fw-bold text-uppercase">{{ __('Confirm Password') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i
                                                class="bi bi-check2-circle text-muted"></i></span>
                                        <input id="password-confirm" type="password"
                                            class="form-control border-start-0 ps-0" name="password_confirmation" required
                                            autocomplete="new-password" placeholder="••••••••">
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-primary py-2 fw-bold rounded-pill shadow-sm">
                                    {{ __('Register') }}
                                </button>
                            </div>

                            <div class="text-center small text-muted">
                                Already have an account? <a href="{{ route('login') }}"
                                    class="fw-bold text-primary text-decoration-none">Sign In</a>
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
            min-width: 40px;
            justify-content: center;
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
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const confirmInput = document.getElementById('password-confirm');
            
            function validatePassword() {
                if (confirmInput.value === '') {
                    confirmInput.classList.remove('is-invalid');
                    confirmInput.classList.remove('is-valid');
                    return;
                }
                
                if (passwordInput.value !== confirmInput.value) {
                    confirmInput.classList.add('is-invalid');
                    confirmInput.classList.remove('is-valid');
                } else {
                    confirmInput.classList.remove('is-invalid');
                    confirmInput.classList.add('is-valid');
                }
            }

            confirmInput.addEventListener('input', validatePassword);
            passwordInput.addEventListener('input', function() {
                if (confirmInput.value !== '') {
                    validatePassword();
                }
            });
        });
    </script>
@endsection