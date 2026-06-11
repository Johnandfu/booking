@extends('index.layouts.main')

@section('content')
    <main class="luxe-listing-page news-page">
        <section class="luxe-listing-hero">
            <div class="container position-relative">
                <div class="row align-items-end g-5">
                    <div class="col-lg-7 reveal">
                        <span class="landing-kicker">{{ $copy['news-heroKicker'] ?? 'news-heroKicker' }}</span>
                        <h1>{{ $copy['news-heroTitle'] ?? 'news-heroTitle' }}</h1>
                        <p>{{ $copy['news-heroText'] ?? 'news-heroText' }}</p>
                    </div>
                    <div class="col-lg-5 reveal">
                        <form class="luxe-filter-panel" method="get" action="{{ $basePath }}">
                            <label for="news-name">{{ $copy['news-searchName'] ?? 'news-searchName' }}</label>
                            <div class="luxe-filter-row">
                                <input id="news-name" class="form-control border-0 bg-transparent text-white shadow-none" name="name" value="{{ $name ?? '' }}" placeholder="{{ $copy['news-searchPlaceholder'] ?? 'news-searchPlaceholder' }}">
                                <button class="btn btn-light rounded-pill px-4" type="submit">{{ $copy['news-searchSubmit'] ?? 'news-searchSubmit' }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <section class="container luxe-featured-section">
            <div class="luxe-section-title reveal">
                <span class="landing-kicker">{{ $copy['news-featuredKicker'] ?? 'news-featuredKicker' }}</span>
                <h2>{{ $copy['news-featuredTitle'] ?? 'news-featuredTitle' }}</h2>
            </div>
            <div class="news-accordion-strip">
                @forelse ($featuredItems as $article)
                    <article class="news-accordion-panel reveal">
                        <a href="{{ $basePath }}/{{ $article->id }}" class="news-accordion-link">
                            <span class="news-accordion-media">
                                @if ($article->cover)
                                    <img src="{{ $article->cover }}" alt="{{ $article->title }}">
                                @else
                                    <svg class="cms-svg-icon"><use href="#icon-waves"></use></svg>
                                @endif
                            </span>
                            <span class="news-accordion-content">
                                <span class="news-accordion-date">
                                    @if ($article->published_at)
                                        {{ $article->published_at->format('d M Y') }}
                                    @else
                                        {{ $copy['news-listingLocation'] ?? 'news-listingLocation' }}
                                    @endif
                                </span>
                                <strong>{{ $article->title }}</strong>
                                <span class="news-accordion-summary">
                                    @if ($article->summary)
                                        {{ $article->summary }}
                                    @else
                                        {{ $copy['news-defaultSummary'] ?? 'news-defaultSummary' }}
                                    @endif
                                </span>
                                <span class="news-accordion-action">{{ $copy['news-viewDetails'] ?? 'news-viewDetails' }}</span>
                            </span>
                        </a>
                    </article>
                @empty
                    <div>
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
                data-name="{{ $name ?? '' }}"
                data-current-page="{{ $paginator->currentPage() }}"
                data-last-page="{{ $paginator->lastPage() }}"
            >
                @forelse ($listingItems as $article)
                    <article class="luxe-listing-card news-masonry-card reveal">
                        <a href="{{ $basePath }}/{{ $article->id }}" class="luxe-listing-media">
                            @if ($article->cover)
                                <img src="{{ $article->cover }}" alt="{{ $article->title }}">
                            @else
                                <svg class="cms-svg-icon"><use href="#icon-beach"></use></svg>
                            @endif
                            <span class="luxe-view">{{ $copy['news-viewDetails'] ?? 'news-viewDetails' }}</span>
                        </a>
                        <div class="luxe-listing-copy">
                            <span class="luxe-location">{{ $copy['news-listingLocation'] ?? 'news-listingLocation' }}</span>
                            <h3><a href="{{ $basePath }}/{{ $article->id }}" class="text-decoration-none stretched-link">{{ $article->title }}</a></h3>
                            <p>
                                @if ($article->summary)
                                    {{ $article->summary }}
                                @else
                                    {{ $copy['news-defaultSummary'] ?? 'news-defaultSummary' }}
                                @endif
                            </p>
                            <div class="luxe-price">
                                @if ($article->published_at)
                                    {{ $article->published_at->format('d M Y') }}
                                @else
                                    {{ $copy['news-heroKicker'] ?? 'news-heroKicker' }}
                                @endif
                            </div>
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

        @if ($paginator->lastPage() > 1)
            <nav class="container news-pagination pb-5" aria-label="{{ $copy['main-paginationLabel'] ?? 'main-paginationLabel' }}">
                <ul class="pagination justify-content-center">
                    @foreach ($pageNumbers as $pageNumber)
                        @php
                            $query = array_filter([
                                'name' => $name ?? '',
                                'page' => $pageNumber > 1 ? $pageNumber : null,
                            ], static fn ($value) => $value !== null && $value !== '');
                        @endphp
                        <li class="page-item @if ($pageNumber === $paginator->currentPage()) active @endif">
                            <a class="page-link" href="{{ $basePath }}@if ($query !== [])?{{ http_build_query($query) }}@endif">{{ $pageNumber }}</a>
                        </li>
                    @endforeach
                </ul>
            </nav>
        @endif
    </main>
@endsection
