@extends('index.layouts.main')

@section('content')
    <main class="page-shell">
        <article class="container">
            <div class="article-hero reveal">
                @if ($article->category)
                    <a href="{{ $article->category->frontend_path ?? '/category/' . $article->category->slug }}" class="badge rounded-pill tag-au text-decoration-none mb-3">{{ $article->category->name }}</a>
                @endif
                <h1>{{ $article->title }}</h1>
                <div class="d-flex flex-wrap justify-content-center gap-3 text-secondary small">
                    <span>{{ $copy['articleAuthor'] }}：{{ $article->author ?? $copy['articleDefaultAuthor'] }}</span>
                    <span>{{ $copy['articlePublished'] }}：{{ $article->published_at?->format('d M Y') }}</span>
                    <span>{{ $copy['articleViews'] }}：{{ (int) $article->views + 1 }}</span>
                </div>
            </div>
            @if ($article->cover)
                <div class="article-cover reveal">
                    <img src="{{ $article->cover }}" alt="{{ $article->title }}">
                </div>
            @endif
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="content-panel article-panel p-4 p-lg-5 reveal">
                        <div class="article-content">
                            @if ($article->content)
                                {!! $article->content !!}
                            @else
                                <p class="text-secondary">{{ $copy['articleEmptyContent'] }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </main>
@endsection
