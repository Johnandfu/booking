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
    <meta property="og:url" content="{{ $seoUrl ?? '' }}">
    @if (! empty($seoImage))
    <meta property="og:image" content="{{ $seoImage }}">
    @endif
    <meta name="twitter:card" content="@if (! empty($seoImage)) summary_large_image @else summary @endif">
    <meta name="twitter:title" content="{{ $seoTitle ?? ($copy['main-title'] ?? 'main-title') }}">
    <meta name="twitter:description" content="{{ $seoDescription ?? ($copy['main-description'] ?? 'main-description') }}">
    @if (! empty($seoImage))
    <meta name="twitter:image" content="{{ $seoImage }}">
    @endif
    @if (! empty($gaMeasurementId))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaMeasurementId }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', '{{ $gaMeasurementId }}');
    </script>
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0&display=swap" rel="stylesheet">
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
        }

        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }

        .site-main {
            flex: 1 0 auto;
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
            color: #ffe5a8;
            border-color: rgba(255, 229, 168, .72);
            transform: translateY(-1px);
        }

        .social-icon {
            width: 1rem;
            height: 1rem;
            display: block;
            fill: currentColor;
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

        .luxe-section-title {
            max-width: 780px;
            margin-bottom: 2.5rem;
        }

        .luxe-section-title h2 {
            font-size: clamp(2rem, 4vw, 4rem);
            font-weight: 650;
            letter-spacing: -.055em;
            line-height: 1;
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

        .luxe-feature-media {
            position: relative;
            display: block;
            overflow: hidden;
            height: clamp(24rem, 42vw, 34rem);
            background: linear-gradient(135deg, #075c66, #d9a441);
            color: #fff;
        }

        .luxe-feature-media>img,
        .luxe-listing-media>img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .3s ease-out;
        }

        .luxe-feature-media>.material-symbols-rounded,
        .luxe-listing-media>.material-symbols-rounded {
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

        .luxe-feature-copy a,
        .luxe-listing-copy a {
            color: var(--md-ink);
        }

        .luxe-feature-copy p {
            color: var(--md-muted);
            line-height: 1.75;
        }

        .luxe-listing-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1.35rem;
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

        .luxe-listing-card:hover {
            transform: translateY(-8px);
            border-color: rgba(217, 164, 65, .42);
            box-shadow: var(--md-shadow-3);
        }

        .luxe-listing-media {
            position: relative;
            display: block;
            overflow: hidden;
            height: 20rem;
            background: linear-gradient(135deg, #075c66, #d9a441);
            color: #fff;
        }

        .luxe-listing-copy {
            padding: 1.35rem;
        }

        .luxe-listing-copy h3 {
            font-size: 1.28rem;
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

        @media (max-width: 991.98px) {
            .luxe-listing-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .luxe-listing-hero {
                min-height: auto;
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

        .article-content img {
            max-width: 100%;
            height: auto;
            border-radius: 1rem;
        }

        .page-shell {
            padding-block: clamp(4rem, 7vw, 7rem);
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
            font-size: clamp(2.6rem, 6vw, 5.7rem);
            font-weight: 700;
            letter-spacing: -.065em;
            line-height: .96;
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
            max-width: 940px;
            margin: 0 auto clamp(2.5rem, 5vw, 4rem);
            text-align: center;
        }

        .article-cover {
            overflow: hidden;
            max-height: 34rem;
            margin-bottom: clamp(2rem, 5vw, 4rem);
            border-radius: 38px;
            box-shadow: var(--md-shadow-3);
        }

        .article-cover img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .article-panel {
            border-radius: 34px;
        }

        .landing-hero {
            position: relative;
            display: flex;
            min-height: calc(100vh - 92px);
            align-items: center;
            overflow: hidden;
            background:
                linear-gradient(115deg, rgba(3, 39, 48, .68), rgba(15, 143, 147, .25), rgba(255, 208, 126, .20)),
                url("https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=2200&q=88") center/cover;
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
            max-width: 760px;
            padding-block: 7rem;
        }

        .landing-kicker {
            display: inline-flex;
            margin-bottom: 1.25rem;
            color: #ffe5a8;
            font-size: .76rem;
            font-weight: 800;
            letter-spacing: .22em;
            text-transform: uppercase;
        }

        .landing-hero h1 {
            max-width: 760px;
            margin-bottom: 1rem;
            font-size: clamp(3.25rem, 8vw, 7.6rem);
            font-weight: 700;
            letter-spacing: -.07em;
            line-height: .92;
        }

        .landing-hero p {
            margin-bottom: 2rem;
            color: rgba(255, 255, 255, .82);
            font-size: clamp(1.2rem, 2.2vw, 1.75rem);
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
            background:
                radial-gradient(circle at 8% 8%, rgba(255, 225, 163, .24), transparent 22rem),
                radial-gradient(circle at 92% 24%, rgba(125, 210, 228, .22), transparent 24rem);
        }

        .landing-section {
            padding-block: clamp(4.5rem, 8vw, 8rem);
        }

        .landing-section-heading {
            max-width: 720px;
            margin-bottom: 3rem;
        }

        .landing-section-heading h2,
        .landing-about-title {
            font-size: clamp(2rem, 4.5vw, 4.25rem);
            font-weight: 650;
            letter-spacing: -.055em;
            line-height: 1;
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

        .landing-feature-card .material-symbols-rounded {
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
            grid-auto-rows: 15rem;
            gap: 1rem;
        }

        .landing-gallery-item {
            overflow: hidden;
            border-radius: 30px;
            box-shadow: var(--md-shadow-1);
        }

        .landing-gallery-item.tall {
            grid-row: span 2;
        }

        .landing-gallery-item.wide {
            grid-column: span 2;
        }

        .landing-gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .3s ease-out;
        }

        .landing-gallery-item:hover img {
            transform: scale(1.045);
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
            .landing-hero {
                min-height: calc(100vh - 130px);
            }

            .landing-gallery {
                display: block;
            }

            .landing-gallery-item {
                height: 16rem;
                margin-bottom: 1rem;
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
            color: #ffe5a8;
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
    </style>
</head>

<body>
    <div class="topbar py-2">
        <div class="container d-flex flex-column flex-md-row justify-content-between gap-2">
            <div class="d-flex flex-wrap align-items-center gap-3">

                <span><span class="material-symbols-rounded me-1">mail</span> {{ $copy['main-contactEmail'] ?? 'contact@example.com' }}</span>

                <span><span class="material-symbols-rounded me-1">call</span> {{ $copy['main-contactPhone'] ?? '+123 456 7890' }}</span>

            </div>
            <div class="d-flex flex-wrap align-items-center gap-3">
                <div class="topbar-social" aria-label="{{ $copy['main-socialMedia'] ?? 'main-socialMedia' }}">
                    @if (! empty($copy['main-socialInstagramUrl']))
                    <a href="{{ $copy['main-socialInstagramUrl'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $copy['main-socialInstagramLabel'] ?? 'main-socialInstagramLabel' }}"><span class="social-icon">IG</span></a>
                    @endif
                    @if (! empty($copy['main-socialFacebookUrl']))
                    <a href="{{ $copy['main-socialFacebookUrl'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $copy['main-socialFacebookLabel'] ?? 'main-socialFacebookLabel' }}"><span class="social-icon">FB</span></a>
                    @endif
                    @if (! empty($copy['main-socialLinkedinUrl']))
                    <a href="{{ $copy['main-socialLinkedinUrl'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $copy['main-socialLinkedinLabel'] ?? 'main-socialLinkedinLabel' }}"><span class="social-icon">IN</span></a>
                    @endif
                    @if (! empty($copy['main-socialYoutubeUrl']))
                    <a href="{{ $copy['main-socialYoutubeUrl'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $copy['main-socialYoutubeLabel'] ?? 'main-socialYoutubeLabel' }}"><span class="social-icon">YT</span></a>
                    @endif
                    @if (! empty($copy['main-socialXUrl']))
                    <a href="{{ $copy['main-socialXUrl'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $copy['main-socialXLabel'] ?? 'main-socialXLabel' }}"><span class="social-icon">X</span></a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg navbar-light navbar-au sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="/">
                <span class="brand-mark material-symbols-rounded">holiday_village</span>
                <span>{{ $copy['main-siteName'] ?? 'main-siteName' }}</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#cmsNavbar" aria-controls="cmsNavbar" aria-expanded="false" aria-label="{{ $copy['main-navToggle'] ?? 'main-navToggle' }}">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="cmsNavbar">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link @if (($activeNav ?? null) === 'home') active @endif" href="/">{{ $copy['main-home'] ?? 'main-home' }}</a></li>
                    @foreach (($frontendCategories ?? []) as $frontendCategory)
                    <li class="nav-item"><a class="nav-link @if (($activeNav ?? null) === $frontendCategory->slug) active @endif" href="/{{ $frontendCategory->slug }}">{{ $frontendCategory->name }}</a></li>
                    @endforeach
                    <li class="nav-item"><a class="nav-link @if (($activeNav ?? null) === 'about') active @endif" href="/about">{{ $copy['main-about'] ?? 'main-about' }}</a></li>
                    <li class="nav-item"><a class="nav-link @if (($activeNav ?? null) === 'contact') active @endif" href="/contact">{{ $copy['main-contact'] ?? 'main-contact' }}</a></li>
                    <li class="nav-item dropdown language-menu ms-lg-2 d-flex align-items-lg-center">
                        <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ $htmlLang ?? 'en' }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @foreach ($frontendLocales as $frontendLocale)
                            <li><button class="dropdown-item @if ($htmlLang === $frontendLocale) active @endif" type="button" data-locale-option="{{ $frontendLocale }}">{{ $frontendLocale }}</button></li>
                            @endforeach
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="site-main">
        @yield('content')
    </div>

    <footer class="footer-au py-5 mt-auto">
        <div class="container">
            <div class="d-flex flex-column flex-lg-row justify-content-between gap-4 align-items-lg-center">
                <a class="footer-brand text-decoration-none" href="/">
                    <span class="brand-mark material-symbols-rounded">holiday_village</span>
                    <strong>{{ $copy['main-siteName'] ?? 'main-siteName' }}</strong>
                </a>
                <div class="footer-links">
                    <a href="/">{{ $copy['main-home'] ?? 'main-home' }}</a>
                    <a href="/about">{{ $copy['main-about'] ?? 'main-about' }}</a>
                    <a href="/contact">{{ $copy['main-contact'] ?? 'main-contact' }}</a>
                </div>
                <div class="footer-social" aria-label="{{ $copy['main-socialMedia'] ?? 'main-socialMedia' }}">
                    @if (! empty($copy['main-socialInstagramUrl']))
                    <a href="{{ $copy['main-socialInstagramUrl'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $copy['main-socialInstagramLabel'] ?? 'main-socialInstagramLabel' }}"><span class="social-icon">IG</span></a>
                    @endif
                    @if (! empty($copy['main-socialFacebookUrl']))
                    <a href="{{ $copy['main-socialFacebookUrl'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $copy['main-socialFacebookLabel'] ?? 'main-socialFacebookLabel' }}"><span class="social-icon">FB</span></a>
                    @endif
                    @if (! empty($copy['main-socialLinkedinUrl']))
                    <a href="{{ $copy['main-socialLinkedinUrl'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $copy['main-socialLinkedinLabel'] ?? 'main-socialLinkedinLabel' }}"><span class="social-icon">IN</span></a>
                    @endif
                    @if (! empty($copy['main-socialYoutubeUrl']))
                    <a href="{{ $copy['main-socialYoutubeUrl'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $copy['main-socialYoutubeLabel'] ?? 'main-socialYoutubeLabel' }}"><span class="social-icon">YT</span></a>
                    @endif
                    @if (! empty($copy['main-socialXUrl']))
                    <a href="{{ $copy['main-socialXUrl'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $copy['main-socialXLabel'] ?? 'main-socialXLabel' }}"><span class="social-icon">X</span></a>
                    @endif
                </div>
            </div>
            <div class="footer-bottom mt-4 pt-4">
                <span>© {{ date('Y') }} {{ $copy['main-siteName'] ?? 'main-siteName' }}. {{ $copy['main-footerCopyrightSuffix'] ?? 'main-footerCopyrightSuffix' }}</span>
                <span>{{ $copy['main-footerServices'] ?? 'main-footerServices' }}</span>
                <a class="admin-entry" href="/admin" aria-label="{{ $copy['main-portal'] ?? 'main-portal' }}">
                    <span class="material-symbols-rounded">settings</span>
                </a>
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