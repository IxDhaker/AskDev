<div class="answer-card position-relative" id="reponse-{{ $reponse->id }}">
    @auth
        @if($reponse->user_id === auth()->id() || auth()->user()->role === 'admin')
            <div class="position-absolute top-0 end-0 m-3 d-flex gap-2">
                @if($reponse->user_id === auth()->id())
                    <a href="{{ route('reponses.edit', $reponse) }}"
                        class="btn btn-sm btn-outline-primary rounded-circle p-2 d-flex align-items-center justify-content-center"
                        style="width: 32px; height: 32px;" title="Edit">
                        <i class="bi bi-pencil" style="font-size: 0.8rem;"></i>
                    </a>
                @endif
                <button type="button"
                    class="btn btn-sm btn-outline-danger rounded-circle p-2 d-flex align-items-center justify-content-center"
                    style="width: 32px; height: 32px;" data-bs-toggle="modal" data-bs-target="#deleteResponseModal"
                    onclick="setDeleteResponseAction('{{ route('reponses.destroy', $reponse) }}')"
                    title="{{ auth()->user()->role === 'admin' ? 'Hide/Delete Answer' : 'Delete' }}">
                    <i class="bi {{ auth()->user()->role === 'admin' ? 'bi-eye-slash' : 'bi-trash' }}"
                        style="font-size: 0.8rem;"></i>
                </button>
            </div>
        @endif
    @endauth

    <div class="vote-section">
        @php
            $reponseScore = $reponse->votes->where('value', 'up')->count() - $reponse->votes->where('value', 'down')->count();
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

        <div class="vote-count">{{ $reponseScore }}</div>

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

    <div class="answer-content pt-3">
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
    </div>
</div>