@extends('index.layouts.main')

@section('content')
<main class="page-shell1">
    <!-- <section class="page-hero container text-center reveal">
        <span class="landing-kicker">{{ $copy['contact-us'] ?? 'contact-us' }}</span>
        <h1>{{ $copy['contact-ready'] ?? 'contact-ready' }}</h1>
        <p class="mx-auto">{{ $copy['contact-intro'] ?? 'contact-intro' }}</p>
    </section> -->

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
                <span class="service-icon mb-3"><svg class="cms-svg-icon"><use href="#icon-location"></use></svg></span>
                <h2 class="h5 fw-bold mb-2">{{ $copy['contact-address'] ?? 'contact-address' }}</h2>
                <p class="text-secondary mb-0">Sydney Opera House, Bennelong Point, Sydney NSW 2000, Australia</p>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 reveal">
            <div class="lux-card p-4 h-100 text-center">
                <span class="service-icon mb-3"><svg class="cms-svg-icon"><use href="#icon-mail"></use></svg></span>
                <h2 class="h5 fw-bold mb-2">{{ $copy['main-contactEmail'] ?? 'main-contactEmail' }}</h2>
                <p class="text-secondary mb-0">{{ $copy['contact-emailBody'] ?? 'contact-emailBody' }}</p>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 reveal">
            <div class="lux-card p-4 h-100 text-center">
                <span class="service-icon mb-3"><svg class="cms-svg-icon"><use href="#icon-support"></use></svg></span>
                <h2 class="h5 fw-bold mb-2">{{ $copy['main-contactPhone'] ?? 'main-contactPhone' }}</h2>
                <p class="text-secondary mb-0">{{ $copy['contact-hotBody'] ?? 'contact-hotBody' }}</p>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 reveal">
            <div class="lux-card p-4 h-100 text-center">
                <span class="service-icon mb-3"><svg class="cms-svg-icon"><use href="#icon-clock"></use></svg></span>
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
                    <div><svg class="cms-svg-icon text-primary me-2"><use href="#icon-home"></use></svg><strong>{{ $copy['contact-servicePropertyTitle'] ?? 'contact-servicePropertyTitle' }}：</strong> {{ $copy['contact-servicePropertyText'] ?? 'contact-servicePropertyText' }}</div>
                    <div><svg class="cms-svg-icon text-primary me-2"><use href="#icon-building"></use></svg><strong>{{ $copy['contact-serviceAccommodationTitle'] ?? 'contact-serviceAccommodationTitle' }}：</strong> {{ $copy['contact-serviceAccommodationText'] ?? 'contact-serviceAccommodationText' }}</div>
                    <div><svg class="cms-svg-icon text-primary me-2"><use href="#icon-payments"></use></svg><strong>{{ $copy['contact-serviceOwnerTitle'] ?? 'contact-serviceOwnerTitle' }}：</strong> {{ $copy['contact-serviceOwnerText'] ?? 'contact-serviceOwnerText' }}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 reveal">
            <form
                class="lux-card p-4 p-lg-5 h-100"
                method="post"
                action="/contact/message"
                id="contactForm"
                data-submit-required="{{ $copy['contact-submitRequired'] ?? 'Please enter your name and message.' }}"
                data-submit-success="{{ $copy['contact-submitSuccess'] ?? 'Your message has been sent.' }}"
                data-submit-failed="{{ $copy['contact-submitFailed'] ?? 'Failed to send your message. Please try again.' }}"
            >
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

<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="contactToast" class="toast text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" data-contact-toast-message></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<script>
    document.getElementById('contactForm')?.addEventListener('submit', async (event) => {
        event.preventDefault();

        const form = event.currentTarget;
        const submitButton = form.querySelector('button[type="submit"]');
        const toastEl = document.getElementById('contactToast');
        const toastMessage = toastEl.querySelector('[data-contact-toast-message]');
        const toast = bootstrap.Toast.getOrCreateInstance(toastEl, {
            autohide: true,
            delay: 5000,
        });
        const messages = {
            'contact-submitRequired': form.dataset.submitRequired || 'Please enter your name and message.',
            'contact-submitSuccess': form.dataset.submitSuccess || 'Your message has been sent.',
            'contact-submitFailed': form.dataset.submitFailed || 'Failed to send your message. Please try again.',
        };
        const showToast = (message, type) => {
            toastEl.className = `toast text-bg-${type} border-0`;
            toastMessage.textContent = message;
            toast.show();
        };

        submitButton.disabled = true;

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    Accept: 'application/json',
                },
            });
            const result = await response.json();

            showToast(
                messages[result.message] || result.message || messages['contact-submitFailed'],
                result.success ? 'success' : 'danger'
            );
            if (result.success) {
                form.reset();
            }
        } catch (error) {
            showToast(messages['contact-submitFailed'], 'danger');
        } finally {
            submitButton.disabled = false;
        }
    });
</script>
@endsection