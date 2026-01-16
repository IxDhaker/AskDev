<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>AskDev</title>
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Outfit:400,500,600,700" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #3b82f6;
            --secondary-color: #6366f1;
            --dark-bg: #0f172a;
            --dark-card: #1e293b;
            --light-bg: #f8fafc;
            --light-card: #ffffff;
            --text-dark: #f8fafc;
            --text-light: #1e293b;
        }

        body {
            font-family: 'Outfit', sans-serif;
            transition: background-color 0.3s, color 0.3s;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        body.light-mode {
            background-color: var(--light-bg);
            color: var(--text-light);
        }

        body.dark-mode {
            background-color: var(--dark-bg);
            color: var(--text-dark);
        }

        body.dark-mode .card,
        body.dark-mode .navbar,
        body.dark-mode .dropdown-menu {
            background-color: var(--dark-card) !important;
            color: var(--text-dark);
            border-color: #334155;
        }

        body.dark-mode .form-control {
            background-color: #334155;
            color: white;
            border-color: #475569;
        }

        body.dark-mode .text-muted {
            color: #94a3b8 !important;
        }

        .navbar {
            backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.8);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        body.dark-mode .navbar {
            background-color: rgba(30, 41, 59, 0.8) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .navbar-brand img {
            height: 40px;
            transition: transform 0.3s;
        }

        .navbar-brand:hover img {
            transform: scale(1.05);
        }

        .nav-link {
            font-weight: 500;
            transition: color 0.2s;
        }

        body.dark-mode .nav-link,
        body.dark-mode .navbar-brand {
            color: #e2e8f0 !important;
        }

        body.dark-mode .dropdown-item {
            color: #e2e8f0;
        }

        body.dark-mode .dropdown-item:hover {
            background-color: #334155;
        }

        main {
            flex: 1;
        }

        .footer {
            margin-top: auto;
            padding: 2rem 0;
            background: linear-gradient(to right, #ffffff, #f1f5f9);
            border-top: 1px solid #e2e8f0;
        }

        body.dark-mode .footer {
            background: linear-gradient(to right, #1e293b, #0f172a);
            border-top: 1px solid #334155;
        }

        .theme-toggle {
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 50%;
            transition: background-color 0.2s;
        }

        .theme-toggle:hover {
            background-color: rgba(128, 128, 128, 0.1);
        }
    </style>
</head>

<body class="light-mode">
    <div id="app">
        <nav class="navbar navbar-expand-md fixed-top">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
                    <span class="d-none d-sm-block fw-bold text-primary">AskDev</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto">
                    </ul>

                    <ul class="navbar-nav ms-auto align-items-center">
                        <li class="nav-item me-3">
                            <button id="themeToggle" class="btn btn-link nav-link p-0 theme-toggle"
                                title="Toggle Theme">
                                <i class="bi bi-moon-stars-fill" id="themeIcon"></i>
                            </button>
                        </li>

                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link px-3 py-2 ms-2 btn btn-primary text-black rounded-pill"
                                        href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link px-3 py-2 ms-2 btn btn-primary text-black rounded-pill"
                                        href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center gap-2"
                                    href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false" v-pre>
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                        style="width: 32px; height: 32px;">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end shadow-lg border-0"
                                    aria-labelledby="navbarDropdown">
                                    @if(Auth::user()->adminProfile)
                                        <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                            <i class="bi bi-speedometer2 me-2"></i> Admin Dashboard
                                        </a>
                                        <div class="dropdown-divider"></div>
                                    @endif

                                    <a class="dropdown-item" href="{{ route('users.show', Auth::user()) }}">
                                        <i class="bi bi-person me-2"></i> {{ __('My Profile') }}
                                    </a>

                                    <a class="dropdown-item text-danger" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right me-2"></i> {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-5 mt-5">
            <div class="container mt-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>

        <footer class="footer mt-auto">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <h5 class="fw-bold text-primary mb-2">AskDev</h5>
                        <p class="text-muted small mb-0">The premier platform for developers to share knowledge and grow
                            together.</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p class="text-muted small mb-0">&copy; {{ date('Y') }} AskDev. All rights reserved.</p>
                        <div class="mt-2">
                            <a href="#" class="text-muted me-3 text-decoration-none"><i class="bi bi-github"></i></a>
                            <a href="#" class="text-muted me-3 text-decoration-none"><i class="bi bi-twitter"></i></a>
                            <a href="#" class="text-muted text-decoration-none"><i class="bi bi-linkedin"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const themeToggle = document.getElementById('themeToggle');
            const themeIcon = document.getElementById('themeIcon');
            const body = document.body;

            // Check local storage
            const currentTheme = localStorage.getItem('theme');
            if (currentTheme === 'dark') {
                body.classList.remove('light-mode');
                body.classList.add('dark-mode');
                themeIcon.classList.remove('bi-moon-stars-fill');
                themeIcon.classList.add('bi-sun-fill');
            }

            themeToggle.addEventListener('click', function () {
                if (body.classList.contains('light-mode')) {
                    body.classList.remove('light-mode');
                    body.classList.add('dark-mode');
                    themeIcon.classList.remove('bi-moon-stars-fill');
                    themeIcon.classList.add('bi-sun-fill');
                    localStorage.setItem('theme', 'dark');
                } else {
                    body.classList.remove('dark-mode');
                    body.classList.add('light-mode');
                    themeIcon.classList.remove('bi-sun-fill');
                    themeIcon.classList.add('bi-moon-stars-fill');
                    localStorage.setItem('theme', 'light');
                }
            });
        });
    </script>
</body>

</html>