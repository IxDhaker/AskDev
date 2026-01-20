@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <!-- Existing content ... -->
        <div class="row">
            <!-- Sidebar Navigation -->
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush rounded-4 overflow-hidden">
                            <div class="p-3 bg-surface border-bottom border-theme">
                                <h6 class="fw-bold mb-0 text-uppercase small text-muted">Archive</h6>
                            </div>

                            <a href="{{ route('users.archive.questions', $user) }}"
                                class="list-group-item list-group-item-action p-3 d-flex align-items-center justify-content-between {{ Route::is('users.archive.questions') ? 'active' : '' }}">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-question-circle"></i>
                                    <span>My Questions</span>
                                </div>
                                <span class="badge bg-secondary rounded-pill">{{ $user->questions()->count() }}</span>
                            </a>

                            <a href="{{ route('users.archive.reponses', $user) }}"
                                class="list-group-item list-group-item-action p-3 d-flex align-items-center justify-content-between {{ Route::is('users.archive.reponses') ? 'active' : '' }}">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-chat-text"></i>
                                    <span>My Answers</span>
                                </div>
                                <span class="badge bg-secondary rounded-pill">{{ $user->reponses()->count() }}</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-center">
                    <a href="{{ route('home') }}" class="text-decoration-none text-muted small">
                        <i class="bi bi-arrow-left me-1"></i> Back to Home
                    </a>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="col-md-9">
                @yield('archive-content')
            </div>
        </div>
    </div>

    <!-- Generic Delete Confirmation Modal -->
    <div class="modal fade" id="deleteArchiveModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-body p-4 text-center">
                    <div class="mb-3 text-danger">
                        <i class="bi bi-trash" style="font-size: 3rem;"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Confirm Deletion</h4>
                    <p class="text-muted mb-4">Are you sure you want to delete this item? This action cannot be undone.</p>

                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-light rounded-pill px-4"
                            data-bs-dismiss="modal">Cancel</button>
                        <form id="deleteArchiveForm" action="" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger rounded-pill px-4">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const deleteModal = document.getElementById('deleteArchiveModal');
                if (deleteModal) {
                    deleteModal.addEventListener('show.bs.modal', function (event) {
                        const button = event.relatedTarget;
                        const actionUrl = button.getAttribute('data-action');
                        const modalForm = deleteModal.querySelector('#deleteArchiveForm');
                        modalForm.setAttribute('action', actionUrl);
                    });
                }
            });
        </script>
    @endpush

    <style>
        .list-group-item {
            background-color: var(--surface);
            border-color: var(--border);
            color: var(--text-main);
            border-left: none;
            border-right: none;
        }

        .list-group-item:hover {
            background-color: var(--background);
            color: var(--text-main);
        }

        .list-group-item.active {
            background-color: var(--primary) !important;
            border-color: var(--primary) !important;
            color: white !important;
        }

        .list-group-item.active:hover {
            background-color: var(--primary) !important;
            color: white !important;
        }

        .list-group-item.active .badge {
            background-color: white !important;
            color: var(--primary) !important;
        }

        .list-group-item:first-child {
            border-top: none;
        }

        .list-group-item:last-child {
            border-bottom: none;
        }

        /* Dark Mode Specific Sidebar Header */
        body.dark-mode .bg-surface.border-bottom {
            background-color: rgba(255, 255, 255, 0.05) !important;
        }
    </style>
@endsection