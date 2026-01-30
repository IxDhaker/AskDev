@extends('layouts.app')

@section('content')
    <style>
        .hero-section {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border-radius: 24px;
            padding: 4rem 2rem;
            margin-bottom: 3rem;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
        }

        body.dark-mode .hero-section {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.9) 0%, rgba(244, 114, 182, 0.9) 100%);
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .hero-title {
            font-size: 3rem;
            font-weight: 800;
            color: white;
            margin-bottom: 1rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .hero-subtitle {
            font-size: 1.25rem;
            color: rgba(255, 255, 255, 0.95);
            margin-bottom: 2rem;
        }

        .stats-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary);
        }

        .stats-number {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
        }

        .stats-label {
            color: var(--text-muted);
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Special styling for developers card - compact glassmorphism */
        .developers-card {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 1rem 1.5rem;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 1rem;
        }

        body.dark-mode .developers-card {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .developers-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
            background: rgba(255, 255, 255, 0.25);
        }

        body.dark-mode .developers-card:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .developers-card .stats-number {
            font-size: 2rem;
            color: white;
            background: none;
            -webkit-text-fill-color: white;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            font-weight: 800;
            margin: 0;
        }

        .developers-card .stats-label {
            color: rgba(255, 255, 255, 0.95);
            font-size: 0.85rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-shadow: 0 1px 5px rgba(0, 0, 0, 0.2);
            margin: 0;
        }

        .developer-icon {
            font-size: 1.5rem;
            color: white;
            opacity: 0.95;
            margin: 0;
        }

        .question-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: block;
        }

        .question-card:hover {
            transform: translateX(8px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary);
        }

        .question-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 0.75rem;
            transition: color 0.2s;
        }

        .question-card:hover .question-title {
            color: var(--primary);
        }

        .question-content {
            color: var(--text-muted);
            margin-bottom: 1rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .question-meta {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .meta-item i {
            color: var(--primary);
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
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

        .btn-hero {
            padding: 0.875rem 2rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            border: 2px solid white;
            background: white;
            color: var(--primary);
        }

        .btn-hero:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .btn-hero-outline {
            background: transparent;
            color: white;
            border: 2px solid white;
        }

        .btn-hero-outline:hover {
            background: white;
            color: var(--primary);
        }

        .section-title {
            font-size: 2rem;
            font-weight: 800;
            color: var(--text-main);
            margin-bottom: 2rem;
            position: relative;
            padding-bottom: 1rem;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 4px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border-radius: 2px;
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2rem;
            }

            .hero-subtitle {
                font-size: 1rem;
            }
        }
    </style>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="hero-content text-center">
            <h1 class="hero-title">Welcome to AskDev</h1>
            <p class="hero-subtitle">The premier platform for developers to share knowledge and grow together</p>



            <div class="d-flex gap-3 justify-content-center flex-wrap">
                @guest
                    <a href="{{ route('register') }}?redirect={{ urlencode(url()->current()) }}" class="btn btn-hero">
                        <i class="bi bi-rocket-takeoff me-2"></i>Get Started
                    </a>
                    <a href="{{ route('login') }}?redirect={{ urlencode(url()->current()) }}"
                        class="btn btn-hero btn-hero-outline">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                    </a>
                @else
                    <a href="{{ route('questions.create') }}" class="btn btn-hero">
                        <i class="bi bi-plus-circle me-2"></i>Ask a Question
                    </a>
                @endguest
            </div>
        </div>
    </div>



    <!-- Recent Questions Section -->
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="section-title mb-0">Recent Questions</h2>
            @auth
                <a href="{{ route('questions.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Ask Question
                </a>
            @endauth
        </div>

        <!-- Search Bar -->
        <div class="row mb-4">
            <div class="col-md-12">
                <form action="{{ route('home') }}" method="GET" class="position-relative">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                    <input type="text" name="search"
                        class="form-control form-control-lg ps-5 rounded-pill border-0 shadow-sm"
                        placeholder="Search questions by title or content..." value="{{ request('search') }}"
                        style="background-color: var(--surface);">
                </form>
            </div>
        </div>

        <div id="questions-container" style="transition: opacity 0.3s ease;">
            @if($questions->count() > 0)
                @foreach($questions as $question)
                    <div class="question-card position-relative">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <a href="{{ route('questions.show', $question) }}" class="text-decoration-none text-reset flex-grow-1">
                                <h3 class="question-title mb-0">{{ $question->title }}</h3>
                            </a>
                            @if(Auth::check() && Auth::user()->role === 'admin')
                                <button type="button" class="btn btn-sm btn-outline-danger border-0 p-1 z-2"
                                    onclick="event.preventDefault(); window.confirmDeleteQuestion('{{ route('questions.destroy', $question) }}')"
                                    title="Delete Question">
                                    <i class="bi bi-trash"></i>
                                </button>
                            @endif
                        </div>

                        <p class="question-content">{{ Str::limit(strip_tags($question->content), 150) }}</p>

                        <div class="question-meta">
                            <div class="meta-item">
                                <i class="bi bi-person-circle"></i>
                                <span>{{ $question->user ? $question->user->name : 'Deleted User' }}</span>
                            </div>
                            <div class="meta-item">
                                <i class="bi bi-chat-dots"></i>
                                <span>{{ $question->reponses->count() }} answers</span>
                            </div>
                            <div class="meta-item">
                                <i class="bi bi-eye"></i>
                                <span>{{ $question->views }} views</span>
                            </div>
                            <div class="meta-item">
                                <i class="bi bi-clock"></i>
                                <span>{{ $question->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $questions->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-search" style="font-size: 4rem; color: var(--text-muted);"></i>
                    <h3 class="mt-3 text-muted">
                        @if(request('search'))
                            No results found for "{{ request('search') }}"
                        @else
                            No questions yet
                        @endif
                    </h3>
                    <p class="text-muted">
                        @if(request('search'))
                            Try adjusting your search terms or ask a new question.
                        @else
                            Be the first to ask a question!
                        @endif
                    </p>
                    @auth
                        <a href="{{ route('questions.create') }}" class="btn btn-primary mt-3">
                            <i class="bi bi-plus-circle me-2"></i>Ask the First Question
                        </a>
                    @endauth
                </div>
            @endif
        </div>
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
                    <p class="text-muted mb-4">Are you sure you want to delete this question? This action cannot be undone.
                    </p>

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

    @push('scripts')
        <script>
            window.confirmDeleteQuestion = function (url) {
                const form = document.getElementById('deleteQuestionForm');
                if (form) {
                    form.action = url;
                    const modal = new bootstrap.Modal(document.getElementById('deleteQuestionModal'));
                    modal.show();
                }
            };

            document.addEventListener('DOMContentLoaded', function () {
                let searchTimeout;
                const searchInput = document.querySelector('input[name="search"]');
                const container = document.getElementById('questions-container');

                if (searchInput && container) {
                    searchInput.addEventListener('input', function () {
                        clearTimeout(searchTimeout);
                        const query = this.value;

                        searchTimeout = setTimeout(() => {
                            // Show loading state
                            container.style.opacity = '0.5';

                            const url = new URL(window.location.href);
                            url.searchParams.set('search', query);
                            url.searchParams.delete('page');

                            fetch(url, {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            })
                                .then(response => response.text())
                                .then(html => {
                                    const parser = new DOMParser();
                                    const doc = parser.parseFromString(html, 'text/html');
                                    const newContent = doc.getElementById('questions-container').innerHTML;
                                    container.innerHTML = newContent;
                                    container.style.opacity = '1';

                                    // Update URL without refresh
                                    window.history.pushState({}, '', url);
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    container.style.opacity = '1';
                                });
                        }, 500); // 500ms delay
                    });
                }
            });
        </script>
    @endpush
@endsection