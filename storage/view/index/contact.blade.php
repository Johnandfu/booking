@extends('index.layouts.main')

@section('content')
<main class="page-shell">
    <section class="page-hero container text-center reveal">
        <span class="landing-kicker">{{ $copy['contact-us'] ?? 'contact-us' }}</span>
        <h1>{{ $copy['contact-ready'] ?? 'contact-ready' }}</h1>
        <p class="mx-auto">{{ $copy['contact-intro'] ?? 'contact-intro' }}</p>
    </section>

    <section class="container-fluid px-0 mb-5 reveal">
        <div class="coastal-map">
            <iframe
                src="https://www.google.com/maps?q={{$copy['contact-mapLocation'] ?? '-27.4698,153.0251'}}&z=16&output=embed"
                width="100%"
                height="560"
                style="border:0; display:block;"
                allowfullscreen
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                title="{{ $copy['contact-mapTitlePrefix'] ?? 'contact-mapTitlePrefix' }}"></iframe>
        </div>
    </section>

    <section class="container row g-4 mb-5 mx-auto">
        <div class="col-md-6 col-lg-3 reveal">
            <div class="lux-card p-4 h-100 text-center">
                <span class="service-icon material-symbols-rounded mb-3">location_on</span>
                <h2 class="h5 fw-bold mb-2">{{ $copy['contact-address'] ?? 'contact-address' }}</h2>
                <p class="text-secondary mb-0">Sydney Opera House, Bennelong Point, Sydney NSW 2000, Australia</p>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 reveal">
            <div class="lux-card p-4 h-100 text-center">
                <span class="service-icon material-symbols-rounded mb-3">mail</span>
                <h2 class="h5 fw-bold mb-2">{{ $copy['main-contactEmail'] ?? 'main-contactEmail' }}</h2>
                <p class="text-secondary mb-0">{{ $copy['contact-emailBody'] ?? 'contact-emailBody' }}</p>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 reveal">
            <div class="lux-card p-4 h-100 text-center">
                <span class="service-icon material-symbols-rounded mb-3">support_agent</span>
                <h2 class="h5 fw-bold mb-2">{{ $copy['main-contactPhone'] ?? 'main-contactPhone' }}</h2>
                <p class="text-secondary mb-0">{{ $copy['contact-hotBody'] ?? 'contact-hotBody' }}</p>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 reveal">
            <div class="lux-card p-4 h-100 text-center">
                <span class="service-icon material-symbols-rounded mb-3">schedule</span>
                <h2 class="h5 fw-bold mb-2">{{ $copy['contact-hours'] ?? 'contact-hours' }}</h2>
                <p class="text-secondary mb-0">{{ $copy['contact-hoursBody'] ?? 'contact-hoursBody' }}</p>
            </div>
        </div>
    </section>

    <section class="container row g-4 align-items-stretch mx-auto">
        <div class="col-lg-6 reveal">
            <div class="lux-card p-4 p-lg-5 h-100">
                <div class="section-kicker mb-3">{{ $copy['contact-book'] ?? 'contact-book' }}</div>
                <h2 class="display-6 fw-bold mb-3">{{ $copy['contact-send'] ?? 'contact-send' }}</h2>
                <p class="text-secondary mb-4">{{ $copy['contact-messageIntro'] ?? 'contact-messageIntro' }}</p>
                <div class="vstack gap-3">
                    <div><span class="material-symbols-rounded text-primary me-2">home_work</span><strong>{{ $copy['contact-servicePropertyTitle'] ?? 'contact-servicePropertyTitle' }}：</strong> {{ $copy['contact-servicePropertyText'] ?? 'contact-servicePropertyText' }}</div>
                    <div><span class="material-symbols-rounded text-primary me-2">hotel</span><strong>{{ $copy['contact-serviceAccommodationTitle'] ?? 'contact-serviceAccommodationTitle' }}：</strong> {{ $copy['contact-serviceAccommodationText'] ?? 'contact-serviceAccommodationText' }}</div>
                    <div><span class="material-symbols-rounded text-primary me-2">payments</span><strong>{{ $copy['contact-serviceOwnerTitle'] ?? 'contact-serviceOwnerTitle' }}：</strong> {{ $copy['contact-serviceOwnerText'] ?? 'contact-serviceOwnerText' }}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 reveal">
            <form class="lux-card p-4 p-lg-5 h-100" method="post" action="/contact/message">
                @if ($message)
                <div class="alert alert-{{ $messageType }}">{{ $message ?? '' }}</div>
                @endif
                <div class="mb-3">
                    <label class="form-label" for="name">{{ $copy['contact-name'] ?? 'contact-name' }}</label>
                    <input class="form-control" id="name" name="name" type="text" placeholder="{{ $copy['contact-name'] ?? 'contact-name' }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="email">{{ $copy['contact-email'] ?? 'contact-email'    }}</label>
                    <input class="form-control" id="email" name="email" type="email" placeholder="{{ $copy['contact-email'] ?? 'contact-email'}}">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="phone">{{ $copy['contact-phone'] ?? 'contact-phone' }}</label>
                    <input class="form-control" id="phone" name="phone" type="text" placeholder="{{ $copy['contact-phone'] ?? 'contact-phone' }}">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="content">{{ $copy['contact-message'] ?? 'contact-message' }}</label>
                    <textarea class="form-control" id="content" name="content" rows="5" placeholder="{{ $copy['contact-message'] ?? 'contact-message' }}" required></textarea>
                </div>
                <button class="btn btn-au w-100" type="submit">{{ $copy['contact-submit'] ?? 'contact-submit' }}</button>
            </form>
        </div>
    </section>
</main>
@endsection