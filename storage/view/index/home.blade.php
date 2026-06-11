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
                   
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#homeBannerCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                   
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
                                <span class="cms-feature-icon"><svg class="cms-svg-icon"><use href="#icon-article"></use></svg></span>
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
                            <span class="cms-feature-icon"><svg class="cms-svg-icon"><use href="#icon-beach"></use></svg></span>
                            <h3>{{ $copy['home-featureBeachTitle'] ?? 'home-featureBeachTitle' }}</h3>
                            <p>{{ $copy['home-featureBeachText'] ?? 'home-featureBeachText' }}</p>
                        </article>
                    </div>
                    <div class="col-md-4 reveal">
                        <article class="landing-feature-card h-100">
                            <span class="cms-feature-icon"><svg class="cms-svg-icon"><use href="#icon-building"></use></svg></span>
                            <h3>{{ $copy['home-featureUrbanTitle'] ?? 'home-featureUrbanTitle' }}</h3>
                            <p>{{ $copy['home-featureUrbanText'] ?? 'home-featureUrbanText' }}</p>
                        </article>
                    </div>
                    <div class="col-md-4 reveal">
                        <article class="landing-feature-card h-100">
                            <span class="cms-feature-icon"><svg class="cms-svg-icon"><use href="#icon-kayak"></use></svg></span>
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

        <section class="about-muted-section home-team-section">
            <div class="container">
                <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-end mb-5">
                    <div>
                        <span class="landing-kicker">{{ $copy['about-expertKicker'] ?? 'about-expertKicker' }}</span>
                        <h2>{{ $copy['about-teamTitle'] ?? 'about-teamTitle' }}</h2>
                    </div>
                    <a href="/contact" class="btn btn-au px-4">{{ $copy['about-viewAllMember'] ?? 'View All Member' }}</a>
                </div>
                <div id="homeTeamCarousel" class="carousel slide about-home-carousel" data-bs-ride="false">
                    <div class="carousel-inner">
                    @foreach (($teamSlides ?? []) as $teamGroup)
                        <div class="carousel-item @if ($loop->first) active @endif">
                            <div class="row g-3 justify-content-center">
                                @foreach ($teamGroup as $member)
                                    <div class="col-md-6 col-lg-3">
                                        <div class="about-team-card h-100" @if (! empty($member['avatar'])) style="--team-bg: url('{{ $member['avatar'] }}');" @endif>
                                            <div class="about-team-card-bg" aria-hidden="true"></div>
                                            <div class="about-team-card-shade" aria-hidden="true"></div>
                                            @if (empty($member['avatar']))
                                                <div class="about-team-placeholder" aria-hidden="true">
                                                    <svg class="cms-svg-icon"><use href="#icon-user"></use></svg>
                                                </div>
                                            @endif
                                            <div class="about-team-card-content">
                                                <p class="about-team-contact">{{ $copy['about-call'] ?? 'Call:' }} {{ $member['phone'] ?: ($copy['main-contactPhone'] ?? '(0123) 456 789') }}</p>
                                                <div>
                                                    <h3>{{ $member['name'] }}</h3>
                                                    @if (! empty($member['position']))
                                                        <span>{{ $member['position'] }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                    </div>
                    <button class="about-carousel-control prev" type="button" data-bs-target="#homeTeamCarousel" data-bs-slide="prev" aria-label="Previous">
                        <svg class="cms-svg-icon"><use href="#icon-arrow-left"></use></svg>
                    </button>
                    <button class="about-carousel-control next" type="button" data-bs-target="#homeTeamCarousel" data-bs-slide="next" aria-label="Next">
                        <svg class="cms-svg-icon"><use href="#icon-arrow-right"></use></svg>
                    </button>
                </div>
            </div>
        </section>

        <section class="container about-section home-feedback-section">
            <div class="landing-section-heading reveal">
                <span class="landing-kicker">{{ $copy['about-testimonialsKicker'] ?? 'Testimonials' }}</span>
                <h2>{{ $copy['about-feedbackTitle'] ?? 'What Our Clients Say?' }}</h2>
            </div>
            <div id="homeFeedbackCarousel" class="carousel slide about-home-carousel mt-4" data-bs-ride="false">
                <div class="carousel-inner">
                @foreach (($testimonialSlides ?? []) as $feedbackGroup)
                    <div class="carousel-item @if ($loop->first) active @endif">
                        <div class="row g-3 justify-content-center">
                            @foreach ($feedbackGroup as $feedback)
                                <div class="col-md-6 col-lg-3">
                                    <div class="about-feedback-card h-100">
                                        @if (! empty($feedback['avatar']))
                                            <img class="about-feedback-avatar" src="{{ $feedback['avatar'] }}" alt="{{ $feedback['name'] }}">
                                        @else
                                            <div class="about-feedback-quote"><svg class="cms-svg-icon"><use href="#icon-quote"></use></svg></div>
                                        @endif
                                        <p>{{ $feedback['content'] ?: ($copy['about-feedbackText'] ?? 'Praesent ut lacus a velit tincidunt aliquam a eget urna. Sed ullamcorper tristique nisl at pharetra turpis accumsan et etiam eu sollicitudin eros.') }}</p>
                                        <h3>{{ $feedback['name'] }}</h3>
                                        <span>{{ $feedback['position'] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
                </div>
                <button class="about-carousel-control prev" type="button" data-bs-target="#homeFeedbackCarousel" data-bs-slide="prev" aria-label="Previous">
                    <svg class="cms-svg-icon"><use href="#icon-arrow-left"></use></svg>
                </button>
                <button class="about-carousel-control next" type="button" data-bs-target="#homeFeedbackCarousel" data-bs-slide="next" aria-label="Next">
                    <svg class="cms-svg-icon"><use href="#icon-arrow-right"></use></svg>
                </button>
            </div>
        </section>
    </main>
@endsection
