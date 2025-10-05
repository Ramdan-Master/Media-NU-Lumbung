@extends('layouts.app')

@section('title', $banom->name . ' - Media Organisasi')

@section('content')
<div class="container py-5">
    <!-- Banom Header -->
    <div class="banom-header-card card shadow-lg border-0 mb-5 overflow-hidden">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-3 text-center mb-4 mb-md-0">
                    @if($banom->logo)
                        <div class="logo-container mx-auto">
                            <img src="{{ asset('storage/' . $banom->logo) }}"
                                 class="banom-main-logo img-fluid"
                                 style="max-height: 200px; max-width: 200px; object-fit: contain;"
                                 alt="{{ $banom->name }}">
                        </div>
                    @else
                        <div class="default-logo-container">
                            <i class="fas fa-building fa-7x text-primary"></i>
                        </div>
                    @endif
                </div>
                <div class="col-md-9">
                    <h1 class="display-5 fw-bold text-gradient mb-3">{{ $banom->name }}</h1>
                    <div class="contact-details">
                        @if($banom->email)
                            <div class="contact-item mb-2">
                                <i class="fas fa-envelope me-2 text-primary"></i>
                                <span>{{ $banom->email }}</span>
                            </div>
                        @endif
                        @if($banom->phone)
                            <div class="contact-item mb-2">
                                <i class="fas fa-phone me-2 text-success"></i>
                                <span>{{ $banom->phone }}</span>
                            </div>
                        @endif
                        @if($banom->address)
                            <div class="contact-item">
                                <i class="fas fa-map-marker-alt me-2 text-danger"></i>
                                <span>{{ $banom->address }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Description -->
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Tentang {{ $banom->name }}</h5>
                </div>
                <div class="card-body">
                    <div class="content">
                        {!! $banom->description !!}
                    </div>
                </div>
            </div>
            
            <!-- Management -->
            @if($banom->management->count() > 0)
                <div class="card shadow-lg border-0 overflow-hidden">
                    <div class="card-header-modern bg-gradient-success text-white position-relative">
                        <div class="card-header-overlay"></div>
                        <div class="card-header-content position-relative">
                            <h4 class="mb-0 fw-bold">
                                <i class="fas fa-users me-3"></i>Pengurus {{ $banom->name }}
                            </h4>
                            <p class="mb-0 mt-1 opacity-75">Struktur kepengurusan organisasi</p>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            @foreach($banom->management as $person)
                                <div class="col-lg-6 col-xl-4">
                                    <div class="management-card card h-100 border-0 shadow-sm hover-lift">
                                        <div class="card-body text-center p-4">
                                            <!-- Photo -->
                                            <div class="photo-container mb-3">
                                                @if($person->photo)
                                                    <img src="{{ asset('storage/' . $person->photo) }}"
                                                         class="management-photo rounded-circle border border-3 border-white shadow"
                                                         style="width: 100px; height: 100px; object-fit: cover;"
                                                         alt="{{ $person->name }}">
                                                @else
                                                    <div class="management-photo-placeholder rounded-circle border border-3 border-white shadow d-flex align-items-center justify-content-center bg-gradient-primary text-white"
                                                         style="width: 100px; height: 100px;">
                                                        <i class="fas fa-user fa-2x"></i>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Info -->
                                            <div class="info-section">
                                                <h6 class="name fw-bold mb-2 text-dark">{{ $person->name }}</h6>
                                                <div class="position-badge mb-2">
                                                    <span class="badge bg-primary px-3 py-2 fw-semibold">
                                                        <i class="fas fa-crown me-1"></i>{{ $person->position }}
                                                    </span>
                                                </div>
                                                @if($person->period)
                                                    <div class="period-info">
                                                        <small class="text-muted d-block">
                                                            <i class="fas fa-calendar-alt me-1"></i>Periode: {{ $person->period }}
                                                        </small>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Contact Info -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-address-card me-2"></i>Kontak</h5>
                </div>
                <div class="card-body">
                    @if($banom->email)
                        <div class="mb-3">
                            <strong><i class="fas fa-envelope me-2"></i>Email:</strong><br>
                            <span class="text-muted">{{ $banom->email }}</span>
                        </div>
                    @endif

                    @if($banom->phone)
                        <div class="mb-3">
                            <strong><i class="fas fa-phone me-2"></i>Telepon:</strong><br>
                            <span class="text-muted">{{ $banom->phone }}</span>
                        </div>
                    @endif

                    @if($banom->address)
                        <div>
                            <strong><i class="fas fa-map-marker-alt me-2"></i>Alamat:</strong><br>
                            <span class="text-muted">{{ $banom->address }}</span>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Other Banoms -->
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-building me-2"></i>Badan Otonom Lainnya</h5>
                </div>
                <div class="card-body p-0">
                    @foreach(\App\Models\Banom::where('is_active', true)->where('id', '!=', $banom->id)->orderBy('sort_order')->take(5)->get() as $index => $other)
                        <div class="p-3 {{ $index < 4 ? 'border-bottom' : '' }}">
                            <div class="d-flex align-items-center">
                                @if($other->logo)
                                    <img src="{{ asset('storage/' . $other->logo) }}" 
                                         class="me-3" 
                                         style="width: 50px; height: 50px; object-fit: contain;"
                                         alt="{{ $other->name }}">
                                @endif
                                <div>
                                    <h6 class="mb-0">
                                        <a href="{{ route('banom.show', $other->slug) }}" 
                                           class="text-decoration-none text-dark">
                                            {{ $other->name }}
                                        </a>
                                    </h6>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
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

.banom-header-card {
    border-radius: 15px;
    background: #ffffff;
}

.banner-container {
    border-radius: 15px 15px 0 0;
    overflow: hidden;
}

.banner-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
}

.logo-container {
    position: relative;
    z-index: 10;
}

.contact-details {
    margin-top: 1rem;
}

.contact-item {
    display: flex;
    align-items: center;
    font-size: 1.1rem;
}

.contact-item i {
    width: 20px;
}

.content {
    font-size: 1.05rem;
    line-height: 1.8;
}

.content img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 1rem 0;
}

.content p {
    margin-bottom: 1rem;
}

/* Management Section Styles */
.card-header-modern {
    border-radius: 15px 15px 0 0;
    padding: 2rem 1.5rem;
    position: relative;
    overflow: hidden;
}

.bg-gradient-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}

.card-header-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, rgba(255,255,255,0.1), rgba(255,255,255,0.05));
}

.card-header-content {
    z-index: 2;
}

.management-card {
    border-radius: 12px;
    transition: all 0.3s ease;
    background: linear-gradient(145deg, #ffffff, #f8f9fa);
    position: relative;
    overflow: hidden;
}

.management-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #667eea, #764ba2);
}

.hover-lift:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important;
}

.photo-container {
    position: relative;
    display: inline-block;
}

.management-photo,
.management-photo-placeholder {
    transition: all 0.3s ease;
}

.management-card:hover .management-photo,
.management-card:hover .management-photo-placeholder {
    transform: scale(1.05);
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.position-badge {
    min-height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.info-section {
    position: relative;
    z-index: 2;
}

.name {
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
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

    .display-5 {
        font-size: 1.1rem !important;
        margin-bottom: 0.25rem !important;
    }

    .contact-item {
        font-size: 0.75rem !important;
        margin-bottom: 0.125rem !important;
    }

    .card-header-modern {
        padding: 0.5rem 0.375rem !important;
    }

    .card-header-modern h4 {
        font-size: 0.95rem !important;
        margin-bottom: 0.125rem !important;
    }

    .card-header-modern p {
        font-size: 0.7rem !important;
        margin-bottom: 0 !important;
    }

    .card-body {
        padding: 0.75rem 0.5rem !important;
    }

    .management-card {
        margin-bottom: 0.5rem !important;
    }

    .management-card .card-body {
        padding: 0.5rem 0.375rem !important;
    }

    .management-photo,
    .management-photo-placeholder {
        width: 45px !important;
        height: 45px !important;
    }

    .name {
        font-size: 0.8rem !important;
        margin-bottom: 0.125rem !important;
    }

    .badge {
        font-size: 0.6rem !important;
        padding: 0.2rem 0.375rem !important;
    }

    .period-info small {
        font-size: 0.65rem !important;
    }

    .banom-main-logo {
        max-height: 70px !important;
        max-width: 70px !important;
    }

    .content {
        font-size: 0.85rem !important;
    }

    .card.shadow-sm {
        margin-bottom: 0.75rem !important;
    }

    .card.shadow-sm .card-header {
        padding: 0.5rem !important;
    }

    .card.shadow-sm .card-header h5 {
        font-size: 0.9rem !important;
        margin-bottom: 0 !important;
    }

    .card.shadow-sm .card-body {
        padding: 0.75rem 0.5rem !important;
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

    .display-5 {
        font-size: 1rem !important;
        margin-bottom: 0.125rem !important;
    }

    .col-xl-4 {
        flex: 0 0 100%;
        max-width: 100%;
    }

    .row.g-4 {
        --bs-gutter-x: 0.125rem;
        --bs-gutter-y: 0.125rem;
    }

    .card-header-modern {
        padding: 0.375rem 0.25rem !important;
    }

    .card-header-modern h4 {
        font-size: 0.85rem !important;
    }

    .card-body {
        padding: 0.5rem 0.375rem !important;
    }

    .management-card .card-body {
        padding: 0.375rem 0.25rem !important;
    }

    .management-photo,
    .management-photo-placeholder {
        width: 40px !important;
        height: 40px !important;
    }

    .name {
        font-size: 0.7rem !important;
        margin-bottom: 0.0625rem !important;
    }

    .badge {
        font-size: 0.55rem !important;
        padding: 0.15rem 0.3rem !important;
    }

    .period-info small {
        font-size: 0.6rem !important;
    }

    .banom-main-logo {
        max-height: 60px !important;
        max-width: 60px !important;
    }

    .contact-item {
        font-size: 0.7rem !important;
    }

    .content {
        font-size: 0.8rem !important;
    }

    .card.shadow-sm {
        margin-bottom: 0.5rem !important;
    }

    .card.shadow-sm .card-header {
        padding: 0.375rem !important;
    }

    .card.shadow-sm .card-header h5 {
        font-size: 0.8rem !important;
    }

    .card.shadow-sm .card-body {
        padding: 0.5rem 0.375rem !important;
    }

    .card.shadow-sm.mb-4 {
        margin-bottom: 0.375rem !important;
    }
}
</style>
@endpush
