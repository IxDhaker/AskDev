@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Back Button -->
                <div class="mb-4">
                    <a href="{{ route('home') }}" class="btn btn-link text-decoration-none text-muted ps-0">
                        <i class="bi bi-arrow-left me-2"></i>Back to Home
                    </a>
                </div>

                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-header bg-primary text-white p-4 p-md-5 position-relative overflow-hidden">
                        <div class="position-absolute top-0 start-0 w-100 h-100 bg-gradient-overlay"></div>
                        <h2 class="fw-bold mb-2 position-relative">Ask a Question</h2>
                        <p class="mb-0 text-white-50 position-relative">Get help from the community by posting your question
                            below.</p>
                    </div>

                    <div class="card-body p-4 p-md-5 bg-surface">
                        <form action="{{ route('questions.store') }}" method="POST">
                            @csrf

                            <!-- Title Input -->
                            <div class="mb-4">
                                <label for="title" class="form-label fw-bold text-uppercase text-muted small">
                                    Question Title <span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light border-end-0 text-muted">
                                        <i class="bi bi-type-h1"></i>
                                    </span>
                                    <input type="text"
                                        class="form-control border-start-0 ps-0 @error('title') is-invalid @enderror"
                                        id="title" name="title" value="{{ old('title') }}"
                                        placeholder="e.g., How to fix 'Undefined variable' in PHP?" required autofocus>
                                </div>
                                @error('title')
                                    <div class="invalid-feedback d-block mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-text mt-2 text-muted">
                                    Be specific and imagine you’re asking a question to another person.
                                </div>
                            </div>

                            <!-- Content Input -->
                            <div class="mb-4">
                                <label for="content" class="form-label fw-bold text-uppercase text-muted small">
                                    Detailed Description <span class="text-danger">*</span>
                                </label>
                                <div class="position-relative">
                                    <textarea class="form-control @error('content') is-invalid @enderror" id="content"
                                        name="content" rows="10"
                                        placeholder="Describe your problem in detail. Include code snippets if possible."
                                        required>{{ old('content') }}</textarea>

                                    <!-- Optional: Helper icon inside textarea context could go here, but keeping it simple for now -->
                                </div>
                                @error('content')
                                    <div class="invalid-feedback d-block mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-text mt-2 text-muted">
                                    Include all the information someone would need to answer your question.
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-5">
                                <a href="{{ route('home') }}"
                                    class="btn btn-light px-4 py-2 fw-semibold rounded-pill me-md-2">
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-primary px-5 py-2 fw-bold rounded-pill shadow-sm">
                                    <i class="bi bi-send me-2"></i>Post Question
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Tips / Guidelines Side (Optional - currently inline for simplicity but could be a side column) -->
                <div class="mt-4 text-center text-muted small">
                    <p>By posting, you agree to our <a href="#" class="text-decoration-none">Community Guidelines</a>.</p>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-gradient-overlay {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(0, 0, 0, 0.1) 100%);
        }

        .form-control,
        .input-group-text {
            background-color: var(--background);
            /* Use the slightly off-white background for inputs */
        }

        .form-control:focus {
            background-color: var(--surface);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }

        /* Dark mode overrides if needed specifically for this page, though layout handles most */
        textarea {
            resize: vertical;
            min-height: 150px;
        }

        /* TinyMCE Dark Mode Fixes if needed, or variable usage */
        .tox-tinymce {
            border-radius: 0.5rem !important;
            border: 1px solid var(--border) !important;
        }
    </style>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                tinymce.init({
                    selector: '#content',
                    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
                    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
                    skin: document.body.classList.contains('dark-mode') ? 'oxide-dark' : 'oxide',
                    content_css: document.body.classList.contains('dark-mode') ? 'dark' : 'default',
                    height: 400,
                    menubar: false,
                    statusbar: false,
                    setup: function (editor) {
                        editor.on('change', function () {
                            editor.save(); // Syncs content to textarea
                        });
                    }
                });
            });
        </script>
    @endpush
@endsection