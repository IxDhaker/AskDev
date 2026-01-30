@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="fw-bold mb-0">Manage Questions</h2>
                <p class="text-muted">Review pending questions and manage published content.</p>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <ul class="nav nav-pills mb-4" id="questionsTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active rounded-pill px-4" id="pending-tab" data-bs-toggle="tab"
                    data-bs-target="#pending" type="button" role="tab" aria-controls="pending" aria-selected="true">
                    <i class="bi bi-hourglass-split me-2"></i>Pending <span
                        class="badge bg-white text-primary ms-2">{{ $pendingQuestions->count() }}</span>
                </button>
            </li>
            <li class="nav-item ms-2" role="presentation">
                <button class="nav-link rounded-pill px-4" id="published-tab" data-bs-toggle="tab"
                    data-bs-target="#published" type="button" role="tab" aria-controls="published" aria-selected="false">
                    <i class="bi bi-check-circle me-2"></i>Open
                </button>
            </li>

        </ul>

        <!-- Tabs Content -->
        <div class="tab-content" id="questionsTabContent">

            <!-- Pending Questions Tab -->
            <div class="tab-pane fade show active" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush rounded-4">
                            @forelse($pendingQuestions as $question)
                                <div class="list-group-item px-4 py-4 border-bottom-0 border-top">
                                    <div class="row align-items-start">
                                        <div class="col-md-9">
                                            <div class="d-flex align-items-center mb-2">
                                                <span
                                                    class="badge bg-warning-subtle text-warning-emphasis me-2 rounded-pill small">Pending</span>
                                                <span class="text-muted small"><i class="bi bi-clock me-1"></i>
                                                    {{ $question->created_at->diffForHumans() }}</span>
                                            </div>
                                            <h5 class="fw-bold mb-2">
                                                <a href="{{ route('questions.show', $question) }}"
                                                    class="text-dark text-decoration-none">{{ $question->title }}</a>
                                            </h5>
                                            <p class="text-muted small mb-2 text-truncate">
                                                {{ Str::limit($question->content, 200) }}
                                            </p>
                                            <div class="d-flex align-items-center gap-3 text-muted small">
                                                <span><i class="bi bi-person me-1"></i>
                                                    {{ $question->user ? $question->user->name : 'Deleted User' }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 text-end pt-2">
                                            <form action="{{ route('admin.questions.status', $question) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="open">
                                                <button type="submit"
                                                    class="btn btn-success btn-sm rounded-pill px-3 mb-1 w-100">
                                                    <i class="bi bi-check-lg me-1"></i> Approve
                                                </button>
                                            </form>
                                            <button type="button"
                                                class="btn btn-outline-danger btn-sm rounded-pill px-3 w-100 mt-2"
                                                onclick="window.confirmDeleteQuestion('{{ route('questions.destroy', $question) }}', 'Are you sure you want to permanently reject and delete this question?')">
                                                <i class="bi bi-x-lg me-1"></i> Reject
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-5">
                                    <div class="mb-3 text-muted opacity-50">
                                        <i class="bi bi-inbox-fill" style="font-size: 3rem;"></i>
                                    </div>
                                    <h5 class="fw-semibold text-muted">No pending questions</h5>
                                    <p class="text-muted small">All caught up! There are no questions waiting for approval.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Published (Open) Questions Tab -->
            <div class="tab-pane fade" id="published" role="tabpanel" aria-labelledby="published-tab">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush rounded-4">
                            @forelse($publishedQuestions as $question)
                                <div class="list-group-item px-4 py-3 border-bottom-0 border-top">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="flex-grow-1 pe-3">
                                            <div class="d-flex align-items-center mb-1">
                                                <span class="badge bg-success-subtle text-success rounded-pill small me-2">
                                                    Open
                                                </span>
                                                <a href="{{ route('questions.show', $question) }}"
                                                    class="fw-semibold text-dark text-decoration-none">
                                                    {{ $question->title }}
                                                </a>
                                            </div>
                                            <div class="d-flex align-items-center gap-3 text-muted small">
                                                <span><i class="bi bi-person me-1"></i>
                                                    {{ $question->user ? $question->user->name : 'Deleted User' }}</span>
                                                <span><i class="bi bi-calendar3 me-1"></i>
                                                    {{ $question->created_at->format('M d, Y') }}</span>
                                                <span><i class="bi bi-chat-dots me-1"></i>
                                                    {{ $question->reponses_count ?? $question->reponses()->count() }}
                                                    answers</span>
                                            </div>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-light btn-sm rounded-circle" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm p-2">
                                                <li>
                                                    <button type="button" class="dropdown-item rounded-2 text-danger"
                                                        onclick="window.confirmDeleteQuestion('{{ route('questions.destroy', $question) }}', 'Are you sure you want to permanently delete this question?')">
                                                        <i class="bi bi-trash-fill me-2"></i>
                                                        Delete Question
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-5">
                                    <p class="text-muted">No open questions found.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    {{ $publishedQuestions->appends(['tab' => 'published'])->links() }}
                </div>
            </div>

            <!-- Closed Questions Tab -->

        </div>

        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteQuestionModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <div class="modal-body p-4 text-center">
                        <div class="mb-3 text-danger">
                            <i class="bi bi-exclamation-circle" style="font-size: 3rem;"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Delete this Question?</h4>
                        <p class="text-muted mb-4" id="deleteModalMessage">Are you sure you want to delete this question?
                            This action cannot be undone.</p>

                        <div class="d-flex justify-content-center gap-2">
                            <button type="button" class="btn btn-light rounded-pill px-4"
                                data-bs-dismiss="modal">Cancel</button>
                            <form id="deleteQuestionForm" action="" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger rounded-pill px-4">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Global function for delete confirmation
            window.confirmDeleteQuestion = function (url, message) {
                const form = document.getElementById('deleteQuestionForm');
                const messageElement = document.getElementById('deleteModalMessage');

                if (form) {
                    form.action = url;
                    if (message && messageElement) {
                        messageElement.textContent = message;
                    }
                    const modal = new bootstrap.Modal(document.getElementById('deleteQuestionModal'));
                    modal.show();
                }
            };

            // Keep tab active on page reload (useful for pagination)
            document.addEventListener('DOMContentLoaded', function () {
                var triggerTabList = [].slice.call(document.querySelectorAll('#questionsTab button'))
                triggerTabList.forEach(function (triggerEl) {
                    var tabTrigger = new bootstrap.Tab(triggerEl)
                    triggerEl.addEventListener('click', function (event) {
                        event.preventDefault()
                        tabTrigger.show()
                        // Store active tab
                        localStorage.setItem('activeQuestionsTab', event.target.id);
                    })
                })

                // Restore active tab
                var activeTabId = localStorage.getItem('activeQuestionsTab');
                if (activeTabId) {
                    var activeTab = document.getElementById(activeTabId);
                    if (activeTab) {
                        var activeTabBtn = document.querySelector('#' + activeTabId);
                        if (activeTabBtn) {
                            var tab = new bootstrap.Tab(activeTabBtn);
                            tab.show();
                        }
                    }
                }
            });
        </script>
@endsection