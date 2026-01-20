@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="mb-3">
                    <a href="{{ route('questions.show', $reponse->question) }}" class="text-decoration-none text-muted">
                        <i class="bi bi-arrow-left me-2"></i>Back to Question
                    </a>
                </div>

                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-transparent border-0 pt-4 px-4">
                        <h2 class="h4 fw-bold mb-0">Edit Your Answer</h2>
                        <p class="text-muted small mt-1">
                            For question: <span
                                class="fw-semibold text-primary">{{Str::limit($reponse->question->title, 60)}}</span>
                        </p>
                    </div>

                    <div class="card-body p-4">
                        <form action="{{ route('reponses.update', $reponse) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label for="content" class="form-label fw-semibold text-secondary">Your Answer</label>
                                <textarea name="content" id="content" rows="8"
                                    class="form-control @error('content') is-invalid @enderror"
                                    required>{{ old('content', $reponse->content) }}</textarea>

                                @error('content')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('questions.show', $reponse->question) }}"
                                    class="btn btn-light rounded-pill px-4">
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-primary rounded-pill px-4">
                                    Update Answer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection