@extends('index.layouts.main')

@section('content')
    <header class="landing-hero">
        <div class="container position-relative">
            <div class="landing-hero-content reveal">
                <span class="landing-kicker">{{ $copy['homeHeroKicker'] }}</span>
                <h1>{{ $copy['homeHeroTitle'] }}</h1>
                <p>{{ $copy['homeHeroSubtitle'] }}</p>
                <a href="/service" class="btn btn-light btn-lg rounded-pill px-5">{{ $copy['homeHeroCta'] }}</a>
            </div>
        </div>
        <a href="#features" class="scroll-indicator" aria-label="{{ $copy['homeScrollLabel'] }}">
            <span></span>
        </a>
    </header>

    <main class="landing-page">
        <section id="features" class="landing-section container">
            <div class="landing-section-heading reveal">
                <span class="landing-kicker">{{ $copy['homeFeaturesKicker'] }}</span>
                <h2>{{ $copy['homeFeaturesTitle'] }}</h2>
            </div>
            <div class="row g-4">
                <div class="col-md-4 reveal">
                    <article class="landing-feature-card h-100">
                        <span class="material-symbols-rounded">beach_access</span>
                        <h3>{{ $copy['homeFeatureBeachTitle'] }}</h3>
                        <p>{{ $copy['homeFeatureBeachText'] }}</p>
                    </article>
                </div>
                <div class="col-md-4 reveal">
                    <article class="landing-feature-card h-100">
                        <span class="material-symbols-rounded">apartment</span>
                        <h3>{{ $copy['homeFeatureUrbanTitle'] }}</h3>
                        <p>{{ $copy['homeFeatureUrbanText'] }}</p>
                    </article>
                </div>
                <div class="col-md-4 reveal">
                    <article class="landing-feature-card h-100">
                        <span class="material-symbols-rounded">kayaking</span>
                        <h3>{{ $copy['homeFeatureOutdoorTitle'] }}</h3>
                        <p>{{ $copy['homeFeatureOutdoorText'] }}</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="landing-section container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 reveal">
                    <div class="landing-about-image">
                        <img src="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1200&q=85" alt="{{ $copy['homeAboutImageAlt'] }}">
                    </div>
                </div>
                <div class="col-lg-6 reveal">
                    <span class="landing-kicker">{{ $copy['homeAboutKicker'] }}</span>
                    <h2 class="landing-about-title">{{ $copy['homeAboutTitle'] }}</h2>
                    <p class="landing-copy">{{ str_replace('{siteName}', $copy['siteName'], $copy['homeAboutText']) }}</p>
                    <a href="/about" class="btn btn-au px-4">{{ $copy['homeAboutCta'] }}</a>
                </div>
            </div>
        </section>

        <section class="landing-section container">
            <div class="landing-section-heading reveal">
                <span class="landing-kicker">{{ $copy['homeGalleryKicker'] }}</span>
                <h2>{{ $copy['homeGalleryTitle'] }}</h2>
            </div>
            <div class="landing-gallery">
                <div class="landing-gallery-item tall reveal">
                    <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=900&q=85" alt="{{ $copy['homeGalleryBeachAlt'] }}">
                </div>
                <div class="landing-gallery-item reveal">
                    <img src="https://images.unsplash.com/photo-1519046904884-53103b34b206?auto=format&fit=crop&w=900&q=85" alt="{{ $copy['homeGalleryHolidayAlt'] }}">
                </div>
                <div class="landing-gallery-item reveal">
                    <img src="https://images.unsplash.com/photo-1500375592092-40eb2168fd21?auto=format&fit=crop&w=900&q=85" alt="{{ $copy['homeGalleryWavesAlt'] }}">
                </div>
                <div class="landing-gallery-item wide reveal">
                    <img src="https://images.unsplash.com/photo-1499793983690-e29da59ef1c2?auto=format&fit=crop&w=1200&q=85" alt="{{ $copy['homeGalleryPoolAlt'] }}">
                </div>
            </div>
        </section>
    </main>
@endsection
