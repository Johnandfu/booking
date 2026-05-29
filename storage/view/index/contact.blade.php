@extends('index.layouts.main')

@section('content')
    <main class="page-shell">
        <section class="page-hero container text-center reveal">
            <span class="landing-kicker">{{ $copy['contactUs'] }}</span>
            <h1>{{ $copy['ready'] }}</h1>
            <p class="mx-auto">{{ $copy['contactIntro'] }}</p>
        </section>

        <section class="container-fluid px-0 mb-5 reveal">
            <div class="coastal-map">
                <iframe
                    src="{{ $mapSetting['embed_url'] }}"
                    width="100%"
                    height="560"
                    style="border:0; display:block;"
                    allowfullscreen
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    title="{{ $copy['mapTitlePrefix'] }} - {{ $mapSetting['title'] }}"
                ></iframe>
            </div>
        </section>

        <section class="container row g-4 mb-5 mx-auto">
            <div class="col-md-6 col-lg-3 reveal">
                <div class="lux-card p-4 h-100 text-center">
                    <span class="service-icon material-symbols-rounded mb-3">location_on</span>
                    <h2 class="h5 fw-bold mb-2">{{ $copy['address'] }}</h2>
                    <p class="text-secondary mb-0">{{ $mapSetting['address'] }}</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 reveal">
                <div class="lux-card p-4 h-100 text-center">
                    <span class="service-icon material-symbols-rounded mb-3">mail</span>
                    <h2 class="h5 fw-bold mb-2">{{ $copy['contactEmail'] }}</h2>
                    <p class="text-secondary mb-0">{{ $copy['emailBody'] }}</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 reveal">
                <div class="lux-card p-4 h-100 text-center">
                    <span class="service-icon material-symbols-rounded mb-3">support_agent</span>
                    <h2 class="h5 fw-bold mb-2">{{ $copy['contactPhone'] }}</h2>
                    <p class="text-secondary mb-0">{{ $copy['hotBody'] }}</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 reveal">
                <div class="lux-card p-4 h-100 text-center">
                    <span class="service-icon material-symbols-rounded mb-3">schedule</span>
                    <h2 class="h5 fw-bold mb-2">{{ $copy['hours'] }}</h2>
                    <p class="text-secondary mb-0">{{ $copy['hoursBody'] }}</p>
                </div>
            </div>
        </section>

        <section class="container row g-4 align-items-stretch mx-auto">
            <div class="col-lg-6 reveal">
                <div class="lux-card p-4 p-lg-5 h-100">
                    <div class="section-kicker mb-3">{{ $copy['contactBook'] }}</div>
                    <h2 class="display-6 fw-bold mb-3">{{ $copy['send'] }}</h2>
                    <p class="text-secondary mb-4">{{ $copy['messageIntro'] }}</p>
                    <div class="vstack gap-3">
                        <div><span class="material-symbols-rounded text-primary me-2">home_work</span><strong>{{ $copy['contactServicePropertyTitle'] }}：</strong> {{ $copy['contactServicePropertyText'] }}</div>
                        <div><span class="material-symbols-rounded text-primary me-2">hotel</span><strong>{{ $copy['contactServiceAccommodationTitle'] }}：</strong> {{ $copy['contactServiceAccommodationText'] }}</div>
                        <div><span class="material-symbols-rounded text-primary me-2">payments</span><strong>{{ $copy['contactServiceOwnerTitle'] }}：</strong> {{ $copy['contactServiceOwnerText'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 reveal">
                <form class="lux-card p-4 p-lg-5 h-100" method="post" action="/contact/message">
                    @if ($message)
                        <div class="alert alert-{{ $messageType }}">{{ $message }}</div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label" for="name">{{ $copy['name'] }}</label>
                        <input class="form-control" id="name" name="name" type="text" placeholder="{{ $copy['name'] }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="email">{{ $copy['email'] }}</label>
                        <input class="form-control" id="email" name="email" type="email" placeholder="{{ $copy['email'] }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="phone">{{ $copy['phone'] }}</label>
                        <input class="form-control" id="phone" name="phone" type="text" placeholder="{{ $copy['phone'] }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="content">{{ $copy['message'] }}</label>
                        <textarea class="form-control" id="content" name="content" rows="5" placeholder="{{ $copy['message'] }}" required></textarea>
                    </div>
                    <button class="btn btn-au w-100" type="submit">{{ $copy['submit'] }}</button>
                </form>
            </div>
        </section>
    </main>
@endsection
