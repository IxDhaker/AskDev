@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-9">
                <div class="border-bottom pb-4 mb-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h1 class="fw-bold mb-0 text-break">{{ $question->title }}</h1>
                        @if(Auth::id() === $question->user_id)
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm rounded-circle" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
                                    <li><a class="dropdown-item" href="{{ route('questions.edit', $question) }}"><i
                                                class="bi bi-pencil me-2"></i> Edit</a></li>
                                    <li>
                                        <form action="{{ route('questions.destroy', $question) }}" method="POST"
                                            onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger"><i
                                                    class="bi bi-trash me-2"></i> Delete</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @endif
                    </div>
                    <div class="d-flex gap-4 text-muted small border-start border-4 ps-3">
                        <div>
                            <span class="text-secondary">Asked</span>
                            <span class="text-dark fw-medium ms-1">{{ $question->created_at->format('M d, Y') }}</span>
                        </div>
                        <div>
                            <span class="text-secondary">Viewed</span>
                            <span class="text-dark fw-medium ms-1">{{ $question->views }} times</span>
                        </div>
                        <div>
                            <span class="text-secondary">Author</span>
                            <a href="{{ route('users.show', $question->user) }}"
                                class="text-primary fw-medium ms-1 text-decoration-none">{{ $question->user->name }}</a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-auto pe-0">
                        <div id="voting-area" class="d-flex flex-column align-items-center gap-1">
                            <form action="{{ route('votes.vote', $question) }}" method="POST" class="ajax-form">
                                @csrf
                                <input type="hidden" name="value" value="up">
                                <button type="submit"
                                    class="btn btn-link p-0 text-decoration-none {{ $question->votes()->where('user_id', Auth::id())->where('value', 'up')->exists() ? 'text-primary' : 'text-secondary' }}"
                                    {{ Auth::id() === $question->user_id ? 'disabled' : '' }}>
                                    <i class="bi bi-caret-up-fill fs-2"></i>
                                </button>
                            </form>

                            <span class="fw-bold fs-4 my-1">{{ $score }}</span>

                            <form action="{{ route('votes.vote', $question) }}" method="POST" class="ajax-form">
                                @csrf
                                <input type="hidden" name="value" value="down">
                                <button type="submit"
                                    class="btn btn-link p-0 text-decoration-none {{ $question->votes()->where('user_id', Auth::id())->where('value', 'down')->exists() ? 'text-danger' : 'text-secondary' }}"
                                    {{ Auth::id() === $question->user_id ? 'disabled' : '' }}>
                                    <i class="bi bi-caret-down-fill fs-2"></i>
                                </button>
                            </form>

                            @if($question->votes()->where('user_id', Auth::id())->exists())
                                <form action="{{ route('votes.remove', $question) }}" method="POST" class="ajax-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link btn-sm text-muted p-0 mt-2" title="Remove vote">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <div class="col ps-4">
                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                            <div class="card-body p-4">
                                <div class="mb-4" style="line-height: 1.8; white-space: pre-wrap;">{{ $question->content }}
                                </div>
                                <div class="d-flex gap-2 mb-4">
                                    <span class="badge bg-light text-secondary border rounded-pill px-3 py-2">php</span>
                                    <span
                                        class="badge bg-light text-secondary border rounded-pill px-3 py-2">framework</span>
                                </div>
                            </div>
                            <div class="card-footer bg-light border-0 p-3 rounded-bottom-4">
                                <div class="d-flex justify-content-end align-items-center">
                                    <div class="d-flex align-items-center bg-white rounded-3 p-2 shadow-sm border">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($question->user->name) }}&background=random"
                                            class="rounded-2 me-2" width="32" height="32">
                                        <div class="lh-1 text-end">
                                            <div class="small fw-bold text-primary">{{ $question->user->name }}</div>
                                            <div class="text-muted" style="font-size: 10px;">Reputation: 150</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="answers-container">
                            <div class="d-flex align-items-center justify-content-between mb-4 mt-5">
                                <h4 class="fw-bold mb-0">{{ $question->reponses->count() }} Answers</h4>
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm dropdown-toggle rounded-pill px-3" type="button">
                                        Sort by: Votes
                                    </button>
                                </div>
                            </div>

                            <div id="answers-list">
                                @foreach($question->reponses as $reponse)
                                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                                        <div class="card-body p-4">
                                            <div class="d-flex justify-content-between mb-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle bg-light text-secondary d-flex align-items-center justify-content-center me-3 font-monospace fw-bold"
                                                        style="width: 40px; height: 40px;">
                                                        {{ substr($reponse->user->name, 0, 2) }}
                                                    </div>
                                                    <div>
                                                        <a href="#"
                                                            class="fw-bold text-dark text-decoration-none">{{ $reponse->user->name }}</a>
                                                        <div class="text-muted small">answered
                                                            {{ $reponse->created_at->diffForHumans() }}</div>
                                                    </div>
                                                </div>
                                                @if(Auth::id() === $reponse->user_id)
                                                    <div class="dropdown">
                                                        <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="dropdown">
                                                            <i class="bi bi-three-dots"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
                                                            <li><a class="dropdown-item"
                                                                    href="{{ route('reponses.edit', $reponse) }}">Edit</a></li>
                                                            <li>
                                                                <form action="{{ route('reponses.destroy', $reponse) }}" method="POST" class="ajax-form">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button class="dropdown-item text-danger">Delete</button>
                                                                </form>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="text-dark" style="line-height: 1.7; white-space: pre-wrap;">
                                                {{ $reponse->content }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="card border-0 shadow rounded-4 mt-5 overflow-hidden">
                            <div class="card-header bg-primary text-white py-3">
                                <h5 class="fw-bold mb-0"><i class="bi bi-chat-square-text me-2"></i>Your Answer</h5>
                            </div>
                            <div class="card-body p-4">
                                <form action="{{ route('reponses.store', $question) }}" method="POST" class="ajax-form" id="answer-form">
                                    @csrf
                                    <div class="mb-3">
                                        <textarea name="content" class="form-control rounded-3 border-0 bg-light p-3"
                                            rows="6" placeholder="Write your answer here..." required
                                            style="resize: none;"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">Post
                                        Answer</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 d-none d-lg-block">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3 small text-uppercase text-muted">Related Questions</h6>
                        <div class="d-flex flex-column gap-3">
                            <a href="#" class="text-decoration-none text-dark small fw-medium text-truncate">How to use
                                Laravel factories?</a>
                            <a href="#" class="text-decoration-none text-dark small fw-medium text-truncate">Difference
                                between map and forEach</a>
                            <a href="#" class="text-decoration-none text-dark small fw-medium text-truncate">CSS Grid vs
                                Flexbox</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        body.dark-mode .text-dark {
            color: #e2e8f0 !important;
        }

        body.dark-mode .bg-light {
            background-color: var(--dark-bg) !important;
        }

        body.dark-mode .card-footer.bg-light {
            background-color: #0f172a !important;
            /* specific override */
        }
        .loading {
            opacity: 0.5;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }
        
        /* Animations */
        @keyframes slideIn {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        .animate-in {
            animation: slideIn 0.4s ease-out forwards;
        }

        /* Toast Notification */
        #toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
        }
        .toast-notification {
            background: white;
            color: #333;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
            transform: translateX(120%);
            transition: transform 0.3s cubic-bezier(0.68, -0.55, 0.27, 1.55);
            border-left: 4px solid var(--primary-color);
        }
        .toast-notification.show {
            transform: translateX(0);
        }
        body.dark-mode .toast-notification {
            background: #1e293b;
            color: #fff;
        }
    </style>

    <!-- Toast Container -->
    <div id="toast-container"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Function to show toast
            function showToast(message, type = 'success') {
                const container = document.getElementById('toast-container');
                const toast = document.createElement('div');
                toast.className = 'toast-notification';
                
                const icon = type === 'success' ? '<i class="bi bi-check-circle-fill text-success fs-5"></i>' : '<i class="bi bi-exclamation-circle-fill text-danger fs-5"></i>';
                
                toast.innerHTML = `${icon} <span class="fw-bold small">${message}</span>`;
                
                container.appendChild(toast);
                
                // Trigger animation
                requestAnimationFrame(() => {
                    toast.classList.add('show');
                });
                
                // Remove after 3 seconds
                setTimeout(() => {
                    toast.classList.remove('show');
                    setTimeout(() => toast.remove(), 300);
                }, 3000);
            }

            // Function to handle AJAX submissions
            function handleAjaxSubmit(e) {
                e.preventDefault();
                const form = e.target.closest('form');
                if (!form) return;

                const votingArea = document.getElementById('voting-area');
                const answersContainer = document.getElementById('answers-container');
                const answerForm = document.getElementById('answer-form');
                
                // Determine action type for feedback
                const isDelete = form.querySelector('input[name="_method"]')?.value === 'DELETE';
                const isAnswer = form === answerForm;
                
                // Add loading state
                if (votingArea.contains(form)) votingArea.classList.add('loading');
                if (isAnswer) {
                     form.querySelector('button').disabled = true;
                     form.querySelector('button').innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Posting...';
                }

                fetch(form.action, {
                    method: form.method || 'POST',
                    body: new FormData(form),
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    // Parse the returned HTML
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');

                    // Update Voting Area
                    const newVoting = doc.getElementById('voting-area');
                    if (newVoting && votingArea) {
                        votingArea.innerHTML = newVoting.innerHTML;
                        votingArea.classList.remove('loading');
                    }

                    // Update Answers Section (if answering or deleting)
                    const newAnswers = doc.getElementById('answers-container');
                    if (newAnswers && answersContainer) {
                        answersContainer.innerHTML = newAnswers.innerHTML;
                        // Add animation class to new items if needed, or just the container
                        const cards = answersContainer.querySelectorAll('.card');
                         cards.forEach((card, index) => {
                             card.style.animationDelay = `${index * 50}ms`;
                             card.classList.add('animate-in');
                         });
                    }
                    
                    // Reset answer form if successful and it was the answer form
                    if (isAnswer) {
                         form.reset();
                         form.querySelector('button').disabled = false;
                         form.querySelector('button').innerHTML = 'Post Answer';
                         showToast('Answer posted successfully!');
                    } else if (isDelete) {
                        showToast('Answer deleted successfully!');
                    } else {
                        // Voting feedback (optional, maybe too noisy)
                        // showToast('Vote recorded'); 
                    }
                    
                    // Re-attach listeners to new forms (since we replaced HTML)
                    attachListeners();
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Something went wrong. Please try again.', 'error');
                    votingArea.classList.remove('loading');
                    if (isAnswer) {
                         form.querySelector('button').disabled = false;
                         form.querySelector('button').innerHTML = 'Post Answer';
                    }
                });
            }

            function attachListeners() {
                document.querySelectorAll('.ajax-form').forEach(form => {
                    // Remove old listener to avoid duplicates if re-attaching
                    form.removeEventListener('submit', handleAjaxSubmit);
                    form.addEventListener('submit', handleAjaxSubmit);
                });
            }

            attachListeners();
        });
    </script>
@endsection