@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="d-flex align-items-center mb-4">
                    <a href="{{ route('home') }}" class="btn btn-light btn-sm rounded-circle me-3 shadow-sm"
                        style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <h2 class="fw-bold mb-0">Ask a Question</h2>
                </div>

                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-header bg-white p-4 border-bottom">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-3"
                                style="width: 48px; height: 48px;">
                                <i class="bi bi-lightbulb fs-4"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0">Tips on getting good answers</h5>
                                <p class="text-muted small mb-0">Make sure your question is clear, concise, and provides
                                    enough context.</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('questions.store') }}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label for="title" class="form-label fw-bold">Title</label>
                                <div class="form-text text-muted mb-2 small">Be specific and imagine you're asking a
                                    question to another person.</div>
                                <input type="text"
                                    class="form-control form-control-lg rounded-3 @error('title') is-invalid @enderror"
                                    id="title" name="title" value="{{ old('title') }}"
                                    placeholder="e.g. Is there an R function for finding the index of an element in a vector?"
                                    required autofocus>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="content" class="form-label fw-bold">Body</label>
                                <div class="form-text text-muted mb-2 small">Include all the information someone would need
                                    to answer your question.</div>
                                <textarea class="form-control rounded-3 @error('content') is-invalid @enderror" id="content"
                                    name="content" rows="10"
                                    placeholder="Describe your problem details and what you tried..."
                                    required>{{ old('content') }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="tags" class="form-label fw-bold">Tags <span
                                        class="text-muted fw-normal small">(optional)</span></label>
                                <input type="text" class="form-control rounded-3" id="tags"
                                    placeholder="e.g. php, laravel, javascript">
                                <div class="form-text text-muted small">Add up to 5 tags to describe what your question is
                                    about.</div>
                            </div>

                            <div class="d-flex justify-content-end gap-3 mt-5">
                                <a href="{{ route('home') }}" class="btn btn-light rounded-pill px-4 fw-bold">Cancel</a>
                                <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                                    Post Question
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection