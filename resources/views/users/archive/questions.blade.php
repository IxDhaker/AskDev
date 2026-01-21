@extends('users.archive.layout')

@section('archive-content')
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
            <h4 class="fw-bold mb-0">My Questions</h4>
            <p class="text-muted small">A history of all questions you have asked.</p>
        </div>
        <div class="card-body p-4">
            @if($questions->count() > 0)
                <div class="list-group list-group-flush">
                    @foreach($questions as $question)
                        <div class="list-group-item px-0 py-3 border-bottom-0 border-top">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="flex-grow-1 pe-3">
                                    <div class="d-flex align-items-center mb-1">
                                        <span
                                            class="badge {{ $question->status === 'open' ? 'bg-success-subtle text-success' : ($question->status === 'closed' ? 'bg-danger-subtle text-danger' : 'bg-warning-subtle text-warning') }} rounded-pill small me-2"
                                            style="font-size: 0.7rem;">
                                            {{ ucfirst($question->status) }}
                                        </span>
                                        <a href="{{ route('questions.show', $question) }}"
                                            class="text-decoration-none fw-bold h6 text-dark mb-0 d-block">
                                            {{ $question->title }}
                                        </a>
                                    </div>

                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('questions.edit', $question) }}"
                                        class="btn btn-sm btn-outline-primary rounded-circle p-2 d-flex align-items-center justify-content-center"
                                        style="width: 32px; height: 32px;" title="Edit">
                                        <i class="bi bi-pencil" style="font-size: 0.8rem;"></i>
                                    </a>
                                    <button type="button"
                                        class="btn btn-sm btn-outline-danger rounded-circle p-2 d-flex align-items-center justify-content-center"
                                        style="width: 32px; height: 32px;" title="Delete" data-bs-toggle="modal"
                                        data-bs-target="#deleteArchiveModal"
                                        data-action="{{ route('questions.destroy', $question) }}">
                                        <i class="bi bi-trash" style="font-size: 0.8rem;"></i>
                                    </button>
                                </div>
                            </div>
                            <p class="text-muted small mb-2 text-truncate line-clamp-2">
                                {{ Str::limit($question->content, 150) }}
                            </p>
                            <div class="d-flex align-items-center gap-3 text-muted small" style="font-size: 0.8rem;">
                                <span><i class="bi bi-clock me-1"></i> {{ $question->created_at->format('M d, Y') }}</span>
                                <span><i class="bi bi-eye me-1"></i> {{ $question->views }} views</span>
                                <span><i class="bi bi-chat me-1"></i>
                                    {{ $question->reponses_count ?? $question->reponses()->count() }} answers</span>
                                <span><i class="bi bi-hand-thumbs-up me-1"></i>
                                    {{ $question->votes()->where('value', 'up')->count() - $question->votes()->where('value', 'down')->count() }}
                                    votes</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    {{ $questions->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3 text-muted opacity-50">
                        <i class="bi bi-question-square" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="fw-semibold text-muted">No questions found</h5>
                    <p class="text-muted small">You haven't asked any questions yet.</p>
                    <a href="{{ route('questions.create') }}" class="btn btn-primary btn-sm rounded-pill mt-2">
                        Ask a Question
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection