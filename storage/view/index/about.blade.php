@extends('index.layouts.main')

@section('content')
    <main class="about-page">
        <section class="about-title-bar">
            <div class="container">
                <h1>{{ $copy['main-about'] ?? 'About' }}</h1>
                <nav class="services-breadcrumb" aria-label="breadcrumb">
                    <a href="/">{{ $copy['main-home'] ?? 'Home' }}</a>
                    <span>{{ $copy['main-about'] ?? 'About' }}</span>
                </nav>
            </div>
        </section>

        <section class="container about-section">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 reveal">
                    <span class="landing-kicker">{{ $copy['about-kicker'] ?? 'about-kicker' }}</span>
                    <h2>{{ $copy['about-title'] ?? 'about-title' }}</h2>
                    <div class="about-copy">
                        <p>{{ $copy['about-lead'] ?? 'about-lead' }}</p>
                        <p>{{ $copy['about-body'] ?? 'about-body' }}</p>
                        <p>{{ $copy['about-olympicText'] ?? 'about-olympicText' }}</p>
                    </div>
                    <a href="/contact" class="btn btn-au px-4">{{ $copy['main-contact'] ?? 'main-contact' }}</a>
                </div>
                <div class="col-lg-6 reveal">
                    <div class="about-image-card">
                        <img src="https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=1200&q=85" alt="{{ $copy['about-imageAlt'] ?? 'about-imageAlt' }}">
                    </div>
                </div>
            </div>
        </section>

        <section class="about-story-section">
            <div class="container">
                <div class="row align-items-center g-5">
                    <div class="col-lg-6 reveal">
                        <div class="about-image-stack">
                            <img src="https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1100&q=85" alt="{{ $copy['about-imageAlt'] ?? 'about-imageAlt' }}">
                            <div class="about-stat-card">
                                <strong>30k+</strong>
                                <span>{{ $copy['about-satisfiedClient'] ?? 'about-satisfiedClient' }}</span>
                            </div>
                            <div class="about-stat-card about-stat-card-secondary">
                                <strong>700+</strong>
                                <span>{{ $copy['about-house'] ?? 'about-house' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 reveal">
                        <span class="landing-kicker">{{ $copy['about-us'] ?? 'about-us' }}</span>
                        <h2>{{ $copy['about-storyTitle'] ?? 'about-storyTitle' }}</h2>
                        <div class="about-copy">
                            <p><strong>{{ $copy['about-storyOneTitle'] ?? 'about-storyOneTitle' }}</strong> {{ $copy['about-storyOne'] ?? 'about-storyOne' }}</p>
                            <p><strong>{{ $copy['about-storyTwoTitle'] ?? 'about-storyTwoTitle' }}</strong> {{ $copy['about-storyTwo'] ?? 'about-storyTwo' }}</p>
                            <p><strong>{{ $copy['about-storyThreeTitle'] ?? 'about-storyThreeTitle' }}</strong> {{ $copy['about-storyThree'] ?? 'about-storyThree' }}</p>
                            <p>{{ $copy['about-storyGoal'] ?? 'about-storyGoal' }}</p>
                        </div>
                        <a href="/contact" class="btn btn-au px-4">{{ $copy['about-contact'] ?? 'about-contact' }}</a>
                    </div>
                </div>
            </div>
        </section>

        <section class="container about-section">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 reveal">
                    <span class="landing-kicker">{{ $copy['about-founderKicker  '] ?? 'Founder. & Director' }}</span>
                    <h2>{{ $copy['about-founderTitle'] ?? 'about-founderTitle' }}</h2>
                    <div class="about-copy">
                        <p>{{ $copy['about-founderOne'] ?? 'about-founderOne' }}</p>
                        <p>{{ $copy['about-founderTwo'] ?? 'about-founderTwo' }}</p>
                        <p>{{ $copy['about-founderThree'] ?? 'about-founderThree' }}</p>
                    </div>
                    <a href="/contact" class="btn btn-au px-4">{{ $copy['main-contact'] ?? 'main-contact' }}</a>
                </div>
                <div class="col-lg-6 reveal">
                    <div class="about-founder-card">
                        <img src="https://images.unsplash.com/photo-1542744173-8e7e53415bb0?auto=format&fit=crop&w=1200&q=85" alt="{{ $copy['about-founderTitle'] ?? 'Founder and director' }}">
                    </div>
                </div>
            </div>
        </section>

        <section class="about-muted-section">
            <div class="container">
                <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-end mb-4">
                    <div>
                        <span class="landing-kicker">{{ $copy['about-expertKicker'] ?? 'about-expertKicker' }}</span>
                        <h2>{{ $copy['about-teamTitle'] ?? 'about-teamTitle' }}</h2>
                    </div>
                    <a href="/contact" class="btn btn-au px-4">{{ $copy['about-viewAllMember'] ?? 'View All Member' }}</a>
                </div>
                <div class="row g-4">
                    @foreach (['Savannah Nguyen', 'Annette Black', 'Kathryn Murphy', 'David Hardson'] as $member)
                        <div class="col-md-6 col-lg-3 reveal">
                            <div class="about-team-card">
                                <div class="about-team-avatar"><i class="bi bi-person-fill"></i></div>
                                <h3>{{ $member }}</h3>
                                <p>{{ $copy['about-call'] ?? 'Call:' }} {{ $copy['main-contactPhone'] ?? '(0123) 456 789' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="container about-section">
            <span class="landing-kicker">{{ $copy['about-testimonialsKicker'] ?? 'Testimonials' }}</span>
            <h2>{{ $copy['about-feedbackTitle'] ?? 'Latest Client Feedback' }}</h2>
            <div class="row g-4 mt-2">
                @foreach (['Kristin Watson' => 'Web Designer', 'Wade Warren' => 'President of Sales', 'Jessica Brown' => 'Founder & CEO'] as $name => $role)
                    <div class="col-lg-4 reveal">
                        <div class="about-feedback-card">
                            <p>{{ $copy['about-feedbackText'] ?? 'Praesent ut lacus a velit tincidunt aliquam a eget urna. Sed ullamcorper tristique nisl at pharetra turpis accumsan et etiam eu sollicitudin eros.' }}</p>
                            <h3>{{ $name }}</h3>
                            <span>{{ $role }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </main>
@endsection
