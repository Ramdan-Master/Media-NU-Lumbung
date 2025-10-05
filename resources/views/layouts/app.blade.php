<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Media Organisasi')</title>
    
    <!-- SEO Meta -->
    @yield('meta')

    <!-- Google AdSense -->
    @if(setting('adsense_code'))
        {!! setting('adsense_code') !!}
    @endif

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: {{ setting('primary_color', '#006400') }};
            --secondary-color: {{ setting('secondary_color', '#228B22') }};
            --accent-color: {{ setting('accent_color', '#32CD32') }};
            --text-color: {{ setting('text_color', '#2F4F2F') }};
            --background-color: {{ setting('background_color', '#FFFFFF') }};
            --success-color: #22c55e;
            --danger-color: #ef4444;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background-color: var(--background-color);
        }

        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            border-radius: 12px;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .card-img-top {
            height: 200px;
            object-fit: cover;
        }

        .hero-featured-img {
            height: 400px;
            object-fit: cover;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .badge {
            padding: 0.5rem 1rem;
        }

        footer {
            background-color: var(--primary-color);
            color: white;
            margin-top: 4rem;
        }

        .news-meta {
            font-size: 0.875rem;
            color: var(--text-color);
        }

        .category-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
            background-color: var(--primary-color);
            color: white;
        }

        .text-primary {
            color: var(--primary-color) !important;
        }

        .bg-primary {
            background-color: var(--primary-color) !important;
        }

        .border-primary {
            border-color: var(--primary-color) !important;
        }

        a:hover {
            color: var(--accent-color);
        }

        /* Mobile Responsiveness */
        @media (max-width: 767px) {
            .card-img-top {
                height: 150px !important;
            }

            .card {
                margin-bottom: 1rem;
            }

            .card-body {
                padding: 1rem 0.75rem;
            }

            h1, .h1 { font-size: 1.75rem; }
            h2, .h2 { font-size: 1.5rem; }
            h3, .h3 { font-size: 1.25rem; }
            h4, .h4 { font-size: 1.125rem; }
            h5, .h5 { font-size: 1rem; }

            .badge {
                padding: 0.375rem 0.75rem;
                font-size: 0.75rem;
            }

            .news-meta {
                font-size: 0.75rem;
            }

            .category-badge {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }

            .py-5 {
                padding-top: 2rem !important;
                padding-bottom: 2rem !important;
            }

            .py-4 {
                padding-top: 1.5rem !important;
                padding-bottom: 1.5rem !important;
            }

            .py-3 {
                padding-top: 1rem !important;
                padding-bottom: 1rem !important;
            }

            .mb-4 {
                margin-bottom: 1.5rem !important;
            }

            .mb-3 {
                margin-bottom: 1rem !important;
            }

            footer .col-md-4 {
                margin-bottom: 2rem;
            }

            .navbar-brand {
                font-size: 1.1rem;
            }

            .navbar-nav .nav-link {
                padding: 0.5rem 0.75rem;
            }

            .navbar-toggler {
                width: 2.5rem;
                height: 2.5rem;
                font-size: 0.8rem;
                padding: 0.25rem 0.5rem;
            }

            .hero-featured-img {
                height: 250px !important;
            }

            .areas-section .d-flex {
                gap: 0.5rem !important;
            }

            .areas-section a {
                padding: 0.25rem 0.5rem !important;
                font-size: 0.75rem !important;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Header -->
    @include('partials.header')
    
    <!-- Main Content -->
    <main class="py-4">
        @yield('content')
    </main>
    
    <!-- Footer -->
    @include('partials.footer')
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>
