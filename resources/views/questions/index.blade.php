@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-primary mb-0">
                    <i class="bi bi-patch-question me-2"></i> All Questions
                </h2>
                <a href="{{ route('questions.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">
                    <i class="bi bi-plus-lg me-1"></i> Ask Question
                </a>
            </div>

            @forelse($questions as $question)
                <div class="card border-0 shadow-sm rounded-4 mb-3 hover-scale transition-all">
                    <div class="card-body p-4">
                        <div class="row">
                            <!-- Stats Column -->
                            <div
                                class="col-2 d-none d-sm-flex flex-column align-items-center justify-content-center text-muted small">
                                <div class="mb-2 text-center">
                                    <span
                                        class="d-block fw-bold fs-5 {{ $question->getScore() > 0 ? 'text-primary' : '' }}">{{ $question->getScore() }}</span>
                                    <span>votes</span>
                                </div>
                                <div
                                    class="mb-2 text-center {{ $question->reponses->count() > 0 ? 'p-1 bg-success-subtle text-success rounded border border-success-subtle' : '' }}">
                                    <span class="d-block fw-bold fs-5">{{ $question->reponses->count() }}</span>
                                    <span>answers</span>
                                </div>
                                <div class="text-center">
                                    <span class="d-block fw-bold fs-5 text-warning">{{ $question->views }}</span>
                                    <span>views</span>
                                </div>
                            </div>

                            <!-- Content Column -->
                            <div class="col-12 col-sm-10">
                                <h5 class="mb-2">
                                    <a href="{{ route('questions.show', $question) }}"
                                        class="text-decoration-none text-dark fw-bold stretched-link">
                                        {{ $question->title }}
                                    </a>
                                </h5>
                                <p class="text-muted mb-3 text-truncate-2">
                                    {{ Str::limit($question->content, 150) }}
                                </p>

                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                    <div class="d-flex gap-2">
                                        <span class="badge bg-light text-secondary border rounded-pill">php</span>
                                        <span class="badge bg-light text-secondary border rounded-pill">laravel</span>
                                    </div>
                                    <div class="d-flex align-items-center small">
                                        <div class="me-2 text-end lh-1">
                                            <div class="fw-bold text-primary">{{ $question->user->name }}</div>
                                            <div class="text-muted" style="font-size: 0.75rem;">asked
                                                {{ $question->created_at->diffForHumans() }}</div>
                                        </div>
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                            style="width: 32px; height: 32px; font-size: 0.8rem;">
                                            {{ substr($question->user->name, 0, 1) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <img src="https://cdni.iconscout.com/illustration/premium/thumb/empty-state-2130362-1800926.png" alt="Empty"
                        height="200" class="opacity-75 mb-3">
                    <h4 class="text-muted">No questions found!</h4>
                    <p class="text-muted mb-4">Be the first to ask a question and help the community grow.</p>
                    <a href="{{ route('questions.create') }}" class="btn btn-primary rounded-pill px-4">Ask a Question</a>
                </div>
            @endforelse

            <div class="mt-4">
                {{ $questions->links() }}
            </div>
        </div>

        <div class="col-md-3 d-none d-md-block">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3 text-uppercase small text-muted">Stats</h6>
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between mb-2">
                            <span>Questions</span>
                            <span class="fw-bold">{{ \App\Models\Question::count() }}</span>
                        </li>
                        <li class="d-flex justify-content-between mb-2">
                            <span>Answers</span>
                            <span class="fw-bold">{{ \App\Models\Reponse::count() }}</span>
                        </li>
                        <li class="d-flex justify-content-between">
                            <span>Users</span>
                            <span class="fw-bold">{{ \App\Models\User::count() }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 bg-primary text-white">
                <div class="card-body">
                    <h5 class="fw-bold mb-2">Join the Community</h5>
                    <p class="small mb-3 text-white-50">Collaborate with other developers and solve problems together.</p>
                    <a href="https://discord.gg" target="_blank"
                        class="btn btn-light btn-sm w-100 rounded-pill fw-bold text-primary">
                        <i class="bi bi-discord me-1"></i> Join Discord
                    </a>
                </div>
            </div>
        </div>
    </div>

    <style>
        .text-truncate-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .hover-scale {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .hover-scale:hover {
            transform: translateY(-2px);
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
        }

        /* Dark mode support for specific elements */
        body.dark-mode .card {
            background-color: var(--dark-card);
            border-color: #334155 !important;
        }

        body.dark-mode .stretched-link {
            color: #e2e8f0 !important;
        }
    </style>
@endsection