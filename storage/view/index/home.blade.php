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
        <section id="features" class="landing-section landing-feature-section">
            <div class="landing-section-heading reveal container">
                <span class="landing-kicker">{{ $copy['home-featuresKicker'] ?? 'home-featuresKicker' }}</span>
                <h2>{{ $copy['home-featuresTitle'] ?? 'home-featuresTitle' }}</h2>
            </div>
            @if (! empty($featureSlides))
                <div id="homeFeatureCarousel" class="carousel slide about-home-carousel landing-feature-carousel d-none d-md-block" data-bs-ride="false">
                    <div class="carousel-inner">
                        @foreach ($featureSlides as $featureGroup)
                            <div class="carousel-item @if ($loop->first) active @endif">
                                <div class="landing-feature-accordion">
                                    @foreach ($featureGroup as $featureItem)
                                        <div class="landing-feature-item reveal">
                                            <article class="landing-feature-card h-100" @if ($featureItem['article']->cover) style="--feature-bg: url('{{ $featureItem['article']->cover }}');" @endif>
                                                <a href="{{ $featureItem['url'] }}" class="landing-feature-card-link" aria-label="{{ $featureItem['article']->title }}">
                                                    <div class="landing-feature-bg" aria-hidden="true">
                                                        @if (! $featureItem['article']->cover)
                                                            <span class="cms-feature-icon"><svg class="cms-svg-icon"><use href="#icon-article"></use></svg></span>
                                                        @endif
                                                    </div>
                                                    <div class="landing-feature-body">
                                                        <small>{{ $featureItem['category']->name }}</small>
                                                        <h3>{{ $featureItem['article']->title }}</h3>
                                                        <p>
                                                            @if ($featureItem['article']->summary)
                                                                {{ $featureItem['article']->summary }}
                                                            @else
                                                                {{ $featureItem['category']->description ?? $featureItem['category']->name }}
                                                            @endif
                                                        </p>
                                                    </div>
                                                </a>
                                            </article>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if (count($featureSlides) > 1)
                        <button class="about-carousel-control prev" type="button" data-bs-target="#homeFeatureCarousel" data-bs-slide="prev" aria-label="Previous">
                            <svg class="cms-svg-icon"><use href="#icon-arrow-left"></use></svg>
                        </button>
                        <button class="about-carousel-control next" type="button" data-bs-target="#homeFeatureCarousel" data-bs-slide="next" aria-label="Next">
                            <svg class="cms-svg-icon"><use href="#icon-arrow-right"></use></svg>
                        </button>
                    @endif
                </div>
            @endif
            @if (! empty($featureMobileSlides))
                <div id="homeFeatureMobileCarousel" class="carousel slide about-home-carousel landing-feature-carousel d-md-none" data-bs-ride="false">
                    <div class="carousel-inner">
                        @foreach ($featureMobileSlides as $featureGroup)
                            <div class="carousel-item @if ($loop->first) active @endif">
                                <div class="landing-feature-accordion">
                                    @foreach ($featureGroup as $featureItem)
                                        <div class="landing-feature-item reveal">
                                            <article class="landing-feature-card h-100" @if ($featureItem['article']->cover) style="--feature-bg: url('{{ $featureItem['article']->cover }}');" @endif>
                                                <a href="{{ $featureItem['url'] }}" class="landing-feature-card-link" aria-label="{{ $featureItem['article']->title }}">
                                                    <div class="landing-feature-bg" aria-hidden="true">
                                                        @if (! $featureItem['article']->cover)
                                                            <span class="cms-feature-icon"><svg class="cms-svg-icon"><use href="#icon-article"></use></svg></span>
                                                        @endif
                                                    </div>
                                                    <div class="landing-feature-body">
                                                        <small>{{ $featureItem['category']->name }}</small>
                                                        <h3>{{ $featureItem['article']->title }}</h3>
                                                        <p>
                                                            @if ($featureItem['article']->summary)
                                                                {{ $featureItem['article']->summary }}
                                                            @else
                                                                {{ $featureItem['category']->description ?? $featureItem['category']->name }}
                                                            @endif
                                                        </p>
                                                    </div>
                                                </a>
                                            </article>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button class="about-carousel-control prev" type="button" data-bs-target="#homeFeatureMobileCarousel" data-bs-slide="prev" aria-label="Previous">
                        <svg class="cms-svg-icon"><use href="#icon-arrow-left"></use></svg>
                    </button>
                    <button class="about-carousel-control next" type="button" data-bs-target="#homeFeatureMobileCarousel" data-bs-slide="next" aria-label="Next">
                        <svg class="cms-svg-icon"><use href="#icon-arrow-right"></use></svg>
                    </button>
                </div>
            @endif
        </section>

        <section class="landing-section container home-gallery-section">
            <div class="landing-section-heading reveal">
                <span class="landing-kicker">{{ $copy['home-galleryKicker'] ?? 'home-galleryKicker' }}</span>
                <h2>{{ $copy['home-galleryTitle'] ?? 'home-galleryTitle' }}</h2>
            </div>
            <div class="landing-gallery d-none d-md-grid">
                @if (! empty($galleryArticles))
                    @foreach ($galleryArticles as $galleryItem)
                        <a href="{{ $galleryItem['url'] }}" class="landing-gallery-item @if ($loop->first) tall @elseif ($loop->iteration === 4) wide @endif reveal" @if ($galleryItem['article']->cover) style="--gallery-bg: url('{{ $galleryItem['article']->cover }}');" @endif>
                            <span class="landing-gallery-bg" aria-hidden="true"></span>
                            <span class="landing-gallery-content">
                                <small>{{ $galleryItem['category']->name }}</small>
                                <strong>{{ $galleryItem['article']->title }}</strong>
                                <span>
                                    @if ($galleryItem['article']->summary)
                                        {{ $galleryItem['article']->summary }}
                                    @else
                                        {{ $galleryItem['category']->description ?? $galleryItem['category']->name }}
                                    @endif
                                </span>
                            </span>
                        </a>
                    @endforeach
               
                @endif
            </div>
            @if (! empty($galleryMobileSlides))
                <div id="homeGalleryMobileCarousel" class="carousel slide about-home-carousel landing-gallery-mobile-carousel d-md-none" data-bs-ride="false">
                    <div class="carousel-inner">
                    @foreach ($galleryMobileSlides as $galleryGroup)
                        <div class="carousel-item @if ($loop->first) active @endif">
                            @foreach ($galleryGroup as $galleryItem)
                                <a href="{{ $galleryItem['url'] }}" class="landing-gallery-item reveal" @if ($galleryItem['article']->cover) style="--gallery-bg: url('{{ $galleryItem['article']->cover }}');" @endif>
                                    <span class="landing-gallery-bg" aria-hidden="true"></span>
                                    <span class="landing-gallery-content">
                                        <small>{{ $galleryItem['category']->name }}</small>
                                        <strong>{{ $galleryItem['article']->title }}</strong>
                                        <span>
                                            @if ($galleryItem['article']->summary)
                                                {{ $galleryItem['article']->summary }}
                                            @else
                                                {{ $galleryItem['category']->description ?? $galleryItem['category']->name }}
                                            @endif
                                        </span>
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    @endforeach
                    </div>
                    <button class="about-carousel-control prev" type="button" data-bs-target="#homeGalleryMobileCarousel" data-bs-slide="prev" aria-label="Previous">
                        <svg class="cms-svg-icon"><use href="#icon-arrow-left"></use></svg>
                    </button>
                    <button class="about-carousel-control next" type="button" data-bs-target="#homeGalleryMobileCarousel" data-bs-slide="next" aria-label="Next">
                        <svg class="cms-svg-icon"><use href="#icon-arrow-right"></use></svg>
                    </button>
                </div>
            @endif
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
                <div id="homeTeamCarousel" class="carousel slide about-home-carousel d-none d-md-block" data-bs-ride="false">
                    <div class="carousel-inner">
                    @foreach (($teamSlides ?? []) as $teamGroup)
                        <div class="carousel-item @if ($loop->first) active @endif">
                            <div class="row g-3 justify-content-center">
                                @foreach ($teamGroup as $member)
                                    <div class="col-md-3">
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
                <div id="homeTeamMobileCarousel" class="carousel slide about-home-carousel d-md-none" data-bs-ride="false">
                    <div class="carousel-inner">
                    @foreach (($teamMobileSlides ?? []) as $teamGroup)
                        <div class="carousel-item @if ($loop->first) active @endif">
                            <div class="row g-3 justify-content-center">
                                @foreach ($teamGroup as $member)
                                    <div class="col-12">
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
                    <button class="about-carousel-control prev" type="button" data-bs-target="#homeTeamMobileCarousel" data-bs-slide="prev" aria-label="Previous">
                        <svg class="cms-svg-icon"><use href="#icon-arrow-left"></use></svg>
                    </button>
                    <button class="about-carousel-control next" type="button" data-bs-target="#homeTeamMobileCarousel" data-bs-slide="next" aria-label="Next">
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
            <div id="homeFeedbackCarousel" class="carousel slide about-home-carousel mt-4 d-none d-md-block" data-bs-ride="false">
                <div class="carousel-inner">
                @foreach (($testimonialSlides ?? []) as $feedbackGroup)
                    <div class="carousel-item @if ($loop->first) active @endif">
                        <div class="row g-3 justify-content-center">
                            @foreach ($feedbackGroup as $feedback)
                                <div class="col-md-3">
                                    <div class="about-feedback-card h-100">
                                        <div class="about-feedback-user">
                                            @if (! empty($feedback['avatar']))
                                                <img class="about-feedback-avatar" src="{{ $feedback['avatar'] }}" alt="{{ $feedback['name'] }}">
                                            @else
                                                <div class="about-feedback-avatar about-feedback-avatar-placeholder" aria-hidden="true">
                                                    <svg class="cms-svg-icon"><use href="#icon-user"></use></svg>
                                                </div>
                                            @endif
                                            <div class="about-feedback-user-info">
                                                <h3>{{ $feedback['name'] }}</h3>
                                                @if (! empty($feedback['position']))
                                                    <span>{{ $feedback['position'] }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <p class="about-feedback-content">{{ $feedback['content'] ?: ($copy['about-feedbackText'] ?? 'Praesent ut lacus a velit tincidunt aliquam a eget urna. Sed ullamcorper tristique nisl at pharetra turpis accumsan et etiam eu sollicitudin eros.') }}</p>
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
            <div id="homeFeedbackMobileCarousel" class="carousel slide about-home-carousel mt-4 d-md-none" data-bs-ride="false">
                <div class="carousel-inner">
                @foreach (($testimonialMobileSlides ?? []) as $feedbackGroup)
                    <div class="carousel-item @if ($loop->first) active @endif">
                        <div class="row g-3 justify-content-center">
                            @foreach ($feedbackGroup as $feedback)
                                <div class="col-12">
                                    <div class="about-feedback-card h-100">
                                        <div class="about-feedback-user">
                                            @if (! empty($feedback['avatar']))
                                                <img class="about-feedback-avatar" src="{{ $feedback['avatar'] }}" alt="{{ $feedback['name'] }}">
                                            @else
                                                <div class="about-feedback-avatar about-feedback-avatar-placeholder" aria-hidden="true">
                                                    <svg class="cms-svg-icon"><use href="#icon-user"></use></svg>
                                                </div>
                                            @endif
                                            <div class="about-feedback-user-info">
                                                <h3>{{ $feedback['name'] }}</h3>
                                                @if (! empty($feedback['position']))
                                                    <span>{{ $feedback['position'] }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <p class="about-feedback-content">{{ $feedback['content'] ?: ($copy['about-feedbackText'] ?? 'Praesent ut lacus a velit tincidunt aliquam a eget urna. Sed ullamcorper tristique nisl at pharetra turpis accumsan et etiam eu sollicitudin eros.') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
                </div>
                <button class="about-carousel-control prev" type="button" data-bs-target="#homeFeedbackMobileCarousel" data-bs-slide="prev" aria-label="Previous">
                    <svg class="cms-svg-icon"><use href="#icon-arrow-left"></use></svg>
                </button>
                <button class="about-carousel-control next" type="button" data-bs-target="#homeFeedbackMobileCarousel" data-bs-slide="next" aria-label="Next">
                    <svg class="cms-svg-icon"><use href="#icon-arrow-right"></use></svg>
                </button>
            </div>
        </section>
    </main>
@endsection
