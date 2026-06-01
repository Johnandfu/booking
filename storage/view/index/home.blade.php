@extends('index.layouts.main')

@section('content')
    <header class="landing-hero">
        <div class="container position-relative">
            <div class="landing-hero-content reveal">
                <span class="landing-kicker">{{ $copy['home-heroKicker'] ?? 'home-heroKicker' }}</span>
                <h1>{{ $copy['home-heroTitle'] ?? 'home-heroTitle' }}</h1>
                <p>{{ $copy['home-heroSubtitle'] ?? 'home-heroSubtitle' }}</p>
                <a href="/service" class="btn btn-light btn-lg rounded-pill px-5">{{ $copy['home-heroCta'] ?? 'home-heroCta' }}</a>
            </div>
        </div>
        <a href="#features" class="scroll-indicator" aria-label="{{ $copy['home-scrollLabel'] ?? 'home-scrollLabel' }}">
            <span></span>
        </a>
    </header>

    <main class="landing-page">
        <section id="features" class="landing-section container">
            <div class="landing-section-heading reveal">
                <span class="landing-kicker">{{ $copy['home-featuresKicker'] ?? 'home-featuresKicker' }}</span>
                <h2>{{ $copy['home-featuresTitle'] ?? 'home-featuresTitle' }}</h2>
            </div>
            <div class="row g-4">
                <div class="col-md-4 reveal">
                    <article class="landing-feature-card h-100">
                        <span class="material-symbols-rounded">beach_access</span>
                        <h3>{{ $copy['home-featureBeachTitle'] ?? 'home-featureBeachTitle' }}</h3>
                        <p>{{ $copy['home-featureBeachText'] ?? 'home-featureBeachText' }}</p>
                    </article>
                </div>
                <div class="col-md-4 reveal">
                    <article class="landing-feature-card h-100">
                        <span class="material-symbols-rounded">apartment</span>
                        <h3>{{ $copy['home-featureUrbanTitle'] ?? 'home-featureUrbanTitle' }}</h3>
                        <p>{{ $copy['home-featureUrbanText'] ?? 'home-featureUrbanText' }}</p>
                    </article>
                </div>
                <div class="col-md-4 reveal">
                    <article class="landing-feature-card h-100">
                        <span class="material-symbols-rounded">kayaking</span>
                        <h3>{{ $copy['home-featureOutdoorTitle'] ?? 'home-featureOutdoorTitle' }}</h3>
                        <p>{{ $copy['home-featureOutdoorText'] ?? 'home-featureOutdoorText' }}</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="landing-section container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 reveal">
                    <div class="landing-about-image">
                        <img src="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1200&q=85" alt="{{ $copy['home-aboutImageAlt'] ?? 'home-aboutImageAlt' }}">
                    </div>
                </div>
                <div class="col-lg-6 reveal">
                    <span class="landing-kicker">{{ $copy['home-aboutKicker'] ?? 'home-aboutKicker' }}</span>
                    <h2 class="landing-about-title">{{ $copy['home-aboutTitle'] ?? 'home-aboutTitle' }}</h2>
                    <p class="landing-copy">{{ str_replace('{siteName}', $copy['main-siteName'] ?? 'main-siteName', $copy['home-aboutText'] ?? 'home-aboutText') }}</p>
                    <a href="/about" class="btn btn-au px-4">{{ $copy['home-aboutCta'] ?? 'home-aboutCta' }}</a>
                </div>
            </div>
        </section>

        <section class="landing-section container">
            <div class="landing-section-heading reveal">
                <span class="landing-kicker">{{ $copy['home-galleryKicker'] ?? 'home-galleryKicker' }}</span>
                <h2>{{ $copy['home-galleryTitle'] ?? 'home-galleryTitle' }}</h2>
            </div>
            <div class="landing-gallery">
                <div class="landing-gallery-item tall reveal">
                    <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=900&q=85" alt="{{ $copy['home-galleryBeachAlt'] ?? 'home-galleryBeachAlt' }}">
                </div>
                <div class="landing-gallery-item reveal">
                    <img src="https://images.unsplash.com/photo-1519046904884-53103b34b206?auto=format&fit=crop&w=900&q=85" alt="{{ $copy['home-galleryHolidayAlt'] ?? 'home-galleryHolidayAlt' }}">
                </div>
                <div class="landing-gallery-item reveal">
                    <img src="https://images.unsplash.com/photo-1500375592092-40eb2168fd21?auto=format&fit=crop&w=900&q=85" alt="{{ $copy['home-galleryWavesAlt'] ?? 'home-galleryWavesAlt' }}">
                </div>
                <div class="landing-gallery-item wide reveal">
                    <img src="https://images.unsplash.com/photo-1499793983690-e29da59ef1c2?auto=format&fit=crop&w=1200&q=85" alt="{{ $copy['home-galleryPoolAlt'] ?? 'home-galleryPoolAlt' }}">
                </div>
            </div>
        </section>
    </main>
@endsection
