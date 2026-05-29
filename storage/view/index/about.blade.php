@extends('index.layouts.main')

@section('content')
    <main class="page-shell">
        <section class="page-hero container reveal">
            <span class="landing-kicker">{{ $copy['aboutKicker'] }}</span>
            <h1>{{ $copy['aboutTitle'] }}</h1>
            <p>{{ $copy['aboutLead'] }}</p>
        </section>

        <section class="container pb-5">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 reveal">
                    <div class="landing-about-image">
                        <img src="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1200&q=85" alt="{{ $copy['aboutImageAlt'] }}">
                    </div>
                </div>
                <div class="col-lg-6 reveal">
                    <div class="lux-card p-4 p-lg-5">
                        <div class="section-kicker mb-3">{{ $copy['aboutSupport'] }}</div>
                        <p class="landing-copy mt-0">{{ $copy['aboutBody'] }}</p>
                        <div class="vstack gap-3">
                            <div class="d-flex gap-3">
                                <span class="material-symbols-rounded text-primary">hotel</span>
                                <span>{{ $copy['aboutShort'] }}</span>
                            </div>
                            <div class="d-flex gap-3">
                                <span class="material-symbols-rounded text-primary">real_estate_agent</span>
                                <span>{{ $copy['aboutLeasing'] }}</span>
                            </div>
                            <div class="d-flex gap-3">
                                <span class="material-symbols-rounded text-primary">trending_up</span>
                                <span>{{ $copy['aboutSales'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
