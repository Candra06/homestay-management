<!-- ============ NAVBAR ============ -->
<header id="navbar">
    <nav class="max-w-7xl mx-auto px-5 sm:px-8 flex items-center justify-between h-20" aria-label="Navigasi utama">
        <a href="#hero" class="flex items-center gap-2 shrink-0">
            <img src="{{ asset('assets') }}/img/brand/logo-icon.png" alt="Ezzy Homestay" class="h-11 w-auto md:hidden">
            <img src="{{ asset('assets') }}/img/brand/logo-landscape.png" alt="Ezzy Homestay"
                class="hidden md:block h-10 w-auto">
        </a>

        <ul class="hidden lg:flex items-center gap-9 text-[13px] tracking-[0.12em] uppercase">
            <li><a href="{{url('/')}}#tentang" class="nav-link nav-link-item" data-nav>Tentang</a></li>
            <li><a href="{{url('/')}}#kamar" class="nav-link nav-link-item" data-nav>Tipe Kamar</a></li>
            <li><a href="{{url('/')}}#fasilitas" class="nav-link nav-link-item" data-nav>Fasilitas</a></li>
            <li><a href="{{url('/')}}#harga" class="nav-link nav-link-item" data-nav>Harga</a></li>
            <li><a href="{{url('/')}}#countdown" class="nav-link nav-link-item" data-nav>Pembukaan</a></li>
        </ul>

        <div class="hidden lg:flex items-center gap-3">
            <a href="#booking"
                class="nav-btn inline-flex items-center gap-2 text-[13px] tracking-[0.1em] uppercase px-5 py-3 rounded-full shadow-card">
                Reservasi Awal
            </a>
        </div>

        <button id="menuBtn" class="menu-toggle-btn lg:hidden p-2" aria-label="Buka menu" aria-expanded="false"
            aria-controls="mobileMenu">
            <svg id="iconMenu" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="1.6" stroke-linecap="round">
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
                <a href="#booking" class="block text-center bg-olive-800 text-cream-50 px-5 py-3 rounded-full">Reservasi
                    Awal</a>
            </li>
        </ul>
    </div>
</header>
