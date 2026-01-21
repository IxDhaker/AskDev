@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="fw-bold mb-0">Manage Users</h2>
                <p class="text-muted">View, edit, and manage system users.</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th
                                    class="px-4 py-3 border-0 rounded-start-4 text-secondary small text-uppercase fw-semibold">
                                    User</th>
                                <th class="py-3 border-0 text-secondary small text-uppercase fw-semibold">Role</th>
                                <th class="py-3 border-0 text-secondary small text-uppercase fw-semibold">Stats</th>
                                <th class="py-3 border-0 text-secondary small text-uppercase fw-semibold">Joined</th>
                                <th
                                    class="px-4 py-3 border-0 rounded-end-4 text-end text-secondary small text-uppercase fw-semibold">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3"
                                                style="width: 40px; height: 40px; font-weight: bold;">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-semibold">{{ $user->name }}</h6>
                                                <small class="text-muted">{{ $user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        @if($user->role === 'admin')
                                            <span class="badge bg-primary-subtle text-primary rounded-pill">Admin</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary rounded-pill">User</span>
                                        @endif
                                    </td>
                                    <td class="py-3">
                                        <div class="d-flex gap-3">
                                            <div class="text-center" data-bs-toggle="tooltip" title="Questions">
                                                <i class="bi bi-question-circle text-muted"></i>
                                                <span class="fw-semibold small ms-1">{{ $user->questions_count }}</span>
                                            </div>
                                            <div class="text-center" data-bs-toggle="tooltip" title="Answers">
                                                <i class="bi bi-chat-dots text-muted"></i>
                                                <span class="fw-semibold small ms-1">{{ $user->reponses_count }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 text-muted small">
                                        {{ $user->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-4 py-3 text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('admin.users.edit', $user) }}"
                                                class="btn btn-sm btn-light rounded-pill px-3" data-bs-toggle="tooltip"
                                                title="Edit User">
                                                <i class="bi bi-pencil"></i>
                                            </a>

                                            @if(auth()->id() !== $user->id)
                                                <button type="button" class="btn btn-sm btn-light rounded-pill px-3 text-danger"
                                                    data-bs-toggle="modal" data-bs-target="#deleteUserModal"
                                                    data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}"
                                                    title="Delete User">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        No users found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($users->hasPages())
                <div class="card-footer bg-transparent border-0 py-3">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Delete User Modal -->
    <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-hidden="true">
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
                    <p class="text-muted mb-4">Are you sure you want to delete <strong id="deleteUserName"></strong>? This
                        action cannot be undone.</p>

                    <form id="deleteUserForm" action="" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="d-flex justify-content-center gap-2">
                            <button type="button" class="btn btn-light rounded-pill px-4"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger rounded-pill px-4">Delete User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var deleteUserModal = document.getElementById('deleteUserModal');
            if (deleteUserModal) {
                deleteUserModal.addEventListener('show.bs.modal', function (event) {
                    var button = event.relatedTarget;
                    var userId = button.getAttribute('data-user-id');
                    var userName = button.getAttribute('data-user-name');

                    var modalUserName = deleteUserModal.querySelector('#deleteUserName');
                    var modalForm = deleteUserModal.querySelector('#deleteUserForm');

                    modalUserName.textContent = userName;
                    modalForm.action = '/admin/users/' + userId;
                });
            }

            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        });
    </script>
@endsection