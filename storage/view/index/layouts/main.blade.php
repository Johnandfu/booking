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
            min-height: clamp(28rem, 58vh, 40rem);
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
            position: relative;
            z-index: 2;
            max-width: 760px;
            padding-block: 7rem;
        }

        .landing-hero-carousel {
            position: relative;
            min-height: clamp(28rem, 58vh, 40rem);
            color: #fff;
        }

        .landing-hero-carousel .carousel-inner,
        .landing-hero-carousel .carousel-item {
            min-height: clamp(28rem, 58vh, 40rem);
        }

        .landing-hero-slide {
            position: relative;
            display: flex;
            min-height: clamp(28rem, 58vh, 40rem);
            align-items: center;
            overflow: hidden;
            background-position: center;
            background-size: cover;
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
            margin-bottom: 1.25rem;
            color:rgb(255, 178, 168);
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

        a.landing-gallery-item {
            display: block;
            color: inherit;
            text-decoration: none;
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

            .landing-hero,
            .landing-hero-carousel,
            .landing-hero-carousel .carousel-inner,
            .landing-hero-carousel .carousel-item,
            .landing-hero-slide {
                min-height: 28rem;
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
            background:
                radial-gradient(circle at 8% 12%, rgba(227, 87, 43, .10), transparent 20rem),
                linear-gradient(180deg, #FFF8F2 0%, #fff 100%);
        }

        .home-team-section::before {
            content: "";
            position: absolute;
            inset: 0;
            pointer-events: none;
            background-image:
                linear-gradient(rgba(13, 35, 48, .035) 1px, transparent 1px),
                linear-gradient(90deg, rgba(13, 35, 48, .035) 1px, transparent 1px);
            background-size: 42px 42px;
            mask-image: linear-gradient(180deg, rgba(0, 0, 0, .45), transparent 70%);
        }

        .home-team-section .container {
            position: relative;
            z-index: 1;
        }

        .home-feedback-section {
            max-width: 100%;
            overflow-x: clip;
            padding-top: clamp(4.5rem, 7vw, 6.5rem);
            padding-bottom: clamp(4.5rem, 7vw, 6.5rem);
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
            background:
                var(--team-bg, none) center/cover no-repeat,
                linear-gradient(135deg, #17374a, #e3572b);
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

        .about-home-carousel .about-feedback-card::before {
            content: "";
            display: block;
            width: 3rem;
            height: .22rem;
            margin-bottom: 1rem;
            border-radius: 999px;
            background: #e3572b;
            opacity: .72;
        }

        .about-team-card:hover,
        .about-home-carousel .about-feedback-card:hover {
            border-color: #e3572b;
            transform: translateY(-18px) scale(1.035);
            box-shadow: 0 28px 70px rgba(104, 73, 45, .20);
        }

        .about-home-carousel .about-feedback-card:hover {
            background: linear-gradient(180deg, #fff 0%, #FFF8F2 100%);
        }

        .about-team-card:hover .about-team-card-bg {
            transform: scale(1.08);
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

        .about-home-carousel .about-feedback-card p {
            display: -webkit-box;
            overflow: hidden;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 5;
            line-clamp: 5;
            line-height: 1.65;
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
            width: 4.5rem;
            height: 4.5rem;
            margin-bottom: 1rem;
            border: 4px solid #fff4ec;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 12px 28px rgba(104, 73, 45, .14);
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
                padding-block: 1.25rem 4.5rem;
            }

            .about-home-carousel .carousel-item .row {
                padding-block: .75rem;
            }

            .about-carousel-control {
                top: auto;
                bottom: 0;
                opacity: .75;
                transform: none;
            }

            .about-carousel-control:hover {
                transform: scale(1.05);
            }

            .about-carousel-control.prev {
                left: calc(50% - 7.75rem);
            }

            .about-carousel-control.next {
                right: calc(50% - 5.75rem);
            }
        }

        .services-title-bar {
            padding-block: clamp(3.5rem, 7vw, 6rem);
            background:
                linear-gradient(120deg, rgba(13, 35, 48, .88), rgba(13, 35, 48, .74)),
                url("https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1800&q=85") center/cover;
            color: #fff;
        }

        .services-title-bar h1 {
            margin-bottom: 1rem;
            font-size: clamp(2.4rem, 5vw, 4.5rem);
            font-weight: 800;
            letter-spacing: -.045em;
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
            padding-block: clamp(3rem, 6vw, 5.5rem);
        }

        .service-card {
            display: grid;
            grid-template-columns: minmax(280px, 38%) 1fr;
            overflow: hidden;
            border: 1px solid #f1e3d8;
            border-radius: 24px;
            background: #FFF8F2;
            box-shadow: 0 16px 38px rgba(104, 73, 45, .08);
            transition: transform .25s ease-out, box-shadow .25s ease-out, border-color .25s ease-out;
        }

        .service-card:hover {
            transform: translateY(-4px);
            border-color: #e7d0bd;
            box-shadow: 0 22px 52px rgba(104, 73, 45, .12);
        }

        .service-card-media {
            min-height: 100%;
            background: #f4e7dc;
        }

        .service-card-media img {
            display: block;
            width: 100%;
            height: 100%;
            min-height: 22rem;
            object-fit: cover;
        }

        .service-card-copy {
            padding: clamp(1.5rem, 3vw, 2.5rem);
        }

        .service-card h2 {
            margin-bottom: 1rem;
            color: #102b3a;
            font-size: clamp(1.35rem, 2.4vw, 1.9rem);
            font-weight: 800;
            letter-spacing: -.035em;
        }

        .service-section-body {
            color: #415160;
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
            .service-card {
                grid-template-columns: 1fr;
            }

            .service-card-media img {
                min-height: 15rem;
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