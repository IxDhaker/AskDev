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
            --primary: #4f46e5;
            --primary-light: #818cf8;
            --secondary: #ec4899;
            --background: #f8fafc;
            --surface: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;

            --glass-bg: rgba(255, 255, 255, 0.8);
            --glass-border: rgba(255, 255, 255, 0.5);

            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -1px rgb(0 0 0 / 0.06);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -2px rgb(0 0 0 / 0.05);
        }

        body.dark-mode {
            --primary: #6366f1;
            --primary-light: #818cf8;
            --secondary: #f472b6;
            --background: #0f172a;
            --surface: #1e293b;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --border: #334155;

            --glass-bg: rgba(30, 41, 59, 0.8);
            --glass-border: rgba(255, 255, 255, 0.05);

            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.3);
            --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.3), 0 2px 4px -1px rgb(0 0 0 / 0.2);
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--background);
            color: var(--text-main);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        body.light-mode {
            background-image:
                radial-gradient(circle at 10% 20%, rgba(79, 70, 229, 0.05) 0%, transparent 20%),
                radial-gradient(circle at 90% 80%, rgba(236, 72, 153, 0.05) 0%, transparent 20%);
            background-attachment: fixed;
        }

        body.dark-mode {
            background-image:
                radial-gradient(circle at 10% 20%, rgba(99, 102, 241, 0.1) 0%, transparent 20%),
                radial-gradient(circle at 90% 80%, rgba(244, 114, 182, 0.1) 0%, transparent 20%);
            background-attachment: fixed;
        }

        /* Utilities */
        .text-muted {
            color: var(--text-muted) !important;
        }

        .bg-surface {
            background-color: var(--surface) !important;
        }

        .border-theme {
            border-color: var(--border) !important;
        }

        /* Navbar & Glassmorphism */
        .navbar {
            background-color: var(--glass-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            padding: 1rem 0;
            transition: all 0.3s ease;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            -webkit-background-clip: text;
            /* standard property */
            background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -0.5px;
        }

        .nav-link {
            font-weight: 500;
            color: var(--text-muted) !important;
            transition: all 0.2s ease;
            border-radius: 8px;
            padding: 0.5rem 1rem !important;
        }

        .nav-link:hover,
        .nav-link:focus {
            color: var(--primary) !important;
            background-color: rgba(79, 70, 229, 0.05);
        }

        body.dark-mode .nav-link:hover {
            background-color: rgba(99, 102, 241, 0.15);
        }

        /* Cards */
        .card {
            background-color: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: var(--shadow);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
            border-color: rgba(79, 70, 229, 0.3);
        }

        .card-header {
            background-color: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 1.5rem;
            font-weight: 600;
        }

        /* Buttons */
        .btn {
            padding: 0.6rem 1.2rem;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            border: none;
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.3);
            color: white !important;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 15px -3px rgba(79, 70, 229, 0.4);
        }

        /* Inputs */
        .form-control {
            background-color: var(--surface);
            border: 1px solid var(--border);
            color: var(--text-main);
            border-radius: 10px;
            padding: 0.75rem 1rem;
            transition: all 0.2s;
        }

        .form-control:focus {
            background-color: var(--surface);
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.15);
            color: var(--text-main);
        }

        .form-control::placeholder {
            color: var(--text-muted);
            opacity: 0.7;
        }

        /* Dropdowns */
        .dropdown-menu {
            background-color: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: var(--shadow-lg);
            padding: 0.5rem;
        }

        .dropdown-item {
            color: var(--text-main);
            border-radius: 8px;
            padding: 0.6rem 1rem;
            font-weight: 500;
        }

        .dropdown-item:hover {
            background-color: rgba(79, 70, 229, 0.05);
            color: var(--primary);
        }

        body.dark-mode .dropdown-item:hover {
            background-color: rgba(99, 102, 241, 0.15);
        }

        /* Theme Toggle */
        .theme-toggle {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background-color: var(--surface);
            border: 1px solid var(--border);
            color: var(--text-muted);
            transition: all 0.3s ease;
        }

        .theme-toggle:hover {
            background-color: var(--border);
            transform: rotate(15deg);
            color: var(--primary);
        }

        /* Alerts */
        .alert {
            border: none;
            border-radius: 12px;
            padding: 1rem 1.5rem;
        }

        .alert-success {
            background-color: rgba(16, 185, 129, 0.1);
            color: #059669;
        }

        body.dark-mode .alert-success {
            background-color: rgba(16, 185, 129, 0.2);
            color: #6EE7B7;
        }

        /* Footer */
        .footer {
            background-color: var(--surface);
            border-top: 1px solid var(--border);
            padding: 3rem 0;
            margin-top: auto;
        }

        /* Override Bootstrap dark mode specifics if needed */
        body.dark-mode .navbar-toggler-icon {
            filter: invert(1);
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