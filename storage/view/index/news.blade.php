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
                        <span class="landing-kicker">{{ $copy['newsHeroKicker'] }}</span>
                        <h1>{{ $copy['newsHeroTitle'] }}</h1>
                        <p>{{ $copy['newsHeroText'] }}</p>
                    </div>
                    <div class="col-lg-5 reveal">
                        <div class="luxe-filter-panel">
                            <label>{{ $copy['newsFilterLocationLabel'] }}</label>
                            <div class="luxe-filter-row">
                                <span>{{ $copy['listingLocation'] }}</span>
                                <span class="material-symbols-rounded">expand_more</span>
                            </div>
                            <label>{{ $copy['newsFilterBudgetLabel'] }}</label>
                            <div class="luxe-range"><span style="width: 68%;"></span></div>
                            <div class="luxe-tags">
                                <span>{{ $copy['newsTagBeachfront'] }}</span>
                                <span>{{ $copy['newsTagRiverView'] }}</span>
                                <span>{{ $copy['newsTagCityCoastal'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="container luxe-featured-section">
            <div class="luxe-section-title reveal">
                <span class="landing-kicker">{{ $copy['newsFeaturedKicker'] }}</span>
                <h2>{{ $copy['newsFeaturedTitle'] }}</h2>
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
                                    <span>{{ $copy['viewDetails'] }}</span>
                                </div>
                            </a>
                            <div class="luxe-feature-copy">
                                <span>{{ $article->published_at?->format('d M Y') ?: $copy['listingLocation'] }}</span>
                                <h2><a href="/article/{{ $article->slug }}" class="text-decoration-none">{{ $article->title }}</a></h2>
                                <p>{{ $article->summary ?: $copy['newsDefaultSummary'] }}</p>
                            </div>
                        </article>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-light border mb-0">{{ $copy['newsEmpty'] }}</div>
                    </div>
                @endforelse
            </div>
        </section>

        <section class="container luxe-grid-section">
            <div class="luxe-section-title reveal">
                <span class="landing-kicker">{{ $copy['newsGridKicker'] }}</span>
                <h2>{{ $copy['newsGridTitle'] }}</h2>
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
                            <span class="luxe-view">{{ $copy['viewDetails'] }}</span>
                        </a>
                        <div class="luxe-listing-copy">
                            <span class="luxe-location">{{ $copy['listingLocation'] }}</span>
                            <h3><a href="/article/{{ $article->slug }}" class="text-decoration-none stretched-link">{{ $article->title }}</a></h3>
                            <div class="luxe-price">{{ $article->published_at?->format('d M Y') ?: $copy['newsHeroKicker'] }}</div>
                        </div>
                    </article>
                @empty
                    @if (empty($featuredItems))
                        <div class="alert alert-light border mb-0">{{ $copy['newsEmpty'] }}</div>
                    @endif
                @endforelse
            </div>
        </section>

        <section class="container luxe-category-section reveal">
            <div class="row g-3">
                <div class="col-md-4"><div>{{ $copy['listingBeachfront'] }}</div></div>
                <div class="col-md-4"><div>{{ $copy['listingRiverView'] }}</div></div>
                <div class="col-md-4"><div>{{ $copy['listingCityCoastal'] }}</div></div>
            </div>
        </section>

        @if ($paginator->currentPage() < $paginator->lastPage())
            <div class="container text-center pb-5">
                <button class="btn btn-au btn-lg px-5 news-load-more" type="button" data-news-load-more>
                    {{ $copy['newsLoadMore'] }}
                </button>
            </div>
        @endif
    </main>
@endsection
