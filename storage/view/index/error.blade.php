@extends('index.layouts.main')

@section('content')
    <main class="container py-5">
        <div class="content-panel text-center py-5 px-4">
            <span class="landing-kicker">{{ $copy['error-kicker'] ?? 'Page Error' }}</span>
            <h1 class="display-4 fw-bold">{{ $errorCode ?? 500 }}</h1>
            <p class="lead text-secondary mb-4">{{ $errorMessage ?? ($copy['error-text'] ?? 'Something went wrong.') }}</p>
            <a href="/" class="btn btn-au">{{ $copy['error-backHome'] ?? 'Back to home' }}</a>
        </div>
    </main>
@endsection
