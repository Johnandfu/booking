@extends('index.layouts.main')

@section('content')
    @if (! empty($banners) && $banners->isNotEmpty())
        <header id="homeBannerCarousel" class="carousel slide landing-hero-carousel" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach ($banners as $banner)
                    <div class="carousel-item @if ($loop->first) active @endif">
                        <div class="landing-hero-slide" style="background-image: url('{{ $banner->image }}');">
                            <div class="container position-relative">
                                <div class="landing-hero-content reveal">
                                    <span class="landing-kicker">{{ $copy['home-heroKicker'] ?? 'home-heroKicker' }}</span>
                                    <h1>{{ $banner->title }}</h1>
                                    @if ($banner->description)
                                        <p>{{ $banner->description }}</p>
                                    @endif
                                    @if ($banner->link)
                                        <a href="{{ $banner->link }}" class="btn btn-light btn-lg rounded-pill px-5">{{ $copy['home-heroCta'] ?? 'home-heroCta' }}</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @if ($banners->count() > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#homeBannerCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#homeBannerCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            @endif
            <a href="#features" class="scroll-indicator" aria-label="{{ $copy['home-scrollLabel'] ?? 'home-scrollLabel' }}">
                <span></span>
            </a>
        </header>
    @else
        <header class="landing-hero">
            <div class="container position-relative">
                <div class="landing-hero-content reveal">
                    <span class="landing-kicker">{{ $copy['home-heroKicker'] ?? 'home-heroKicker' }}</span>
                    <h1>{{ $copy['home-heroTitle'] ?? 'home-heroTitle' }}</h1>
                    <p>{{ $copy['home-heroSubtitle'] ?? 'home-heroSubtitle' }}</p>
                    <a href="/services" class="btn btn-light btn-lg rounded-pill px-5">{{ $copy['home-heroCta'] ?? 'home-heroCta' }}</a>
                </div>
            </div>
            <a href="#features" class="scroll-indicator" aria-label="{{ $copy['home-scrollLabel'] ?? 'home-scrollLabel' }}">
                <span></span>
            </a>
        </header>
    @endif

    <main class="landing-page">
        <section id="features" class="landing-section container">
            <div class="landing-section-heading reveal">
                <span class="landing-kicker">{{ $copy['home-featuresKicker'] ?? 'home-featuresKicker' }}</span>
                <h2>{{ $copy['home-featuresTitle'] ?? 'home-featuresTitle' }}</h2>
            </div>
            <div class="row g-4">
                @if (! empty($featureArticles))
                    @foreach ($featureArticles as $featureItem)
                        <div class="col-lg-3 col-md-6 reveal">
                            <article class="landing-feature-card h-100">
                                <span class="material-symbols-rounded">article</span>
                                <small class="d-inline-flex mb-3 text-secondary">{{ $featureItem['category']->name }}</small>
                                <h3><a href="{{ $featureItem['url'] }}" class="text-decoration-none">{{ $featureItem['article']->title }}</a></h3>
                                <p>
                                    @if ($featureItem['article']->summary)
                                        {{ $featureItem['article']->summary }}
                                    @else
                                        {{ $featureItem['category']->description ?? $featureItem['category']->name }}
                                    @endif
                                </p>
                            </article>
                        </div>
                    @endforeach
                @else
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
                @endif
            </div>
        </section>

        <!-- <section class="landing-section container">
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
        </section> -->

        <section class="landing-section container">
            <div class="landing-section-heading reveal">
                <span class="landing-kicker">{{ $copy['home-galleryKicker'] ?? 'home-galleryKicker' }}</span>
                <h2>{{ $copy['home-galleryTitle'] ?? 'home-galleryTitle' }}</h2>
            </div>
            <div class="landing-gallery">
                @if (! empty($galleryArticles))
                    @foreach ($galleryArticles as $galleryItem)
                        <a href="{{ $galleryItem['url'] }}" class="landing-gallery-item @if ($loop->first) tall @elseif ($loop->iteration === 4) wide @endif reveal">
                            <img src="{{ $galleryItem['article']->cover }}" alt="{{ $galleryItem['article']->title }}">
                        </a>
                    @endforeach
               
                @endif
            </div>
        </section>
    </main>
@endsection
