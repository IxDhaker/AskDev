@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="fw-bold mb-0">Admin Dashboard</h2>
                <p class="text-muted">Overview of system activity and management.</p>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 bg-primary text-white h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="bg-white bg-opacity-25 rounded-circle p-2 d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px;">
                                <i class="bi bi-people-fill fs-4"></i>
                            </div>
                            <span class="badge bg-white bg-opacity-25 rounded-pill">Total Users</span>
                        </div>
                        <h2 class="display-6 fw-bold mb-0">{{ $stats['total_users'] }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 bg-success text-white h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="bg-white bg-opacity-25 rounded-circle p-2 d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px;">
                                <i class="bi bi-question-circle-fill fs-4"></i>
                            </div>
                            <span class="badge bg-white bg-opacity-25 rounded-pill">Total Questions</span>
                        </div>
                        <h2 class="display-6 fw-bold mb-0">{{ $stats['total_questions'] }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 bg-warning text-dark h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="bg-white bg-opacity-25 rounded-circle p-2 d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px;">
                                <i class="bi bi-hourglass-split fs-4 text-dark"></i>
                            </div>
                            <span class="badge bg-white bg-opacity-50 rounded-pill text-dark">Pending</span>
                        </div>
                        <h2 class="display-6 fw-bold mb-0">{{ $stats['pending_questions'] }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 bg-info text-white h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="bg-white bg-opacity-25 rounded-circle p-2 d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px;">
                                <i class="bi bi-activity fs-4"></i>
                            </div>
                            <span class="badge bg-white bg-opacity-25 rounded-pill">System Status</span>
                        </div>
                        <h5 class="fw-bold mb-0 mt-2">Active</h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Recent Users -->
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div
                        class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Recent Users</h5>
                        <a href="{{ route('admin.users') }}" class="btn btn-sm btn-light rounded-pill">View All</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush rounded-bottom-4">
                            @forelse($stats['recent_users'] as $user)
                                <div class="list-group-item px-4 py-3 border-bottom-0 border-top">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-3"
                                            style="width: 40px; height: 40px; font-weight: bold;">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-semibold">{{ $user->name }}</h6>
                                            <small class="text-muted">{{ $user->email }}</small>
                                        </div>
                                        <span
                                            class="badge bg-light text-dark ms-auto">{{ $user->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            @empty
                                <div class="p-4 text-center text-muted">No recent users.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Questions -->
            <div class="col-md-8 mb-4">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div
                        class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Recent Questions</h5>
                        <a href="{{ route('admin.questions') }}" class="btn btn-sm btn-light rounded-pill">View All</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush rounded-bottom-4">
                            @forelse($stats['recent_questions'] as $question)
                                <div class="list-group-item px-4 py-3 border-bottom-0 border-top">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <a href="{{ route('questions.show', $question) }}"
                                                class="fw-semibold text-dark text-decoration-none mb-1 d-block">
                                                {{ Str::limit($question->title, 60) }}
                                            </a>
                                            <div class="d-flex align-items-center gap-2 small text-muted">
                                                <span><i
                                                        class="bi bi-person me-1"></i>{{ $question->user ? $question->user->name : 'Deleted User' }}</span>
                                                <span>&bull;</span>
                                                <span>{{ $question->created_at->format('M d, Y') }}</span>
                                            </div>
                                        </div>
                                        <span
                                            class="badge {{ $question->status === 'open' ? 'bg-success-subtle text-success' : ($question->status === 'closed' ? 'bg-danger-subtle text-danger' : 'bg-warning-subtle text-warning') }} rounded-pill">
                                            {{ ucfirst($question->status) }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div class="p-4 text-center text-muted">No recent questions.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection