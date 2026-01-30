@extends('layouts.app')

@section('content')
    <style>
        .question-header {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow);
        }

        .question-title {
            font-size: 2rem;
            font-weight: 800;
            color: var(--text-main);
            margin-bottom: 1rem;
            line-height: 1.3;
        }

        .question-meta {
            display: flex;
            align-items: center;
            gap: 2rem;
            flex-wrap: wrap;
            padding-top: 1rem;
            border-top: 1px solid var(--border);
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        .meta-item i {
            color: var(--primary);
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-open {
            background: rgba(16, 185, 129, 0.1);
            color: #059669;
        }

        .status-closed {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }

        .status-pending {
            background: rgba(245, 158, 11, 0.1);
            color: #d97706;
        }

        .question-body {
            display: flex;
            gap: 2rem;
        }

        .vote-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            min-width: 50px;
        }

        .vote-btn {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            border: 2px solid var(--border);
            background: var(--surface);
            color: var(--text-muted);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .vote-btn:hover {
            border-color: var(--primary);
            color: var(--primary);
            transform: scale(1.1);
        }

        .vote-btn.active {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
        }

        .vote-count {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-main);
            margin: 0.5rem 0;
        }

        .question-content-section {
            flex: 1;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 2rem;
        }

        .question-content {
            color: var(--text-main);
            line-height: 1.8;
            font-size: 1.05rem;
            /* white-space: pre-wrap; Removed for HTML content */
            word-wrap: break-word;
        }

        .author-card {
            background: var(--background);
            border-radius: 12px;
            padding: 1rem;
            margin-top: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .author-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            font-weight: 700;
            white-space: normal;
            /* Fix alignment in answers (inherits pre-wrap) */
            text-transform: uppercase;
        }

        .author-info {
            flex: 1;
        }

        .author-name {
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 0.25rem;
        }

        .author-date {
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .answers-section {
            margin-top: 3rem;
        }

        .section-header {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--border);
        }

        .answer-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
            display: flex;
            gap: 1.5rem;
        }

        .answer-card .author-card {
            margin-top: 1rem;
            padding: 0.75rem;
            background: transparent;
            border: 1px solid var(--border);
        }

        .answer-card .author-avatar {
            width: 32px;
            height: 32px;
            font-size: 0.9rem;
        }

        .answer-content {
            flex: 1;
            color: var(--text-main);
            line-height: 1.8;
            /* white-space: pre-wrap; Removed for HTML content */
            word-wrap: break-word;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .empty-answers {
            text-align: center;
            padding: 3rem 2rem;
            background: var(--surface);
            border: 2px dashed var(--border);
            border-radius: 16px;
        }

        .empty-answers i {
            font-size: 3rem;
            color: var(--text-muted);
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            .question-body {
                flex-direction: column;
            }

            .vote-section {
                flex-direction: row;
                width: 100%;
                justify-content: center;
            }

            .answer-card {
                flex-direction: column;
            }
        }
    </style>

    <div class="mb-3">
        <a href="{{ route('home') }}" class="btn btn-link text-decoration-none">
            <i class="bi bi-arrow-left me-2"></i>Back to Questions
        </a>
    </div>

    <!-- Question Header -->
    <div class="question-header">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <h1 class="question-title mb-0">{{ $question->title }}</h1>
        </div>

        <div class="question-meta">
            <div class="meta-item">
                <i class="bi bi-person-circle"></i>
                <span>Asked by <strong>{{ $question->user->name }}</strong></span>
            </div>
            <div class="meta-item">
                <i class="bi bi-clock"></i>
                <span>{{ $question->created_at->diffForHumans() }}</span>
            </div>
            <div class="meta-item">
                <i class="bi bi-eye"></i>
                <span>{{ $question->views }} views</span>
            </div>
            <div class="meta-item">
                <i class="bi bi-chat-dots"></i>
                <span>{{ $question->reponses->count() }} answers</span>
            </div>
        </div>
    </div>

    <!-- Question Body -->
    <div class="question-body">
        <!-- Vote Section -->
        <div class="vote-section">
            @auth
                <form action="{{ route('votes.vote', $question) }}" method="POST" class="vote-form">
                    @csrf
                    <input type="hidden" name="value" value="up">
                    <button type="submit"
                        class="vote-btn {{ $question->votes->where('user_id', auth()->id())->where('value', 'up')->count() > 0 ? 'active' : '' }}">
                        <i class="bi bi-chevron-up"></i>
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}?redirect={{ urlencode(url()->current()) }}" class="vote-btn text-decoration-none">
                    <i class="bi bi-chevron-up"></i>
                </a>
            @endauth

            <div class="vote-count">{{ $score }}</div>

            @auth
                <form action="{{ route('votes.vote', $question) }}" method="POST" class="vote-form">
                    @csrf
                    <input type="hidden" name="value" value="down">
                    <button type="submit"
                        class="vote-btn {{ $question->votes->where('user_id', auth()->id())->where('value', 'down')->count() > 0 ? 'active' : '' }}">
                        <i class="bi bi-chevron-down"></i>
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}?redirect={{ urlencode(url()->current()) }}" class="vote-btn text-decoration-none">
                    <i class="bi bi-chevron-down"></i>
                </a>
            @endauth
        </div>

        <!-- Question Content -->
        <div class="question-content-section">
            <div class="question-content">{!! $question->content !!}</div>

            <div class="author-card">
                <div class="author-avatar">
                    {{ substr($question->user->name, 0, 1) }}
                </div>
                <div class="author-info">
                    <div class="author-name">{{ $question->user->name }}</div>
                    <div class="author-date">Asked {{ $question->created_at->format('M d, Y \a\t H:i') }}</div>
                </div>
            </div>

            @auth
                @if($question->user_id === auth()->id() || auth()->user()->role === 'admin')
                    <div class="action-buttons">
                        @if($question->user_id === auth()->id())
                            <a href="{{ route('questions.edit', $question) }}" class="btn btn-outline-primary">
                                <i class="bi bi-pencil me-2"></i>Edit
                            </a>
                        @endif
                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
                            data-bs-target="#deleteQuestionModal">
                            <i class="bi bi-trash me-2"></i>Delete
                        </button>
                    </div>
                @endif
            @endauth
        </div>
    </div>

    <!-- Answers Section -->
    <div class="answers-section">
        <h2 class="section-header">
            {{ $question->reponses->count() }} {{ Str::plural('Answer', $question->reponses->count()) }}
        </h2>

        @if($question->reponses->count() > 0)
            @foreach($question->reponses as $reponse)
                @include('reponses.partials.card', ['reponse' => $reponse])
            @endforeach
        @else
            <div class="empty-answers">
                <i class="bi bi-chat-left-text"></i>
                <h4 class="text-muted">No answers yet</h4>
                <p class="text-muted">Be the first to answer this question!</p>
            </div>
        @endif

        <!-- Answer Form -->
        @auth
            <div class="mt-4">
                <h3 class="section-header">Your Answer</h3>
                <div class="question-content-section">
                    <form action="{{ route('reponses.store', $question) }}" method="POST" id="reponse-form">
                        @csrf
                        <div class="mb-3">
                            <textarea name="content" id="content" class="form-control" rows="6"
                                placeholder="Write your answer here..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send me-2"></i>Post Your Answer
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="mt-4 text-center">
                <p class="text-muted">
                    <a href="{{ route('login') }}?redirect={{ urlencode(url()->current()) }}"
                        class="text-decoration-none">Login</a> or
                    <a href="{{ route('register') }}?redirect={{ urlencode(url()->current()) }}"
                        class="text-decoration-none">Register</a> to post an answer
                </p>
            </div>
        @endauth
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
                    <p class="text-muted mb-4">Are you sure you want to delete this question? This action cannot be
                        undone.</p>

                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-light rounded-pill px-4"
                            data-bs-dismiss="modal">Cancel</button>
                        <form action="{{ route('questions.destroy', $question) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger rounded-pill px-4">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Answer Confirmation Modal -->
    <div class="modal fade" id="deleteResponseModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-body p-4 text-center">
                    <div class="mb-3 text-danger">
                        <i class="bi bi-trash" style="font-size: 3rem;"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Delete this Answer?</h4>
                    <p class="text-muted mb-4">Are you sure you want to delete this answer? This action cannot be undone.
                    </p>

                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-light rounded-pill px-4"
                            data-bs-dismiss="modal">Cancel</button>
                        <form id="deleteResponseForm" action="" method="POST">
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
                // Use event delegation for better reliability
                document.addEventListener('submit', function (e) {
                    if (e.target && e.target.classList.contains('vote-form')) {
                        e.preventDefault(); // Stop page refresh

                        const form = e.target;
                        const container = form.closest('.vote-section');
                        const formData = new FormData(form);
                        const url = form.getAttribute('action');
                        const buttons = container.querySelectorAll('.vote-btn');
                        const scoreDisplay = container.querySelector('.vote-count');

                        // Disable buttons briefly to prevent double clicks
                        buttons.forEach(btn => btn.style.pointerEvents = 'none');

                        fetch(url, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                            .then(response => {
                                if (!response.ok) throw new Error('Network response was not ok');
                                return response.json();
                            })
                            .then(data => {
                                if (data.success) {
                                    // Update score
                                    if (scoreDisplay) {
                                        scoreDisplay.textContent = data.score;
                                    }

                                    // Reset all buttons active state within this container
                                    buttons.forEach(b => {
                                        b.classList.remove('active');
                                        b.style.pointerEvents = 'auto'; // Re-enable
                                    });

                                    // Set active state based on response
                                    if (data.user_vote === 'up') {
                                        const upInput = container.querySelector('input[name="value"][value="up"]');
                                        if (upInput) {
                                            const upForm = upInput.closest('form');
                                            if (upForm) upForm.querySelector('.vote-btn').classList.add('active');
                                        }
                                    } else if (data.user_vote === 'down') {
                                        const downInput = container.querySelector('input[name="value"][value="down"]');
                                        if (downInput) {
                                            const downForm = downInput.closest('form');
                                            if (downForm) downForm.querySelector('.vote-btn').classList.add('active');
                                        }
                                    }
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                buttons.forEach(btn => btn.style.pointerEvents = 'auto'); // Re-enable on error
                            });
                    }
                    // Vote logic ends here

                });
            });

            // Global function to set delete action
            window.setDeleteResponseAction = function (url) {
                const modalForm = document.querySelector('#deleteResponseModal form');
                if (modalForm) {
                    modalForm.setAttribute('action', url);
                }
            };

            // Answer form AJAX
            document.addEventListener('DOMContentLoaded', function () {
                const reponseForm = document.getElementById('reponse-form');
                if (reponseForm) {
                    reponseForm.addEventListener('submit', function (e) {
                        e.preventDefault();
                        if (window.tinymce) tinymce.triggerSave();

                        const form = e.target;
                        const formData = new FormData(form);
                        const url = form.getAttribute('action');
                        const submitBtn = form.querySelector('button[type="submit"]');
                        const originalBtnText = submitBtn.innerHTML;

                        // Disable button
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Posting...';

                        fetch(url, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                            .then(response => {
                                // If validation fails, Laravel returns 422
                                if (response.status === 422) {
                                    return response.json().then(data => {
                                        throw { status: 422, errors: data.errors };
                                    });
                                }
                                if (!response.ok) throw new Error('Network response was not ok');
                                return response.json();
                            })
                            .then(data => {
                                if (data.success) {
                                    // 1. Remove empty state if present
                                    const emptyState = document.querySelector('.empty-answers');
                                    if (emptyState) emptyState.remove();

                                    // 2. Update count
                                    const countHeader = document.querySelector('.answers-section .section-header');
                                    if (countHeader && data.count) {
                                        countHeader.textContent = `${data.count} ${data.count === 1 ? 'Answer' : 'Answers'}`;
                                    }

                                    // 3. Append new answer
                                    const formContainer = document.querySelector('.answers-section > .mt-4');
                                    const tempDiv = document.createElement('div');
                                    tempDiv.innerHTML = data.html;
                                    const newAnswer = tempDiv.firstElementChild;

                                    const section = document.querySelector('.answers-section');
                                    section.insertBefore(newAnswer, formContainer);

                                    // 4. Clear form and errors
                                    form.reset();
                                    const textarea = form.querySelector('textarea[name="content"]');
                                    if (textarea) {
                                        textarea.classList.remove('is-invalid');
                                        textarea.classList.add('is-valid');
                                        setTimeout(() => textarea.classList.remove('is-valid'), 3000); // Remove success state after 3s
                                    }

                                    // 5. Scroll to new answer
                                    newAnswer.scrollIntoView({ behavior: 'smooth', block: 'center' });

                                    // 6. Show success animation
                                    newAnswer.style.animation = 'fadeIn 0.5s ease-out';
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                if (error.status === 422 && error.errors && error.errors.content) {
                                    const textarea = form.querySelector('textarea[name="content"]');
                                    if (textarea) {
                                        textarea.classList.add('is-invalid');

                                        // Optional: Add a shake animation to draw attention
                                        textarea.style.animation = 'shake 0.5s';
                                        setTimeout(() => textarea.style.animation = '', 500);

                                        // Focus the textarea
                                        textarea.focus();
                                    }
                                } else {
                                    // Only alert for other types of errors (not validation)
                                    alert('Something went wrong. Please try again.');
                                }
                            })
                            .finally(() => {
                                submitBtn.disabled = false;
                                submitBtn.innerHTML = originalBtnText;
                            });
                    });

                    // Add input listener to remove is-invalid class when user types
                    const textarea = reponseForm.querySelector('textarea[name="content"]');
                    if (textarea) {
                        textarea.addEventListener('input', function () {
                            this.classList.remove('is-invalid');
                        });
                    }
                }
            });

            // Add shake keyframes to existing style
            const styleSheet = document.createElement("style");
            styleSheet.innerText = `
                                                                    @keyframes shake {
                                                                        0% { transform: translateX(0); }
                                                                        25% { transform: translateX(-5px); }
                                                                        50% { transform: translateX(5px); }
                                                                        75% { transform: translateX(-5px); }
                                                                        100% { transform: translateX(0); }
                                                                    }
                                                                `;
            document.head.appendChild(styleSheet);

            document.addEventListener('DOMContentLoaded', function () {
                tinymce.init({
                    selector: '#content',
                    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
                    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
                    skin: document.body.classList.contains('dark-mode') ? 'oxide-dark' : 'oxide',
                    content_css: document.body.classList.contains('dark-mode') ? 'dark' : 'default',
                    height: 250,
                    menubar: false,
                    statusbar: false,
                    setup: function (editor) {
                        editor.on('change', function () {
                            editor.save();
                        });
                    }
                });
            });
        </script>
    @endpush
@endsection