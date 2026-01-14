@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="d-flex align-items-center mb-4">
                    <a href="{{ route('questions.show', $question) }}"
                        class="btn btn-light btn-sm rounded-circle me-3 shadow-sm"
                        style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <h2 class="fw-bold mb-0">Edit Question</h2>
                </div>

                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('questions.update', $question) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label for="title" class="form-label fw-bold">Title</label>
                                <input type="text"
                                    class="form-control form-control-lg rounded-3 @error('title') is-invalid @enderror"
                                    id="title" name="title" value="{{ old('title', $question->title) }}" required autofocus>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="content" class="form-label fw-bold">Body</label>
                                <textarea class="form-control rounded-3 @error('content') is-invalid @enderror" id="content"
                                    name="content" rows="10" required>{{ old('content', $question->content) }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="status" class="form-label fw-bold">Status</label>
                                <select class="form-select rounded-3" name="status">
                                    <option value="open" {{ $question->status == 'open' ? 'selected' : '' }}>Open</option>
                                    <option value="closed" {{ $question->status == 'closed' ? 'selected' : '' }}>Closed
                                    </option>
                                </select>
                            </div>

                            <div class="d-flex justify-content-end gap-3 mt-5">
                                <a href="{{ route('questions.show', $question) }}"
                                    class="btn btn-light rounded-pill px-4 fw-bold">Cancel</a>
                                <button type="submit"
                                    class="btn btn-warning rounded-pill px-5 fw-bold shadow-sm text-white">
                                    Update Question
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection