@extends('index.layouts.main')

@section('content')
    <main class="page-shell">
        <section class="page-hero container reveal">
            <span class="landing-kicker">{{ $copy['page-kicker'] ?? 'page-kicker' }}</span>
            <h1>{{ $page->title }}</h1>
        </section>
        <article class="container row justify-content-center mx-auto">
            <div class="col-lg-9">
                <div class="content-panel p-4 p-lg-5 reveal">
                    <div class="article-content">
                        @if ($page->content)
                            {!! $page->content !!}
                        @else
                            <p class="text-secondary">{{ $copy['page-emptyContent'] ?? 'page-emptyContent' }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </article>
    </main>
@endsection
