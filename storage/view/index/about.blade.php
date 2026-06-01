@extends('index.layouts.main')

@section('content')
    <main class="page-shell">
        <section class="page-hero container reveal">
            <span class="landing-kicker">{{ $copy['about-kicker'] ?? 'about-kicker' }}</span>
            <h1>{{ $copy['about-title'] ?? 'about-title' }}</h1>
            <p>{{ $copy['about-lead'] ?? 'about-lead' }}</p>
        </section>

        <section class="container pb-5">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 reveal">
                    <div class="landing-about-image">
                        <img src="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1200&q=85" alt="{{ $copy['about-imageAlt'] ?? 'about-imageAlt' }}">
                    </div>
                </div>
                <div class="col-lg-6 reveal">
                    <div class="lux-card p-4 p-lg-5">
                        <div class="section-kicker mb-3">{{ $copy['about-support'] ?? 'about-support' }}</div>
                        <p class="landing-copy mt-0">{{ $copy['about-body'] ?? 'about-body' }}</p>
                        <div class="vstack gap-3">
                            <div class="d-flex gap-3">
                                <span class="material-symbols-rounded text-primary">hotel</span>
                                <span>{{ $copy['about-short'] ?? 'about-short' }}</span>
                            </div>
                            <div class="d-flex gap-3">
                                <span class="material-symbols-rounded text-primary">real_estate_agent</span>
                                <span>{{ $copy['about-leasing'] ?? 'about-leasing' }}</span>
                            </div>
                            <div class="d-flex gap-3">
                                <span class="material-symbols-rounded text-primary">trending_up</span>
                                <span>{{ $copy['about-sales'] ?? 'about-sales' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
