<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title id="pageTitle">Ezzy Homestay</title>
    <meta name="description" id="pageDescription"
        content="Ezzy Homestay, tempat singgah yang hangat dan asri. Segera hadir — reservasi awal dibuka sekarang, dapatkan harga spesial pra-pembukaan.">
    <meta name="theme-color" content="#3A3B2E">

    <!-- Open Graph -->
    <meta property="og:title" content="Ezzy Homestay">
    <meta property="og:description"
        content="Tempat singgah yang hangat, asri, dan menenangkan. Reservasi awal kini dibuka.">
    <meta property="og:image" content="{{ asset('assets') }}/img/brand/logo-landscape.png">
    <meta property="og:type" content="website">

    <link rel="icon" type="image/png" href="{{ asset('assets') }}/img/brand/favicon.png">
    <link rel="apple-touch-icon" href="{{ asset('assets') }}/img/brand/favicon.png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400;1,500&family=Jost:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Tailwind (Play CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        cream: {
                            50: '#FDFBF8',
                            100: '#FAF6EE',
                            200: '#F1E8D8'
                        },
                        olive: {
                            900: '#2A2B21',
                            800: '#33342A',
                            700: '#3F4033',
                            600: '#545545',
                            500: '#6B6C55',
                            400: '#8B8C71',
                            300: '#AEAF96',
                            200: '#D3D4C3',
                            100: '#E7E8DD'
                        },
                        clay: {
                            700: '#8A6A49',
                            600: '#A5815F',
                            500: '#BC9A78',
                            400: '#D3B594',
                            300: '#E7D3B9',
                            200: '#F3E7D6',
                            100: '#F9F1E7'
                        }
                    },
                    fontFamily: {
                        display: ['"Fraunces"', 'serif'],
                        sans: ['"Jost"', 'ui-sans-serif', 'sans-serif']
                    },
                    boxShadow: {
                        soft: '0 20px 45px -20px rgba(42, 43, 33, 0.25)',
                        card: '0 12px 30px -12px rgba(42, 43, 33, 0.18)'
                    }
                }
            }
        }
    </script>

    <style>
        :root {
            scroll-behavior: smooth;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background: #FAF6EE;
        }

        section[id] {
            scroll-margin-top: 88px;
        }

        /* Decorative background texture */
        .bg-grain {
            background-image: radial-gradient(circle at 1px 1px, rgba(84, 85, 69, 0.14) 1px, transparent 0);
            background-size: 22px 22px;
        }

        /* Reveal on scroll */
        .reveal {
            opacity: 0;
            transform: translateY(18px);
            transition: opacity .7s ease, transform .7s ease;
        }

        .reveal.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.001ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.001ms !important;
                scroll-behavior: auto !important;
            }

            .reveal {
                opacity: 1;
                transform: none;
            }
        }

        /* Custom focus ring */
        a:focus-visible,
        button:focus-visible,
        input:focus-visible,
        select:focus-visible,
        textarea:focus-visible {
            outline: 2px solid #A5815F;
            outline-offset: 3px;
            border-radius: 4px;
        }

        /* Countdown number ticking style */
        .countdown-box {
            font-variant-numeric: tabular-nums;
        }

        /* Pretty select arrow */
        select {
            -webkit-appearance: none;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='none' stroke='%23545545' stroke-width='1.6' viewBox='0 0 24 24'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.9rem center;
            background-size: 16px;
        }

        .leaf-decor {
            filter: drop-shadow(0 12px 25px rgba(42, 43, 33, 0.10));
        }

        /* Hero slider */
        .hero-slide {
            opacity: 0;
            transition: opacity 1.1s ease;
        }

        .hero-slide.is-active {
            opacity: 1;
        }

        .hero-slide-img {
            transition: opacity .6s ease;
        }

        .hero-slide-img.is-loaded {
            opacity: 1;
        }

        .slider-dot {
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: rgba(250, 246, 238, 0.35);
            transition: background .3s ease, width .3s ease;
        }

        .slider-dot.is-active {
            background: #E7D3B9;
            width: 22px;
        }

        @media (prefers-reduced-motion: reduce) {
            .hero-slide {
                transition: none;
            }
        }

        .nav-link {
            position: relative;
        }

        .nav-link::after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            bottom: -4px;
            height: 2px;
            background: #A5815F;
            transform: scaleX(0);
            transform-origin: left;
            transition: transform .3s ease;
        }

        .nav-link:hover::after,
        .nav-link.active::after {
            transform: scaleX(1);
        }

        /* Navbar scroll states */
        #navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 50;
            transition: background 0.35s ease, border-color 0.35s ease, box-shadow 0.35s ease, color 0.35s ease;
            background: linear-gradient(180deg, rgba(30, 31, 23, 0.8) 0%, rgba(30, 31, 23, 0.35) 60%, transparent 100%);
            
            color: #FAF6EE;
        }

        #navbar.is-scrolled,
        #navbar.is-menu-open {
            background: rgba(250, 246, 238, 0.95) !important;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom-color: rgba(42, 43, 33, 0.08);
            box-shadow: 0 4px 20px -5px rgba(42, 43, 33, 0.08);
            color: #545545;
        }

        #navbar .nav-link-item {
            color: #FAF6EE;
            transition: color 0.3s ease;
        }

        #navbar.is-scrolled .nav-link-item,
        #navbar.is-menu-open .nav-link-item {
            color: #545545;
        }

        #navbar .nav-btn {
            background-color: #A5815F;
            color: #FAF6EE;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        #navbar.is-scrolled .nav-btn,
        #navbar.is-menu-open .nav-btn {
            background-color: #33342A;
            color: #FAF6EE;
        }

        #navbar .menu-toggle-btn {
            color: #FAF6EE;
            transition: color 0.3s ease;
        }

        #navbar.is-scrolled .menu-toggle-btn,
        #navbar.is-menu-open .menu-toggle-btn {
            color: #33342A;
        }

        :root {
            scroll-behavior: smooth;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background: #FAF6EE;
        }

        section[id] {
            scroll-margin-top: 88px;
        }

        .bg-grain {
            background-image: radial-gradient(circle at 1px 1px, rgba(84, 85, 69, 0.14) 1px, transparent 0);
            background-size: 22px 22px;
        }

        .marquee-track {
            display: flex;
            width: max-content;
            animation: marquee 26s linear infinite;
        }

        .marquee-track:hover {
            animation-play-state: paused;
        }

        @keyframes marquee {
            from {
                transform: translateX(0);
            }

            to {
                transform: translateX(-50%);
            }
        }

        .reveal {
            opacity: 0;
            transform: translateY(18px);
            transition: opacity .7s ease, transform .7s ease;
        }

        .reveal.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.001ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.001ms !important;
                scroll-behavior: auto !important;
            }

            .reveal {
                opacity: 1;
                transform: none;
            }
        }

        a:focus-visible,
        button:focus-visible,
        input:focus-visible,
        select:focus-visible,
        textarea:focus-visible {
            outline: 2px solid #A5815F;
            outline-offset: 3px;
            border-radius: 4px;
        }

        select {
            -webkit-appearance: none;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='none' stroke='%23545545' stroke-width='1.6' viewBox='0 0 24 24'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.9rem center;
            background-size: 16px;
        }

        .gallery-media {
            transition: opacity .4s ease;
        }

        .gallery-photo {
            transition: opacity .5s ease;
        }

        .gallery-photo.is-loaded {
            opacity: 1;
        }

        .thumb-btn {
            transition: outline-color .2s ease, opacity .2s ease;
            outline: 2px solid transparent;
            outline-offset: 2px;
        }

        .thumb-btn.is-active {
            outline-color: #A5815F;
        }

        .thumb-btn:not(.is-active) {
            opacity: .75;
        }

        .thumb-btn:hover {
            opacity: 1;
        }

        #lightbox {
            transition: opacity .25s ease;
        }
    </style>
</head>
