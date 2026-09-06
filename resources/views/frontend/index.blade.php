@extends('template.app-frontend')
@section('main-frontend')
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
            <span id="slideCaption"
                class="text-[11px] sm:text-xs tracking-[0.2em] uppercase text-clay-300">{{ $data->facility[0]['name'] ?? '' }}</span>
        </div>

        <!-- Arrows -->
        <button id="sliderPrev" type="button" aria-label="Slide sebelumnya"
            class="hidden sm:flex items-center justify-center absolute left-4 md:left-6 top-1/2 -translate-y-1/2 z-10 h-11 w-11 rounded-full bg-cream-50/10 hover:bg-cream-50/20 border border-cream-50/25 backdrop-blur text-cream-50 transition-colors">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M15 18l-6-6 6-6" />
            </svg>
        </button>
        <button id="sliderNext" type="button" aria-label="Slide berikutnya"
            class="hidden sm:flex items-center justify-center absolute right-4 md:right-6 top-1/2 -translate-y-1/2 z-10 h-11 w-11 rounded-full bg-cream-50/10 hover:bg-cream-50/20 border border-cream-50/25 backdrop-blur text-cream-50 transition-colors">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 18l6-6-6-6" />
            </svg>
        </button>

        <!-- Dots -->
        <div id="sliderDots" class="absolute bottom-8 sm:bottom-9 left-1/2 -translate-x-1/2 z-10 flex items-center gap-2.5">
        </div>
    </section>

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
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
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
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
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
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
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
                    <a href="{{ url('/room-detail/'.$r['slug']) }}">
                        <article
                            class="reveal group {{ $i == 1 ? 'bg-olive-900 text-cream-100' : 'bg-cream-50' }} rounded-[1.75rem] border border-olive-100 overflow-hidden shadow-card flex flex-col">
                            <div class="h-48 w-full overflow-hidden relative">
                                <img src="{{ $r['photo'] }}" alt="{{ $r['name'] }}"
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
                                <ul class="mt-5 space-y-2 text-sm {{ $i == 1 ? 'text-olive-200' : 'text-olive-600' }}">
                                    @foreach ($r['facility'] as $f)
                                        <li class="flex items-center gap-2"><svg width="16" height="16"
                                                viewBox="0 0 24 24" fill="none"
                                                stroke="{{ $i == 1 ? '#D3B594' : '#A5815F' }}" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M20 6 9 17l-5-5" />
                                            </svg>{{ $f }}</li>
                                    @endforeach

                                </ul>
                                <div class="flex items-center justify-between mt-6 pt-6 border-t border-olive-100">
                                    <div>
                                        <span
                                            class="text-[11px] uppercase tracking-wide {{ $i == 1 ? 'text-cream-400' : 'text-olive-300' }}">Mulai
                                            dari</span>
                                        <div
                                            class="font-display text-lg {{ $i == 1 ? 'text-cream-50' : 'text-olive-900' }}">
                                            {{ App\Helper\Helpers::rupiah($r['price'], 'Rp. ') }}<span
                                                class="text-xs {{ $i == 1 ? 'text-cream-400' : 'text-olive-300' }} font-sans">/malam</span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </article>
                    </a>

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
                        <p class="text-sm {{ $i == 1 ? 'text-olive-300' : 'text-olive-500' }} mt-2">Untuk solo
                            traveler
                            &amp; pasangan</p>
                        <div class="mt-6">
                            <span
                                class="font-display text-4xl {{ $i == 1 ? 'text-cream-50' : 'text-olive-900' }} price-value"
                                data-nightly="250000"
                                data-weekly="1487500">{{ App\Helper\Helpers::rupiah($room['price'], 'Rp ') }}</span>
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
                            <a href="#booking" data-room="{{ $room['name'] }}"
                                class="room-cta mt-8 text-center bg-clay-600 hover:bg-clay-700 text-cream-50 text-sm uppercase tracking-wide px-5 py-3.5 rounded-full transition-colors">Pilih
                                Paket</a>
                        @else
                            <a href="#booking" data-room="{{ $room['name'] }}"
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

                <div class="reveal lg:col-span-2 bg-olive-900 text-cream-100 p-10 sm:p-12 flex flex-col justify-between">
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
                            <span class="text-olive-200">+62 823-7454-7179</span>
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
                                <input id="bkName" name="nama" type="text" required placeholder="Nama kamu"
                                    class="w-full bg-white border border-olive-200 rounded-xl px-4 py-3 text-sm text-olive-800 placeholder:text-olive-300">
                            </div>
                            <div>
                                <label for="bkPhone"
                                    class="block text-xs uppercase tracking-wide text-olive-500 mb-2">No.
                                    WhatsApp</label>
                                <input id="bkPhone" name="telepon" type="tel" required placeholder="0812xxxxxxxx"
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
                                <label for="bkRoom"
                                    class="block text-xs uppercase tracking-wide text-olive-500 mb-2">Tipe
                                    Kamar</label>
                                <select id="bkRoom" name="tipe_kamar" required
                                    class="w-full bg-white border border-olive-200 rounded-xl px-4 py-3 text-sm text-olive-800">
                                    <option value="">Pilih tipe kamar</option>
                                    @foreach ($data->rooms as $room)
                                        <option value="{{ $room['name'] }}">{{ $room['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="bkGuests"
                                    class="block text-xs uppercase tracking-wide text-olive-500 mb-2">Kode
                                    Voucher</label>
                                <input id="bkGuests" name="voucher" type="text" placeholder="Kode Voucher(Opsional)"
                                    class="w-full bg-white border border-olive-200 rounded-xl px-4 py-3 text-sm text-olive-800">
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
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                            </svg>
                            Pesan via WhatsApp
                        </button>
                        <p id="bookingMsg" class="mt-4 text-sm text-clay-600 h-5" role="status" aria-live="polite">
                        </p>
                    </form>
                </div>

            </div>
        </div>
    </section>
@endsection
