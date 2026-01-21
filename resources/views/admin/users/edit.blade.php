@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-transparent border-0 pt-4 px-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="fw-bold mb-0">Edit User</h4>
                                <p class="text-muted small">Update user information and role</p>
                            </div>
                            <a href="{{ route('admin.users') }}" class="btn btn-light rounded-pill btn-sm">
                                <i class="bi bi-arrow-left me-1"></i> Back to Users
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <!-- Profile Update Form -->
                        <form action="{{ route('admin.users.update', $user) }}" method="POST">
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

                            <div class="mb-4">
                                <label for="role"
                                    class="form-label fw-semibold text-secondary small text-uppercase">Role</label>
                                <select class="form-select @error('role') is-invalid @enderror" id="role" name="role">
                                    <option value="user" {{ (old('role', $user->role) == 'user') ? 'selected' : '' }}>User
                                    </option>
                                    <option value="admin" {{ (old('role', $user->role) == 'admin') ? 'selected' : '' }}>Admin
                                    </option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr class="my-5 opacity-10">

                            <h5 class="fw-bold mb-3">Change Password</h5>
                            <p class="text-muted small mb-4">Leave blank if you don't want to change the user's password.
                            </p>

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
                                <button type="submit" class="btn btn-primary rounded-pill px-4">Update User</button>
                            </div>
                        </form>

                        @if(auth()->id() !== $user->id)
                            <hr class="my-5 opacity-10">

                            <!-- Delete Account Section -->
                            <div class="bg-danger-subtle rounded-4 p-4 mt-5">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="fw-bold text-danger mb-1">Delete Account</h5>
                                        <p class="text-danger-emphasis small mb-0">Permanently delete this user's account and
                                            all associated data.</p>
                                    </div>
                                    <button type="button" class="btn btn-outline-danger rounded-pill px-4"
                                        data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                                        Delete User
                                    </button>
                                </div>
                            </div>
                        @endif

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
                    <h4 class="fw-bold mb-3">Delete User?</h4>
                    <p class="text-muted mb-4">Are you sure you want to delete <strong>{{ $user->name }}</strong>? This
                        action cannot be undone.</p>

                    <form action="{{ route('admin.users.delete', $user) }}" method="POST">
                        @csrf
                        @method('DELETE')

                        <div class="d-grid">
                            <button type="submit" class="btn btn-danger rounded-pill py-2 fw-bold">Permanently Delete
                                User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
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