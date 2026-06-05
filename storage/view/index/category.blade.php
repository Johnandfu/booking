@extends('index.layouts.main')

@section('content')
    @if (in_array($category->slug, ['service', 'services'], true))
        <main class="luxe-listing-page">
            <section class="luxe-listing-hero luxe-service-hero">
                <div class="container position-relative">
                    <div class="row align-items-end g-5">
                        <div class="col-lg-7 reveal">
                            <span class="landing-kicker">{{ $categoryCopy['heroKicker'] }}</span>
                            <h1>{{ $categoryCopy['heroTitle'] }}</h1>
                            <p>{{ $categoryCopy['heroText'] }}</p>
                        </div>
                        <div class="col-lg-5 reveal">
                            <div class="luxe-filter-panel">
                                <label>{{ $categoryCopy['filterLocationLabel'] }}</label>
                                <div class="luxe-filter-row">
                                    <span>{{ $categoryCopy['listingLocation'] }}</span>
                                    <span class="material-symbols-rounded">expand_more</span>
                                </div>
                                <label>{{ $categoryCopy['filterBudgetLabel'] }}</label>
                                <div class="luxe-range"><span style="width: 76%;"></span></div>
                                <div class="luxe-tags">
                                    <span>{{ $categoryCopy['listingBeachfront'] }}</span>
                                    <span>{{ $categoryCopy['listingRiverView'] }}</span>
                                    <span>{{ $categoryCopy['listingCityCoastal'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="container luxe-featured-section">
                <div class="luxe-section-title reveal">
                    <span class="landing-kicker">{{ $categoryCopy['featuredKicker'] }}</span>
                    <h2>{{ $categoryCopy['featuredTitle'] }}</h2>
                </div>
                <div class="row g-4">
                    @forelse ($featuredItems as $article)
                        <div class="col-lg-6 reveal">
                            <article class="luxe-feature-card">
                                <a href="{{ $basePath }}/{{ $article->slug }}" class="luxe-feature-media">
                                    @if ($article->cover)
                                        <img src="{{ $article->cover }}" alt="{{ $article->title }}">
                                    @else
                                        <span class="material-symbols-rounded">villa</span>
                                    @endif
                                    <div class="luxe-feature-overlay">
                                        <span>{{ $categoryCopy['viewDetails'] }}</span>
                                    </div>
                                </a>
                                <div class="luxe-feature-copy">
                                    <span>{{ $categoryCopy['cardEyebrow'] }}</span>
                                    <h2><a href="{{ $basePath }}/{{ $article->slug }}" class="text-decoration-none">{{ $article->title }}</a></h2>
                                    <p>
                                        @if ($article->summary)
                                            {{ $article->summary }}
                                        @else
                                            {{ $categoryCopy['defaultSummary'] }}
                                        @endif
                                    </p>
                                </div>
                            </article>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-light border mb-0">{{ $categoryCopy['empty'] }}</div>
                        </div>
                    @endforelse
                </div>
            </section>

            <section class="container luxe-grid-section">
                <div class="luxe-section-title reveal">
                    <span class="landing-kicker">{{ $categoryCopy['gridKicker'] }}</span>
                    <h2>{{ $categoryCopy['gridTitle'] }}</h2>
                </div>
                <div class="luxe-listing-grid">
                    @forelse ($listingItems as $article)
                        <article class="luxe-listing-card reveal">
                            <a href="{{ $basePath }}/{{ $article->slug }}" class="luxe-listing-media">
                                @if ($article->cover)
                                    <img src="{{ $article->cover }}" alt="{{ $article->title }}">
                                @else
                                    <span class="material-symbols-rounded">home_work</span>
                                @endif
                                <span class="luxe-view">{{ $categoryCopy['viewDetails'] }}</span>
                            </a>
                            <div class="luxe-listing-copy">
                                <span class="luxe-location">{{ $categoryCopy['listingLocation'] }}</span>
                                <h3><a href="{{ $basePath }}/{{ $article->slug }}" class="text-decoration-none stretched-link">{{ $article->title }}</a></h3>
                                <div class="luxe-price">{{ $categoryCopy['cardEyebrow'] }}</div>
                            </div>
                        </article>
                    @empty
                        @if (empty($featuredItems))
                            <div class="alert alert-light border mb-0">{{ $categoryCopy['empty'] }}</div>
                        @endif
                    @endforelse
                </div>
            </section>

            <section class="container luxe-category-section reveal">
                <div class="row g-3">
                    <div class="col-md-4"><div>{{ $categoryCopy['listingBeachfront'] }}</div></div>
                    <div class="col-md-4"><div>{{ $categoryCopy['listingRiverView'] }}</div></div>
                    <div class="col-md-4"><div>{{ $categoryCopy['listingCityCoastal'] }}</div></div>
                </div>
            </section>

            @if ($paginator->lastPage() > 1)
                <nav class="container pb-5" aria-label="{{ $copy['main-paginationLabel'] ?? 'main-paginationLabel' }}">
                    <ul class="pagination justify-content-center">
                        @foreach ($pageNumbers as $pageNumber)
                            <li class="page-item @if ($pageNumber === $paginator->currentPage()) active @endif">
                                <a class="page-link" href="{{ $basePath }}@if ($pageNumber > 1)?page={{ $pageNumber }}@endif">{{ $pageNumber }}</a>
                            </li>
                        @endforeach
                    </ul>
                </nav>
            @endif
        </main>
    @else
        <main class="page-shell container">
            <div class="page-hero reveal">
                <span class="landing-kicker">{{ $categoryCopy['kicker'] }}</span>
                <h1>{{ $categoryCopy['title'] }}</h1>
                <p>{{ $categoryCopy['description'] }}</p>
            </div>
            <div class="vstack gap-3 mb-4">
                @forelse ($paginator->items() as $article)
                    <div class="lux-card reveal">
                        <div class="card-body p-4">
                            <h2 class="h5 fw-bold mb-2"><a href="{{ $basePath }}/{{ $article->slug }}" class="text-decoration-none">{{ $article->title }}</a></h2>
                            @if ($article->summary)
                                <p class="text-secondary mb-2">{{ $article->summary }}</p>
                            @endif
                            <small class="text-secondary">{{ $article->published_at?->format('d M Y') }}</small>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-light border">{{ $categoryCopy['empty'] }}</div>
                @endforelse
            </div>

            @if ($paginator->lastPage() > 1)
                <nav aria-label="{{ $copy['main-paginationLabel'] ?? 'main-paginationLabel' }}">
                    <ul class="pagination justify-content-center">
                        @foreach ($pageNumbers as $pageNumber)
                            <li class="page-item @if ($pageNumber === $paginator->currentPage()) active @endif">
                                <a class="page-link" href="{{ $basePath }}@if ($pageNumber > 1)?page={{ $pageNumber }}@endif">{{ $pageNumber }}</a>
                            </li>
                        @endforeach
                    </ul>
                </nav>
            @endif
        </main>
    @endif
@endsection
