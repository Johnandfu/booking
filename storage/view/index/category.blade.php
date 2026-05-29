@extends('index.layouts.main')

@section('content')
    @if ($category->slug === 'service')
        @php
            $items = $paginator->items();
            $featuredItems = array_slice($items, 0, 2);
            $listingItems = array_slice($items, 2);
        @endphp

        <main class="luxe-listing-page">
            <section class="luxe-listing-hero luxe-service-hero">
                <div class="container position-relative">
                    <div class="row align-items-end g-5">
                        <div class="col-lg-7 reveal">
                            <span class="landing-kicker">{{ $copy['serviceHeroKicker'] }}</span>
                            <h1>{{ $copy['serviceHeroTitle'] }}</h1>
                            <p>{{ $copy['serviceHeroText'] }}</p>
                        </div>
                        <div class="col-lg-5 reveal">
                            <div class="luxe-filter-panel">
                                <label>{{ $copy['newsFilterLocationLabel'] }}</label>
                                <div class="luxe-filter-row">
                                    <span>{{ $copy['listingLocation'] }}</span>
                                    <span class="material-symbols-rounded">expand_more</span>
                                </div>
                                <label>{{ $copy['serviceFilterBudgetLabel'] }}</label>
                                <div class="luxe-range"><span style="width: 76%;"></span></div>
                                <div class="luxe-tags">
                                    <span>{{ $copy['listingBeachfront'] }}</span>
                                    <span>{{ $copy['listingRiverView'] }}</span>
                                    <span>{{ $copy['listingCityCoastal'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="container luxe-featured-section">
                <div class="luxe-section-title reveal">
                    <span class="landing-kicker">{{ $copy['serviceFeaturedKicker'] }}</span>
                    <h2>{{ $copy['serviceFeaturedTitle'] }}</h2>
                </div>
                <div class="row g-4">
                    @forelse ($featuredItems as $article)
                        <div class="col-lg-6 reveal">
                            <article class="luxe-feature-card">
                                <a href="/article/{{ $article->slug }}" class="luxe-feature-media">
                                    @if ($article->cover)
                                        <img src="{{ $article->cover }}" alt="{{ $article->title }}">
                                    @else
                                        <span class="material-symbols-rounded">villa</span>
                                    @endif
                                    <div class="luxe-feature-overlay">
                                        <span>{{ $copy['viewDetails'] }}</span>
                                    </div>
                                </a>
                                <div class="luxe-feature-copy">
                                    <span>{{ $copy['serviceCardEyebrow'] }}</span>
                                    <h2><a href="/article/{{ $article->slug }}" class="text-decoration-none">{{ $article->title }}</a></h2>
                                    <p>{{ $article->summary ?: $copy['serviceDefaultSummary'] }}</p>
                                </div>
                            </article>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-light border mb-0">{{ $copy['categoryEmpty'] }}</div>
                        </div>
                    @endforelse
                </div>
            </section>

            <section class="container luxe-grid-section">
                <div class="luxe-section-title reveal">
                    <span class="landing-kicker">{{ $copy['serviceGridKicker'] }}</span>
                    <h2>{{ $copy['serviceGridTitle'] }}</h2>
                </div>
                <div class="luxe-listing-grid">
                    @forelse ($listingItems as $article)
                        <article class="luxe-listing-card reveal">
                            <a href="/article/{{ $article->slug }}" class="luxe-listing-media">
                                @if ($article->cover)
                                    <img src="{{ $article->cover }}" alt="{{ $article->title }}">
                                @else
                                    <span class="material-symbols-rounded">home_work</span>
                                @endif
                                <span class="luxe-view">{{ $copy['viewDetails'] }}</span>
                            </a>
                            <div class="luxe-listing-copy">
                                <span class="luxe-location">{{ $copy['listingLocation'] }}</span>
                                <h3><a href="/article/{{ $article->slug }}" class="text-decoration-none stretched-link">{{ $article->title }}</a></h3>
                                <div class="luxe-price">{{ $copy['serviceCardEyebrow'] }}</div>
                            </div>
                        </article>
                    @empty
                        @if (empty($featuredItems))
                            <div class="alert alert-light border mb-0">{{ $copy['categoryEmpty'] }}</div>
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

            @if ($paginator->lastPage() > 1)
                <nav class="container pb-5" aria-label="{{ $copy['paginationLabel'] }}">
                    <ul class="pagination justify-content-center">
                        @for ($i = 1; $i <= $paginator->lastPage(); ++$i)
                            <li class="page-item {{ $i === $paginator->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $basePath }}{{ $i > 1 ? '?page=' . $i : '' }}">{{ $i }}</a>
                            </li>
                        @endfor
                    </ul>
                </nav>
            @endif
        </main>
    @else
        <main class="page-shell container">
            <div class="page-hero reveal">
                <span class="landing-kicker">{{ $copy['categoryKicker'] }}</span>
                <h1>{{ $category->name }}</h1>
                <p>{{ $category->description ?? $category->name }}</p>
            </div>
            <div class="vstack gap-3 mb-4">
                @forelse ($paginator->items() as $article)
                    <div class="lux-card reveal">
                        <div class="card-body p-4">
                            <h2 class="h5 fw-bold mb-2"><a href="/article/{{ $article->slug }}" class="text-decoration-none">{{ $article->title }}</a></h2>
                            <p class="text-secondary mb-2">{{ $article->summary ?: '' }}</p>
                            <small class="text-secondary">{{ $article->published_at?->format('d M Y') }}</small>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-light border">{{ $copy['categoryEmpty'] }}</div>
                @endforelse
            </div>

            @if ($paginator->lastPage() > 1)
                <nav aria-label="{{ $copy['paginationLabel'] }}">
                    <ul class="pagination justify-content-center">
                        @for ($i = 1; $i <= $paginator->lastPage(); ++$i)
                            <li class="page-item {{ $i === $paginator->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $basePath }}{{ $i > 1 ? '?page=' . $i : '' }}">{{ $i }}</a>
                            </li>
                        @endfor
                    </ul>
                </nav>
            @endif
        </main>
    @endif
@endsection
