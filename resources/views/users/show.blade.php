@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <!-- Profile Sidebar -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0 rounded-4 overflow-hidden text-center h-100">
                    <div class="card-body p-5">
                        <div class="mb-4 position-relative d-inline-block">
                            <div class="avatar-circle rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto shadow-sm"
                                style="width: 120px; height: 120px; font-size: 3rem; font-weight: 700;">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            @if(auth()->id() === $user->id)
                                <a href="{{ route('users.edit', $user) }}"
                                    class="position-absolute bottom-0 end-0 btn btn-sm btn-light rounded-circle shadow-sm border"
                                    style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;"
                                    title="Edit Profile">
                                    <i class="bi bi-pencil-fill text-primary" style="font-size: 0.9rem;"></i>
                                </a>
                            @endif
                        </div>
                        <h3 class="fw-bold mb-1">{{ $user->name }}</h3>
                        <p class="text-muted mb-4 small"><i class="bi bi-envelope me-1"></i> {{ $user->email }}</p>

                        <div class="d-flex justify-content-center gap-2 mb-4">
                            <div class="text-center px-3 border-end">
                                <h5 class="fw-bold mb-0 text-primary">{{ $stats['total_questions'] }}</h5>
                                <small class="text-muted text-uppercase"
                                    style="font-size: 0.65rem; letter-spacing: 1px;">Questions</small>
                            </div>
                            <div class="text-center px-3 border-end">
                                <h5 class="fw-bold mb-0 text-primary">{{ $stats['total_reponses'] }}</h5>
                                <small class="text-muted text-uppercase"
                                    style="font-size: 0.65rem; letter-spacing: 1px;">Answers</small>
                            </div>
                            <div class="text-center px-3">
                                <h5 class="fw-bold mb-0 text-primary">{{ $stats['total_votes'] }}</h5>
                                <small class="text-muted text-uppercase"
                                    style="font-size: 0.65rem; letter-spacing: 1px;">Votes</small>
                            </div>
                        </div>

                        @if(auth()->id() === $user->id)
                            <div class="d-grid gap-2 col-8 mx-auto">
                                <a href="{{ route('users.archive.questions', $user) }}"
                                    class="btn btn-outline-primary btn-sm rounded-pill">
                                    <i class="bi bi-archive me-2"></i>My Archive
                                </a>
                            </div>
                        @endif
                        <p class="text-muted mt-4 mb-0 small" style="font-size: 0.75rem;">Member since
                            {{ $user->created_at->format('M Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="col-md-8">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div
                        class="card-header bg-transparent border-0 pt-4 px-4 d-flex align-items-center justify-content-between">
                        <h5 class="fw-bold mb-0 icon-title">
                            <i class="bi bi-activity me-2 text-primary"></i>Recent Activity
                        </h5>
                        @if(auth()->id() === $user->id)
                            <a href="{{ route('users.archive.questions', $user) }}" class="text-decoration-none small fw-semibold">View
                                All</a>
                        @endif
                    </div>
                    <div class="card-body p-4">
                        @if($user->questions->isEmpty() && $user->reponses->isEmpty())
                            <div class="text-center py-5 text-muted">
                                <i class="bi bi-person-slash mb-3" style="font-size: 2.5rem; opacity: 0.5;"></i>
                                <p class="mb-0">No recent activity to show.</p>
                            </div>
                        @else
                            <ul class="list-group list-group-flush">
                                @foreach($user->questions->take(3) as $question)
                                    <li class="list-group-item px-0 py-3 border-bottom-0 border-top">
                                        <div class="d-flex align-items-start">
                                            <div class="me-3 mt-1">
                                                <span class="badge bg-primary-subtle text-primary rounded-pill p-2"><i
                                                        class="bi bi-question-lg"></i></span>
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="small text-muted mb-1">Asked a question</p>
                                                <a href="{{ route('questions.show', $question) }}"
                                                    class="fw-semibold text-dark text-decoration-none d-block mb-1">{{ $question->title }}</a>
                                                <small class="text-muted">{{ $question->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach

                                @foreach($user->reponses->take(3) as $reponse)
                                    <li class="list-group-item px-0 py-3 border-bottom-0 border-top">
                                        <div class="d-flex align-items-start">
                                            <div class="me-3 mt-1">
                                                <span class="badge bg-success-subtle text-success rounded-pill p-2"><i
                                                        class="bi bi-chat-text"></i></span>
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="small text-muted mb-1">Answered a question</p>
                                                <a href="{{ route('questions.show', $reponse->question) }}"
                                                    class="fw-semibold text-dark text-decoration-none d-block mb-1">
                                                    Re: {{ Str::limit($reponse->question->title, 50) }}
                                                </a>
                                                <p class="small text-muted mb-1 text-truncate" style="max-width: 90%;">
                                                    "{{ Str::limit($reponse->content, 60) }}"</p>
                                                <small class="text-muted">{{ $reponse->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection