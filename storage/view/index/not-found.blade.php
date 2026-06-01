@extends('index.layouts.main')

@section('content')

    <main class="container py-5">
        <div class="content-panel text-center py-5 px-4">
            <h1 class="display-4 fw-bold">404</h1>
            <p class="lead text-secondary mb-4">{{ $copy['not-found-text'] ?? 'not-found-text' }}</p>
            <a href="/" class="btn btn-au">{{ $copy['not-found-backHome'] ?? 'not-found-backHome' }}</a>
        </div>
    </main>
@endsection


