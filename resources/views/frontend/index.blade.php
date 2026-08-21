<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ezzy Homestay — Segera Hadir</title>
    <meta name="description"
        content="Ezzy Homestay, tempat singgah yang hangat dan asri. Segera hadir — reservasi awal dibuka sekarang, dapatkan harga spesial pra-pembukaan.">
    <meta name="theme-color" content="#3A3B2E">

    <!-- Open Graph -->
    <meta property="og:title" content="Ezzy Homestay — Segera Hadir">
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
            border-bottom: 1px solid transparent;
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
    </style>
</head>

<body class="font-sans text-olive-700 antialiased">

    <!-- Skip link -->
    <a href="#konten"
        class="sr-only focus:not-sr-only focus:fixed focus:top-2 focus:left-2 focus:z-[100] focus:bg-olive-900 focus:text-cream-50 focus:px-4 focus:py-2 focus:rounded">Langsung
        ke konten</a>

    <!-- ============ NAVBAR ============ -->
    <header id="navbar">
        <nav class="max-w-7xl mx-auto px-5 sm:px-8 flex items-center justify-between h-20" aria-label="Navigasi utama">
            <a href="#hero" class="flex items-center gap-2 shrink-0">
                <img src="{{ asset('assets') }}/img/brand/logo-icon.png" alt="Ezzy Homestay"
                    class="h-11 w-auto md:hidden">
                <img src="{{ asset('assets') }}/img/brand/logo-landscape.png" alt="Ezzy Homestay"
                    class="hidden md:block h-10 w-auto">
            </a>

            <ul class="hidden lg:flex items-center gap-9 text-[13px] tracking-[0.12em] uppercase">
                <li><a href="#tentang" class="nav-link nav-link-item" data-nav>Tentang</a></li>
                <li><a href="#kamar" class="nav-link nav-link-item" data-nav>Tipe Kamar</a></li>
                <li><a href="#fasilitas" class="nav-link nav-link-item" data-nav>Fasilitas</a></li>
                <li><a href="#harga" class="nav-link nav-link-item" data-nav>Harga</a></li>
                <li><a href="#countdown" class="nav-link nav-link-item" data-nav>Pembukaan</a></li>
            </ul>

            <div class="hidden lg:flex items-center gap-3">
                <a href="#booking"
                    class="nav-btn inline-flex items-center gap-2 text-[13px] tracking-[0.1em] uppercase px-5 py-3 rounded-full shadow-card">
                    Reservasi Awal
                </a>
            </div>

            <button id="menuBtn" class="menu-toggle-btn lg:hidden p-2" aria-label="Buka menu" aria-expanded="false"
                aria-controls="mobileMenu">
                <svg id="iconMenu" width="26" height="26" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.6" stroke-linecap="round">
                    <path d="M4 7h16M4 12h16M4 17h16" />
                </svg>
                <svg id="iconClose" class="hidden" width="26" height="26" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.6" stroke-linecap="round">
                    <path d="M6 6l12 12M18 6L6 18" />
                </svg>
            </button>
        </nav>

        <!-- Mobile menu -->
        <div id="mobileMenu" class="hidden lg:hidden bg-cream-100 border-t border-olive-200 px-5 pb-6">
            <ul class="flex flex-col gap-1 pt-4 text-sm tracking-[0.08em] uppercase text-olive-700">
                <li><a href="#tentang" class="block py-3 border-b border-olive-200/70" data-nav>Tentang</a></li>
                <li><a href="#kamar" class="block py-3 border-b border-olive-200/70" data-nav>Tipe Kamar</a></li>
                <li><a href="#fasilitas" class="block py-3 border-b border-olive-200/70" data-nav>Fasilitas</a></li>
                <li><a href="#harga" class="block py-3 border-b border-olive-200/70" data-nav>Harga</a></li>
                <li><a href="#countdown" class="block py-3 border-b border-olive-200/70" data-nav>Pembukaan</a></li>
                <li class="pt-4">
                    <a href="#booking"
                        class="block text-center bg-olive-800 text-cream-50 px-5 py-3 rounded-full">Reservasi Awal</a>
                </li>
            </ul>
        </div>
    </header>

    <main id="konten">

        <!-- ============ HERO SLIDER ============ -->
        <section id="hero" class="relative h-[150vh] min-h-[760px] max-h-[880px] overflow-hidden bg-olive-900"
            aria-roledescription="carousel" aria-label="Galeri fasilitas Ezzy Homestay">

            <!-- Slides -->
            <div id="heroSlider" class="absolute inset-0">
                @foreach ($data->facility as $index => $fac)
                    <div class="hero-slide absolute inset-0" data-index="{{ $index }}" data-name="{{ $fac['name'] }}"
                        aria-hidden="true">
                        <div class="absolute inset-0 bg-gradient-to-br from-olive-800 via-olive-700 to-clay-600"></div>
                        <img data-src="{{ asset('assets/img/facility/' . $fac['image']) }}" alt="{{ $fac['name'] }}"
                            class="hero-slide-img absolute inset-0 w-full h-full object-cover opacity-0">
                    </div>
                @endforeach
            </div>

            <!-- Legibility overlay -->
            <div
                class="pointer-events-none absolute inset-0 bg-gradient-to-t from-olive-900/95 via-olive-900/45 to-olive-900/25">
            </div>
            <div class="pointer-events-none absolute inset-0 bg-grain opacity-[0.05]"></div>

            <!-- Static hero content -->
            <div class="relative z-10 h-full flex flex-col items-center justify-center text-center px-5 sm:px-8">
                <span
                    class="inline-flex items-center gap-2 text-[12px] tracking-[0.3em] uppercase text-clay-200 bg-cream-50/10 border border-cream-50/25 backdrop-blur px-4 py-2 rounded-full">
                    <span class="relative flex h-2 w-2">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-clay-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-clay-400"></span>
                    </span>
                    Segera Hadir
                </span>

                <h1
                    class="font-display font-medium text-4xl sm:text-5xl lg:text-[3.4rem] leading-[1.12] text-cream-50 mt-6 max-w-2xl">
                    Nyaman Seperti di Rumah,<br class="hidden sm:block">
                    Hangat Seperti <em class="italic text-clay-300 font-normal">Keluarga</em>
                </h1>

                <p class="mt-5 text-cream-100/85 text-base sm:text-lg leading-relaxed max-w-md">
                    Ezzy Homestay hadir sebagai tempat singgah yang tenang dan asri — dirancang untuk siapa pun yang
                    ingin beristirahat dengan nyaman, sederhana, dan penuh kehangatan.
                </p>

                <div class="mt-9 flex flex-wrap items-center justify-center gap-4">
                    <a href="#booking"
                        class="inline-flex items-center gap-2 bg-clay-600 hover:bg-clay-700 text-cream-50 text-sm tracking-[0.08em] uppercase px-7 py-4 rounded-full transition-colors shadow-soft">
                        Reservasi Awal
                    </a>
                    <a href="#kamar"
                        class="inline-flex items-center gap-2 border border-cream-50/40 hover:border-cream-50 text-cream-50 text-sm tracking-[0.08em] uppercase px-7 py-4 rounded-full transition-colors">
                        Lihat Tipe Kamar
                    </a>
                </div>
            </div>

            <!-- Current facility caption -->
            <div class="absolute bottom-24 sm:bottom-28 left-5 sm:left-8 z-10 flex items-center gap-2 text-cream-50">
                <span class="h-px w-8 bg-clay-300"></span>
                <span class="text-[11px] sm:text-xs tracking-[0.2em] uppercase text-cream-100/90">Fasilitas —</span>
                <span id="slideCaption" class="text-[11px] sm:text-xs tracking-[0.2em] uppercase text-clay-300">{{ $data->facility[0]['name'] ?? '' }}</span>
            </div>

            <!-- Arrows -->
            <button id="sliderPrev" type="button" aria-label="Slide sebelumnya"
                class="hidden sm:flex items-center justify-center absolute left-4 md:left-6 top-1/2 -translate-y-1/2 z-10 h-11 w-11 rounded-full bg-cream-50/10 hover:bg-cream-50/20 border border-cream-50/25 backdrop-blur text-cream-50 transition-colors">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M15 18l-6-6 6-6" />
                </svg>
            </button>
            <button id="sliderNext" type="button" aria-label="Slide berikutnya"
                class="hidden sm:flex items-center justify-center absolute right-4 md:right-6 top-1/2 -translate-y-1/2 z-10 h-11 w-11 rounded-full bg-cream-50/10 hover:bg-cream-50/20 border border-cream-50/25 backdrop-blur text-cream-50 transition-colors">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 18l6-6-6-6" />
                </svg>
            </button>

            <!-- Dots -->
            <div id="sliderDots"
                class="absolute bottom-8 sm:bottom-9 left-1/2 -translate-x-1/2 z-10 flex items-center gap-2.5"></div>
        </section>

        <!-- Floating stats card, overlapping the slider -->
        {{-- <div class="relative z-20 -mt-12 sm:-mt-16 px-5 sm:px-8">
    <div class="max-w-3xl mx-auto bg-cream-50 border border-olive-100 rounded-[1.5rem] shadow-soft grid grid-cols-3 divide-x divide-olive-100 py-6 sm:py-8">
      <div class="text-center px-2">
        <div class="font-display text-2xl sm:text-3xl text-olive-900">3</div>
        <div class="text-[10px] sm:text-[11px] tracking-[0.12em] uppercase text-olive-500 mt-1">Tipe Kamar</div>
      </div>
      <div class="text-center px-2">
        <div class="font-display text-2xl sm:text-3xl text-olive-900">8+</div>
        <div class="text-[10px] sm:text-[11px] tracking-[0.12em] uppercase text-olive-500 mt-1">Fasilitas</div>
      </div>
      <div class="text-center px-2">
        <div class="font-display text-2xl sm:text-3xl text-olive-900">1</div>
        <div class="text-[10px] sm:text-[11px] tracking-[0.12em] uppercase text-olive-500 mt-1">Lokasi Strategis</div>
      </div>
    </div>
  </div> --}}

        <!-- ============ COUNTDOWN / COMING SOON ============ -->
        {{-- <section id="countdown" class="relative bg-olive-900 text-cream-100 py-20 md:py-28 overflow-hidden">
    <div class="pointer-events-none absolute inset-0 bg-grain opacity-[0.06]"></div>
    <div class="relative max-w-4xl mx-auto px-5 sm:px-8 text-center reveal">
      <p class="flex items-center justify-center gap-4 text-[12px] tracking-[0.35em] uppercase text-clay-300">
        <span class="h-px w-10 bg-clay-400/60"></span>
        Menuju Hari Pembukaan
        <span class="h-px w-10 bg-clay-400/60"></span>
      </p>
      <h2 class="font-display font-medium text-3xl sm:text-4xl md:text-5xl mt-5">Sebentar Lagi Kami Buka!</h2>
      <p class="text-olive-200 mt-4 max-w-xl mx-auto leading-relaxed">
        Jadilah yang pertama merasakan kenyamanan Ezzy Homestay. Daftarkan dirimu untuk mendapat kabar peluncuran dan promo spesial pra-pembukaan.
      </p>

      <div id="countdownGrid" class="mt-12 grid grid-cols-4 gap-3 sm:gap-6 max-w-2xl mx-auto">
        <div class="countdown-box bg-cream-50/5 border border-cream-50/15 rounded-2xl py-5 sm:py-8">
          <div id="cd-days" class="font-display text-3xl sm:text-5xl">00</div>
          <div class="text-[10px] sm:text-xs tracking-[0.2em] uppercase text-olive-300 mt-2">Hari</div>
        </div>
        <div class="countdown-box bg-cream-50/5 border border-cream-50/15 rounded-2xl py-5 sm:py-8">
          <div id="cd-hours" class="font-display text-3xl sm:text-5xl">00</div>
          <div class="text-[10px] sm:text-xs tracking-[0.2em] uppercase text-olive-300 mt-2">Jam</div>
        </div>
        <div class="countdown-box bg-cream-50/5 border border-cream-50/15 rounded-2xl py-5 sm:py-8">
          <div id="cd-minutes" class="font-display text-3xl sm:text-5xl">00</div>
          <div class="text-[10px] sm:text-xs tracking-[0.2em] uppercase text-olive-300 mt-2">Menit</div>
        </div>
        <div class="countdown-box bg-cream-50/5 border border-cream-50/15 rounded-2xl py-5 sm:py-8">
          <div id="cd-seconds" class="font-display text-3xl sm:text-5xl">00</div>
          <div class="text-[10px] sm:text-xs tracking-[0.2em] uppercase text-olive-300 mt-2">Detik</div>
        </div>
      </div>

      <form id="notifyForm" class="mt-12 flex flex-col sm:flex-row items-center justify-center gap-3 max-w-md mx-auto" novalidate>
        <label for="notifyEmail" class="sr-only">Alamat email</label>
        <input id="notifyEmail" type="email" required placeholder="Alamat email kamu"
          class="w-full sm:flex-1 bg-cream-50/10 border border-cream-50/25 placeholder:text-olive-300 text-cream-50 rounded-full px-5 py-3.5 text-sm focus:bg-cream-50/15">
        <button type="submit" class="w-full sm:w-auto shrink-0 bg-clay-600 hover:bg-clay-700 text-cream-50 text-sm tracking-[0.08em] uppercase px-6 py-3.5 rounded-full transition-colors">
          Ingatkan Saya
        </button>
      </form>
      <p id="notifyMsg" class="mt-4 text-sm text-clay-300 h-5" role="status" aria-live="polite"></p>
    </div>
  </section> --}}

        <!-- ============ TENTANG KAMI ============ -->
        <section id="tentang" class="pt-16 md:pt-24 pb-20 md:pb-28 bg-cream-100">
            <div class="max-w-7xl mx-auto px-5 sm:px-8 grid md:grid-cols-2 gap-14 items-center">
                <div class="reveal order-2 md:order-1 relative">
                    <div
                        class="relative rounded-[2rem] bg-clay-100 border border-clay-200 aspect-[4/5] max-w-sm mx-auto md:mx-0 flex items-center justify-center p-14 shadow-soft">
                        <img src="{{ asset('assets') }}/img/brand/logo-icon.png" alt=""
                            class="leaf-decor w-full h-auto opacity-90">
                    </div>
                </div>

                <div class="reveal order-1 md:order-2">
                    <p class="flex items-center gap-4 text-[12px] tracking-[0.3em] uppercase text-clay-600">
                        <span class="h-px w-10 bg-clay-400"></span> Tentang Kami
                    </p>
                    <h2 class="font-display font-medium text-3xl sm:text-4xl text-olive-900 mt-4 leading-tight">
                        Sepotong Ketenangan di Tengah Kesibukan
                    </h2>
                    <p class="text-olive-600 mt-5 leading-relaxed">
                        Ezzy Homestay lahir dari keinginan sederhana: menghadirkan tempat singgah yang terasa seperti
                        rumah sendiri. Dengan sentuhan desain alami dan pelayanan yang hangat, kami ingin setiap tamu
                        pulang dengan perasaan lebih tenang dari saat mereka datang.
                    </p>

                    <div class="mt-10 grid sm:grid-cols-1 gap-6">
                        <div class="flex gap-4">
                            <div
                                class="shrink-0 h-11 w-11 rounded-xl bg-olive-100 flex items-center justify-center text-olive-700">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M12 3c-1 3-4 4-4 8a4 4 0 0 0 8 0c0-4-3-5-4-8Z" />
                                    <path d="M12 21v-6" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-display text-lg text-olive-900">Nuansa Alami</h3>
                                <p class="text-sm text-olive-500 mt-1 leading-relaxed">Desain interior terinspirasi
                                    alam yang menghadirkan ketenangan di setiap sudut ruang.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div
                                class="shrink-0 h-11 w-11 rounded-xl bg-olive-100 flex items-center justify-center text-olive-700">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M12 21s7-6.5 7-12a7 7 0 1 0-14 0c0 5.5 7 12 7 12Z" />
                                    <circle cx="12" cy="9" r="2.5" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-display text-lg text-olive-900">Lokasi Strategis</h3>
                                <p class="text-sm text-olive-500 mt-1 leading-relaxed">Akses mudah menuju pusat kota
                                    dan area wisata favorit, cocok untuk semua jenis perjalanan.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div
                                class="shrink-0 h-11 w-11 rounded-xl bg-olive-100 flex items-center justify-center text-olive-700">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2" />
                                    <circle cx="10" cy="7" r="4" />
                                    <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-display text-lg text-olive-900">Layanan Personal</h3>
                                <p class="text-sm text-olive-500 mt-1 leading-relaxed">Tim kami siap membantu selama
                                    masa tinggal Anda, dari kedatangan hingga kepulangan.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============ TIPE KAMAR ============ -->
        <section id="kamar" class="py-20 md:py-28 bg-cream-200/60">
            <div class="max-w-7xl mx-auto px-5 sm:px-8">
                <div class="max-w-2xl reveal">
                    <p class="flex items-center gap-4 text-[12px] tracking-[0.3em] uppercase text-clay-600">
                        <span class="h-px w-10 bg-clay-400"></span> Tipe Kamar
                    </p>
                    <h2 class="font-display font-medium text-3xl sm:text-4xl text-olive-900 mt-4 leading-tight">
                        Pilih Kenyamanan yang Sesuai untuk Anda
                    </h2>
                    <p class="text-olive-600 mt-4 leading-relaxed">
                        Setiap kamar dirancang dengan detail, memadukan kenyamanan modern dan kehangatan rumah.
                    </p>
                </div>

                <div class="mt-14 grid md:grid-cols-3 gap-8">

                    <!-- Kamar Standard -->
                    @php
                        $i = 0;
                    @endphp
                    @foreach ($data->rooms as $r)
                        <article
                            class="reveal group {{ $i == 1 ? 'bg-olive-900 text-cream-100' : 'bg-cream-50' }} rounded-[1.75rem] border border-olive-100 overflow-hidden shadow-card flex flex-col">
                            <div class="h-48 w-full overflow-hidden relative">
                                <img src="{{ asset('assets/img/facility/' . $r['photo']) }}" alt="{{ $r['name'] }}"
                                    class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500">
                            </div>
                            <div class="p-7 flex flex-col flex-1">
                                <h3 class="font-display text-xl {{ $i == 1 ? '' : 'text-olive-900' }}">
                                    {{ $r['name'] }}
                                </h3>
                                <p
                                    class="text-sm {{ $i == 1 ? 'text-olive-300' : 'text-olive-500' }} mt-2 leading-relaxed flex-1">
                                    Nyaman untuk solo
                                    traveler atau pasangan yang ingin menginap dengan tenang.</p>
                                <ul
                                    class="mt-5 space-y-2 text-sm {{ $i == 1 ? 'text-olive-200' : 'text-olive-600' }}">
                                    @foreach ($r['facility'] as $f)
                                        <li class="flex items-center gap-2"><svg width="16" height="16"
                                                viewBox="0 0 24 24" fill="none"
                                                stroke="{{ $i == 1 ? '#D3B594' : '#A5815F' }}" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M20 6 9 17l-5-5" />
                                            </svg>{{ $f }}</li>
                                    @endforeach
                                    <li class="flex items-center gap-2"><svg width="16" height="16"
                                            viewBox="0 0 24 24" fill="none" stroke="#A5815F" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20 6 9 17l-5-5" />
                                        </svg>2 Tamu &middot; 18 m&sup2;</li>

                                </ul>
                                <div class="flex items-center justify-between mt-6 pt-6 border-t border-olive-100">
                                    <div>
                                        <span
                                            class="text-[11px] uppercase tracking-wide {{ $i == 1 ? 'text-cream-400' : 'text-olive-300' }}">Mulai
                                            dari</span>
                                        <div
                                            class="font-display text-lg {{ $i == 1 ? 'text-cream-50' : 'text-olive-900' }}">
                                            {{ App\Helper\Helpers::rupiah($r['price'],'Rp. ') }}<span
                                                class="text-xs {{ $i == 1 ? 'text-cream-400' : 'text-olive-300' }} font-sans">/malam</span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </article>

                        @php
                            $i++;
                        @endphp
                    @endforeach

                </div>
            </div>
        </section>

        <!-- ============ FASILITAS ============ -->
        <section id="fasilitas" class="py-20 md:py-28 bg-cream-100">
            <div class="max-w-7xl mx-auto px-5 sm:px-8">
                <div class="max-w-2xl reveal">
                    <p class="flex items-center gap-4 text-[12px] tracking-[0.3em] uppercase text-clay-600">
                        <span class="h-px w-10 bg-clay-400"></span> Fasilitas
                    </p>
                    <h2 class="font-display font-medium text-3xl sm:text-4xl text-olive-900 mt-4 leading-tight">
                        Semua yang Dibutuhkan untuk Menginap Nyaman
                    </h2>
                </div>

                <div class="mt-14 grid grid-cols-2 md:grid-cols-4 gap-5">
                    <!-- Facility item template x8 -->
                    <div
                        class="reveal bg-cream-50 border border-olive-100 rounded-2xl p-6 text-center hover:border-clay-400 transition-colors">
                        <svg class="mx-auto text-olive-700" width="30" height="30" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M5 12.55a11 11 0 0 1 14 0" />
                            <path d="M1.42 9a16 16 0 0 1 21.16 0" />
                            <path d="M8.53 16.11a6 6 0 0 1 6.95 0" />
                            <circle cx="12" cy="20" r="1" />
                        </svg>
                        <p class="text-sm mt-3 text-olive-700">WiFi Cepat</p>
                    </div>
                    <div
                        class="reveal bg-cream-50 border border-olive-100 rounded-2xl p-6 text-center hover:border-clay-400 transition-colors">
                        <svg class="mx-auto text-olive-700" width="30" height="30" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <rect x="3" y="7" width="18" height="10" rx="2" />
                            <circle cx="7.5" cy="17" r="1.5" />
                            <circle cx="16.5" cy="17" r="1.5" />
                            <path d="M7 7V5a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2" />
                        </svg>
                        <p class="text-sm mt-3 text-olive-700">Parkir Luas</p>
                    </div>
                    <div
                        class="reveal bg-cream-50 border border-olive-100 rounded-2xl p-6 text-center hover:border-clay-400 transition-colors">
                        <svg class="mx-auto text-olive-700" width="30" height="30" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M12 2v20M2 12h20M4.9 4.9l14.2 14.2M19.1 4.9 4.9 19.1" />
                        </svg>
                        <p class="text-sm mt-3 text-olive-700">AC di Setiap Kamar</p>
                    </div>
                    <div
                        class="reveal bg-cream-50 border border-olive-100 rounded-2xl p-6 text-center hover:border-clay-400 transition-colors">
                        <svg class="mx-auto text-olive-700" width="30" height="30" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M3 10h18l-1.5 9a2 2 0 0 1-2 1.7H6.5a2 2 0 0 1-2-1.7L3 10Z" />
                            <path d="M7 10V6a5 5 0 0 1 10 0v4" />
                        </svg>
                        <p class="text-sm mt-3 text-olive-700">Dapur Bersama</p>
                    </div>
                    <div
                        class="reveal bg-cream-50 border border-olive-100 rounded-2xl p-6 text-center hover:border-clay-400 transition-colors">
                        <svg class="mx-auto text-olive-700" width="30" height="30" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M12 3c-1 3-4 4-4 8a4 4 0 0 0 8 0c0-4-3-5-4-8Z" />
                            <path d="M12 21v-6" />
                        </svg>
                        <p class="text-sm mt-3 text-olive-700">Taman &amp; Teras</p>
                    </div>
                    <div
                        class="reveal bg-cream-50 border border-olive-100 rounded-2xl p-6 text-center hover:border-clay-400 transition-colors">
                        <svg class="mx-auto text-olive-700" width="30" height="30" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z" />
                        </svg>
                        <p class="text-sm mt-3 text-olive-700">Keamanan 24 Jam</p>
                    </div>
                    <div
                        class="reveal bg-cream-50 border border-olive-100 rounded-2xl p-6 text-center hover:border-clay-400 transition-colors">
                        <svg class="mx-auto text-olive-700" width="30" height="30" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M3 8h18l-2 5H5L3 8Z" />
                            <path d="M5 13v6a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-6" />
                            <path d="M8 8V6a4 4 0 0 1 8 0v2" />
                        </svg>
                        <p class="text-sm mt-3 text-olive-700">Chiller</p>
                    </div>
                    <div
                        class="reveal bg-cream-50 border border-olive-100 rounded-2xl p-6 text-center hover:border-clay-400 transition-colors">
                        <svg class="mx-auto text-olive-700" width="30" height="30" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M6 4a2 2 0 0 1 2-2 2 2 0 0 1 2 2v10" />
                            <path d="M14 4a2 2 0 0 1 2-2 2 2 0 0 1 2 2v10" />
                            <path d="M10 7h8" />
                            <path d="M10 11h8" />
                            <path d="M2 16c1.5 0 2.25.8 3.75.8S8.25 16 9.75 16s2.25.8 3.75.8 2.25-.8 3.75-.8 2.25.8 3.75.8" />
                            <path d="M2 19.5c1.5 0 2.25.8 3.75.8s2.25-.8 3.75-.8 2.25.8 3.75.8 2.25-.8 3.75-.8 2.25.8 3.75.8" />
                        </svg>
                        <p class="text-sm mt-3 text-olive-700">Kolam Renang</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============ PRICING ============ -->
        <section id="harga" class="py-20 md:py-28 bg-cream-200/60">
            <div class="max-w-7xl mx-auto px-5 sm:px-8">
                <div class="mt-14 grid md:grid-cols-3 gap-8">
                    @php
                        $i = 0;
                    @endphp
                    @foreach ($data->rooms as $room)
                        <div
                            class="reveal {{ $i == 1 ? 'bg-olive-900 text-cream-100' : 'bg-cream-50' }}  rounded-[1.75rem] border border-olive-100 p-8 shadow-card flex flex-col">
                            <h3 class="font-display text-xl {{ $i == 1 ? 'text-cream-50' : 'text-olive-900' }}">
                                {{ $room['name'] }}</h3>
                            <p class="text-sm {{ $i == 1 ? 'text-olive-300' : 'text-olive-500' }} mt-2">Untuk solo traveler
                                &amp; pasangan</p>
                            <div class="mt-6">
                                <span
                                    class="font-display text-4xl {{ $i == 1 ? 'text-cream-50' : 'text-olive-900' }} price-value"
                                    data-nightly="250000" data-weekly="1487500">{{ App\Helper\Helpers::rupiah($room['price'],'Rp ') }}</span>
                                <span
                                    class="text-sm {{ $i == 1 ? 'text-olive-300' : 'text-olive-400' }} price-unit">/malam</span>
                            </div>
                            <ul class="mt-7 space-y-3 text-sm {{ $i == 1 ? 'text-olive-200' : 'text-olive-600' }} flex-1">

                                @foreach ($room['facility'] as $f)
                                    <li class="flex items-center gap-2"><svg width="16" height="16"
                                            viewBox="0 0 24 24" fill="none"
                                            stroke="{{ $i == 1 ? '#D3B594' : '#A5815F' }}" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20 6 9 17l-5-5" />
                                        </svg>{{ $f }}</li>
                                @endforeach
                            </ul>
                            @if ($i == 1)
                                <a href="#booking" data-room="deluxe"
                                    class="room-cta mt-8 text-center bg-clay-600 hover:bg-clay-700 text-cream-50 text-sm uppercase tracking-wide px-5 py-3.5 rounded-full transition-colors">Pilih
                                    Paket</a>
                            @else
                                <a href="#booking" data-room="standard"
                                    class="room-cta mt-8 text-center border border-olive-300 hover:border-olive-700 hover:bg-olive-800 hover:text-cream-50 text-olive-700 text-sm uppercase tracking-wide px-5 py-3.5 rounded-full transition-colors">Pilih
                                    Paket</a>
                            @endif
                        </div>
                        @php
                            $i++;
                        @endphp
                    @endforeach

                </div>
            </div>
        </section>

        <!-- ============ BOOKING ============ -->
        <section id="booking" class="py-20 md:py-28 bg-cream-100">
            <div class="max-w-7xl mx-auto px-5 sm:px-8">
                <div class="grid lg:grid-cols-5 gap-0 rounded-[2rem] overflow-hidden shadow-soft">

                    <div
                        class="reveal lg:col-span-2 bg-olive-900 text-cream-100 p-10 sm:p-12 flex flex-col justify-between">
                        <div>
                            <p class="flex items-center gap-4 text-[12px] tracking-[0.3em] uppercase text-clay-300">
                                <span class="h-px w-10 bg-clay-400/60"></span> Booking
                            </p>
                            <h2 class="font-display font-medium text-3xl mt-4 leading-tight">Reservasi Awal</h2>
                            <p class="text-olive-300 mt-4 leading-relaxed text-sm">
                                Amankan kamar favoritmu lebih awal dan dapatkan harga spesial pra-pembukaan. Tim kami
                                akan menghubungi kamu melalui WhatsApp untuk konfirmasi.
                            </p>
                        </div>

                        <ul class="mt-10 space-y-5 text-sm">
                            <li class="flex items-start gap-3">
                                <svg class="shrink-0 mt-0.5" width="18" height="18" viewBox="0 0 24 24"
                                    fill="none" stroke="#D3B594" stroke-width="1.6" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M12 21s7-6.5 7-12a7 7 0 1 0-14 0c0 5.5 7 12 7 12Z" />
                                    <circle cx="12" cy="9" r="2.5" />
                                </svg>
                                <span class="text-olive-200">Jl. Contoh Raya No. 123, Jember, Jawa Timur</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg class="shrink-0 mt-0.5" width="18" height="18" viewBox="0 0 24 24"
                                    fill="none" stroke="#D3B594" stroke-width="1.6" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path
                                        d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.99.36 1.96.68 2.9a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.18-1.25a2 2 0 0 1 2.11-.45c.94.32 1.91.55 2.9.68A2 2 0 0 1 22 16.92Z" />
                                </svg>
                                <span class="text-olive-200">+62 812-3456-7890</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg class="shrink-0 mt-0.5" width="18" height="18" viewBox="0 0 24 24"
                                    fill="none" stroke="#D3B594" stroke-width="1.6" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <rect x="2" y="4" width="20" height="16" rx="2" />
                                    <path d="m22 6-10 7L2 6" />
                                </svg>
                                <span class="text-olive-200">halo@ezzyhomestay.com</span>
                            </li>
                        </ul>

                        <img src="{{ asset('assets') }}/img/brand/logo-icon.png" alt=""
                            class="w-28 mt-10 opacity-70">
                    </div>

                    <div class="reveal lg:col-span-3 bg-cream-50 p-10 sm:p-12">
                        <form id="bookingForm" novalidate>
                            <div class="grid sm:grid-cols-2 gap-5">
                                <div>
                                    <label for="bkName"
                                        class="block text-xs uppercase tracking-wide text-olive-500 mb-2">Nama
                                        Lengkap</label>
                                    <input id="bkName" name="nama" type="text" required
                                        placeholder="Nama kamu"
                                        class="w-full bg-white border border-olive-200 rounded-xl px-4 py-3 text-sm text-olive-800 placeholder:text-olive-300">
                                </div>
                                <div>
                                    <label for="bkPhone"
                                        class="block text-xs uppercase tracking-wide text-olive-500 mb-2">No.
                                        WhatsApp</label>
                                    <input id="bkPhone" name="telepon" type="tel" required
                                        placeholder="0812xxxxxxxx"
                                        class="w-full bg-white border border-olive-200 rounded-xl px-4 py-3 text-sm text-olive-800 placeholder:text-olive-300">
                                </div>
                                <div>
                                    <label for="bkCheckin"
                                        class="block text-xs uppercase tracking-wide text-olive-500 mb-2">Check-in</label>
                                    <input id="bkCheckin" name="checkin" type="date" required
                                        class="w-full bg-white border border-olive-200 rounded-xl px-4 py-3 text-sm text-olive-800">
                                </div>
                                <div>
                                    <label for="bkCheckout"
                                        class="block text-xs uppercase tracking-wide text-olive-500 mb-2">Check-out</label>
                                    <input id="bkCheckout" name="checkout" type="date" required
                                        class="w-full bg-white border border-olive-200 rounded-xl px-4 py-3 text-sm text-olive-800">
                                </div>
                                <div>
                                    <label for="bkGuests"
                                        class="block text-xs uppercase tracking-wide text-olive-500 mb-2">Jumlah
                                        Tamu</label>
                                    <input id="bkGuests" name="tamu" type="number" min="1"
                                        max="10" value="2" required
                                        class="w-full bg-white border border-olive-200 rounded-xl px-4 py-3 text-sm text-olive-800">
                                </div>
                                <div>
                                    <label for="bkRoom"
                                        class="block text-xs uppercase tracking-wide text-olive-500 mb-2">Tipe
                                        Kamar</label>
                                    <select id="bkRoom" name="tipe_kamar" required
                                        class="w-full bg-white border border-olive-200 rounded-xl px-4 py-3 text-sm text-olive-800">
                                        <option value="standard">Kamar Standard</option>
                                        <option value="deluxe">Kamar Deluxe</option>
                                        <option value="suite">Suite Keluarga</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-5">
                                <label for="bkNote"
                                    class="block text-xs uppercase tracking-wide text-olive-500 mb-2">Catatan
                                    (opsional)</label>
                                <textarea id="bkNote" name="catatan" rows="3" placeholder="Ada permintaan khusus?"
                                    class="w-full bg-white border border-olive-200 rounded-xl px-4 py-3 text-sm text-olive-800 placeholder:text-olive-300 resize-none"></textarea>
                            </div>

                            <button type="submit"
                                class="mt-7 w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-olive-800 hover:bg-olive-900 text-cream-50 text-sm uppercase tracking-wide px-8 py-4 rounded-full transition-colors">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.6" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                                </svg>
                                Kirim via WhatsApp
                            </button>
                            <p id="bookingMsg" class="mt-4 text-sm text-clay-600 h-5" role="status"
                                aria-live="polite"></p>
                        </form>
                    </div>

                </div>
            </div>
        </section>

    </main>

    <!-- ============ FOOTER ============ -->
    <footer class="bg-olive-900 text-olive-200">
        <div class="max-w-7xl mx-auto px-5 sm:px-8 py-16 grid sm:grid-cols-2 lg:grid-cols-4 gap-10">
            <div>
                <div class="inline-block bg-cream-50 rounded-2xl px-4 py-3">
                    <img src="{{ asset('assets') }}/img/brand/logo-landscape.png" alt="Ezzy Homestay"
                        class="h-8 w-auto">
                </div>
                <p class="text-sm mt-5 leading-relaxed max-w-xs text-olive-300">
                    Tempat singgah yang hangat dan asri. Segera hadir untuk menyambut Anda beristirahat dengan nyaman.
                </p>
            </div>

            <div>
                <h4 class="text-cream-100 text-sm tracking-[0.15em] uppercase mb-5">Jelajahi</h4>
                <ul class="space-y-3 text-sm text-olive-300">
                    <li><a href="#tentang" class="hover:text-clay-300 transition-colors">Tentang Kami</a></li>
                    <li><a href="#kamar" class="hover:text-clay-300 transition-colors">Tipe Kamar</a></li>
                    <li><a href="#fasilitas" class="hover:text-clay-300 transition-colors">Fasilitas</a></li>
                    <li><a href="#harga" class="hover:text-clay-300 transition-colors">Harga</a></li>
                    <li><a href="#booking" class="hover:text-clay-300 transition-colors">Booking</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-cream-100 text-sm tracking-[0.15em] uppercase mb-5">Kontak</h4>
                <ul class="space-y-3 text-sm text-olive-300">
                    <li>Jl. Contoh Raya No. 123,<br>Jember, Jawa Timur</li>
                    <li>+62 812-3456-7890</li>
                    <li>halo@ezzyhomestay.com</li>
                </ul>
            </div>

            <div>
                <h4 class="text-cream-100 text-sm tracking-[0.15em] uppercase mb-5">Ikuti Kami</h4>
                <div class="flex items-center gap-3">
                    <a href="#" aria-label="Instagram"
                        class="h-10 w-10 rounded-full border border-olive-600 flex items-center justify-center hover:bg-clay-600 hover:border-clay-600 transition-colors">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.6">
                            <rect x="3" y="3" width="18" height="18" rx="5" />
                            <circle cx="12" cy="12" r="4" />
                            <circle cx="17.5" cy="6.5" r="1" />
                        </svg>
                    </a>
                    <a href="#" aria-label="WhatsApp"
                        class="h-10 w-10 rounded-full border border-olive-600 flex items-center justify-center hover:bg-clay-600 hover:border-clay-600 transition-colors">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.6">
                            <path
                                d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" />
                        </svg>
                    </a>
                    <a href="#" aria-label="Facebook"
                        class="h-10 w-10 rounded-full border border-olive-600 flex items-center justify-center hover:bg-clay-600 hover:border-clay-600 transition-colors">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.6">
                            <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <div class="border-t border-olive-700/60">
            <div
                class="max-w-7xl mx-auto px-5 sm:px-8 py-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-olive-400">
                <p>&copy; <span id="year"></span> Ezzy Homestay. Semua hak dilindungi.</p>
                <p>Dibuat dengan &hearts; untuk pengalaman menginap yang lebih hangat.</p>
            </div>
        </div>
    </footer>

    <script>
        (function() {
            "use strict";

            /* ---------- Config: sesuaikan dengan kebutuhan ---------- */
            var LAUNCH_DATE = new Date('2026-12-20T00:00:00+08:00'); // TODO: ganti tanggal peluncuran
            var WHATSAPP_NUMBER = '6282374547179'; // TODO: ganti nomor WhatsApp (format 62xxxxxxxxxx)

            var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            /* ---------- Transparent / Scrolled navbar ---------- */
            var navbar = document.getElementById('navbar');

            function onScrollNav() {
                if (window.scrollY > 30) {
                    navbar.classList.add('is-scrolled');
                } else {
                    navbar.classList.remove('is-scrolled');
                }
            }
            document.addEventListener('scroll', onScrollNav, {
                passive: true
            });
            onScrollNav();

            /* ---------- Mobile menu ---------- */
            var menuBtn = document.getElementById('menuBtn');
            var mobileMenu = document.getElementById('mobileMenu');
            var iconMenu = document.getElementById('iconMenu');
            var iconClose = document.getElementById('iconClose');

            menuBtn.addEventListener('click', function() {
                var isOpen = !mobileMenu.classList.contains('hidden');
                mobileMenu.classList.toggle('hidden');
                iconMenu.classList.toggle('hidden');
                iconClose.classList.toggle('hidden');
                menuBtn.setAttribute('aria-expanded', String(!isOpen));
                if (!isOpen && window.scrollY <= 30) {
                    navbar.classList.add('is-menu-open');
                } else {
                    navbar.classList.remove('is-menu-open');
                }
            });

            document.querySelectorAll('#mobileMenu a').forEach(function(a) {
                a.addEventListener('click', function() {
                    mobileMenu.classList.add('hidden');
                    iconMenu.classList.remove('hidden');
                    iconClose.classList.add('hidden');
                    menuBtn.setAttribute('aria-expanded', 'false');
                    navbar.classList.remove('is-menu-open');
                });
            });

            /* ---------- Active nav link on scroll ---------- */
            var sections = ['tentang', 'kamar', 'fasilitas', 'harga', 'countdown'].map(function(id) {
                return document.getElementById(id);
            }).filter(Boolean);
            var navLinks = document.querySelectorAll('[data-nav]');

            if ('IntersectionObserver' in window && sections.length) {
                var navObserver = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            navLinks.forEach(function(link) {
                                var match = link.getAttribute('href') === '#' + entry.target.id;
                                link.classList.toggle('active', match);
                            });
                        }
                    });
                }, {
                    rootMargin: '-45% 0px -45% 0px'
                });
                sections.forEach(function(s) {
                    navObserver.observe(s);
                });
            }

            /* ---------- Reveal on scroll ---------- */
            var revealEls = document.querySelectorAll('.reveal');
            if ('IntersectionObserver' in window && !reduceMotion) {
                var revealObserver = new IntersectionObserver(function(entries, obs) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('is-visible');
                            obs.unobserve(entry.target);
                        }
                    });
                }, {
                    threshold: 0.12
                });
                revealEls.forEach(function(el) {
                    revealObserver.observe(el);
                });
            } else {
                revealEls.forEach(function(el) {
                    el.classList.add('is-visible');
                });
            }

            /* ---------- Hero facility slider ---------- */
            var sliderEl = document.getElementById('heroSlider');
            var heroSection = document.getElementById('hero');
            if (sliderEl && heroSection) {
                var slides = Array.prototype.slice.call(sliderEl.querySelectorAll('.hero-slide'));
                var captionEl = document.getElementById('slideCaption');
                var dotsWrap = document.getElementById('sliderDots');
                var prevBtn = document.getElementById('sliderPrev');
                var nextBtn = document.getElementById('sliderNext');

                // Nama fasilitas untuk tiap slide, dan path foto (opsional).
                // Taruh foto asli di {{ asset('assets') }}/img/brand/hero-slider/ dengan nama file yang sama
                // seperti pada atribut data-src tiap slide — foto akan otomatis
                // menggantikan gradien begitu file ditemukan.
                var slideNames = slides.map(function(s) {
                    return s.getAttribute('data-name') || '';
                });

                var current = 0;
                var autoplayMs = 5000;
                var timer = null;

                // Lazy-load each slide photo; keep the gradient/icon fallback if the file is missing.
                slides.forEach(function(slide) {
                    var img = slide.querySelector('.hero-slide-img');
                    var src = img.getAttribute('data-src');
                    if (!src) return;
                    var loader = new Image();
                    loader.onload = function() {
                        img.src = src;
                        img.classList.add('is-loaded');
                    };
                    loader.onerror = function() {
                        /* keep gradient + icon fallback */
                    };
                    loader.src = src;
                });

                // Build dots
                slides.forEach(function(_, i) {
                    var dot = document.createElement('button');
                    dot.type = 'button';
                    dot.className = 'slider-dot';
                    dot.setAttribute('aria-label', 'Ke slide ' + (i + 1) + ': ' + (slideNames[i] || ''));
                    dot.addEventListener('click', function() {
                        goTo(i, true);
                    });
                    dotsWrap.appendChild(dot);
                });
                var dots = Array.prototype.slice.call(dotsWrap.children);

                function render() {
                    slides.forEach(function(slide, i) {
                        var active = i === current;
                        slide.classList.toggle('is-active', active);
                        slide.setAttribute('aria-hidden', String(!active));
                    });
                    dots.forEach(function(dot, i) {
                        dot.classList.toggle('is-active', i === current);
                        dot.setAttribute('aria-current', i === current ? 'true' : 'false');
                    });
                    if (captionEl) {
                        captionEl.textContent = slideNames[current] || '';
                    }
                }

                function goTo(index, userInitiated) {
                    current = (index + slides.length) % slides.length;
                    render();
                    if (userInitiated) restartAutoplay();
                }

                function next() {
                    goTo(current + 1);
                }

                function prevSlide() {
                    goTo(current - 1);
                }

                function startAutoplay() {
                    if (reduceMotion) return; // respect reduced-motion: no forced auto-advance
                    stopAutoplay();
                    timer = setInterval(next, autoplayMs);
                }

                function stopAutoplay() {
                    if (timer) {
                        clearInterval(timer);
                        timer = null;
                    }
                }

                function restartAutoplay() {
                    stopAutoplay();
                    startAutoplay();
                }

                if (prevBtn) prevBtn.addEventListener('click', function() {
                    prevSlide();
                    restartAutoplay();
                });
                if (nextBtn) nextBtn.addEventListener('click', function() {
                    next();
                    restartAutoplay();
                });

                heroSection.addEventListener('mouseenter', stopAutoplay);
                heroSection.addEventListener('mouseleave', startAutoplay);
                heroSection.addEventListener('focusin', stopAutoplay);
                heroSection.addEventListener('focusout', startAutoplay);

                // Keyboard navigation
                heroSection.setAttribute('tabindex', '0');
                heroSection.addEventListener('keydown', function(e) {
                    if (e.key === 'ArrowRight') {
                        next();
                        restartAutoplay();
                    }
                    if (e.key === 'ArrowLeft') {
                        prevSlide();
                        restartAutoplay();
                    }
                });

                // Swipe support
                var touchStartX = null;
                heroSection.addEventListener('touchstart', function(e) {
                    touchStartX = e.changedTouches[0].clientX;
                    stopAutoplay();
                }, {
                    passive: true
                });
                heroSection.addEventListener('touchend', function(e) {
                    if (touchStartX === null) return;
                    var delta = e.changedTouches[0].clientX - touchStartX;
                    if (Math.abs(delta) > 40) {
                        delta < 0 ? next() : prevSlide();
                    }
                    touchStartX = null;
                    startAutoplay();
                }, {
                    passive: true
                });

                // Pause autoplay when the slider scrolls out of view
                if ('IntersectionObserver' in window) {
                    var visibilityObserver = new IntersectionObserver(function(entries) {
                        entries.forEach(function(entry) {
                            entry.isIntersecting ? startAutoplay() : stopAutoplay();
                        });
                    }, {
                        threshold: 0.2
                    });
                    visibilityObserver.observe(heroSection);
                } else {
                    startAutoplay();
                }

                render();
            }

            /* ---------- Countdown ---------- */
            var elDays = document.getElementById('cd-days');
            var elHours = document.getElementById('cd-hours');
            var elMinutes = document.getElementById('cd-minutes');
            var elSeconds = document.getElementById('cd-seconds');

            function pad(n) {
                return String(n).padStart(2, '0');
            }

            function tickCountdown() {
                var now = new Date();
                var diff = LAUNCH_DATE.getTime() - now.getTime();
                if (diff <= 0) {
                    elDays.textContent = '00';
                    elHours.textContent = '00';
                    elMinutes.textContent = '00';
                    elSeconds.textContent = '00';
                    return;
                }
                var days = Math.floor(diff / (1000 * 60 * 60 * 24));
                var hours = Math.floor((diff / (1000 * 60 * 60)) % 24);
                var minutes = Math.floor((diff / (1000 * 60)) % 60);
                var seconds = Math.floor((diff / 1000) % 60);
                elDays.textContent = pad(days);
                elHours.textContent = pad(hours);
                elMinutes.textContent = pad(minutes);
                elSeconds.textContent = pad(seconds);
            }
            tickCountdown();
            setInterval(tickCountdown, 1000);

            /* ---------- Notify form ---------- */
            var notifyForm = document.getElementById('notifyForm');
            var notifyMsg = document.getElementById('notifyMsg');
            notifyForm.addEventListener('submit', function(e) {
                e.preventDefault();
                var email = document.getElementById('notifyEmail').value.trim();
                var valid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
                if (!valid) {
                    notifyMsg.textContent = 'Mohon masukkan alamat email yang valid.';
                    notifyMsg.classList.add('text-red-300');
                    return;
                }
                notifyMsg.classList.remove('text-red-300');
                notifyMsg.textContent = 'Terima kasih! Kami akan mengabari kamu saat pembukaan tiba. 🌿';
                notifyForm.reset();
            });

            /* ---------- Room CTA: scroll to booking + preselect ---------- */
            document.querySelectorAll('.room-cta').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var room = btn.getAttribute('data-room');
                    var select = document.getElementById('bkRoom');
                    if (room && select) {
                        select.value = room;
                    }
                });
            });

            /* ---------- Pricing toggle ---------- */
            var toggleNightly = document.getElementById('toggleNightly');
            var toggleWeekly = document.getElementById('toggleWeekly');
            var priceValues = document.querySelectorAll('.price-value');
            var priceUnits = document.querySelectorAll('.price-unit');

            function formatRupiah(n) {
                return 'Rp ' + Number(n).toLocaleString('id-ID');
            }

            function setPricing(mode) {
                priceValues.forEach(function(el) {
                    var val = mode === 'weekly' ? el.getAttribute('data-weekly') : el.getAttribute(
                        'data-nightly');
                    el.textContent = formatRupiah(val);
                });
                priceUnits.forEach(function(el) {
                    el.textContent = mode === 'weekly' ? '/minggu' : '/malam';
                });
                var nightlyActive = mode !== 'weekly';
                toggleNightly.classList.toggle('bg-olive-800', nightlyActive);
                toggleNightly.classList.toggle('text-cream-50', nightlyActive);
                toggleNightly.classList.toggle('text-olive-600', !nightlyActive);
                toggleWeekly.classList.toggle('bg-olive-800', !nightlyActive);
                toggleWeekly.classList.toggle('text-cream-50', !nightlyActive);
                toggleWeekly.classList.toggle('text-olive-600', nightlyActive);
            }
            toggleNightly.addEventListener('click', function() {
                setPricing('nightly');
            });
            toggleWeekly.addEventListener('click', function() {
                setPricing('weekly');
            });

            /* ---------- Booking form -> WhatsApp ---------- */
            var bookingForm = document.getElementById('bookingForm');
            var bookingMsg = document.getElementById('bookingMsg');
            var roomLabels = {
                standard: 'Superior King',
                deluxe: 'Deluxe King',
                suite: 'Superior Twin'
            };

            bookingForm.addEventListener('submit', function(e) {
                e.preventDefault();
                var data = new FormData(bookingForm);
                var nama = data.get('nama').trim();
                var telepon = data.get('telepon').trim();
                var checkin = data.get('checkin');
                var checkout = data.get('checkout');
                var tamu = data.get('tamu');
                var tipeKamar = roomLabels[data.get('tipe_kamar')] || data.get('tipe_kamar');
                var catatan = data.get('catatan').trim();

                if (!nama || !telepon || !checkin || !checkout) {
                    bookingMsg.classList.add('text-red-500');
                    bookingMsg.classList.remove('text-clay-600');
                    bookingMsg.textContent = 'Mohon lengkapi nama, WhatsApp, dan tanggal menginap.';
                    return;
                }
                bookingMsg.classList.remove('text-red-500');
                bookingMsg.classList.add('text-clay-600');

                var pesan = 'Halo Ezzy Homestay, saya ingin reservasi awal:%0A' +
                    '- Nama: ' + encodeURIComponent(nama) + '%0A' +
                    '- Tipe Kamar: ' + encodeURIComponent(tipeKamar) + '%0A' +
                    '- Check-in: ' + encodeURIComponent(checkin) + '%0A' +
                    '- Check-out: ' + encodeURIComponent(checkout) + '%0A' +
                    '- Jumlah Tamu: ' + encodeURIComponent(tamu) +
                    (catatan ? '%0A- Catatan: ' + encodeURIComponent(catatan) : '');

                var waUrl = 'https://wa.me/' + WHATSAPP_NUMBER + '?text=' + pesan;
                bookingMsg.textContent = 'Membuka WhatsApp untuk konfirmasi reservasi...';
                window.open(waUrl, '_blank', 'noopener');
            });

            /* ---------- Footer year ---------- */
            document.getElementById('year').textContent = new Date().getFullYear();

        })();
    </script>
</body>

</html>
