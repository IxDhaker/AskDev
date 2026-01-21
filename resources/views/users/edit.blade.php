@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-transparent border-0 pt-4 px-4">
                        <h4 class="fw-bold mb-0">Edit Profile</h4>
                        <p class="text-muted small">Update your personal information</p>
                    </div>

                    <div class="card-body p-4">
                        <!-- Profile Update Form -->
                        <form action="{{ route('users.update', $user) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label for="name"
                                    class="form-label fw-semibold text-secondary small text-uppercase">Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                    name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="email" class="form-label fw-semibold text-secondary small text-uppercase">Email
                                    Address</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                    name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr class="my-5 opacity-10">

                            <h5 class="fw-bold mb-3">Change Password</h5>
                            <p class="text-muted small mb-4">Leave blank if you don't want to change your password.</p>

                            <div class="mb-3">
                                <label for="current_password"
                                    class="form-label fw-semibold text-secondary small text-uppercase">Current
                                    Password</label>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                    id="current_password" name="current_password">
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password"
                                        class="form-label fw-semibold text-secondary small text-uppercase">New
                                        Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation"
                                        class="form-label fw-semibold text-secondary small text-uppercase">Confirm New
                                        Password</label>
                                    <input type="password" class="form-control" id="password_confirmation"
                                        name="password_confirmation">
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <a href="{{ route('users.show', $user) }}"
                                    class="btn btn-light rounded-pill px-4">Cancel</a>
                                <button type="submit" class="btn btn-primary rounded-pill px-4">Update Profile</button>
                            </div>
                        </form>

                        <hr class="my-5 opacity-10">

                        <!-- Delete Account Section -->
                        <div class="bg-danger-subtle rounded-4 p-4 mt-5">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="fw-bold text-danger mb-1">Delete Account</h5>
                                    <p class="text-danger-emphasis small mb-0">Once you delete your account, there is no
                                        going back. Please be certain.</p>
                                </div>
                                <button type="button" class="btn btn-outline-danger rounded-pill px-4"
                                    data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                                    Delete Account
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Account Modal -->
    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <div class="mb-3 text-danger">
                        <i class="bi bi-exclamation-triangle" style="font-size: 3rem;"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Delete Account?</h4>
                    <p class="text-muted mb-4">Please enter your password to confirm you want to permanently delete your
                        account.</p>

                    <form action="{{ route('users.delete', $user) }}" method="POST">
                        @csrf
                        @method('DELETE')

                        <div class="mb-3 text-start">
                            <label for="delete_password"
                                class="form-label fw-semibold text-secondary small text-uppercase">Password</label>
                            <input type="password" class="form-control" id="delete_password" name="password" required
                                placeholder="Enter your password">
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-danger rounded-pill py-2 fw-bold">Permanently Delete
                                Account</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Current Password Validation
            const currentPasswordInput = document.getElementById('current_password');
            let timeoutId;

            if (currentPasswordInput) {
                currentPasswordInput.addEventListener('input', function () {
                    const value = this.value;
                    const input = this;

                    // Reset state if empty
                    if (value.length === 0) {
                        input.classList.remove('is-valid', 'is-invalid');
                        return;
                    }

                    // Debounce AJAX call
                    clearTimeout(timeoutId);
                    timeoutId = setTimeout(() => {
                        fetch('{{ route("users.check-password") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ password: value })
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.valid) {
                                    input.classList.remove('is-invalid');
                                    input.classList.add('is-valid');
                                } else {
                                    input.classList.remove('is-valid');
                                    input.classList.add('is-invalid');
                                }
                            })
                            .catch(error => console.error('Error:', error));
                    }, 500); // 500ms delay
                });
            }

            // New Password Confirmation Validation
            const passwordInput = document.getElementById('password');
            const confirmInput = document.getElementById('password_confirmation');

            function validateConfirmation() {
                const password = passwordInput.value;
                const confirm = confirmInput.value;

                if (confirm.length === 0) {
                    confirmInput.classList.remove('is-valid', 'is-invalid');
                    return;
                }

                if (password === confirm) {
                    confirmInput.classList.remove('is-invalid');
                    confirmInput.classList.add('is-valid');
                } else {
                    confirmInput.classList.remove('is-valid');
                    confirmInput.classList.add('is-invalid');
                }
            }

            if (passwordInput && confirmInput) {
                passwordInput.addEventListener('input', validateConfirmation);
                confirmInput.addEventListener('input', validateConfirmation);
            }
        });
    </script>
@endsection