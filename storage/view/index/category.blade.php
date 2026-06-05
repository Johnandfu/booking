@extends('index.layouts.main')

@section('content')
    @if (!in_array($category->slug, ['news'], true))
        <main class="services-page">
            <section class="services-title-bar">
                <div class="container">
                    <h1>{{ $category->name }}</h1>
                    <nav class="services-breadcrumb" aria-label="breadcrumb">
                        <a href="/">{{ $copy['main-home'] ?? 'Home' }}</a>
                        <span>{{ $category->name }}</span>
                    </nav>
                </div>
            </section>

            <section class="services-content">
                <div class="container">
                    <div class="vstack gap-4">
                        @foreach (($serviceSections ?? []) as $section)
                            <article class="service-card reveal">
                                <div class="service-card-media">
                                    <img src="{{ $section['image'] }}" alt="{{ $section['title'] }}">
                                </div>
                                <div class="service-card-copy">
                                    <h2>{{ $section['title'] }}</h2>
                                    <div class="article-content service-section-body">
                                        {!! $section['content'] !!}
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>
        </main>
    @else
        <main class="page-shell container">
            <div class="page-hero reveal">
                <span class="landing-kicker">{{ $category['kicker'] }}</span>
                <h1>{{ $category['title'] }}</h1>
                <p>{{ $category['description'] }}</p>
            </div>
            <div class="vstack gap-3 mb-4">
                @forelse ($paginator->items() as $article)
                    <div class="lux-card reveal">
                        <div class="card-body p-4">
                            <h2 class="h5 fw-bold mb-2"><a href="{{ $basePath }}/{{ $article->id }}" class="text-decoration-none">{{ $article->title }}</a></h2>
                            @if ($article->summary)
                                <p class="text-secondary mb-2">{{ $article->summary }}</p>
                            @endif
                            <small class="text-secondary">{{ $article->published_at?->format('d M Y') }}</small>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-light border">{{ $category['empty'] }}</div>
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
