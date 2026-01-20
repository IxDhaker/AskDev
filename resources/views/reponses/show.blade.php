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
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--text-main);
            margin-bottom: 0;
            line-height: 1.3;
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

        .answer-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 2rem;
            display: flex;
            gap: 2rem;
        }

        .answer-content {
            flex: 1;
            color: var(--text-main);
            line-height: 1.8;
            font-size: 1.05rem;
            white-space: pre-wrap;
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

        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        @media (max-width: 768px) {
            .answer-card {
                flex-direction: column;
            }

            .vote-section {
                flex-direction: row;
                width: 100%;
                justify-content: center;
            }
        }
    </style>

    <div class="mb-4">
        <a href="{{ route('questions.show', $reponse->question) }}" class="btn btn-link text-decoration-none ps-0">
            <i class="bi bi-arrow-left me-2"></i>Back to Question
        </a>
    </div>

    <!-- Context Header -->
    <div class="question-header">
        <div class="d-flex align-items-center gap-2 text-muted small mb-2 fw-bold text-uppercase tracking-wider">
            <i class="bi bi-reply-fill"></i> Response to Question
        </div>
        <h1 class="question-title">
            <a href="{{ route('questions.show', $reponse->question) }}"
                class="text-decoration-none text-reset hover-primary">
                {{ $reponse->question->title }}
            </a>
        </h1>
    </div>

    <!-- The Response -->
    <div class="answer-card shadow-sm">
        <div class="vote-section">
            @php
                $score = $reponse->votes->where('value', 'up')->count() - $reponse->votes->where('value', 'down')->count();
            @endphp

            @auth
                <form action="{{ route('reponses.vote', $reponse) }}" method="POST" class="vote-form">
                    @csrf
                    <input type="hidden" name="value" value="up">
                    <button type="submit"
                        class="vote-btn {{ $reponse->votes->where('user_id', auth()->id())->where('value', 'up')->count() > 0 ? 'active' : '' }}">
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
                <form action="{{ route('reponses.vote', $reponse) }}" method="POST" class="vote-form">
                    @csrf
                    <input type="hidden" name="value" value="down">
                    <button type="submit"
                        class="vote-btn {{ $reponse->votes->where('user_id', auth()->id())->where('value', 'down')->count() > 0 ? 'active' : '' }}">
                        <i class="bi bi-chevron-down"></i>
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}?redirect={{ urlencode(url()->current()) }}" class="vote-btn text-decoration-none">
                    <i class="bi bi-chevron-down"></i>
                </a>
            @endauth
        </div>

        <div class="answer-content">
            {{ $reponse->content }}

            <div class="author-card">
                <div class="author-avatar">
                    {{ substr($reponse->user->name, 0, 1) }}
                </div>
                <div class="author-info">
                    <div class="author-name">{{ $reponse->user->name }}</div>
                    <div class="author-date">Answered {{ $reponse->created_at->format('M d, Y \a\t H:i') }}</div>
                </div>
            </div>

            @auth
                @if($reponse->user_id === auth()->id())
                    <div class="action-buttons">
                        <a href="{{ route('reponses.edit', $reponse) }}" class="btn btn-outline-primary">
                            <i class="bi bi-pencil me-2"></i>Edit
                        </a>
                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
                            data-bs-target="#deleteResponseModal">
                            <i class="bi bi-trash me-2"></i>Delete
                        </button>
                    </div>
                @endif
            @endauth
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteResponseModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-body p-4 text-center">
                    <div class="mb-3 text-danger">
                        <i class="bi bi-exclamation-circle" style="font-size: 3rem;"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Delete this Response?</h4>
                    <p class="text-muted mb-4">Are you sure you want to delete this response? This action cannot be undone.
                    </p>

                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-light rounded-pill px-4"
                            data-bs-dismiss="modal">Cancel</button>
                        <form action="{{ route('reponses.destroy', $reponse) }}" method="POST">
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
                document.addEventListener('submit', function (e) {
                    if (e.target && e.target.classList.contains('vote-form')) {
                        e.preventDefault();

                        const form = e.target;
                        const container = form.closest('.vote-section');
                        const formData = new FormData(form);
                        const url = form.getAttribute('action');
                        const buttons = container.querySelectorAll('.vote-btn');
                        const scoreDisplay = container.querySelector('.vote-count');

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
                                    if (scoreDisplay) scoreDisplay.textContent = data.score;
                                    buttons.forEach(b => {
                                        b.classList.remove('active');
                                        b.style.pointerEvents = 'auto';
                                    });
                                    if (data.user_vote === 'up') {
                                        const upInput = container.querySelector('input[name="value"][value="up"]');
                                        if (upInput) upInput.closest('form').querySelector('.vote-btn').classList.add('active');
                                    } else if (data.user_vote === 'down') {
                                        const downInput = container.querySelector('input[name="value"][value="down"]');
                                        if (downInput) downInput.closest('form').querySelector('.vote-btn').classList.add('active');
                                    }
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                buttons.forEach(btn => btn.style.pointerEvents = 'auto');
                            });
                    }
                });
            });
        </script>
    @endpush
@endsection