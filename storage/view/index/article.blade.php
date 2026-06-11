@extends('index.layouts.main')

@section('content')
    <main class="page-shell">
        <article class="container article-detail-page">
            <div class="article-hero reveal">
                @if ($article->category)
                    <a href="{{ $article->category->frontend_path ?? '/category/' . $article->category->slug }}" class="article-category-badge text-decoration-none">{{ $article->category->name }}</a>
                @endif
                <h1>{{ $article->title }}</h1>
                <div class="article-meta">
                    <span>{{ $copy['article-author'] ?? 'article-author' }}：{{ $article->author ?? $copy['article-defaultAuthor'] ?? 'article-defaultAuthor' }}</span>
                    <span>{{ $copy['article-published'] ?? 'article-published' }}：{{ $article->published_at ?  $article->published_at->format('d M Y') : ( $article->created_at?->format('d M Y')) }}</span>
                    <span>{{ $copy['article-views'] ?? 'article-views' }}：{{ (int) $article->views + 1 }}</span>
                </div>
            </div>
            @if ($article->cover)
                <div class="article-cover reveal">
                    <img src="{{ $article->cover }}" alt="{{ $article->title }}">
                </div>
            @endif
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="article-body reveal">
                        <div class="article-content">
                            @if ($article->content)
                                {!! $article->content !!}
                            @else
                                <p class="text-secondary">{{ $copy['article-emptyContent'] ?? 'article-emptyContent' }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </main>
@endsection
