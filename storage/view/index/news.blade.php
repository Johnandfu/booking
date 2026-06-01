@extends('index.layouts.main')

@section('content')
    @php
        $items = $paginator->items();
        $featuredItems = array_slice($items, 0, 2);
        $listingItems = $paginator->currentPage() > 1 ? $items : array_slice($items, 2);
    @endphp

    <main class="luxe-listing-page">
        <section class="luxe-listing-hero">
            <div class="container position-relative">
                <div class="row align-items-end g-5">
                    <div class="col-lg-7 reveal">
                        <span class="landing-kicker">{{ $copy['news-heroKicker'] ?? 'news-heroKicker' }}</span>
                        <h1>{{ $copy['news-heroTitle'] ?? 'news-heroTitle' }}</h1>
                        <p>{{ $copy['news-heroText'] ?? 'news-heroText' }}</p>
                    </div>
                    <div class="col-lg-5 reveal">
                        <div class="luxe-filter-panel">
                            <label>{{ $copy['news-filterLocationLabel'] ?? 'news-filterLocationLabel' }}</label>
                            <div class="luxe-filter-row">
                                <span>{{ $copy['news-listingLocation'] ?? 'news-listingLocation' }}</span>
                                <span class="material-symbols-rounded">expand_more</span>
                            </div>
                            <label>{{ $copy['news-filterBudgetLabel'] ?? 'news-filterBudgetLabel' }}</label>
                            <div class="luxe-range"><span style="width: 68%;"></span></div>
                            <div class="luxe-tags">
                                <span>{{ $copy['news-tagBeachfront'] ?? 'news-tagBeachfront' }}</span>
                                <span>{{ $copy['news-tagRiverView'] ?? 'news-tagRiverView' }}</span>
                                <span>{{ $copy['news-tagCityCoastal'] ?? 'news-tagCityCoastal' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="container luxe-featured-section">
            <div class="luxe-section-title reveal">
                <span class="landing-kicker">{{ $copy['news-featuredKicker'] ?? 'news-featuredKicker' }}</span>
                <h2>{{ $copy['news-featuredTitle'] ?? 'news-featuredTitle' }}</h2>
            </div>
            <div class="row g-4">
                @forelse ($featuredItems as $article)
                    <div class="col-lg-6 reveal">
                        <article class="luxe-feature-card">
                            <a href="/article/{{ $article->slug }}" class="luxe-feature-media">
                                @if ($article->cover)
                                    <img src="{{ $article->cover }}" alt="{{ $article->title }}">
                                @else
                                    <span class="material-symbols-rounded">waves</span>
                                @endif
                                <div class="luxe-feature-overlay">
                                    <span>{{ $copy['news-viewDetails'] ?? 'news-viewDetails' }}</span>
                                </div>
                            </a>
                            <div class="luxe-feature-copy">
                                <span>{{ $article->published_at?->format('d M Y') ?: ($copy['news-listingLocation'] ?? 'news-listingLocation') }}</span>
                                <h2><a href="/article/{{ $article->slug }}" class="text-decoration-none">{{ $article->title }}</a></h2>
                                <p>{{ $article->summary ?: ($copy['news-defaultSummary'] ?? 'news-defaultSummary') }}</p>
                            </div>
                        </article>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-light border mb-0">{{ $copy['news-empty'] ?? 'news-empty' }}</div>
                    </div>
                @endforelse
            </div>
        </section>

        <section class="container luxe-grid-section">
            <div class="luxe-section-title reveal">
                <span class="landing-kicker">{{ $copy['news-gridKicker'] ?? 'news-gridKicker' }}</span>
                <h2>{{ $copy['news-gridTitle'] ?? 'news-gridTitle' }}</h2>
            </div>
            <div
                class="luxe-listing-grid"
                data-news-masonry
                data-base-path="{{ $basePath }}"
                data-current-page="{{ $paginator->currentPage() }}"
                data-last-page="{{ $paginator->lastPage() }}"
            >
                @forelse ($listingItems as $article)
                    <article class="luxe-listing-card news-masonry-card reveal">
                        <a href="/article/{{ $article->slug }}" class="luxe-listing-media">
                            @if ($article->cover)
                                <img src="{{ $article->cover }}" alt="{{ $article->title }}">
                            @else
                                <span class="material-symbols-rounded">beach_access</span>
                            @endif
                            <span class="luxe-view">{{ $copy['news-viewDetails'] ?? 'news-viewDetails' }}</span>
                        </a>
                        <div class="luxe-listing-copy">
                            <span class="luxe-location">{{ $copy['news-listingLocation'] ?? 'news-listingLocation' }}</span>
                            <h3><a href="/article/{{ $article->slug }}" class="text-decoration-none stretched-link">{{ $article->title }}</a></h3>
                            <div class="luxe-price">{{ $article->published_at?->format('d M Y') ?: ($copy['news-heroKicker'] ?? 'news-heroKicker') }}</div>
                        </div>
                    </article>
                @empty
                    @if (empty($featuredItems))
                        <div class="alert alert-light border mb-0">{{ $copy['news-empty'] ?? 'news-empty' }}</div>
                    @endif
                @endforelse
            </div>
        </section>

        <section class="container luxe-category-section reveal">
            <div class="row g-3">
                <div class="col-md-4"><div>{{ $copy['news-listingBeachfront'] ?? 'news-listingBeachfront' }}</div></div>
                <div class="col-md-4"><div>{{ $copy['news-listingRiverView'] ?? 'news-listingRiverView' }}</div></div>
                <div class="col-md-4"><div>{{ $copy['news-listingCityCoastal'] ?? 'news-listingCityCoastal' }}</div></div>
            </div>
        </section>

        @if ($paginator->currentPage() < $paginator->lastPage())
            <div class="container text-center pb-5">
                <button class="btn btn-au btn-lg px-5 news-load-more" type="button" data-news-load-more>
                    {{ $copy['news-loadMore'] ?? 'news-loadMore' }}
                </button>
            </div>
        @endif
    </main>
@endsection
