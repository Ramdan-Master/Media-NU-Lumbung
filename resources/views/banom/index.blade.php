@extends('layouts.app')

@section('title', 'Badan Otonom - Media Organisasi')

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold text-gradient mb-3">
            <i class="fas fa-building me-3"></i>Badan Otonom
        </h1>
        <p class="lead text-muted">Organisasi-organisasi di bawah naungan kami</p>
        <div class="divider mx-auto"></div>
    </div>

    <!-- Banom List -->
    @if($banoms->count() > 0)
        <div class="row g-4">
            @foreach($banoms as $banom)
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('banom.show', $banom->slug) }}" class="text-decoration-none banom-card-link">
                        <div class="banom-card card h-100 shadow-lg border-0 overflow-hidden">
                            <!-- Card Header with Logo -->
                            <div class="card-header-custom position-relative d-flex align-items-center justify-content-center"
                                 style="height: 160px; background: #ffffff;">
                                @if($banom->logo)
                                    <img src="{{ asset('storage/' . $banom->logo) }}"
                                         class="banom-logo img-fluid"
                                         style="max-height: 140px; max-width: 140px; object-fit: contain;"
                                         alt="{{ $banom->name }}">
                                @else
                                    <div class="default-icon text-primary">
                                        <i class="fas fa-building fa-4x"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Card Body -->
                            <div class="card-body text-center py-4">
                                <h5 class="card-title fw-bold mb-3">{{ $banom->name }}</h5>
                                <p class="card-text text-muted small mb-3">
                                    {{ Str::limit(strip_tags($banom->description), 100) }}
                                </p>

                                <!-- Contact Info -->
                                @if($banom->email || $banom->phone)
                                    <div class="contact-info mt-3">
                                        @if($banom->email)
                                            <small class="d-block text-muted mb-1">
                                                <i class="fas fa-envelope me-1"></i>{{ $banom->email }}
                                            </small>
                                        @endif
                                        @if($banom->phone)
                                            <small class="d-block text-muted">
                                                <i class="fas fa-phone me-1"></i>{{ $banom->phone }}
                                            </small>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <!-- Hover Effect Indicator -->
                            <div class="card-footer-custom text-center py-2">
                                <small class="text-primary fw-semibold">
                                    <i class="fas fa-arrow-right me-1"></i>Klik untuk detail
                                </small>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info border-0 shadow-sm">
            <div class="d-flex align-items-center">
                <i class="fas fa-info-circle fa-2x me-3 text-info"></i>
                <div>
                    <h5 class="mb-1">Belum ada data badan otonom</h5>
                    <p class="mb-0">Data badan otonom akan segera ditambahkan.</p>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.text-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.divider {
    width: 80px;
    height: 4px;
    background: linear-gradient(90deg, #667eea, #764ba2);
    border-radius: 2px;
}

.banom-card-link {
    transition: all 0.3s ease;
}

.banom-card-link:hover {
    transform: translateY(-8px);
}

.banom-card {
    transition: all 0.3s ease;
    border-radius: 15px;
    overflow: hidden;
    background: #ffffff;
}

.banom-card:hover {
    box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
    transform: translateY(-5px);
}

.card-header-custom {
    padding: 0;
    position: relative;
}

.card-gradient-bg {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.card-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
}

.logo-overlay {
    position: absolute;
    bottom: -40px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 10;
}

.card-footer-custom {
    background: rgba(248, 249, 250, 0.8);
    border-top: 1px solid rgba(0,0,0,0.05);
}

.default-icon {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.contact-info {
    opacity: 0.8;
}

/* Ultra-compact responsive - Like NU Online mobile */
@media (max-width: 768px) {
    .container {
        padding-left: 4px;
        padding-right: 4px;
    }

    .py-5 {
        padding-top: 0.5rem !important;
        padding-bottom: 0.5rem !important;
    }

    .mb-5 {
        margin-bottom: 0.75rem !important;
    }

    .display-4 {
        font-size: 1.1rem !important;
        margin-bottom: 0.25rem !important;
    }

    .lead {
        font-size: 0.8rem !important;
        margin-bottom: 0.5rem !important;
    }

    .divider {
        width: 30px !important;
        height: 2px !important;
        margin-bottom: 0.75rem !important;
    }

    .row.g-4 {
        --bs-gutter-x: 0.125rem;
        --bs-gutter-y: 0.125rem;
    }

    .banom-card-link:hover {
        transform: none;
    }

    .banom-card:hover {
        transform: none;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05) !important;
    }

    .card-header-custom {
        height: 50px !important;
        padding: 0.25rem !important;
    }

    .banom-logo {
        max-height: 35px !important;
        max-width: 35px !important;
    }

    .card-body {
        padding: 0.375rem 0.25rem !important;
    }

    .card-title {
        font-size: 0.75rem !important;
        margin-bottom: 0.125rem !important;
        line-height: 1.1 !important;
    }

    .card-text {
        font-size: 0.65rem !important;
        margin-bottom: 0.25rem !important;
        line-height: 1.2 !important;
    }

    .contact-info small {
        font-size: 0.6rem !important;
    }

    .card-footer-custom {
        padding: 0.25rem !important;
    }

    .card-footer-custom small {
        font-size: 0.6rem !important;
    }
}

@media (max-width: 576px) {
    .container {
        padding-left: 2px;
        padding-right: 2px;
    }

    .py-5 {
        padding-top: 0.25rem !important;
        padding-bottom: 0.25rem !important;
    }

    .mb-5 {
        margin-bottom: 0.5rem !important;
    }

    .display-4 {
        font-size: 1rem !important;
        margin-bottom: 0.125rem !important;
    }

    .lead {
        font-size: 0.75rem !important;
        margin-bottom: 0.375rem !important;
    }

    .divider {
        width: 25px !important;
        height: 2px !important;
        margin-bottom: 0.5rem !important;
    }

    .row.g-4 {
        --bs-gutter-x: 0.0625rem;
        --bs-gutter-y: 0.0625rem;
    }

    .card-header-custom {
        height: 45px !important;
        padding: 0.125rem !important;
    }

    .banom-logo {
        max-height: 30px !important;
        max-width: 30px !important;
    }

    .card-body {
        padding: 0.25rem 0.1875rem !important;
    }

    .card-title {
        font-size: 0.7rem !important;
        margin-bottom: 0.0625rem !important;
    }

    .card-text {
        font-size: 0.6rem !important;
        margin-bottom: 0.1875rem !important;
    }

    .contact-info small {
        font-size: 0.55rem !important;
    }

    .card-footer-custom {
        padding: 0.1875rem !important;
    }

    .card-footer-custom small {
        font-size: 0.55rem !important;
    }

    .alert {
        padding: 0.5rem !important;
        margin-bottom: 0.5rem !important;
    }

    .alert h5 {
        font-size: 0.8rem !important;
        margin-bottom: 0.125rem !important;
    }

    .alert p {
        font-size: 0.7rem !important;
        margin-bottom: 0 !important;
    }
}
</style>
@endpush
