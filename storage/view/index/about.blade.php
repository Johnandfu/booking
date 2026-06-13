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
            <div class="row align-items-center g-5 about-split-row">
                <div class="col-lg-6 about-split-col reveal">
                    <span class="landing-kicker">{{ $copy['about-kicker'] ?? 'about-kicker' }}</span>
                    <h2>{{ $copy['about-title'] ?? 'about-title' }}</h2>
                    <div class="about-copy">
                        <p>{{ $copy['about-lead'] ?? 'about-lead' }}</p>
                        <p>{{ $copy['about-body'] ?? 'about-body' }}</p>
                        <p>{{ $copy['about-olympicText'] ?? 'about-olympicText' }}</p>
                    </div>
                    <a href="/contact" class="btn btn-au px-4">{{ $copy['main-contact'] ?? 'main-contact' }}</a>
                </div>
                <div class="col-lg-6 about-split-col reveal">
                    <div class="about-image-card">
                        <img src="{{ $copy['about-introImage'] ?? '' }}" alt="{{ $copy['about-imageAlt'] ?? 'about-imageAlt' }}">
                    </div>
                </div>
            </div>
        </section>

        <section class="about-story-section">
            <div class="container">
                <div class="row align-items-center g-5 about-split-row">
                    <div class="col-lg-6 about-split-col reveal">
                        <div class="about-image-stack">
                            <img src="{{ $copy['about-storyImage'] ?? '' }}" alt="{{ $copy['about-imageAlt'] ?? 'about-imageAlt' }}">
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
                    <div class="col-lg-6 about-split-col reveal">
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
            <div class="row align-items-center g-5 about-split-row">
                <div class="col-lg-6 about-split-col reveal">
                    <span class="landing-kicker">{{ $copy['about-founderKicker'] ?? 'Founder. & Director' }}</span>
                    <h2>{{ $copy['about-founderTitle'] ?? 'about-founderTitle' }}</h2>
                    <div class="about-copy">
                        <p>{{ $copy['about-founderOne'] ?? 'about-founderOne' }}</p>
                        <p>{{ $copy['about-founderTwo'] ?? 'about-founderTwo' }}</p>
                        <p>{{ $copy['about-founderThree'] ?? 'about-founderThree' }}</p>
                    </div>
                    <a href="/contact" class="btn btn-au px-4">{{ $copy['main-contact'] ?? 'main-contact' }}</a>
                </div>
                <div class="col-lg-6 about-split-col reveal">
                    <div class="about-founder-card">
                        <img src="{{ $copy['about-founderImage'] ?? '' }}" alt="{{ $copy['about-founderTitle'] ?? 'Founder and director' }}">
                    </div>
                </div>
            </div>
        </section>

    </main>
@endsection
