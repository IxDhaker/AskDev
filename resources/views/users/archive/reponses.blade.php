@extends('users.archive.layout')

@section('archive-content')
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
            <h4 class="fw-bold mb-0">My Answers</h4>
            <p class="text-muted small">A history of all answers you have contributed.</p>
        </div>
        <div class="card-body p-4">
            @if($reponses->count() > 0)
                <div class="list-group list-group-flush">
                    @foreach($reponses as $reponse)
                        <div class="list-group-item px-0 py-3 border-bottom-0 border-top">
                            <div class="mb-2">
                                <span class="text-muted small">In response to:</span>
                                <a href="{{ route('questions.show', $reponse->question) }}"
                                    class="text-decoration-none fw-semibold text-primary ms-1">
                                    {{ $reponse->question->title }}
                                </a>
                            </div>

                            <div class="p-3 bg-light rounded-3 mb-2">
                                <p class="mb-0 text-dark small text-truncate line-clamp-3" style="white-space: pre-wrap;">
                                    {{ Str::limit($reponse->content, 200) }}
                                </p>
                            </div>

                            <div class="d-flex align-items-center justify-content-between mt-2">
                                <div class="d-flex align-items-center gap-3 text-muted small" style="font-size: 0.8rem;">
                                    <span><i class="bi bi-clock me-1"></i> {{ $reponse->created_at->format('M d, Y') }}</span>
                                    <span><i class="bi bi-hand-thumbs-up me-1"></i>
                                        {{ $reponse->votes()->where('value', 'up')->count() - $reponse->votes()->where('value', 'down')->count() }}
                                        score</span>
                                </div>

                                <div class="d-flex gap-2">
                                    <a href="{{ route('reponses.show', $reponse) }}#reponse-{{ $reponse->id }}"
                                        class="btn btn-sm btn-outline-primary rounded-pill" style="font-size: 0.75rem;">
                                        View
                                    </a>
                                    <a href="{{ route('reponses.edit', $reponse) }}"
                                        class="btn btn-sm btn-outline-secondary rounded-circle p-2 d-flex align-items-center justify-content-center"
                                        style="width: 32px; height: 32px;" title="Edit">
                                        <i class="bi bi-pencil" style="font-size: 0.8rem;"></i>
                                    </a>
                                    <button type="button"
                                        class="btn btn-sm btn-outline-danger rounded-circle p-2 d-flex align-items-center justify-content-center"
                                        style="width: 32px; height: 32px;" title="Delete" data-bs-toggle="modal"
                                        data-bs-target="#deleteArchiveModal"
                                        data-action="{{ route('reponses.destroy', $reponse) }}">
                                        <i class="bi bi-trash" style="font-size: 0.8rem;"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    {{ $reponses->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3 text-muted opacity-50">
                        <i class="bi bi-chat-square-text" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="fw-semibold text-muted">No answers found</h5>
                    <p class="text-muted small">You haven't answered any questions yet.</p>
                    <a href="{{ route('home') }}" class="btn btn-primary btn-sm rounded-pill mt-2">
                        Browse Questions
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection