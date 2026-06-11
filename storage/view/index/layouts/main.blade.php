<!doctype html>
<html lang="{{ $htmlLang }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $seoTitle ?? ($copy['main-title'] ?? 'main-title') }} - {{ $copy['main-siteName'] ?? 'main-siteName' }}</title>
    <meta name="description" content="{{ $seoDescription ?? ($copy['main-description'] ?? 'main-description') }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ $seoUrl ?? '' }}">
    <meta property="og:locale" content="{{ $htmlLang ?? 'en' }}">
    <meta property="og:type" content="@if (! empty($article)) article @else website @endif">
    <meta property="og:site_name" content="{{ $copy['main-siteName'] ?? 'main-siteName' }}">
    <meta property="og:title" content="{{ $seoTitle ?? ($copy['main-title'] ?? 'main-title') }}">
    <meta property="og:description" content="{{ $seoDescription ?? ($copy['main-description'] ?? 'main-description') }}">

    <meta name="twitter:card" content="@if (! empty($seoImage)) summary_large_image @else summary @endif">
    <meta name="twitter:title" content="{{ $seoTitle ?? ($copy['main-title'] ?? 'main-title') }}">
    <meta name="twitter:description" content="{{ $seoDescription ?? ($copy['main-description'] ?? 'main-description') }}">

    @if (! empty($copy['main-gaMeasurementId']))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $copy['main-gaMeasurementId'] }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', "{{ $copy['main-gaMeasurementId'] }}");
    </script>
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --md-primary: #0f8f93;
            --md-primary-dark: #075c66;
            --md-secondary: #2f7fc1;
            --md-tertiary: #d9a441;
            --md-surface: rgba(255, 255, 255, .88);
            --md-surface-container: #fff8ed;
            --md-outline: rgba(214, 184, 130, .34);
            --md-ink: #253331;
            --md-muted: #6f7d79;
            --md-sand: #f5dfbd;
            --md-foam: #eefdf9;
            --md-sky: #d8f0ff;
            --md-shadow-1: 0 10px 28px rgba(42, 75, 82, .08), 0 1px 0 rgba(255, 255, 255, .80) inset;
            --md-shadow-2: 0 18px 48px rgba(42, 75, 82, .13), 0 1px 0 rgba(255, 255, 255, .86) inset;
            --md-shadow-3: 0 28px 70px rgba(36, 65, 72, .20), 0 1px 0 rgba(255, 255, 255, .92) inset;
        }

        body {
            background:
                radial-gradient(circle at 13% 0%, rgba(255, 220, 154, .52), transparent 30rem),
                radial-gradient(circle at 86% 8%, rgba(135, 213, 235, .45), transparent 28rem),
                linear-gradient(180deg, #fff8ed 0%, #f4fbfb 48%, #fffaf3 100%),
                var(--md-surface-container);
            color: var(--md-ink);
            font-family: Inter, "Segoe UI", "PingFang SC", "Microsoft YaHei", sans-serif;
        }

        html,
        body {
            min-height: 100%;
            overflow-x: clip;
        }

        @supports not (overflow: clip) {
            html,
            body {
                overflow-x: hidden;
            }
        }

        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }

        .site-main {
            flex: 1 0 auto;
            overflow-x: clip;
        }

        .navbar-au {
            border-bottom: 1px solid rgba(255, 255, 255, .62);
            background: rgba(255, 250, 242, .78);
            backdrop-filter: blur(24px) saturate(140%);
            box-shadow: 0 16px 42px rgba(71, 93, 95, .10);
            z-index: 1040;
        }

        .navbar-brand .brand-mark {
            display: inline-flex;
            width: 2.1rem;
            height: 2.1rem;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            background: linear-gradient(135deg, #f4c76b, var(--md-primary), #5ec5d2);
            color: #fff;
            box-shadow: var(--md-shadow-2);
        }

        .navbar-brand span:last-child {
            font-family: Inter, "Segoe UI", sans-serif;
            letter-spacing: .02em;
        }

        .nav-link {
            color: var(--md-muted);
            font-weight: 500;
            border-radius: 999px;
            padding-inline: 1rem !important;
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--md-primary);
            background: rgba(15, 143, 147, .10);
        }

        .hero-au {
            position: relative;
            overflow: hidden;
            background:
                linear-gradient(110deg, rgba(3, 46, 55, .72), rgba(15, 143, 147, .42), rgba(255, 198, 105, .28)),
                url("https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1800&q=80") center/cover;
            color: #fff;
        }

        .hero-au::before {
            content: "";
            position: absolute;
            inset: 0;
            pointer-events: none;
            background:
                radial-gradient(circle at 80% 12%, rgba(255, 236, 188, .42), transparent 18rem),
                linear-gradient(180deg, transparent, rgba(4, 38, 44, .22));
        }

        .hero-au::after {
            content: "";
            position: relative;
            display: block;
            height: 1px;
            background: rgba(255, 255, 255, .18);
        }

        .hero-au .container::after {
            content: "";
            position: absolute;
            right: 1rem;
            bottom: 1rem;
            width: 12rem;
            height: 12rem;
            border-radius: 999px;
            border: 1px solid rgba(255, 234, 186, .46);
            transform: rotate(12deg);
            opacity: .68;
        }

        h1,
        h2.display-6,
        .display-3,
        .display-5,
        .display-6 {
            font-family: Inter, "Segoe UI", sans-serif;
            letter-spacing: -.025em;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            color: #ffe7b0;
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .18em;
            text-transform: uppercase;
        }

        .eyebrow::before {
            content: "";
            width: 2rem;
            height: 1px;
            background: currentColor;
        }

        .btn-au {
            --bs-btn-bg: var(--md-primary);
            --bs-btn-border-color: var(--md-primary);
            --bs-btn-color: #fff;
            --bs-btn-hover-bg: var(--md-primary-dark);
            --bs-btn-hover-border-color: var(--md-primary-dark);
            --bs-btn-hover-color: #fff;
            font-weight: 700;
            border-radius: 999px;
            box-shadow: var(--md-shadow-2);
        }

        .btn-au,
        .btn-light {
            letter-spacing: .02em;
        }

        .btn-light {
            --bs-btn-bg: rgba(255, 250, 238, .92);
            --bs-btn-border-color: rgba(255, 255, 255, .72);
            --bs-btn-color: var(--md-primary-dark);
            backdrop-filter: blur(12px);
        }

        .btn-outline-au {
            --bs-btn-color: #fff;
            --bs-btn-border-color: rgba(255, 255, 255, .55);
            --bs-btn-hover-bg: #fff;
            --bs-btn-hover-border-color: #fff;
            --bs-btn-hover-color: var(--md-primary-dark);
        }

        .text-white-75 {
            color: rgba(255, 255, 255, .75);
        }

        .section-kicker {
            color: var(--md-primary);
            font-size: .76rem;
            font-weight: 800;
            letter-spacing: .16em;
            text-transform: uppercase;
        }

        .surface-card,
        .article-card {
            background: var(--md-surface);
            border: 1px solid var(--md-outline);
            border-radius: 28px;
            box-shadow: var(--md-shadow-1);
            backdrop-filter: blur(18px) saturate(124%);
        }

        .article-card {
            overflow: hidden;
            transition: transform .25s ease, box-shadow .25s ease;
        }

        .article-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--md-shadow-3);
        }

        .article-card a,
        .content-panel a {
            color: var(--md-primary-dark);
        }

        .home-section-card {
            border: 1px solid var(--md-outline);
            border-radius: 32px;
            box-shadow: var(--md-shadow-2);
            backdrop-filter: blur(18px) saturate(124%);
        }

        .service-section-card {
            background:
                radial-gradient(circle at 8% 0%, rgba(255, 218, 151, .30), transparent 18rem),
                linear-gradient(135deg, rgba(215, 249, 242, .70), rgba(255, 252, 244, .94)),
                var(--md-surface);
        }

        .news-section-card {
            background:
                linear-gradient(135deg, rgba(232, 238, 244, .70), rgba(255, 253, 247, .96)),
                var(--md-surface);
        }

        .content-carousel {
            position: relative;
        }

        .content-carousel .carousel-control-prev,
        .content-carousel .carousel-control-next {
            top: 50%;
            bottom: auto;
            width: 3.5rem;
            height: 3.5rem;
            opacity: 0;
            transform: translateY(-50%);
            transition: opacity .2s ease;
            z-index: 5;
        }

        .content-carousel:hover .carousel-control-prev,
        .content-carousel:hover .carousel-control-next,
        .content-carousel:focus-within .carousel-control-prev,
        .content-carousel:focus-within .carousel-control-next {
            opacity: 1;
        }

        .content-carousel .carousel-control-prev {
            left: .75rem;
        }

        .content-carousel .carousel-control-next {
            right: .75rem;
        }

        .content-carousel .carousel-control-prev-icon,
        .content-carousel .carousel-control-next-icon {
            width: 3.25rem;
            height: 3.25rem;
            border-radius: 999px;
            background-color: var(--md-primary);
            background-size: 1.25rem;
            box-shadow: var(--md-shadow-2);
        }

        .content-carousel .carousel-control-prev-icon:hover,
        .content-carousel .carousel-control-next-icon:hover {
            background-color: var(--md-primary-dark);
        }

        @media (max-width: 767.98px) {

            .content-carousel .carousel-control-prev,
            .content-carousel .carousel-control-next {
                opacity: 1;
            }

            .content-carousel .carousel-control-prev {
                left: .5rem;
            }

            .content-carousel .carousel-control-next {
                right: .5rem;
            }
        }

        .tag-au {
            background: #e5faf6;
            color: var(--md-primary-dark);
            border: 1px solid rgba(15, 143, 147, .22);
        }

        .topbar {
            background: linear-gradient(90deg, #073f48, #0f8f93 58%, #d9a441);
            color: rgba(255, 255, 255, .88);
            font-size: .875rem;
        }

        .topbar a {
            color: rgba(255, 255, 255, .86);
            text-decoration: none;
        }

        .topbar-social {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
        }

        .topbar-social a {
            display: inline-flex;
            width: 1.85rem;
            height: 1.85rem;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255, 255, 255, .24);
            border-radius: 999px;
            color: rgba(255, 255, 255, .88);
            transition: color .3s ease-out, border-color .3s ease-out, transform .3s ease-out;
        }

        .topbar-social a:hover {
            color:rgb(255, 168, 168);
            border-color: rgba(255, 229, 168, .72);
            transform: translateY(-1px);
        }

        .social-icon {
            width: 1rem;
            height: 1rem;
            display: block;
            fill: currentColor;
        }

        .cms-svg-icon {
            width: 1em;
            height: 1em;
            display: inline-block;
            flex: 0 0 auto;
            fill: currentColor;
            vertical-align: -.125em;
        }

        .language-menu .dropdown-toggle {
            color: #073f48;
            background: linear-gradient(135deg, #fff4d5, #dbfbf4);
            border: 0;
            border-radius: 999px;
            font-size: .8rem;
            font-weight: 800;
            padding: .35rem .75rem;
        }

        .language-menu,
        .language-menu .dropdown-menu {
            z-index: 2000;
        }

        .search-panel {
            margin-top: -3rem;
            position: relative;
            z-index: 2;
        }

        .property-stat {
            border-left: 3px solid var(--md-tertiary);
            padding-left: 1rem;
        }

        .luxe-listing-page {
            overflow: hidden;
            padding-bottom: clamp(4rem, 7vw, 7rem);
        }

        .news-page {
            background:
                radial-gradient(circle at 10% 20%, rgba(255, 226, 168, .22), transparent 24rem),
                radial-gradient(circle at 90% 34%, rgba(125, 210, 228, .18), transparent 26rem),
                linear-gradient(180deg, #fffaf3 0%, #f4fbfb 44%, #fffaf6 100%);
            overflow: visible;
        }

        .luxe-listing-hero {
            position: relative;
            display: flex;
            min-height: 72vh;
            align-items: flex-end;
            padding-block: clamp(6rem, 12vw, 10rem) clamp(4rem, 8vw, 7rem);
            background:
                linear-gradient(115deg, rgba(4, 42, 51, .78), rgba(15, 143, 147, .30), rgba(217, 164, 65, .20)),
                url("https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=2200&q=88") center/cover;
            color: #fff;
        }

        .news-page .luxe-listing-hero {
            min-height: min(68vh, 42rem);
            padding-block: clamp(5.5rem, 10vw, 8rem) clamp(3rem, 6vw, 5rem);
            background:
                linear-gradient(120deg, rgba(3, 36, 43, .82), rgba(15, 143, 147, .42), rgba(217, 164, 65, .18)),
                url("https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=2200&q=88") center/cover;
        }

        .luxe-service-hero {
            background:
                linear-gradient(115deg, rgba(4, 42, 51, .74), rgba(15, 143, 147, .24), rgba(217, 164, 65, .24)),
                url("https://images.unsplash.com/photo-1499793983690-e29da59ef1c2?auto=format&fit=crop&w=2200&q=88") center/cover;
        }

        .luxe-listing-hero::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 78% 18%, rgba(255, 235, 184, .34), transparent 22rem),
                linear-gradient(180deg, transparent, rgba(2, 28, 34, .44));
            pointer-events: none;
        }

        .luxe-listing-hero h1 {
            max-width: 760px;
            margin-bottom: 1rem;
            font-size: clamp(3.2rem, 7vw, 7rem);
            font-weight: 700;
            letter-spacing: -.07em;
            line-height: .92;
        }

        .news-page .luxe-listing-hero h1 {
            max-width: 720px;
            font-size: clamp(2.7rem, 6vw, 5.7rem);
            letter-spacing: -.06em;
        }

        .luxe-listing-hero p {
            max-width: 620px;
            color: rgba(255, 255, 255, .80);
            font-size: clamp(1.1rem, 2vw, 1.45rem);
            line-height: 1.75;
        }

        .luxe-filter-panel {
            padding: 1.35rem;
            border: 1px solid rgba(255, 255, 255, .34);
            border-radius: 28px;
            background: rgba(255, 255, 255, .16);
            box-shadow: 0 24px 70px rgba(2, 28, 34, .18);
            backdrop-filter: blur(22px) saturate(150%);
        }

        .news-page .luxe-filter-panel {
            padding: 1rem;
            border-color: rgba(255, 255, 255, .42);
            border-radius: 24px;
            background: rgba(255, 255, 255, .18);
            box-shadow: 0 22px 60px rgba(2, 28, 34, .20);
        }

        .luxe-filter-panel label {
            display: block;
            margin-bottom: .55rem;
            color: rgba(255, 255, 255, .70);
            font-size: .68rem;
            font-weight: 800;
            letter-spacing: .16em;
            text-transform: uppercase;
        }

        .luxe-filter-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.1rem;
            padding: .85rem 1rem;
            border: 1px solid rgba(255, 255, 255, .22);
            border-radius: 999px;
            color: #fff;
        }

        .news-page .luxe-filter-row {
            gap: .75rem;
            margin-bottom: 0;
            background: rgba(255, 255, 255, .10);
        }

        .news-page .luxe-filter-row input::placeholder {
            color: rgba(255, 255, 255, .62);
        }

        .news-page .luxe-filter-row .btn {
            font-weight: 700;
            color: var(--md-primary-dark);
            box-shadow: 0 12px 28px rgba(2, 28, 34, .14);
        }

        .luxe-range {
            height: .35rem;
            margin: .9rem 0 1.25rem;
            overflow: hidden;
            border-radius: 999px;
            background: rgba(255, 255, 255, .24);
        }

        .luxe-range span {
            display: block;
            height: 100%;
            border-radius: inherit;
            background: linear-gradient(90deg, #ffe1a0, #6ee7df);
        }

        .luxe-tags {
            display: flex;
            flex-wrap: wrap;
            gap: .55rem;
        }

        .luxe-tags span {
            padding: .45rem .72rem;
            border: 1px solid rgba(255, 255, 255, .26);
            border-radius: 999px;
            color: rgba(255, 255, 255, .84);
            font-size: .78rem;
        }

        .luxe-featured-section,
        .luxe-grid-section,
        .luxe-category-section {
            padding-top: clamp(4.5rem, 8vw, 7rem);
        }

        .news-page .luxe-featured-section,
        .news-page .luxe-grid-section,
        .news-page .luxe-category-section {
            padding-top: clamp(3.4rem, 6vw, 5.4rem);
        }

        .luxe-section-title {
            max-width: 780px;
            margin-bottom: 2.5rem;
        }

        .news-page .luxe-section-title {
            max-width: 680px;
            margin-bottom: 1.8rem;
        }

        .luxe-section-title h2 {
            font-size: clamp(2rem, 4vw, 4rem);
            font-weight: 650;
            letter-spacing: -.055em;
            line-height: 1;
        }

        .news-page .luxe-section-title h2 {
            font-size: clamp(1.9rem, 3.6vw, 3.35rem);
        }

        .news-accordion-strip {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 1rem;
        }

        .news-accordion-panel {
            position: relative;
            min-height: clamp(20rem, 30vw, 25rem);
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, .74);
            border-radius: 28px;
            background: #0b3e47;
            box-shadow: var(--md-shadow-2);
            transition: transform .28s ease-out, box-shadow .28s ease-out, border-color .28s ease-out;
        }

        .news-accordion-panel:hover,
        .news-accordion-panel:focus-within {
            transform: translateY(-6px);
            border-color: rgba(217, 164, 65, .42);
            box-shadow: var(--md-shadow-3);
        }

        .news-accordion-link {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: flex-end;
            color: #fff;
            text-decoration: none;
        }

        .news-accordion-media {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, #075c66, #d9a441);
        }

        .news-accordion-media::after {
            content: "";
            position: absolute;
            inset: 0;
            background:
                linear-gradient(180deg, rgba(2, 28, 34, .04) 20%, rgba(2, 28, 34, .38) 62%, rgba(2, 28, 34, .84) 100%),
                radial-gradient(circle at 12% 12%, rgba(255, 225, 163, .28), transparent 14rem);
        }

        .news-accordion-media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transform-origin: center center;
            transition: transform .35s ease-out;
        }

        .news-accordion-media .cms-svg-icon {
            display: flex;
            width: 100%;
            height: 100%;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, .82);
            font-size: 4rem;
        }

        .news-accordion-panel:hover .news-accordion-media img,
        .news-accordion-panel:focus-within .news-accordion-media img {
            transform: scale(1.045);
        }

        .news-accordion-panel:hover .news-accordion-media::after,
        .news-accordion-panel:focus-within .news-accordion-media::after {
            background:
                linear-gradient(180deg, rgba(2, 28, 34, .02) 12%, rgba(2, 28, 34, .28) 58%, rgba(2, 28, 34, .78) 100%),
                radial-gradient(circle at 12% 12%, rgba(255, 225, 163, .42), transparent 16rem);
        }

        .news-accordion-content {
            position: relative;
            z-index: 1;
            display: grid;
            width: 100%;
            gap: .7rem;
            padding: clamp(1rem, 2vw, 1.55rem);
        }

        .news-accordion-date {
            color: rgba(255, 235, 184, .92);
            font-size: .72rem;
            font-weight: 850;
            letter-spacing: .14em;
            text-transform: uppercase;
        }

        .news-accordion-content strong {
            display: -webkit-box;
            overflow: hidden;
            font-size: clamp(1.1rem, 1.6vw, 1.5rem);
            font-weight: 760;
            letter-spacing: -.04em;
            line-height: 1.06;
            line-clamp: 2;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
        }

        .news-accordion-summary {
            display: -webkit-box;
            overflow: hidden;
            color: rgba(255, 255, 255, .82);
            font-size: .92rem;
            line-height: 1.65;
            line-clamp: 2;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
        }

        .news-accordion-action {
            display: inline-flex;
            align-items: center;
            width: fit-content;
            color: #fff;
            font-size: .75rem;
            font-weight: 850;
            letter-spacing: .12em;
            text-transform: uppercase;
        }

        .news-accordion-action::after {
            content: "";
            width: 1.8rem;
            height: 1px;
            margin-left: .65rem;
            background: currentColor;
            opacity: .58;
            transition: width .2s ease-out;
        }

        .news-accordion-panel:hover .news-accordion-action::after,
        .news-accordion-panel:focus-within .news-accordion-action::after {
            width: 2.6rem;
        }

        .luxe-feature-card {
            overflow: hidden;
            height: 100%;
            border: 1px solid rgba(255, 255, 255, .70);
            border-radius: 36px;
            background: rgba(255, 255, 255, .72);
            box-shadow: var(--md-shadow-2);
            backdrop-filter: blur(18px) saturate(130%);
        }

        .news-page .luxe-feature-card,
        .news-page .luxe-listing-card {
            border-color: rgba(255, 255, 255, .76);
            background:
                linear-gradient(180deg, rgba(255, 255, 255, .84), rgba(255, 250, 242, .72)),
                rgba(255, 255, 255, .74);
        }

        .news-page .luxe-feature-card {
            border-radius: 26px;
        }

        .luxe-feature-media {
            position: relative;
            display: block;
            overflow: hidden;
            height: clamp(24rem, 42vw, 34rem);
            background: linear-gradient(135deg, #075c66, #d9a441);
            color: #fff;
        }

        .news-page .luxe-feature-media {
            height: clamp(12rem, 18vw, 16rem);
        }

        .news-page .luxe-feature-media::after,
        .news-page .luxe-listing-media::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, transparent 45%, rgba(3, 36, 43, .18));
            pointer-events: none;
        }

        .luxe-feature-media>img,
        .luxe-listing-media>img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .3s ease-out;
        }

        .luxe-feature-media>.material-symbols-rounded,
        .luxe-listing-media>.material-symbols-rounded,
        .luxe-feature-media>.cms-svg-icon,
        .luxe-listing-media>.cms-svg-icon {
            display: flex;
            width: 100%;
            height: 100%;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
        }

        .luxe-feature-card:hover img,
        .luxe-listing-card:hover img {
            transform: scale(1.045);
        }

        .luxe-feature-overlay,
        .luxe-view {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(4, 42, 51, .38);
            color: #fff;
            opacity: 0;
            transition: opacity .3s ease-out;
        }

        .luxe-feature-overlay span,
        .luxe-view {
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .luxe-feature-card:hover .luxe-feature-overlay,
        .luxe-listing-card:hover .luxe-view {
            opacity: 1;
        }

        .luxe-feature-copy {
            padding: 1.6rem;
        }

        .news-page .luxe-feature-copy {
            display: block !important;
            padding: 1.2rem 1.2rem 1.35rem;
        }

        .luxe-feature-copy>span,
        .luxe-location,
        .luxe-price {
            color: #9b6d1e;
            font-size: .74rem;
            font-weight: 850;
            letter-spacing: .14em;
            text-transform: uppercase;
        }

        .luxe-feature-copy h2,
        .luxe-listing-copy h3 {
            margin: .65rem 0 .75rem;
            font-weight: 650;
            letter-spacing: -.035em;
            line-height: 1.08;
        }

        .news-page .luxe-feature-copy h2 {
            display: -webkit-box;
            overflow: hidden;
            min-height: 2.7rem;
            font-size: 1.18rem;
            line-clamp: 2;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
        }

        .luxe-feature-copy a,
        .luxe-listing-copy a {
            color: var(--md-ink);
        }

        .luxe-feature-copy p {
            color: var(--md-muted);
            line-height: 1.75;
        }

        .news-page .luxe-feature-copy p,
        .news-page .luxe-listing-copy p {
            display: -webkit-box;
            overflow: hidden;
            color: var(--md-muted);
            line-height: 1.7;
            -webkit-box-orient: vertical;
        }

        .news-page .luxe-feature-copy p {
            margin-bottom: 1rem;
            font-size: .92rem;
            line-clamp: 3;
            -webkit-line-clamp: 3;
        }

        .news-page .luxe-listing-copy p {
            margin-bottom: 1rem;
            font-size: .92rem;
            line-clamp: 2;
            -webkit-line-clamp: 2;
        }

        .luxe-listing-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1.35rem;
        }

        .news-page .luxe-listing-grid {
            gap: 1.15rem;
        }

        .luxe-listing-card {
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, .70);
            border-radius: 30px;
            background: rgba(255, 255, 255, .74);
            box-shadow: var(--md-shadow-1);
            backdrop-filter: blur(18px) saturate(130%);
            transition: transform .3s ease-out, box-shadow .3s ease-out, border-color .3s ease-out;
        }

        .news-page .luxe-listing-card {
            border-radius: 26px;
        }

        .luxe-listing-card:hover {
            transform: translateY(-8px);
            border-color: rgba(217, 164, 65, .42);
            box-shadow: var(--md-shadow-3);
        }

        .news-page .luxe-listing-card:hover {
            transform: translateY(-6px);
        }

        .luxe-listing-media {
            position: relative;
            display: block;
            overflow: hidden;
            height: 20rem;
            background: linear-gradient(135deg, #075c66, #d9a441);
            color: #fff;
        }

        .news-page .luxe-listing-media {
            height: 14.5rem;
        }

        .luxe-listing-copy {
            padding: 1.35rem;
        }

        .news-page .luxe-listing-copy {
            padding: 1.15rem 1.2rem 1.25rem;
        }

        .luxe-listing-copy h3 {
            font-size: 1.28rem;
        }

        .news-page .luxe-listing-copy h3 {
            display: -webkit-box;
            overflow: hidden;
            min-height: 2.76rem;
            font-size: 1.16rem;
            -webkit-box-orient: vertical;
            line-clamp: 2;
            -webkit-line-clamp: 2;
        }

        .luxe-category-section {
            padding-bottom: clamp(3rem, 6vw, 5rem);
        }

        .luxe-category-section .col-md-4>div {
            min-height: 10rem;
            display: flex;
            align-items: end;
            padding: 1.3rem;
            border: 1px solid rgba(255, 255, 255, .65);
            border-radius: 28px;
            background:
                linear-gradient(135deg, rgba(4, 42, 51, .42), rgba(217, 164, 65, .24)),
                url("https://images.unsplash.com/photo-1519046904884-53103b34b206?auto=format&fit=crop&w=900&q=85") center/cover;
            color: #fff;
            font-size: 1.1rem;
            font-weight: 750;
            box-shadow: var(--md-shadow-1);
        }

        .news-page .luxe-category-section .col-md-4>div {
            min-height: 8rem;
            border-radius: 24px;
            background:
                linear-gradient(135deg, rgba(4, 42, 51, .58), rgba(15, 143, 147, .28), rgba(217, 164, 65, .20)),
                url("https://images.unsplash.com/photo-1519046904884-53103b34b206?auto=format&fit=crop&w=900&q=85") center/cover;
            box-shadow: var(--md-shadow-2);
        }

        .news-page .luxe-category-section .col-md-4:nth-child(2)>div {
            background:
                linear-gradient(135deg, rgba(4, 42, 51, .56), rgba(47, 127, 193, .28), rgba(217, 164, 65, .18)),
                url("https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=900&q=85") center/cover;
        }

        .news-page .luxe-category-section .col-md-4:nth-child(3)>div {
            background:
                linear-gradient(135deg, rgba(4, 42, 51, .58), rgba(15, 143, 147, .24), rgba(217, 164, 65, .22)),
                url("https://images.unsplash.com/photo-1494526585095-c41746248156?auto=format&fit=crop&w=900&q=85") center/cover;
        }

        .news-pagination .pagination {
            gap: .45rem;
            margin-bottom: 0;
        }

        .news-pagination .page-link {
            min-width: 2.6rem;
            border: 1px solid rgba(15, 143, 147, .16);
            border-radius: 999px;
            background: rgba(255, 255, 255, .72);
            color: var(--md-primary-dark);
            font-weight: 700;
            text-align: center;
            box-shadow: 0 10px 26px rgba(42, 75, 82, .07);
        }

        .news-pagination .page-link:hover,
        .news-pagination .page-item.active .page-link {
            border-color: rgba(15, 143, 147, .34);
            background: linear-gradient(135deg, var(--md-primary), var(--md-secondary));
            color: #fff;
        }

        @media (max-width: 991.98px) {
            .luxe-listing-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .news-accordion-strip {
                min-height: auto;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: .9rem;
                padding-top: 0;
            }

            .news-accordion-panel,
            .news-accordion-panel:first-child {
                min-height: 18rem;
            }

            .news-accordion-panel:hover,
            .news-accordion-panel:focus-within {
                transform: translateY(-4px);
            }

            .news-accordion-panel:hover .news-accordion-media img,
            .news-accordion-panel:focus-within .news-accordion-media img {
                transform: scale(1.035);
            }

            .luxe-listing-hero {
                min-height: auto;
            }

            .news-page .luxe-listing-hero {
                padding-block: 5rem 3.5rem;
            }
        }

        @media (max-width: 575.98px) {
            .luxe-listing-grid {
                grid-template-columns: 1fr;
            }

            .luxe-feature-media,
            .luxe-listing-media {
                height: 17rem;
            }

            .news-page .luxe-listing-hero h1 {
                font-size: clamp(2.35rem, 15vw, 3.4rem);
            }

            .news-page .luxe-filter-row {
                align-items: stretch;
                flex-direction: column;
                border-radius: 22px;
            }

            .news-accordion-strip {
                display: grid;
                grid-template-columns: 1fr;
                gap: .9rem;
                padding-top: 0;
            }

            .news-accordion-panel,
            .news-accordion-panel:first-child {
                min-height: 15.5rem;
                border-radius: 22px;
            }

            .news-accordion-panel:hover,
            .news-accordion-panel:focus-within {
                transform: none;
            }

            .news-accordion-panel:hover .news-accordion-media img,
            .news-accordion-panel:focus-within .news-accordion-media img {
                transform: none;
            }

            .news-accordion-panel:hover .news-accordion-content,
            .news-accordion-panel:focus-within .news-accordion-content {
                transform: none;
            }

            .news-accordion-link {
                position: relative;
                min-height: inherit;
            }

            .news-accordion-content {
                padding: 1rem;
            }

            .news-accordion-content strong {
                font-size: 1.16rem;
            }

            .news-accordion-summary {
                font-size: .9rem;
            }

            .news-page .luxe-feature-media,
            .news-page .luxe-listing-media {
                height: 14.5rem;
            }
        }

        .service-icon {
            width: 3rem;
            height: 3rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
            background: linear-gradient(135deg, #fff2ce, #d8fbf4);
            color: var(--md-primary-dark);
        }

        .service-icon .cms-svg-icon {
            width: 1.5rem;
            height: 1.5rem;
        }

        .service-list-shell {
            border: 1px solid rgba(217, 164, 65, .24);
            border-radius: 36px;
            background:
                radial-gradient(circle at 6% 12%, rgba(255, 216, 139, .32), transparent 18rem),
                radial-gradient(circle at 92% 4%, rgba(95, 197, 210, .28), transparent 20rem),
                linear-gradient(135deg, rgba(255, 252, 242, .96), rgba(226, 250, 246, .88));
            box-shadow: var(--md-shadow-2);
        }

        .service-list-shell-full {
            margin-right: 0;
            margin-left: 0;
            border-right: 0;
            border-left: 0;
            border-radius: 0;
        }

        .service-list-card {
            position: relative;
            display: flex;
            overflow: hidden;
            min-height: 15rem;
            border: 1px solid rgba(217, 164, 65, .22);
            border-radius: 28px;
            background: rgba(255, 255, 255, .94);
            box-shadow: var(--md-shadow-1);
            transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
        }

        .service-list-card:hover {
            transform: translateY(-6px);
            border-color: rgba(217, 164, 65, .48);
            box-shadow: var(--md-shadow-3);
        }

        .service-list-media {
            position: relative;
            display: block;
            flex: 0 0 38%;
            min-width: 7rem;
            align-self: stretch;
            background: linear-gradient(160deg, #075c66, #24b4bd, #f2c266);
            color: #fff;
        }

        .service-list-media img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }

        .service-list-media .material-symbols-rounded {
            display: flex;
            width: 100%;
            height: 100%;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
        }

        .service-list-body {
            display: flex;
            flex: 1;
            min-width: 0;
            flex-direction: column;
            padding: 1.35rem;
        }

        .service-list-label {
            margin-bottom: .75rem;
            color: #b47a18;
            font-size: .68rem;
            font-weight: 900;
            letter-spacing: .14em;
            text-transform: uppercase;
        }

        .service-list-body a {
            color: var(--md-primary-dark);
        }

        .service-list-body p {
            display: -webkit-box;
            overflow: hidden;
            line-clamp: 3;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            font-size: .92rem;
            line-height: 1.65;
        }

        .service-list-more {
            margin-top: auto;
            color: var(--md-primary);
            font-size: .84rem;
            font-weight: 800;
        }

        .news-list-shell {
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(148, 163, 184, .38);
            border-radius: 36px;
            background:
                radial-gradient(circle at 12% 0%, rgba(255, 231, 180, .42), transparent 22rem),
                linear-gradient(135deg, #fbfaf6, #d9e0e5 48%, #f7f4ed);
            box-shadow:
                0 24px 60px rgba(15, 23, 42, .13),
                inset 0 1px 0 rgba(255, 255, 255, .88);
        }

        .news-list-shell::before {
            content: "";
            position: absolute;
            inset: 0;
            pointer-events: none;
            background: linear-gradient(115deg, rgba(255, 255, 255, .38), transparent 28%, rgba(100, 116, 139, .10) 58%, transparent);
        }

        .news-masonry {
            position: relative;
            z-index: 1;
            column-count: 3;
            column-gap: 1.35rem;
        }

        .news-list-card {
            position: relative;
            display: flex;
            overflow: hidden;
            min-height: 13rem;
            border: 1px solid rgba(100, 116, 139, .24);
            border-radius: 28px;
            background:
                linear-gradient(135deg, rgba(255, 255, 255, .92), rgba(241, 245, 249, .78)),
                rgba(255, 255, 255, .86);
            box-shadow:
                0 12px 32px rgba(15, 23, 42, .10),
                inset 0 1px 0 rgba(255, 255, 255, .92);
            transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
        }

        .news-masonry-card {
            display: inline-block;
            width: 100%;
            min-height: 0;
            margin: 0 0 1.35rem;
            break-inside: avoid;
        }

        .news-list-card:hover {
            transform: translateY(-4px);
            border-color: rgba(71, 85, 105, .42);
            box-shadow:
                0 22px 46px rgba(15, 23, 42, .16),
                inset 0 1px 0 rgba(255, 255, 255, .96);
        }

        .news-list-media {
            position: relative;
            display: block;
            flex: 0 0 28%;
            min-width: 14rem;
            height: 15rem;
            background: linear-gradient(145deg, #475569, #cbd5e1);
        }

        .news-masonry-card:nth-child(3n + 1) .news-list-media {
            height: 19rem;
        }

        .news-masonry-card:nth-child(3n + 2) .news-list-media {
            height: 13rem;
        }

        .news-list-media img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            filter: saturate(.92) contrast(1.04);
        }

        .news-list-content {
            flex: 1;
            padding: 1.75rem;
        }

        .news-list-content a {
            color: #1f2937;
        }

        .news-list-badge,
        .news-list-date {
            display: inline-flex;
            align-items: center;
            min-height: 1.8rem;
            border-radius: 999px;
            font-size: .72rem;
            font-weight: 900;
            letter-spacing: .12em;
            text-transform: uppercase;
        }

        .news-list-badge {
            padding: .35rem .8rem;
            border: 1px solid rgba(71, 85, 105, .28);
            background: linear-gradient(135deg, #334155, #9aa6ad, #d9a441);
            color: #fff;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, .28);
        }

        .news-list-date {
            color: #64748b;
        }

        .news-list-content p {
            line-height: 1.8;
        }

        .news-load-more {
            min-width: 12rem;
            transition: transform .3s ease-out, opacity .3s ease-out, box-shadow .3s ease-out;
        }

        .news-load-more:hover {
            transform: translateY(-2px);
        }

        .news-load-more.is-loading {
            opacity: .72;
            pointer-events: none;
        }

        @media (max-width: 991.98px) {
            .news-masonry {
                column-count: 2;
            }
        }

        @media (max-width: 767.98px) {
            .news-masonry {
                column-count: 1;
            }

            .news-list-card {
                display: block;
            }

            .news-list-media {
                min-width: 0;
                height: 12rem;
            }
        }

        .content-panel {
            background: var(--md-surface);
            border: 1px solid var(--md-outline);
            border-radius: 32px;
            box-shadow: var(--md-shadow-2);
            backdrop-filter: blur(18px) saturate(124%);
        }

        .article-content {
            color: #2f3b47;
            font-size: 1.04rem;
            line-height: 1.88;
        }

        .article-detail-page .article-content {
            max-height: min(46vh, 28rem);
            overflow-y: auto;
            padding-right: .85rem;
            overscroll-behavior: contain;
            scrollbar-color: rgba(15, 143, 147, .38) rgba(15, 143, 147, .08);
            scrollbar-width: thin;
        }

        .article-detail-page .article-content::-webkit-scrollbar {
            width: .45rem;
        }

        .article-detail-page .article-content::-webkit-scrollbar-track {
            border-radius: 999px;
            background: rgba(15, 143, 147, .08);
        }

        .article-detail-page .article-content::-webkit-scrollbar-thumb {
            border-radius: 999px;
            background: linear-gradient(180deg, rgba(15, 143, 147, .48), rgba(217, 164, 65, .42));
        }

        .article-content img {
            display: block;
            width: 100%;
            max-width: 100%;
            max-height: clamp(18rem, 48vw, 30rem);
            height: auto;
            margin: 1.25rem auto;
            border-radius: .75rem;
            object-fit: contain;
        }

        .article-content figure,
        .article-content p {
            max-width: 100%;
        }

        .article-content figure {
            margin: 1.75rem 0;
        }

        .article-content figure img {
            margin: 0 auto;
        }

        .article-category-badge {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            margin-bottom: 1.1rem;
            padding: .55rem 1rem;
            border: 1px solid rgba(15, 143, 147, .18);
            border-radius: 999px;
            background: rgba(255, 255, 255, .72);
            color: var(--md-primary-dark);
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            box-shadow: 0 12px 28px rgba(42, 75, 82, .08);
            backdrop-filter: blur(14px) saturate(130%);
        }

        .article-category-badge::before {
            content: "";
            width: .45rem;
            height: .45rem;
            border-radius: 999px;
            background: linear-gradient(135deg, var(--md-tertiary), var(--md-primary));
            box-shadow: 0 0 0 .28rem rgba(15, 143, 147, .10);
        }

        .page-shell {
            padding-block: clamp(3rem, 6vw, 5.5rem);
            background:
                radial-gradient(circle at 12% 12%, rgba(255, 225, 163, .16), transparent 22rem),
                radial-gradient(circle at 88% 30%, rgba(125, 210, 228, .12), transparent 24rem),
                linear-gradient(180deg, #fffdf8 0%, #fffaf6 100%);
        }

        .page-hero {
            max-width: 860px;
            margin-bottom: clamp(2.5rem, 5vw, 4.5rem);
        }

        .page-hero.text-center {
            margin-inline: auto;
        }

        .page-hero h1,
        .article-hero h1 {
            margin-bottom: 1.25rem;
            font-size: clamp(2.1rem, 4.8vw, 4.6rem);
            font-weight: 700;
            letter-spacing: -.052em;
            line-height: 1;
        }

        .page-hero p {
            max-width: 720px;
            color: var(--md-muted);
            font-size: clamp(1.05rem, 2vw, 1.35rem);
            line-height: 1.75;
        }

        .lux-card {
            border: 1px solid rgba(255, 255, 255, .68);
            border-radius: 32px;
            background: rgba(255, 255, 255, .72);
            box-shadow: var(--md-shadow-1);
            backdrop-filter: blur(18px) saturate(130%);
            transition: transform .3s ease-out, box-shadow .3s ease-out, border-color .3s ease-out;
        }

        .lux-card:hover {
            transform: translateY(-4px);
            border-color: rgba(217, 164, 65, .36);
            box-shadow: var(--md-shadow-2);
        }

        .coastal-map {
            overflow: hidden;
            border-block: 1px solid rgba(255, 255, 255, .70);
            box-shadow: var(--md-shadow-2);
        }

        .article-hero {
            position: relative;
            max-width: 940px;
            margin: 0 auto clamp(1.8rem, 4vw, 3rem);
            padding: clamp(1.5rem, 4vw, 3rem) clamp(1rem, 4vw, 3.25rem);
            border: 1px solid rgba(255, 255, 255, .72);
            border-radius: clamp(1.5rem, 4vw, 2.4rem);
            background:
                radial-gradient(circle at 12% 20%, rgba(255, 218, 142, .30), transparent 16rem),
                radial-gradient(circle at 88% 18%, rgba(125, 210, 228, .22), transparent 17rem),
                rgba(255, 255, 255, .68);
            box-shadow: var(--md-shadow-2);
            text-align: center;
            backdrop-filter: blur(18px) saturate(128%);
        }

        .article-hero::after {
            content: "";
            position: absolute;
            left: 50%;
            bottom: -1px;
            width: min(16rem, 54%);
            height: 3px;
            border-radius: 999px;
            background: linear-gradient(90deg, transparent, rgba(217, 164, 65, .72), rgba(15, 143, 147, .58), transparent);
            transform: translateX(-50%);
        }

        .article-cover {
            overflow: hidden;
            height: clamp(16rem, 42vw, 28rem);
            margin-bottom: clamp(1.5rem, 3.5vw, 2.5rem);
            border: 1px solid rgba(255, 255, 255, .74);
            border-radius: clamp(1.35rem, 3vw, 2rem);
            background:
                linear-gradient(135deg, rgba(255, 255, 255, .62), rgba(238, 253, 249, .42)),
                rgba(255, 255, 255, .52);
            box-shadow: var(--md-shadow-2);
        }

        .article-cover img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .article-body {
            position: relative;
            padding: clamp(1.15rem, 3vw, 2rem);
            border: 1px solid rgba(255, 255, 255, .74);
            border-radius: clamp(1.25rem, 3vw, 2rem);
            background:
                linear-gradient(180deg, rgba(255, 255, 255, .86), rgba(255, 250, 242, .72)),
                var(--md-surface);
            box-shadow: var(--md-shadow-2);
            backdrop-filter: blur(18px) saturate(124%);
        }

        .article-body::before {
            content: "";
            position: absolute;
            inset: .75rem .75rem auto;
            height: 1px;
            border-radius: 999px;
            background: linear-gradient(90deg, transparent, rgba(15, 143, 147, .28), rgba(217, 164, 65, .34), transparent);
        }

        .article-meta {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: .7rem;
            color: var(--md-muted);
            font-size: .9rem;
        }

        .article-meta span {
            display: inline-flex;
            align-items: center;
            min-height: 2.05rem;
            padding: .35rem .82rem;
            border: 1px solid rgba(15, 143, 147, .10);
            border-radius: 999px;
            background: rgba(255, 255, 255, .56);
            box-shadow: 0 8px 20px rgba(42, 75, 82, .05);
        }

        @media (max-width: 575.98px) {
            .page-shell {
                padding-block: 2.25rem;
            }

            .article-hero {
                margin-bottom: 1.35rem;
                padding: 1.35rem 1rem;
                border-radius: 1.35rem;
            }

            .article-cover {
                height: 13.5rem;
                margin-bottom: 1.15rem;
                border-radius: 1.15rem;
            }

            .article-body {
                padding: 1rem;
                border-radius: 1.15rem;
            }

            .article-meta {
                gap: .5rem;
                font-size: .82rem;
            }

            .article-content {
                font-size: .98rem;
                line-height: 1.78;
            }

            .article-detail-page .article-content {
                max-height: 40vh;
                padding-right: .55rem;
            }

            .article-content img {
                max-height: 16rem;
                margin-block: 1.15rem;
                border-radius: .85rem;
            }
        }

        .landing-hero {
            position: relative;
            display: flex;
            min-height: clamp(24rem, 52vh, 34rem);
            align-items: center;
            overflow: hidden;
            background:
                linear-gradient(115deg, rgba(3, 39, 48, .68), rgba(15, 143, 147, .25), rgba(255, 208, 126, .20)),
                url("https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=2200&q=88") center/cover;
            background-color: #0d2330;
            background-position: center, center;
            background-repeat: no-repeat;
            background-size: auto, contain;
            color: #fff;
        }

        .landing-hero::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 76% 16%, rgba(255, 231, 175, .38), transparent 22rem),
                linear-gradient(180deg, rgba(2, 28, 34, .12), rgba(2, 28, 34, .36));
            pointer-events: none;
        }

        .landing-hero-content {
            position: relative;
            z-index: 2;
            max-width: 760px;
            padding-block: clamp(4.8rem, 9vw, 6.4rem);
        }

        .landing-hero-carousel {
            position: relative;
            min-height: clamp(24rem, 52vh, 34rem);
            color: #fff;
        }

        .landing-hero-carousel .carousel-inner,
        .landing-hero-carousel .carousel-item {
            min-height: clamp(24rem, 52vh, 34rem);
        }

        .landing-hero-carousel .carousel-control-prev,
        .landing-hero-carousel .carousel-control-next {
            z-index: 8;
            width: clamp(3rem, 8vw, 4.5rem);
            opacity: .92;
            pointer-events: auto;
            touch-action: manipulation;
        }

        .landing-hero-carousel .carousel-control-prev-icon,
        .landing-hero-carousel .carousel-control-next-icon {
            width: 2.65rem;
            height: 2.65rem;
            border-radius: 50%;
            background-color: rgba(13, 35, 48, .38);
            background-size: 52%;
            box-shadow: 0 12px 30px rgba(13, 35, 48, .22);
            backdrop-filter: blur(10px);
        }

        .landing-hero-slide {
            position: relative;
            display: flex;
            min-height: clamp(24rem, 52vh, 34rem);
            align-items: center;
            overflow: hidden;
            background-color: #0d2330;
            background-position: center;
            background-repeat: no-repeat;
            background-size: contain;
        }

        .landing-hero-slide::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                linear-gradient(115deg, rgba(3, 39, 48, .68), rgba(15, 143, 147, .25), rgba(255, 208, 126, .20)),
                radial-gradient(circle at 76% 16%, rgba(255, 231, 175, .38), transparent 22rem),
                linear-gradient(180deg, rgba(2, 28, 34, .12), rgba(2, 28, 34, .36));
            pointer-events: none;
        }

        .landing-kicker {
            display: inline-flex;
            margin-bottom: .85rem;
            color:rgb(255, 178, 168);
            font-size: .76rem;
            font-weight: 800;
            letter-spacing: .22em;
            text-transform: uppercase;
        }

        .landing-hero h1 {
            max-width: 760px;
            margin-bottom: 1rem;
            font-size: clamp(2.65rem, 6.4vw, 5.85rem);
            font-weight: 700;
            letter-spacing: -.06em;
            line-height: .96;
        }

        .landing-hero p {
            max-width: 42rem;
            margin-bottom: 1.65rem;
            color: rgba(255, 255, 255, .82);
            font-size: clamp(1.05rem, 1.75vw, 1.45rem);
            font-weight: 500;
            letter-spacing: .04em;
        }

        .scroll-indicator {
            position: absolute;
            bottom: 2rem;
            left: 50%;
            display: inline-flex;
            width: 1.7rem;
            height: 2.8rem;
            justify-content: center;
            border: 1px solid rgba(255, 255, 255, .62);
            border-radius: 999px;
            transform: translateX(-50%);
        }

        .scroll-indicator span {
            width: .35rem;
            height: .35rem;
            margin-top: .55rem;
            border-radius: 999px;
            background: #fff;
            animation: scrollDot 1.8s ease-out infinite;
        }

        .landing-page {
            position: relative;
            overflow-x: clip;
            background:
                radial-gradient(circle at 8% 9%, rgba(255, 225, 163, .18), transparent 22rem),
                radial-gradient(circle at 92% 28%, rgba(125, 210, 228, .16), transparent 24rem),
                radial-gradient(circle at 15% 72%, rgba(227, 87, 43, .06), transparent 22rem),
                linear-gradient(180deg, #fffdf8 0%, #fffaf6 48%, #fff8f2 100%);
        }

        .landing-section {
            padding-block: clamp(2.1rem, 3.6vw, 3.45rem);
        }

        .landing-section-heading {
            max-width: 680px;
            margin-bottom: 1.55rem;
        }

        .landing-feature-section .landing-section-heading,
        .home-gallery-section .landing-section-heading,
        .home-feedback-section .landing-section-heading {
            margin-right: auto;
            margin-left: auto;
            text-align: center;
        }

        .landing-section-heading h2 {
            color: #102b3a;
        }

        .landing-section-heading h2,
        .landing-about-title {
            font-size: clamp(1.75rem, 3.4vw, 3.1rem);
            font-weight: 650;
            letter-spacing: -.04em;
            line-height: 1.08;
        }

        .landing-page .landing-section-heading {
            max-width: 620px;
            margin-bottom: clamp(1.15rem, 2.2vw, 1.65rem);
        }

        .landing-page .landing-section-heading .landing-kicker,
        .landing-page .home-team-section .landing-kicker {
            margin-bottom: .55rem;
            font-size: .7rem;
            letter-spacing: .18em;
        }

        .landing-page .landing-section-heading h2,
        .landing-page .home-team-section h2 {
            margin-bottom: .75rem;
            font-size: clamp(1.55rem, 2.45vw, 2.45rem);
            font-weight: 650;
            letter-spacing: -.035em;
            line-height: 1.16;
        }

        .landing-page .home-team-section .align-items-lg-end {
            margin-bottom: clamp(1.4rem, 2.5vw, 2.25rem) !important;
        }

        .landing-feature-card {
            padding: 2rem;
            border: 1px solid rgba(255, 255, 255, .68);
            border-radius: 32px;
            background: rgba(255, 255, 255, .70);
            box-shadow: var(--md-shadow-1);
            backdrop-filter: blur(18px) saturate(130%);
            transition: transform .3s ease-out, box-shadow .3s ease-out, border-color .3s ease-out;
        }

        .landing-feature-card:hover {
            transform: translateY(-8px);
            border-color: rgba(217, 164, 65, .42);
            box-shadow: var(--md-shadow-3);
        }

        .landing-feature-card .material-symbols-rounded,
        .landing-feature-card .cms-feature-icon {
            display: inline-flex;
            width: 3.25rem;
            height: 3.25rem;
            align-items: center;
            justify-content: center;
            margin-bottom: 2rem;
            border-radius: 999px;
            background: linear-gradient(135deg, #fff1cf, #d7faf3);
            color: var(--md-primary-dark);
            font-size: 1.6rem;
        }

        .landing-feature-card .cms-feature-icon .cms-svg-icon {
            width: 1em;
            height: 1em;
        }

        .landing-feature-card h3 {
            margin-bottom: .8rem;
            font-size: 1.3rem;
            font-weight: 700;
            letter-spacing: -.02em;
        }

        .landing-feature-card p,
        .landing-copy {
            color: var(--md-muted);
            line-height: 1.85;
        }

        .landing-feature-section {
            position: relative;
            overflow-x: clip;
            max-width: none;
            padding-top: clamp(2.1rem, 3.5vw, 3.3rem);
            padding-bottom: clamp(1.7rem, 3vw, 2.5rem);
            padding-right: clamp(.75rem, 1.2vw, 1rem);
            padding-left: clamp(.75rem, 1.2vw, 1rem);
        }

        .about-home-carousel.landing-feature-carousel {
            margin-top: .85rem;
            padding-inline: 0;
        }

        .landing-feature-accordion {
            display: flex;
            overflow: hidden;
            min-height: clamp(18rem, 30vw, 21rem);
            border-radius: 28px;
            box-shadow: 0 18px 54px rgba(13, 35, 48, .14);
        }

        .landing-feature-item {
            min-width: 0;
            flex: 1 1 0;
            transition: flex .38s ease-out;
        }

        .landing-feature-accordion:hover .landing-feature-item {
            flex: .82 1 0;
        }

        .landing-feature-accordion .landing-feature-item:hover {
            flex: 1.7 1 0;
        }

        .landing-feature-carousel .landing-feature-card {
            position: relative;
            overflow: hidden;
            padding: 0;
            border: 0;
            border-radius: 0;
            background: #17374a;
            box-shadow: none;
            isolation: isolate;
        }

        .landing-feature-carousel .landing-feature-card:hover {
            border-color: transparent;
            box-shadow: none;
            transform: none;
        }

        .landing-feature-card-link {
            position: relative;
            display: flex;
            min-height: clamp(18rem, 30vw, 21rem);
            height: 100%;
            align-items: flex-end;
            color: #fff;
            text-decoration: none;
        }

        .landing-feature-card-link::after {
            content: "";
            position: absolute;
            inset: 0;
            z-index: 1;
            background:
                linear-gradient(180deg, rgba(13, 35, 48, .06) 0%, rgba(13, 35, 48, .36) 45%, rgba(13, 35, 48, .88) 100%),
                linear-gradient(135deg, rgba(217, 164, 65, .22), rgba(13, 35, 48, .12));
            pointer-events: none;
            transition: background .3s ease-out;
        }

        .landing-feature-bg {
            position: absolute;
            inset: 0;
            z-index: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--feature-bg, linear-gradient(135deg, #17374a, #d9a441));
            background-color: #17374a;
            background-position: center;
            background-repeat: no-repeat;
            background-size: contain;
            transition: transform .38s ease-out, filter .38s ease-out;
        }

        .landing-feature-card:hover .landing-feature-card-link::after {
            background:
                linear-gradient(180deg, rgba(13, 35, 48, .02) 0%, rgba(13, 35, 48, .28) 42%, rgba(13, 35, 48, .84) 100%),
                linear-gradient(135deg, rgba(217, 164, 65, .30), rgba(13, 35, 48, .10));
        }

        .landing-feature-card:hover .landing-feature-bg {
            filter: saturate(1.08) contrast(1.04);
        }

        .landing-feature-bg .cms-feature-icon {
            margin-bottom: 0;
            background: rgba(255, 255, 255, .18);
            color: rgba(255, 255, 255, .72);
            backdrop-filter: blur(8px);
        }

        .landing-feature-body {
            position: relative;
            z-index: 2;
            display: flex;
            width: 100%;
            flex-direction: column;
            justify-content: flex-end;
            padding: clamp(1rem, 1.8vw, 1.45rem);
            color: #fff;
            text-shadow: 0 2px 18px rgba(0, 0, 0, .30);
        }

        .landing-feature-body small {
            align-self: flex-start;
            margin-bottom: .7rem;
            padding: .35rem .65rem;
            border: 1px solid rgba(255, 255, 255, .28);
            border-radius: 999px;
            background: rgba(13, 35, 48, .38);
            color: rgba(255, 255, 255, .88);
            font-size: .72rem;
            font-weight: 800;
            backdrop-filter: blur(8px);
        }

        .landing-feature-body h3 {
            max-width: 18rem;
            color: #fff;
            font-size: clamp(1.1rem, 1.5vw, 1.45rem);
        }

        .landing-feature-body p {
            display: -webkit-box;
            overflow: hidden;
            margin-bottom: 0;
            max-width: 23rem;
            color: rgba(255, 255, 255, .84);
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
            line-clamp: 2;
            opacity: .9;
            transition: opacity .28s ease-out;
        }

        .landing-feature-accordion .landing-feature-item:not(:hover) .landing-feature-body p {
            opacity: 0;
        }

        .landing-feature-carousel .about-carousel-control {
            background: rgba(13, 35, 48, .18);
            color: var(--md-primary-dark);
        }

        .landing-feature-carousel .about-carousel-control:hover {
            background: rgba(217, 164, 65, .92);
            color: #fff;
        }

        .home-gallery-section {
            position: relative;
            padding-top: clamp(1.9rem, 3.2vw, 2.8rem);
            padding-bottom: clamp(2rem, 3.4vw, 3.15rem);
        }

        .home-gallery-section::before {
            content: "";
            position: absolute;
            inset: 8% auto auto 50%;
            width: min(54rem, 82vw);
            height: min(54rem, 82vw);
            border-radius: 50%;
            background: radial-gradient(circle, rgba(217, 164, 65, .12), transparent 68%);
            pointer-events: none;
            transform: translateX(-50%);
        }

        .home-gallery-section > * {
            position: relative;
            z-index: 1;
        }

        @media (max-width: 991.98px) {
            .landing-feature-accordion {
                flex-wrap: wrap;
                min-height: 0;
                border-radius: 0;
            }

            .landing-feature-item,
            .landing-feature-accordion:hover .landing-feature-item,
            .landing-feature-accordion .landing-feature-item:hover {
                flex: 0 0 50%;
            }
        }

        @media (max-width: 767.98px) {
            .landing-feature-carousel.d-md-none .landing-feature-item,
            .landing-feature-carousel.d-md-none .landing-feature-accordion:hover .landing-feature-item,
            .landing-feature-carousel.d-md-none .landing-feature-accordion .landing-feature-item:hover {
                flex: 0 0 100%;
            }

            .landing-feature-carousel.d-md-none .landing-feature-accordion {
                border-radius: 24px;
                box-shadow: 0 18px 48px rgba(13, 35, 48, .14);
            }

            .landing-feature-carousel.d-md-none .landing-feature-body p {
                opacity: .9;
            }

            .landing-gallery-mobile-carousel {
                margin-top: .75rem;
                padding-inline: 0;
            }

            .landing-gallery-mobile-carousel .landing-gallery-item {
                display: flex;
                height: clamp(15rem, 68vw, 18rem);
                margin: 0;
            }

            .about-home-carousel.d-md-none .about-carousel-control,
            .landing-feature-carousel.d-md-none .about-carousel-control,
            .landing-gallery-mobile-carousel .about-carousel-control {
                top: 50%;
                width: 2.6rem;
                height: 2.6rem;
                border-color: rgba(255, 255, 255, .42);
                background: rgba(13, 35, 48, .38);
                color: #fff;
                opacity: .92;
                z-index: 6;
                transform: translateY(-50%);
                box-shadow: 0 10px 24px rgba(13, 35, 48, .22);
            }

            .about-home-carousel.d-md-none .about-carousel-control:hover,
            .landing-feature-carousel.d-md-none .about-carousel-control:hover,
            .landing-gallery-mobile-carousel .about-carousel-control:hover {
                transform: translateY(-50%) scale(1.05);
            }

            .about-home-carousel.d-md-none .about-carousel-control.prev,
            .landing-feature-carousel.d-md-none .about-carousel-control.prev,
            .landing-gallery-mobile-carousel .about-carousel-control.prev {
                left: .55rem;
            }

            .about-home-carousel.d-md-none .about-carousel-control.next,
            .landing-feature-carousel.d-md-none .about-carousel-control.next,
            .landing-gallery-mobile-carousel .about-carousel-control.next {
                right: .55rem;
            }
        }

        @media (max-width: 575.98px) {

            .landing-hero-content {
                padding-block: 5.4rem 4.2rem;
            }

            .landing-hero h1 {
                font-size: clamp(2.25rem, 12vw, 3.65rem);
                letter-spacing: -.055em;
            }

            .landing-hero p {
                margin-bottom: 1.35rem;
                font-size: 1rem;
                line-height: 1.75;
            }

            .landing-feature-item,
            .landing-feature-accordion:hover .landing-feature-item,
            .landing-feature-accordion .landing-feature-item:hover {
                flex-basis: 100%;
            }

            .landing-feature-card-link {
                min-height: clamp(15.5rem, 70vw, 18rem);
            }

            .landing-feature-accordion .landing-feature-item:not(:hover) .landing-feature-body p {
                opacity: .9;
            }
        }

        .landing-about-image {
            overflow: hidden;
            border-radius: 42px;
            box-shadow: var(--md-shadow-3);
        }

        .landing-about-image img {
            display: block;
            width: 100%;
            min-height: 32rem;
            object-fit: cover;
        }

        .landing-copy {
            max-width: 560px;
            margin-block: 1.5rem 2rem;
            font-size: 1.06rem;
        }

        .landing-gallery {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-auto-rows: clamp(11.5rem, 17vw, 13.5rem);
            gap: .85rem;
        }

        .landing-gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 24px;
            background: #17374a;
            box-shadow: var(--md-shadow-1);
            isolation: isolate;
        }

        a.landing-gallery-item {
            display: flex;
            align-items: flex-end;
            color: #fff;
            text-decoration: none;
        }

        .landing-gallery-item.tall {
            grid-row: span 2;
        }

        .landing-gallery-item.wide {
            grid-column: span 2;
        }

        .landing-gallery-bg {
            position: absolute;
            inset: 0;
            z-index: -2;
            background: var(--gallery-bg, linear-gradient(135deg, #17374a, #d9a441));
            background-color: #17374a;
            background-position: center;
            background-repeat: no-repeat;
            background-size: contain;
            transition: transform .3s ease-out;
        }

        .landing-gallery-item::after {
            content: "";
            position: absolute;
            inset: 0;
            z-index: -1;
            background:
                linear-gradient(180deg, rgba(13, 35, 48, .05) 0%, rgba(13, 35, 48, .28) 42%, rgba(13, 35, 48, .86) 100%),
                linear-gradient(135deg, rgba(217, 164, 65, .18), rgba(13, 35, 48, .10));
            transition: background .3s ease-out;
        }

        .landing-gallery-item:hover .landing-gallery-bg {
            filter: saturate(1.08) contrast(1.04);
        }

        .landing-gallery-item:hover::after {
            background:
                linear-gradient(180deg, rgba(13, 35, 48, .02) 0%, rgba(13, 35, 48, .22) 40%, rgba(13, 35, 48, .82) 100%),
                linear-gradient(135deg, rgba(217, 164, 65, .26), rgba(13, 35, 48, .08));
        }

        .landing-gallery-content {
            display: flex;
            width: 100%;
            flex-direction: column;
            justify-content: flex-end;
            padding: clamp(1rem, 2vw, 1.5rem);
            text-shadow: 0 2px 18px rgba(0, 0, 0, .32);
        }

        .landing-gallery-content small {
            align-self: flex-start;
            margin-bottom: .6rem;
            padding: .3rem .6rem;
            border: 1px solid rgba(255, 255, 255, .28);
            border-radius: 999px;
            background: rgba(13, 35, 48, .38);
            color: rgba(255, 255, 255, .88);
            font-size: .72rem;
            font-weight: 800;
            backdrop-filter: blur(8px);
        }

        .landing-gallery-content strong {
            display: -webkit-box;
            overflow: hidden;
            margin-bottom: .45rem;
            color: #fff;
            font-size: clamp(1.05rem, 1.8vw, 1.45rem);
            line-height: 1.25;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
            line-clamp: 2;
        }

        .landing-gallery-content span {
            display: -webkit-box;
            overflow: hidden;
            color: rgba(255, 255, 255, .84);
            line-height: 1.65;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
            line-clamp: 2;
        }

        .reveal {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity .7s ease-out, transform .7s ease-out;
        }

        .reveal.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        @keyframes scrollDot {
            0% {
                opacity: 1;
                transform: translateY(0);
            }

            70% {
                opacity: .25;
                transform: translateY(1.1rem);
            }

            100% {
                opacity: 0;
                transform: translateY(1.1rem);
            }
        }

        @media (max-width: 991.98px) {
            .landing-gallery {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 575.98px) {

            .landing-hero,
            .landing-hero-carousel,
            .landing-hero-carousel .carousel-inner,
            .landing-hero-carousel .carousel-item,
            .landing-hero-slide {
                min-height: clamp(23.5rem, 86vh, 26rem);
            }

            .landing-hero-carousel .carousel-control-prev,
            .landing-hero-carousel .carousel-control-next {
                width: 3.25rem;
            }

            .landing-hero-carousel .carousel-control-prev-icon,
            .landing-hero-carousel .carousel-control-next-icon {
                width: 2.35rem;
                height: 2.35rem;
            }

            .landing-section {
                padding-block: 1.75rem;
            }

            .landing-section-heading {
                margin-bottom: 1.05rem;
            }

            .landing-section-heading h2,
            .landing-about-title {
                font-size: clamp(1.65rem, 8vw, 2.35rem);
                line-height: 1.08;
            }

            .landing-page .landing-section-heading h2,
            .landing-page .home-team-section h2 {
                font-size: clamp(1.38rem, 6vw, 1.9rem);
                letter-spacing: -.03em;
                line-height: 1.18;
            }

            .landing-page .landing-section-heading .landing-kicker,
            .landing-page .home-team-section .landing-kicker {
                margin-bottom: .45rem;
                font-size: .66rem;
            }

            .landing-gallery {
                display: block;
            }

            .landing-gallery-item {
                height: 14.25rem;
                margin-bottom: .8rem;
            }

            .landing-gallery-content {
                padding: 1rem;
            }

            .landing-gallery-content span {
                -webkit-line-clamp: 2;
                line-clamp: 2;
            }
        }

        .material-symbols-rounded {
            font-size: 1.2rem;
            vertical-align: -0.22em;
        }

        .footer-au {
            background:
                radial-gradient(circle at 18% 0%, rgba(217, 164, 65, .22), transparent 20rem),
                linear-gradient(135deg, #062f36, #075c66 52%, #12383b);
            color: rgba(255, 255, 255, .76);
        }

        .footer-au strong {
            color: #fff;
        }

        .footer-brand {
            display: inline-flex;
            align-items: center;
            gap: .75rem;
            color: #fff;
        }

        .footer-brand .brand-mark {
            display: inline-flex;
            width: 2.25rem;
            height: 2.25rem;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            background: linear-gradient(135deg, #f4c76b, var(--md-primary), #5ec5d2);
        }

        .footer-links,
        .footer-social {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .footer-links a,
        .footer-social a {
            color: rgba(255, 255, 255, .76);
            text-decoration: none;
            transition: color .3s ease-out, transform .3s ease-out;
        }

        .footer-links a:hover,
        .footer-social a:hover {
            color:rgb(255, 184, 168);
            transform: translateY(-1px);
        }

        .footer-social a {
            display: inline-flex;
            width: 2.25rem;
            height: 2.25rem;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255, 255, 255, .22);
            border-radius: 999px;
        }

        .footer-bottom {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 1rem;
            border-top: 1px solid rgba(255, 255, 255, .14);
            color: rgba(255, 255, 255, .58);
            font-size: .875rem;
        }

        .admin-entry {
            color: rgba(255, 255, 255, .26);
            font-size: 1rem;
            line-height: 1;
            text-decoration: none;
        }

        .admin-entry:hover {
            color: rgba(255, 255, 255, .5);
        }

        .brissy-header {
            background: #fff;
            box-shadow: 0 10px 30px rgba(23, 43, 54, .08);
            position: sticky;
            top: 0;
            z-index: 1040;
        }

        .brissy-header .navbar {
            min-height: 84px;
            background: #0d2330;
            box-shadow: 0 12px 32px rgba(13, 35, 48, .20);
        }

        .brissy-brand {
            color: #fff;
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: -.03em;
            text-decoration: none;
        }

        .brissy-brand:hover {
            color: #fff;
        }

        .brissy-brand .brand-mark {
            display: inline-flex;
            width: 2.65rem;
            height: 2.65rem;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #fff;
            color: #0d2330;
            box-shadow: 0 12px 24px rgba(0, 0, 0, .18);
        }

        .brissy-header .nav-link {
            color: rgba(255, 255, 255, .82);
            font-size: .96rem;
            font-weight: 700;
            border-radius: 0;
            padding: .5rem .85rem !important;
        }

        .brissy-header .nav-link:hover,
        .brissy-header .nav-link.active {
            color: #f4c76b;
            background: transparent;
        }

        .brissy-header .navbar-toggler {
            border-color: rgba(255, 255, 255, .35);
        }

        .brissy-header .navbar-toggler-icon {
            filter: invert(1) grayscale(1) brightness(2);
        }

        .brissy-meta-bar {
            border-bottom: 1px solid rgba(255, 255, 255, .10);
            background: #0d2330;
            color: rgba(255, 255, 255, .74);
            font-size: .9rem;
        }

        .brissy-meta-bar a {
            color: rgba(255, 255, 255, .74);
            text-decoration: none;
        }

        .brissy-meta-bar a:hover {
            color: #f4c76b;
        }

        .brissy-meta-bar .language-menu .dropdown-toggle {
            color: rgba(255, 255, 255, .88);
            background: rgba(255, 255, 255, .08);
            border: 1px solid rgba(255, 255, 255, .22);
        }

        .brissy-meta-bar .language-menu .dropdown-toggle:hover {
            color: #0d2330;
            background: #f4c76b;
            border-color: #f4c76b;
        }

        .brissy-meta-item {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
        }

        .brissy-social {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
        }

        .brissy-social a {
            display: inline-flex;
            width: 2rem;
            height: 2rem;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, .22);
            background: rgba(255, 255, 255, .08);
            color: rgba(255, 255, 255, .88);
            font-size: .88rem;
            font-weight: 800;
        }

        .brissy-social a:hover {
            border-color: #f4c76b;
            background: #f4c76b;
            color: #0d2330;
        }

        .brissy-footer {
            position: relative;
            z-index: 20;
            background: #0d2330;
            color: rgba(255, 255, 255, .72);
        }

        .brissy-footer h3,
        .brissy-footer strong {
            color: #fff;
        }

        .brissy-footer h3 {
            font-size: 1.25rem;
            font-weight: 800;
            margin-bottom: 1.25rem;
        }

        .brissy-footer p {
            line-height: 1.8;
        }

        .brissy-footer a {
            color: rgba(255, 255, 255, .72);
            text-decoration: none;
        }

        .brissy-footer a:hover {
            color: #fff;
        }

        .brissy-footer-links {
            display: grid;
            gap: .75rem;
        }

        .brissy-footer-contact {
            display: grid;
            gap: .85rem;
        }

        .brissy-footer-contact span {
            display: inline-flex;
            align-items: flex-start;
            gap: .55rem;
        }

        .brissy-footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, .12);
            color: rgba(255, 255, 255, .55);
            font-size: .9rem;
        }

        .footer-contact-card {
            position: relative;
            z-index: 21;
            margin-top: -5.25rem;
            margin-bottom: 3.5rem;
            padding: clamp(1.25rem, 3vw, 2rem);
            border: 1px solid rgba(255, 255, 255, .22);
            border-radius: 28px;
            background: #e3572b;
            box-shadow: 0 22px 55px rgba(13, 35, 48, .20);
            color: #fff;
        }

        .footer-contact-card-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            height: 100%;
            padding: 1rem;
        }

        .footer-contact-card-icon {
            display: inline-flex;
            width: 3.4rem;
            height: 3.4rem;
            flex: 0 0 3.4rem;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #fff;
            color: #e3572b;
            font-size: 1.25rem;
        }

        .footer-contact-card-label {
            margin-bottom: .25rem;
            color: rgba(255, 255, 255, .72);
            font-size: .9rem;
            font-weight: 700;
        }

        .footer-contact-card-value {
            margin: 0;
            color: #fff;
            font-weight: 800;
            line-height: 1.35;
        }

        .services-page {
            background: #f3f7f8;
        }

        .about-page {
            background: #fff;
        }

        .about-title-bar {
            padding-block: clamp(3.5rem, 7vw, 6rem);
            background:
                linear-gradient(120deg, rgba(13, 35, 48, .88), rgba(13, 35, 48, .72)),
                url("https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=1800&q=85") center/cover;
            color: #fff;
        }

        .about-title-bar h1 {
            margin-bottom: 1rem;
            font-size: clamp(2.4rem, 5vw, 4.5rem);
            font-weight: 800;
            letter-spacing: -.045em;
        }

        .about-section,
        .about-story-section,
        .about-muted-section {
            padding-block: clamp(4rem, 7vw, 6rem);
        }

        .about-story-section,
        .about-muted-section {
            background: #FFF8F2;
        }

        .about-section h2,
        .about-muted-section h2 {
            margin-bottom: 1.25rem;
            color: #102b3a;
            font-size: clamp(2rem, 4vw, 3.6rem);
            font-weight: 800;
            letter-spacing: -.055em;
            line-height: 1.05;
        }

        .about-copy {
            color: #4b5d67;
            font-size: 1.04rem;
            line-height: 1.85;
        }

        .about-image-card,
        .about-founder-card,
        .about-image-stack {
            overflow: hidden;
            border-radius: 30px;
            box-shadow: 0 22px 55px rgba(13, 35, 48, .14);
        }

        .about-image-card img,
        .about-founder-card img,
        .about-image-stack img {
            display: block;
            width: 100%;
            min-height: 30rem;
            object-fit: cover;
        }

        .about-image-stack {
            position: relative;
        }

        @media (max-width: 991.98px) {
            .about-split-row {
                --bs-gutter-x: 1rem;
                --bs-gutter-y: 1rem;
                flex-wrap: nowrap;
            }

            .about-split-col {
                flex: 0 0 50%;
                width: 50%;
            }

            .about-split-col .landing-kicker {
                margin-bottom: .45rem;
                font-size: .66rem;
            }

            .about-split-col h2 {
                margin-bottom: .75rem;
                font-size: clamp(1.2rem, 4.4vw, 1.7rem);
                line-height: 1.18;
            }

            .about-split-col .about-copy {
                max-height: clamp(12rem, 46vw, 18rem);
                overflow-y: auto;
                padding-right: .35rem;
                scrollbar-color: rgba(16, 43, 58, .28) transparent;
                scrollbar-width: thin;
            }

            .about-split-col .about-copy::-webkit-scrollbar {
                width: 6px;
            }

            .about-split-col .about-copy::-webkit-scrollbar-track {
                background: transparent;
            }

            .about-split-col .about-copy::-webkit-scrollbar-thumb {
                border-radius: 999px;
                background: rgba(16, 43, 58, .24);
            }

            .about-split-col .about-copy p {
                margin-bottom: .7rem;
                font-size: .92rem;
                line-height: 1.65;
            }

            .about-split-col .btn {
                margin-top: .15rem;
                padding: .45rem .9rem !important;
                font-size: .86rem;
            }

            .about-image-card img,
            .about-founder-card img,
            .about-image-stack img {
                min-height: clamp(14rem, 48vw, 20rem);
                object-fit: contain;
                background: #f3f7f8;
            }
        }

        @media (max-width: 575.98px) {
            .about-split-row {
                --bs-gutter-x: .75rem;
            }

            .about-split-col h2 {
                font-size: clamp(1rem, 5.2vw, 1.32rem);
            }

            .about-split-col .about-copy {
                max-height: clamp(10rem, 58vw, 15rem);
            }

            .about-split-col .about-copy p {
                font-size: .86rem;
                line-height: 1.55;
            }

            .about-image-card,
            .about-founder-card,
            .about-image-stack {
                border-radius: 20px;
            }

            .about-image-card img,
            .about-founder-card img,
            .about-image-stack img {
                min-height: clamp(11rem, 62vw, 15rem);
            }

            .about-stat-card {
                right: .65rem;
                bottom: .65rem;
                padding: .65rem .75rem;
            }

            .about-stat-card-secondary {
                left: .65rem;
            }
        }

        .about-stat-card {
            position: absolute;
            right: 1.25rem;
            bottom: 1.25rem;
            min-width: 8.5rem;
            padding: 1rem 1.25rem;
            border-radius: 18px;
            background: #e3572b;
            color: #fff;
            box-shadow: 0 16px 35px rgba(13, 35, 48, .18);
        }

        .about-stat-card-secondary {
            right: auto;
            left: 1.25rem;
            bottom: 1.25rem;
            background: #0d2330;
        }

        .about-stat-card strong {
            display: block;
            font-size: 1.8rem;
            line-height: 1;
        }

        .about-stat-card span {
            font-size: .9rem;
            opacity: .84;
        }

        .about-team-card,
        .about-feedback-card {
            height: 100%;
            min-height: 18rem;
            padding: 1.35rem;
            border: 1px solid #ead8c8;
            border-radius: 22px;
            background: #fff;
            box-shadow: 0 14px 32px rgba(104, 73, 45, .08);
            cursor: pointer;
            transition: transform .28s ease-out, box-shadow .28s ease-out, border-color .28s ease-out;
        }

        .home-team-section {
            position: relative;
            overflow: visible;
            max-width: 100%;
            overflow-x: clip;
            padding-block: clamp(2.1rem, 3.5vw, 3.25rem);
            background: transparent;
        }

        .home-team-section::before {
            content: "";
            position: absolute;
            inset: 0;
            pointer-events: none;
            background-image:
                linear-gradient(rgba(13, 35, 48, .010) 1px, transparent 1px),
                linear-gradient(90deg, rgba(13, 35, 48, .010) 1px, transparent 1px);
            background-size: 42px 42px;
            mask-image: linear-gradient(180deg, rgba(0, 0, 0, .16), transparent 62%);
        }

        .home-team-section .container {
            position: relative;
            z-index: 1;
        }

        .home-feedback-section {
            position: relative;
            z-index: 1;
            max-width: 100%;
            overflow-x: clip;
            padding-top: clamp(2rem, 3.4vw, 3.15rem);
            padding-bottom: clamp(3.75rem, 6vw, 5.5rem);
            background: transparent;
        }

        .home-feedback-section::before {
            content: "";
            position: absolute;
            right: 4%;
            bottom: 8%;
            width: min(28rem, 70vw);
            height: min(28rem, 70vw);
            border-radius: 50%;
            background: radial-gradient(circle, rgba(227, 87, 43, .10), transparent 68%);
            pointer-events: none;
        }

        .home-feedback-section > * {
            position: relative;
            z-index: 1;
        }

        .about-home-carousel .about-team-card,
        .about-home-carousel .about-feedback-card {
            display: flex;
            min-height: 20rem;
            flex-direction: column;
            justify-content: center;
        }

        .about-team-card {
            position: relative;
            display: flex;
            overflow: hidden;
            min-height: 20rem;
            flex-direction: column;
            justify-content: flex-end;
            padding: 0;
            isolation: isolate;
            color: #fff;
            background: #0d2330;
        }

        .about-team-card-bg,
        .about-team-card-shade,
        .about-team-placeholder {
            position: absolute;
            inset: 0;
        }

        .about-team-card-bg {
            z-index: -3;
            background-color: #0d2330;
            background-image:
                var(--team-bg, none),
                linear-gradient(135deg, #17374a, #e3572b);
            background-position: center, center;
            background-repeat: no-repeat;
            background-size: contain, cover;
            transition: transform .35s ease-out, filter .35s ease-out;
        }

        .about-team-card-shade {
            z-index: -2;
            background:
                linear-gradient(180deg, rgba(13, 35, 48, .22) 0%, rgba(13, 35, 48, .44) 44%, rgba(13, 35, 48, .86) 100%),
                linear-gradient(135deg, rgba(227, 87, 43, .34), rgba(13, 35, 48, .12));
            transition: background .28s ease-out;
        }

        .about-team-placeholder {
            z-index: -1;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, .18);
            font-size: 8rem;
        }

        .about-team-card-content {
            position: relative;
            z-index: 1;
            display: flex;
            min-height: inherit;
            flex-direction: column;
            justify-content: space-between;
            padding: 1rem;
        }

        .about-team-contact {
            align-self: flex-start;
            margin: 0;
            padding: .45rem .7rem;
            border: 1px solid rgba(255, 255, 255, .34);
            border-radius: 999px;
            background: rgba(13, 35, 48, .42);
            color: rgba(255, 255, 255, .92) !important;
            font-size: .76rem;
            font-weight: 800;
            backdrop-filter: blur(10px);
        }

        .about-home-carousel .about-team-card {
            text-align: center;
        }

        .about-team-card:hover,
        .about-home-carousel .about-feedback-card:hover {
            border-color: #e3572b;
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 22px 56px rgba(104, 73, 45, .16);
        }

        .about-home-carousel .about-feedback-card:hover {
            background: linear-gradient(180deg, #fff 0%, #FFF8F2 100%);
        }

        .about-team-card:hover .about-team-card-bg {
            filter: saturate(1.08) contrast(1.04);
        }

        .about-team-card:hover .about-team-card-shade {
            background:
                linear-gradient(180deg, rgba(13, 35, 48, .12) 0%, rgba(13, 35, 48, .34) 42%, rgba(13, 35, 48, .84) 100%),
                linear-gradient(135deg, rgba(227, 87, 43, .28), rgba(13, 35, 48, .08));
        }

        @media (max-width: 767.98px) {
            .about-team-card:hover,
            .about-home-carousel .about-feedback-card:hover {
                transform: translateY(-8px);
            }
        }

        .about-team-card h3,
        .about-feedback-card h3 {
            margin-bottom: .35rem;
            font-size: 1.05rem;
            font-weight: 800;
        }

        .about-team-card h3 {
            color: #fff;
            text-shadow: 0 2px 18px rgba(0, 0, 0, .34);
        }

        .about-feedback-card p,
        .about-feedback-card span {
            color: #63737c;
        }

        .about-home-carousel .about-feedback-card {
            position: relative;
            height: clamp(14.5rem, 22vw, 17.5rem) !important;
            min-height: 0;
            max-height: clamp(14.5rem, 22vw, 17.5rem);
            overflow: hidden;
            justify-content: flex-start;
            padding: 1.15rem;
            border-color: rgba(234, 216, 200, .82);
            background:
                radial-gradient(circle at 92% 8%, rgba(227, 87, 43, .10), transparent 7rem),
                linear-gradient(180deg, #fff 0%, #fffaf6 100%);
            text-align: left;
            z-index: 1;
        }

        .about-home-carousel .about-feedback-card:hover {
            border-color: rgba(227, 87, 43, .55);
            background:
                radial-gradient(circle at 92% 8%, rgba(227, 87, 43, .15), transparent 8rem),
                linear-gradient(180deg, #fff 0%, #fff6ee 100%);
            z-index: 2;
        }

        .about-feedback-user {
            display: flex;
            align-items: center;
            gap: .85rem;
            margin-bottom: 1.05rem;
            padding: .55rem .7rem .55rem .55rem;
            border: 1px solid rgba(227, 87, 43, .10);
            border-radius: 18px;
            background: rgba(255, 248, 242, .78);
        }

        .about-feedback-user-info {
            min-width: 0;
        }

        .about-feedback-user-info h3 {
            margin-bottom: .25rem;
            padding-right: 1.8rem;
            color: #102b3a;
            line-height: 1.2;
        }

        .about-feedback-user-info span {
            display: block;
            overflow: hidden;
            color: #8b6f5b;
            font-size: .88rem;
            font-weight: 700;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .about-home-carousel .about-feedback-card .about-feedback-content {
            flex: 1;
            min-height: 0;
            overflow-y: auto;
            padding-top: .95rem;
            padding-right: .35rem;
            border-top: 1px solid rgba(104, 73, 45, .10);
            margin-bottom: 0;
            color: #4b5c65;
            font-size: .95rem;
            overflow-wrap: anywhere;
            scrollbar-color: rgba(104, 73, 45, .28) transparent;
            scrollbar-width: thin;
            white-space: normal;
            line-height: 1.75;
            transition: color .2s ease-out;
        }

        .about-home-carousel .about-feedback-card .about-feedback-content::-webkit-scrollbar {
            width: 6px;
        }

        .about-home-carousel .about-feedback-card .about-feedback-content::-webkit-scrollbar-track {
            background: transparent;
        }

        .about-home-carousel .about-feedback-card .about-feedback-content::-webkit-scrollbar-thumb {
            border-radius: 999px;
            background: rgba(104, 73, 45, .24);
        }

        .about-home-carousel .about-feedback-card .about-feedback-content::-webkit-scrollbar-thumb:hover {
            background: rgba(104, 73, 45, .36);
        }

        .about-home-carousel .about-feedback-card:hover .about-feedback-content {
            color: #31434d;
        }

        .about-team-card span {
            color: rgba(255, 255, 255, .82);
            font-weight: 700;
        }

        .about-feedback-quote {
            display: inline-flex;
            width: 2.75rem;
            height: 2.75rem;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            border-radius: 50%;
            background: rgba(227, 87, 43, .10);
            color: #e3572b;
            font-size: 1.45rem;
        }

        .about-feedback-avatar {
            display: block;
            flex: 0 0 3.9rem;
            width: 3.9rem;
            height: 3.9rem;
            border: 3px solid #fff;
            border-radius: 50%;
            object-fit: contain;
            background: #fff;
            box-shadow: 0 10px 22px rgba(104, 73, 45, .15);
        }

        .about-feedback-avatar-placeholder {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(227, 87, 43, .10);
            color: #e3572b;
            font-size: 1.55rem;
        }

        .about-home-carousel {
            position: relative;
            overflow: visible;
            max-width: 100%;
            margin-top: -1rem;
            padding-block: 2rem 2.5rem;
            padding-inline: clamp(2.25rem, 5vw, 3.5rem);
            cursor: grab;
            touch-action: pan-y;
            user-select: none;
        }

        .about-home-carousel.is-dragging {
            cursor: grabbing;
        }

        .about-home-carousel .carousel-inner,
        .about-home-carousel .carousel-item {
            overflow: visible;
        }

        .about-home-carousel .carousel-item.active {
            will-change: transform;
        }

        .about-home-carousel.is-dragging .carousel-item.active {
            transition: none;
            transform: translate3d(var(--drag-x, 0px), 0, 0);
        }

        .about-home-carousel.is-dragging .about-team-card:hover,
        .about-home-carousel.is-dragging .about-feedback-card:hover {
            transform: none;
            box-shadow: 0 14px 32px rgba(104, 73, 45, .08);
        }

        .about-home-carousel.is-dragging .about-team-card:hover .about-team-card-bg {
            transform: none;
            filter: none;
        }

        .about-home-carousel .carousel-item .row {
            padding-block: 1.25rem;
        }

        .about-carousel-control {
            position: absolute;
            top: 50%;
            display: inline-flex;
            width: 2.35rem;
            min-width: 0;
            height: 2.35rem;
            align-items: center;
            justify-content: center;
            padding: 0;
            border: 1px solid rgba(13, 35, 48, .12);
            border-radius: 50%;
            background: rgba(13, 35, 48, .10);
            color: #fff;
            font-size: .95rem;
            opacity: 0;
            transform: translateY(-50%);
            backdrop-filter: blur(8px);
            box-shadow: none;
            transition: opacity .2s ease, background .2s ease, transform .2s ease;
        }

        .about-home-carousel:hover .about-carousel-control {
            opacity: 1;
        }

        .about-carousel-control:hover {
            background: rgba(227, 87, 43, .92);
            color: #fff;
            transform: translateY(-50%) scale(1.05);
        }

        .about-carousel-control.prev {
            left: 0;
        }

        .about-carousel-control.next {
            right: 0;
        }

        @media (max-width: 575.98px) {
            .about-home-carousel {
                margin-top: 0;
                padding-inline: 0;
                padding-block: .7rem 1rem;
            }

            .about-team-card {
                min-height: 20rem;
            }

            .about-team-card-content {
                padding: 1rem 3.1rem;
            }

            .about-home-carousel .about-feedback-card {
                height: clamp(17rem, 62vw, 20rem) !important;
                min-height: 0;
                max-height: clamp(17rem, 62vw, 20rem);
                padding-right: 3.1rem;
                padding-left: 3.1rem;
            }

            .about-feedback-user {
                margin-bottom: .85rem;
            }

            .about-home-carousel .carousel-item .row {
                padding-block: .75rem;
            }

            .about-carousel-control {
                top: 50%;
                width: 2.6rem;
                height: 2.6rem;
                border-color: rgba(255, 255, 255, .42);
                background: rgba(13, 35, 48, .38);
                color: #fff;
                opacity: .92;
                z-index: 6;
                transform: translateY(-50%);
                box-shadow: 0 10px 24px rgba(13, 35, 48, .22);
            }

            .about-carousel-control:hover {
                transform: translateY(-50%) scale(1.05);
            }

            .about-carousel-control.prev {
                left: .55rem;
            }

            .about-carousel-control.next {
                right: .55rem;
            }
        }

        .services-title-bar {
            position: relative;
            overflow: hidden;
            padding-block: clamp(3.5rem, 7vw, 6rem);
            background:
                linear-gradient(120deg, rgba(13, 35, 48, .88), rgba(13, 35, 48, .74)),
                url("https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1800&q=85") center/cover;
            color: #fff;
        }

        .services-title-bar::after {
            content: "";
            position: absolute;
            right: -8rem;
            bottom: -10rem;
            width: clamp(18rem, 38vw, 34rem);
            height: clamp(18rem, 38vw, 34rem);
            border-radius: 50%;
            background: radial-gradient(circle, rgba(217, 164, 65, .32), transparent 68%);
            pointer-events: none;
        }

        .services-title-bar .container {
            position: relative;
            z-index: 1;
            max-width: 980px;
        }

        .services-kicker {
            display: inline-flex;
            margin-bottom: .95rem;
            color: rgba(255, 255, 255, .68);
            font-size: .8rem;
            font-weight: 800;
            letter-spacing: .18em;
            text-transform: uppercase;
        }

        .services-title-bar h1 {
            margin-bottom: 1rem;
            font-size: clamp(2.4rem, 5vw, 4.5rem);
            font-weight: 800;
            letter-spacing: -.045em;
        }

        .services-title-bar p {
            max-width: 44rem;
            margin: 0 0 1.35rem;
            color: rgba(255, 255, 255, .78);
            font-size: clamp(1rem, 1.6vw, 1.18rem);
            line-height: 1.8;
        }

        .services-breadcrumb {
            display: inline-flex;
            align-items: center;
            gap: .75rem;
            color: rgba(255, 255, 255, .76);
            font-weight: 700;
        }

        .services-breadcrumb a {
            color: #fff;
            text-decoration: none;
        }

        .services-breadcrumb a::after {
            content: "/";
            margin-left: .75rem;
            color: rgba(255, 255, 255, .52);
        }

        .services-content {
            position: relative;
            padding-block: clamp(3rem, 6vw, 5.5rem);
            background:
                radial-gradient(circle at 8% 12%, rgba(217, 164, 65, .12), transparent 22rem),
                linear-gradient(180deg, #f8fbfb 0%, #f3f7f8 100%);
        }

        .services-section-head {
            display: flex;
            align-items: end;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: clamp(1.35rem, 3vw, 2.25rem);
        }

        .services-section-head span {
            color: rgba(16, 43, 58, .28);
            font-size: clamp(2rem, 5vw, 4.4rem);
            font-weight: 900;
            line-height: .82;
            letter-spacing: -.07em;
        }

        .services-section-head h2 {
            max-width: 34rem;
            margin: 0;
            color: #102b3a;
            font-size: clamp(1.55rem, 3vw, 2.45rem);
            font-weight: 850;
            letter-spacing: -.045em;
            text-align: right;
        }

        .service-card-stack {
            display: grid;
            gap: clamp(1.25rem, 2.8vw, 2rem);
        }

        .service-card {
            position: relative;
            display: grid;
            grid-template-columns: minmax(280px, 40%) 1fr;
            align-items: start;
            overflow: hidden;
            border: 1px solid #f1e3d8;
            border-radius: 30px;
            background:
                linear-gradient(135deg, rgba(255, 255, 255, .82), rgba(255, 248, 242, .92)),
                #fff8f2;
            box-shadow: 0 18px 48px rgba(104, 73, 45, .09);
            transition: transform .25s ease-out, box-shadow .25s ease-out, border-color .25s ease-out;
        }

        .service-card:nth-child(even) {
            grid-template-columns: 1fr minmax(280px, 40%);
        }

        .service-card:hover {
            transform: translateY(-4px);
            border-color: #e7d0bd;
            box-shadow: 0 22px 52px rgba(104, 73, 45, .12);
        }

        .service-card-media {
            position: relative;
            overflow: hidden;
            height: clamp(16rem, 24vw, 22rem);
            align-self: start;
            background:
                radial-gradient(circle at 24% 18%, rgba(255, 255, 255, .76), transparent 13rem),
                linear-gradient(135deg, #f6e7dc, #e8f0ef);
        }

        .service-card:nth-child(even) .service-card-media {
            order: 2;
        }

        .service-card-media img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: contain;
            object-position: center;
            padding: clamp(.65rem, 1.5vw, 1rem);
            transition: transform .32s ease-out;
        }

        .service-card:hover .service-card-media img {
            transform: scale(1.025);
        }

        .service-card-copy {
            display: flex;
            max-height: clamp(16rem, 24vw, 22rem);
            flex-direction: column;
            min-width: 0;
            overflow: hidden;
            padding: clamp(1.5rem, 3vw, 2.5rem);
        }

        .service-card-heading {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: .9rem;
            align-items: start;
            margin-bottom: 1rem;
        }

        .service-card-heading span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2.25rem;
            height: 2.25rem;
            border: 1px solid rgba(217, 164, 65, .32);
            border-radius: 50%;
            background: rgba(255, 255, 255, .72);
            color: #b87d27;
            font-size: .72rem;
            font-weight: 900;
            letter-spacing: .08em;
        }

        .service-card h2 {
            margin-bottom: 0;
            color: #102b3a;
            font-size: clamp(1.35rem, 2.4vw, 1.9rem);
            font-weight: 800;
            letter-spacing: -.035em;
        }

        .service-section-body {
            flex: 1;
            min-height: 0;
            overflow-y: auto;
            padding-right: .45rem;
            color: #415160;
            line-height: 1.78;
            scrollbar-color: rgba(16, 43, 58, .28) transparent;
            scrollbar-width: thin;
        }

        .service-section-body::-webkit-scrollbar {
            width: 6px;
        }

        .service-section-body::-webkit-scrollbar-track {
            background: transparent;
        }

        .service-section-body::-webkit-scrollbar-thumb {
            border-radius: 999px;
            background: rgba(16, 43, 58, .24);
        }

        .service-section-body::-webkit-scrollbar-thumb:hover {
            background: rgba(16, 43, 58, .36);
        }

        .service-section-body img {
            width: auto;
            max-width: min(100%, 32rem);
            max-height: 14rem;
            margin: 1rem 0;
            border-radius: 14px;
            object-fit: contain;
        }

        .service-section-body p {
            margin-bottom: 1rem;
        }

        .service-section-body ul {
            margin: .8rem 0 0;
            padding-left: 1.35rem;
        }

        .service-section-body li {
            margin-bottom: .55rem;
            line-height: 1.75;
        }

        @media (max-width: 991.98px) {
            .services-section-head {
                display: block;
            }

            .services-section-head h2 {
                margin-top: .35rem;
                text-align: left;
            }

            .service-card,
            .service-card:nth-child(even) {
                grid-template-columns: minmax(8.5rem, 38%) 1fr;
            }

            .service-card {
                border-radius: 24px;
            }

            .service-card-media {
                height: clamp(14rem, 42vw, 20rem);
            }

            .service-card:nth-child(even) .service-card-media {
                order: 0;
            }

            .service-card-copy {
                max-height: clamp(14rem, 42vw, 20rem);
            }
        }

        @media (max-width: 575.98px) {
            .service-card,
            .service-card:nth-child(even) {
                grid-template-columns: minmax(6.75rem, 38%) 1fr;
            }

            .service-card-media {
                height: clamp(12rem, 58vw, 16rem);
            }

            .service-card-copy {
                max-height: clamp(12rem, 58vw, 16rem);
                padding: 1rem;
            }

            .service-card-heading {
                gap: .55rem;
                margin-bottom: .65rem;
            }

            .service-card-heading span {
                width: 1.85rem;
                height: 1.85rem;
                font-size: .64rem;
            }

            .service-card h2 {
                font-size: clamp(1rem, 5.2vw, 1.25rem);
                line-height: 1.22;
            }

            .service-section-body {
                font-size: .9rem;
                line-height: 1.65;
            }
        }
    </style>
</head>

<body>
    <svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
        <symbol id="icon-facebook" viewBox="0 0 24 24"><path d="M14 8.5h2.4V5.1c-.4-.1-1.8-.2-3.3-.2-3.3 0-5.5 2-5.5 5.7v3.2H4v3.8h3.6V24H12v-6.4h3.5l.6-3.8H12v-2.9c0-1.1.3-1.9 2-1.9Z"/></symbol>
        <symbol id="icon-twitter-x" viewBox="0 0 24 24"><path d="M18.9 2h3.3l-7.3 8.3L23.4 22h-6.7l-5.2-6.8L5.6 22H2.2l7.8-8.9L1.8 2h6.9l4.7 6.2Zm-1.2 18h1.8L7.7 3.9H5.8Z"/></symbol>
        <symbol id="icon-instagram" viewBox="0 0 24 24"><path d="M7.3 2h9.4A5.3 5.3 0 0 1 22 7.3v9.4a5.3 5.3 0 0 1-5.3 5.3H7.3A5.3 5.3 0 0 1 2 16.7V7.3A5.3 5.3 0 0 1 7.3 2Zm0 2A3.3 3.3 0 0 0 4 7.3v9.4A3.3 3.3 0 0 0 7.3 20h9.4a3.3 3.3 0 0 0 3.3-3.3V7.3A3.3 3.3 0 0 0 16.7 4Zm4.7 3.5a4.5 4.5 0 1 1 0 9 4.5 4.5 0 0 1 0-9Zm0 2a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5Zm5-2.6a1.1 1.1 0 1 1 0 2.2 1.1 1.1 0 0 1 0-2.2Z"/></symbol>
        <symbol id="icon-pinterest" viewBox="0 0 24 24"><path d="M12.2 2C6.6 2 3 5.7 3 10.5c0 3 1.7 5.4 4.2 6.3.5.2.9 0 1-.5l.3-1.3c.1-.4.1-.5-.2-.9-.8-1-1.3-2.1-1.3-3.4 0-3.4 2.6-6.7 7-6.7 3.8 0 5.9 2.3 5.9 5.5 0 4.1-1.8 7.6-4.5 7.6-1.5 0-2.6-1.2-2.2-2.7.4-1.8 1.3-3.7 1.3-5 0-1.2-.6-2.1-1.9-2.1-1.5 0-2.7 1.5-2.7 3.6 0 1.3.4 2.2.4 2.2l-1.8 7.4c-.3 1.2-.2 2.5-.1 3.5h.1c.7-.9 1.5-2 1.8-3.2l1-4c.5 1 1.8 1.8 3.2 1.8 4.2 0 7.1-3.9 7.1-9.1C21.6 5.6 18.3 2 12.2 2Z"/></symbol>
        <symbol id="icon-linkedin" viewBox="0 0 24 24"><path d="M6.9 8.9H3.2V21h3.7ZM5 3a2.1 2.1 0 1 0 0 4.2A2.1 2.1 0 0 0 5 3Zm16 11.1c0-3.3-1.8-5.5-4.7-5.5-2.1 0-3.1 1.2-3.6 2v-1.7H9V21h3.7v-6c0-1.6.3-3.1 2.2-3.1s2 1.8 2 3.2V21H21Z"/></symbol>
        <symbol id="icon-mail" viewBox="0 0 24 24"><path d="M3 5h18a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Zm9 8.2L4.7 7H4v.8l8 6.8 8-6.8V7h-.7Z"/></symbol>
        <symbol id="icon-phone" viewBox="0 0 24 24"><path d="M6.6 10.8a15.5 15.5 0 0 0 6.6 6.6l2.2-2.2c.3-.3.8-.4 1.2-.2 1.3.4 2.6.7 4 .7.7 0 1.2.5 1.2 1.2v3.5c0 .7-.5 1.2-1.2 1.2A18.6 18.6 0 0 1 2.4 3.4c0-.7.5-1.2 1.2-1.2h3.5c.7 0 1.2.5 1.2 1.2 0 1.4.2 2.8.7 4 .1.4 0 .9-.3 1.2Z"/></symbol>
        <symbol id="icon-location" viewBox="0 0 24 24"><path d="M12 2a7 7 0 0 1 7 7c0 5.2-7 13-7 13S5 14.2 5 9a7 7 0 0 1 7-7Zm0 4.2A2.8 2.8 0 1 0 12 12a2.8 2.8 0 0 0 0-5.8Z"/></symbol>
        <symbol id="icon-home" viewBox="0 0 24 24"><path d="M12 3 3 10.5V21h6v-6h6v6h6V10.5Zm6.5 16H17v-6H7v6H5.5v-7.3L12 6.3l6.5 5.4Z"/></symbol>
        <symbol id="icon-user" viewBox="0 0 24 24"><path d="M12 12a5 5 0 1 0 0-10 5 5 0 0 0 0 10Zm0 2c-4.4 0-8 2.2-8 5v1.5c0 .8.7 1.5 1.5 1.5h13c.8 0 1.5-.7 1.5-1.5V19c0-2.8-3.6-5-8-5Z"/></symbol>
        <symbol id="icon-quote" viewBox="0 0 24 24"><path d="M9.3 5C5.8 6.8 4 9.3 4 12.7V19h7v-7H8.1c.1-1.8 1-3.2 3-4.2Zm9 0C14.8 6.8 13 9.3 13 12.7V19h7v-7h-2.9c.1-1.8 1-3.2 3-4.2Z"/></symbol>
        <symbol id="icon-arrow-left" viewBox="0 0 24 24"><path d="M14.8 5.3 8.1 12l6.7 6.7-1.4 1.4L5.3 12l8.1-8.1Z"/></symbol>
        <symbol id="icon-arrow-right" viewBox="0 0 24 24"><path d="m9.2 18.7 6.7-6.7-6.7-6.7 1.4-1.4 8.1 8.1-8.1 8.1Z"/></symbol>
        <symbol id="icon-article" viewBox="0 0 24 24"><path d="M6 2h9l5 5v15H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2Zm8 2.5V8h3.5ZM7 11h10v2H7Zm0 4h10v2H7Zm0-8h5v2H7Z"/></symbol>
        <symbol id="icon-beach" viewBox="0 0 24 24"><path d="M12 3c3.9.3 7 2.8 8.4 6.2l-8.9 3.2-3.8 8.4-1.8-.8 3.4-7.5L2 9.7C3.9 5.5 7.5 2.7 12 3Zm.8 2.2 1.6 4.4 3.3-1.2a8 8 0 0 0-4.9-3.2Zm-2.1.1a8.1 8.1 0 0 0-5.5 3.4l3.8 1.5Z"/></symbol>
        <symbol id="icon-building" viewBox="0 0 24 24"><path d="M4 21V3h16v18h-6v-5h-4v5Zm3-14v2h3V7Zm0 4v2h3v-2Zm0 4v2h3v-2Zm7-8v2h3V7Zm0 4v2h3v-2Zm0 4v2h3v-2Z"/></symbol>
        <symbol id="icon-kayak" viewBox="0 0 24 24"><path d="M3 15c4.5 3.8 13.5 3.8 18 0-1.3 4-4.6 6-9 6s-7.7-2-9-6Zm9-12 2.6 2.6-2.1 2.1 3.8 3.8 2.1-2.1L21 12l-1.4 1.4L10.6 4.4Zm-2 5a2 2 0 1 1-4 0 2 2 0 0 1 4 0Z"/></symbol>
        <symbol id="icon-waves" viewBox="0 0 24 24"><path d="M3 8.8c2.3-1.8 4.7-1.8 7 0 1.3 1 2.7 1 4 0 2.3-1.8 4.7-1.8 7 0v2.5c-2.3-1.8-4.7-1.8-7 0-1.3 1-2.7 1-4 0-2.3-1.8-4.7-1.8-7 0Zm0 6c2.3-1.8 4.7-1.8 7 0 1.3 1 2.7 1 4 0 2.3-1.8 4.7-1.8 7 0v2.5c-2.3-1.8-4.7-1.8-7 0-1.3 1-2.7 1-4 0-2.3-1.8-4.7-1.8-7 0Z"/></symbol>
        <symbol id="icon-support" viewBox="0 0 24 24"><path d="M12 3a8 8 0 0 1 8 8v3a3 3 0 0 1-3 3h-2v-6h3a6 6 0 0 0-12 0h3v6H7a3 3 0 0 1-3-3v-3a8 8 0 0 1 8-8Zm-2 14h4v2h2v2h-6Z"/></symbol>
        <symbol id="icon-clock" viewBox="0 0 24 24"><path d="M12 2a10 10 0 1 1 0 20 10 10 0 0 1 0-20Zm1 5h-2v6l5 3 1-1.7-4-2.3Z"/></symbol>
        <symbol id="icon-payments" viewBox="0 0 24 24"><path d="M3 6h18v12H3Zm2 4h14V8H5Zm0 6h5v-2H5Zm10-2a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"/></symbol>
    </svg>
    <div class="brissy-meta-bar py-2" data-header-meta>
        <div class="container d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-2">
            <div class="d-flex flex-wrap align-items-center gap-3">
                <span class="brissy-meta-item"><svg class="cms-svg-icon"><use href="#icon-mail"></use></svg>{{ $copy['main-contactEmail'] ?? 'contact@example.com' }}</span>
                <span class="brissy-meta-item"><svg class="cms-svg-icon"><use href="#icon-phone"></use></svg>{{ $copy['main-contactPhone'] ?? '123465789' }}</span>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-3">
                <div class="brissy-social" aria-label="{{ $copy['main-socialMedia'] ?? 'Follow on' }}">
                    <a href="{{ $copy['main-socialFacebookUrl'] ?? '#' }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $copy['main-socialFacebookLabel'] ?? 'Facebook' }}"><svg class="cms-svg-icon"><use href="#icon-facebook"></use></svg></a>
                    <a href="{{ $copy['main-socialXUrl'] ?? '#' }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $copy['main-socialXLabel'] ?? 'Twitter' }}"><svg class="cms-svg-icon"><use href="#icon-twitter-x"></use></svg></a>
                    <a href="{{ $copy['main-socialInstagramUrl'] ?? '#' }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $copy['main-socialInstagramLabel'] ?? 'Instagram' }}"><svg class="cms-svg-icon"><use href="#icon-instagram"></use></svg></a>
                    <a href="{{ $copy['main-socialPinterestUrl'] ?? '#' }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $copy['main-socialPinterestLabel'] ?? 'Pinterest' }}"><svg class="cms-svg-icon"><use href="#icon-pinterest"></use></svg></a>
                </div>
                <div class="dropdown language-menu d-flex align-items-center">
                    <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ $frontendLocales[$htmlLang] ?? 'English' }}
                    </button>
                    <ul class="dropdown-menu">
                        @foreach ($frontendLocales as $frontendLocale => $frontendLocaleName)
                        <li><button class="dropdown-item @if ($htmlLang === $frontendLocale) active @endif" type="button" data-locale-option="{{ $frontendLocale }}">{{ $frontendLocaleName }}</button></li>
                        @endforeach
                    </ul>
                </div>

            </div>
        </div>
    </div>
    <header class="brissy-header">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container">
                <a class="brissy-brand d-flex align-items-center gap-2" href="/">
                    <span class="brand-mark"><svg class="cms-svg-icon"><use href="#icon-home"></use></svg></span>
                    <span>{{ $copy['main-siteName'] ?? 'Brissy Breeze' }}</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#cmsNavbar" aria-controls="cmsNavbar" aria-expanded="false" aria-label="{{ $copy['main-navToggle'] ?? 'Toggle navigation' }}">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="cmsNavbar">
                    <ul class="navbar-nav ms-auto align-items-lg-center mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link @if (($activeNav ?? null) === 'home') active @endif" href="/">{{ $copy['main-home'] ?? 'Home' }}</a></li>

                        @foreach (($frontendCategories ?? []) as $frontendCategory)
                        <li class="nav-item"><a class="nav-link @if (($activeNav ?? null) === $frontendCategory->slug) active @endif" href="/{{ $frontendCategory->slug }}">{{ $copy['main-'.$frontendCategory->slug] ?? 'main-'.$frontendCategory->slug }}</a></li>
                        @endforeach
                        <li class="nav-item"><a class="nav-link @if (($activeNav ?? null) === 'about') active @endif" href="/about">{{ $copy['main-about'] ?? 'About' }}</a></li>
                        <li class="nav-item"><a class="nav-link @if (($activeNav ?? null) === 'contact') active @endif" href="/contact">{{ $copy['main-contact'] ?? 'Contact' }}</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <div class="site-main">
        @yield('content')
    </div>

    <footer class="brissy-footer pt-5 mt-auto">
        <div class="container">
            <div class="footer-contact-card">
                <div class="row g-3">
                    <div class="col-lg-4">
                        <div class="footer-contact-card-item">
                            <span class="footer-contact-card-icon"><svg class="cms-svg-icon"><use href="#icon-location"></use></svg></span>
                            <div>
                                <div class="footer-contact-card-label">{{ $copy['contact-address'] ?? 'Address' }}</div>
                                <p class="footer-contact-card-value">{{ $copy['contact-mapTitle'] ?? '6391 Elgin St, Delaware' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="footer-contact-card-item">
                            <span class="footer-contact-card-icon"><svg class="cms-svg-icon"><use href="#icon-mail"></use></svg></span>
                            <div>
                                <div class="footer-contact-card-label">{{ $copy['contact-email'] ?? 'Send Email' }}</div>
                                <p class="footer-contact-card-value">{{ $copy['main-contactEmail'] ?? 'contact@example.com' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="footer-contact-card-item">
                            <span class="footer-contact-card-icon"><svg class="cms-svg-icon"><use href="#icon-phone"></use></svg></span>
                            <div>
                                <div class="footer-contact-card-label">{{ $copy['contact-phone'] ?? 'Call Emergency' }}</div>
                                <p class="footer-contact-card-value">{{ $copy['main-contactPhone'] ?? '+88 0123 654 99' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container py-4">
            <div class="row g-5">
                <div class="col-lg-4">
                    <a class="brissy-brand d-inline-flex align-items-center gap-2 mb-3" href="/">
                        <span class="brand-mark"><svg class="cms-svg-icon"><use href="#icon-home"></use></svg></span>
                        <span>{{ $copy['main-siteName'] ?? 'Brissy Breeze' }}</span>
                    </a>
                    <p class="mb-4">{{ $copy['main-description'] ?? 'Nullam interdum libero vitae pretium aliquam donec nibh purus laoreet in ullamcorper vel malesuada sit amet enim.' }}</p>
                    <h3>{{ $copy['main-socialMedia'] ?? 'Follow on' }}</h3>
                    <div class="brissy-social">
                        <a href="{{ $copy['main-socialFacebookUrl'] ?? '#' }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $copy['main-socialFacebookLabel'] ?? 'Facebook' }}"><svg class="cms-svg-icon"><use href="#icon-facebook"></use></svg></a>
                        <a href="{{ $copy['main-socialXUrl'] ?? '#' }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $copy['main-socialXLabel'] ?? 'Twitter' }}"><svg class="cms-svg-icon"><use href="#icon-twitter-x"></use></svg></a>
                        <a href="{{ $copy['main-socialLinkedinUrl'] ?? '#' }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $copy['main-socialLinkedinLabel'] ?? 'LinkedIn' }}"><svg class="cms-svg-icon"><use href="#icon-linkedin"></use></svg></a>
                        <a href="{{ $copy['main-socialInstagramUrl'] ?? '#' }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $copy['main-socialInstagramLabel'] ?? 'Instagram' }}"><svg class="cms-svg-icon"><use href="#icon-instagram"></use></svg></a>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <h3>{{ $copy['main-quickLink'] ?? 'Quick Link' }}</h3>
                    <div class="brissy-footer-links">
                        <a href="/">{{ $copy['main-home'] ?? 'Home' }}</a>
                        <a href="/about">{{ $copy['main-about'] ?? 'Aboout Us' }}</a>
                        @foreach (($frontendCategories ?? []) as $frontendCategory)
                        <a href="/{{ $frontendCategory->slug }}">{{ $frontendCategory->name }}</a>
                        @endforeach
                        <a href="/contact">{{ $copy['main-contact'] ?? 'Contact Us' }}</a>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <h3>{{ $copy['main-contactInfo'] ?? 'Contact Info' }}</h3>
                    <div class="brissy-footer-contact">
                        <span><svg class="cms-svg-icon"><use href="#icon-phone"></use></svg>{{ $copy['main-contactPhone'] ?? '+123456789' }}</span>
                        <span><svg class="cms-svg-icon"><use href="#icon-mail"></use></svg>{{ $copy['main-contactEmail'] ?? 'email@domain.com' }}</span>
                        <span><svg class="cms-svg-icon"><use href="#icon-location"></use></svg>{{ $copy['contact-mapTitle'] ?? 'ABC Street, 123' }}</span>
                    </div>
                </div>
                <div class="col-lg-2">
                    <h3>{{ $copy['contact-hours'] ?? 'Opening Hour' }}</h3>
                    <p class="mb-0">{{ $copy['contact-hoursBody'] ?? 'Sunday-Fri: 9 AM - 6 PM' }}</p>
                    <a class="admin-entry d-inline-flex mt-4" href="/admin" aria-label="{{ $copy['main-portal'] ?? 'Client Portal' }}">
                        <svg class="cms-svg-icon"><use href="#icon-home"></use></svg>
                    </a>
                </div>
            </div>
        </div>
        <div class="brissy-footer-bottom py-3">
            <div class="container">
                © {{ date('Y') }}, {{ $copy['main-siteName'] ?? 'Brissy Breeze' }}. {{ $copy['main-footerCopyrightSuffix'] ?? 'All Rights Reserved' }}
            </div>
        </div>
    </footer>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
    <script>
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.14
        });

        document.querySelectorAll('.reveal').forEach((element) => {
            revealObserver.observe(element);
        });

        const headerMeta = document.querySelector('[data-header-meta]');
        const headerNavbar = document.getElementById('cmsNavbar');
        if (headerMeta && headerNavbar) {
            headerNavbar.addEventListener('show.bs.collapse', () => {
                headerMeta.classList.add('d-none');
            });
            headerNavbar.addEventListener('hide.bs.collapse', () => {
                headerMeta.classList.remove('d-none');
            });
        }

        const homeBannerCarousel = document.getElementById('homeBannerCarousel');
        if (homeBannerCarousel) {
            const homeBannerInstance = bootstrap.Carousel.getOrCreateInstance(homeBannerCarousel);
            homeBannerCarousel.querySelector('.carousel-control-prev')?.addEventListener('click', (event) => {
                event.preventDefault();
                homeBannerInstance.prev();
            });
            homeBannerCarousel.querySelector('.carousel-control-next')?.addEventListener('click', (event) => {
                event.preventDefault();
                homeBannerInstance.next();
            });
        }

        document.querySelectorAll('.about-home-carousel').forEach((carousel) => {
            const carouselInstance = bootstrap.Carousel.getOrCreateInstance(carousel, {
                interval: false,
                touch: false,
            });
            let startX = 0;
            let startY = 0;
            let currentX = 0;
            let dragging = false;
            let rafId = 0;

            const setDragX = (value) => {
                currentX = Math.max(-140, Math.min(140, value));
                if (rafId) {
                    return;
                }
                rafId = requestAnimationFrame(() => {
                    carousel.style.setProperty('--drag-x', `${currentX}px`);
                    rafId = 0;
                });
            };

            const resetDrag = (event) => {
                dragging = false;
                currentX = 0;
                carousel.classList.remove('is-dragging');
                carousel.style.setProperty('--drag-x', '0px');
                if (event?.pointerId !== undefined) {
                    carousel.releasePointerCapture?.(event.pointerId);
                }
            };

            carousel.addEventListener('pointerdown', (event) => {
                if (event.button !== undefined && event.button !== 0) {
                    return;
                }
                if (event.target.closest('.about-carousel-control')) {
                    return;
                }
                startX = event.clientX;
                startY = event.clientY;
                currentX = 0;
                dragging = true;
                carousel.classList.add('is-dragging');
                carousel.style.setProperty('--drag-x', '0px');
                carousel.setPointerCapture?.(event.pointerId);
            });

            carousel.addEventListener('pointermove', (event) => {
                if (! dragging) {
                    return;
                }
                const deltaX = event.clientX - startX;
                const deltaY = event.clientY - startY;
                if (Math.abs(deltaY) > Math.abs(deltaX) && Math.abs(deltaY) > 12) {
                    resetDrag(event);
                    return;
                }
                setDragX(deltaX * .72);
            });

            carousel.addEventListener('pointerup', (event) => {
                if (! dragging) {
                    return;
                }
                const deltaX = event.clientX - startX;
                const deltaY = event.clientY - startY;
                resetDrag(event);

                if (Math.abs(deltaX) < 60 || Math.abs(deltaX) < Math.abs(deltaY)) {
                    return;
                }

                deltaX < 0 ? carouselInstance.next() : carouselInstance.prev();
            });

            carousel.addEventListener('pointercancel', (event) => {
                resetDrag(event);
            });
        });

        const newsMasonry = document.querySelector('[data-news-masonry]');
        const newsLoadMore = document.querySelector('[data-news-load-more]');
        if (newsMasonry && newsLoadMore) {
            newsLoadMore.addEventListener('click', async () => {
                const currentPage = Number(newsMasonry.dataset.currentPage || '1');
                const lastPage = Number(newsMasonry.dataset.lastPage || '1');
                const nextPage = currentPage + 1;

                if (nextPage > lastPage) {
                    newsLoadMore.remove();
                    return;
                }

                const originalText = newsLoadMore.textContent;
                const loadingText = newsLoadMore.dataset.loadingText || 'Loading...';
                const loadFailedText = newsLoadMore.dataset.loadFailedText || 'Retry loading';
                newsLoadMore.classList.add('is-loading');
                newsLoadMore.textContent = loadingText;

                try {
                    const basePath = newsMasonry.dataset.basePath || window.location.pathname;
                    const query = new URLSearchParams({
                        page: String(nextPage)
                    });
                    if (newsMasonry.dataset.name) {
                        query.set('name', newsMasonry.dataset.name);
                    }
                    const response = await fetch(`${basePath}?${query.toString()}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                    });
                    const html = await response.text();
                    const doc = new DOMParser().parseFromString(html, 'text/html');
                    const cards = doc.querySelectorAll('[data-news-masonry] .news-masonry-card');

                    cards.forEach((card) => {
                        const importedCard = document.importNode(card, true);
                        importedCard.style.opacity = '0';
                        importedCard.style.transform = 'translateY(18px)';
                        newsMasonry.appendChild(importedCard);
                        requestAnimationFrame(() => {
                            importedCard.style.transition = 'opacity .45s ease-out, transform .45s ease-out';
                            importedCard.style.opacity = '1';
                            importedCard.style.transform = 'translateY(0)';
                        });
                    });

                    newsMasonry.dataset.currentPage = String(nextPage);
                    if (nextPage >= lastPage) {
                        newsLoadMore.remove();
                    }
                } catch (error) {
                    console.error(error);
                    newsLoadMore.textContent = loadFailedText;
                } finally {
                    newsLoadMore.classList.remove('is-loading');
                    if (document.body.contains(newsLoadMore) && newsLoadMore.textContent !== loadFailedText) {
                        newsLoadMore.textContent = originalText;
                    }
                }
            });
        }

        document.querySelectorAll('[data-locale-option]').forEach((button) => {
            button.addEventListener('click', () => {
                const locale = button.getAttribute('data-locale-option') || 'en';
                const maxAge = 60 * 60 * 24 * 365;
                document.cookie = `frontend_lang=${locale};path=/;max-age=${maxAge};SameSite=Lax`;
                window.location.reload();
            });
        });
    </script>
</body>

</html>