@extends('template.app-frontend')
@section('main-frontend')
    <!-- ============ BREADCRUMB ============ -->
    @php
        $data = $data->rooms;
    @endphp
    <div class="max-w-7xl mx-auto px-5 sm:px-8 pt-20 mt-10">
        <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-xs text-olive-500">
            <a href="index.html" class="hover:text-clay-600 transition-colors">Beranda</a>
            <span>/</span>
            <a href="index.html#kamar" class="hover:text-clay-600 transition-colors">Tipe Kamar</a>
            <span>/</span>
            <span id="breadcrumbRoom" class="text-olive-700">{{ $data->type_name }}</span>
        </nav>
    </div>

    <!-- ============ MAIN CONTENT ============ -->
    <section class="py-10 md:py-14">
        <div class="max-w-7xl mx-auto px-5 sm:px-8 grid lg:grid-cols-3 gap-12 lg:gap-14 items-start">

            <div class="lg:col-span-2 space-y-14">

                <!-- Gallery -->
                <div id="galleryTop" class="reveal">
                    <div id="galleryMain"
                        class="relative rounded-[1.75rem] overflow-hidden aspect-[4/3] sm:aspect-[16/10] shadow-card cursor-zoom-in"
                        role="button" tabindex="0" aria-label="Perbesar foto">
                        <!-- filled by JS -->
                    </div>
                    <div id="galleryThumbs" class="mt-4 grid grid-cols-5 gap-3">
                        <!-- filled by JS -->
                    </div>
                </div>

                <!-- Room header -->
                <div class="reveal">
                    <p class="flex items-center gap-3 text-[12px] tracking-[0.25em] uppercase text-clay-600">
                        <span class="h-px w-8 bg-clay-400"></span> Tipe Kamar
                    </p>
                    <h1 id="roomName" class="font-display font-medium text-3xl sm:text-4xl text-olive-900 mt-3"></h1>
                    <p id="roomTagline" class="text-olive-500 mt-2 max-w-xl leading-relaxed"></p>

                    <div id="roomSpecs" class="flex flex-wrap gap-x-8 gap-y-4 mt-7 pt-7 border-t border-olive-100">
                        <!-- filled by JS -->
                    </div>
                </div>

                <!-- Description -->
                <div class="reveal">
                    <h2 class="font-display text-2xl text-olive-900">Tentang Kamar Ini</h2>
                    <div id="roomDescription" class="mt-4 space-y-4 text-olive-600 leading-relaxed max-w-2xl"></div>
                </div>

                <!-- Facilities -->
                <div class="reveal">
                    <h2 class="font-display text-2xl text-olive-900">Fasilitas Kamar</h2>
                    <div id="roomFacilities" class="mt-6 grid grid-cols-2 sm:grid-cols-3 gap-4"></div>
                </div>

            </div>

            <!-- Sticky sidebar -->
            <aside class="lg:col-span-1 reveal">
                <div class="lg:sticky lg:top-28 space-y-6">

                    <div class="bg-cream-50 border border-olive-100 rounded-[1.5rem] shadow-card p-7">
                        <h1 class="font-display text-2xl text-olive-900">{{ $data->type_name }}</h1>

                        <div class="mt-6 pt-6 border-t border-olive-100">
                            <span class="text-[11px] uppercase tracking-wide text-olive-400">Mulai dari</span>
                            <div class="mt-1 flex items-baseline gap-2 flex-wrap">
                                @if (!empty($data->original_price) && $data->original_price > 0)
                                    <span id="sidebarOriginalPrice" class="line-through text-sm text-red-500/80 font-sans">
                                        {{ App\Helper\Helpers::rupiah($data->original_price, 'Rp. ') }}
                                    </span>
                                @else
                                    <span id="sidebarOriginalPrice" class="line-through text-sm text-red-500/80 font-sans hidden"></span>
                                @endif
                                <span id="sidebarPrice"
                                    class="font-display text-3xl text-olive-900">{{ App\Helper\Helpers::rupiah($data->base_price, 'Rp. ') }}</span>
                                <span class="text-sm text-olive-400">/malam</span>
                            </div>

                        </div>

                        <a href="#booking"
                            class="mt-6 flex items-center justify-center gap-2 bg-olive-800 hover:bg-olive-900 text-cream-50 text-sm uppercase tracking-wide px-6 py-4 rounded-full transition-colors shadow-soft">
                            Pesan Sekarang
                        </a>

                        <ul class="mt-6 space-y-2.5 text-xs text-olive-500">
                            <li class="flex items-center gap-2">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#A5815F"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0">
                                    <path d="M20 6 9 17l-5-5" />
                                </svg>
                                Harga spesial pra-pembukaan
                            </li>
                            <li class="flex items-center gap-2">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#A5815F"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0">
                                    <path d="M20 6 9 17l-5-5" />
                                </svg>
                                Konfirmasi cepat via WhatsApp
                            </li>
                            <li class="flex items-center gap-2">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#A5815F"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0">
                                    <path d="M20 6 9 17l-5-5" />
                                </svg>
                                Tanpa biaya pemesanan tersembunyi
                            </li>
                        </ul>
                    </div>



                </div>
            </aside>

        </div>
    </section>

    <!-- ============ BOOKING ============ -->
    <section id="booking" class="py-20 md:py-28 bg-cream-200/60">
        <div class="max-w-3xl mx-auto px-5 sm:px-8">
            <div class="text-center reveal">
                <p class="flex items-center justify-center gap-4 text-[12px] tracking-[0.3em] uppercase text-clay-600">
                    <span class="h-px w-10 bg-clay-400"></span> Booking <span class="h-px w-10 bg-clay-400"></span>
                </p>
                <h2 class="font-display font-medium text-3xl sm:text-4xl text-olive-900 mt-4">Lengkapi Data Reservasi</h2>
                <p class="text-olive-600 mt-4 leading-relaxed">
                    Amankan kamar favoritmu lebih awal. Tim kami akan menghubungimu via WhatsApp untuk konfirmasi.
                </p>
            </div>

            <div class="reveal mt-12 bg-cream-50 border border-olive-100 rounded-[1.75rem] shadow-card p-7 sm:p-10">

                <div class="flex items-center justify-between flex-wrap gap-3 pb-6 mb-7 border-b border-olive-100">
                    <div>
                        <span class="text-[11px] uppercase tracking-wide text-olive-400">Kamar Dipilih</span>
                        <div id="bookingRoomLabel" class="font-display text-lg text-olive-900 mt-0.5"></div>
                    </div>
                    
                </div>

                <form id="bookingForm" novalidate>
                    <div class="grid sm:grid-cols-2 gap-5">
                        <div>
                            <label for="bkName" class="block text-xs uppercase tracking-wide text-olive-500 mb-2">Nama
                                Lengkap</label>
                            <input id="bkName" name="nama" type="text" required placeholder="Nama kamu"
                                class="w-full bg-white border border-olive-200 rounded-xl px-4 py-3 text-sm text-olive-800 placeholder:text-olive-300">
                        </div>
                        <div>
                            <label for="bkPhone" class="block text-xs uppercase tracking-wide text-olive-500 mb-2">No.
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
                            <label for="bkGuests" class="block text-xs uppercase tracking-wide text-olive-500 mb-2">Jumlah
                                Tamu</label>
                            <input id="bkGuests" name="tamu" type="number" min="1" max="10"
                                value="2" required
                                class="w-full bg-white border border-olive-200 rounded-xl px-4 py-3 text-sm text-olive-800">
                        </div>
                        <div>
                            <label for="bkVoucherCode"
                                class="block text-xs uppercase tracking-wide text-olive-500 mb-2">Kode Voucher</label>
                            <input id="bkVoucherCode" placeholder="Masukkan kode voucher(Opsional)" name="voucher_code" type="text"
                                class="w-full bg-white border border-olive-200 rounded-xl px-4 py-3 text-sm text-olive-800">
                        </div>
                    </div>

                    <div class="mt-5">
                        <label for="bkNote" class="block text-xs uppercase tracking-wide text-olive-500 mb-2">Catatan
                            (opsional)</label>
                        <textarea id="bkNote" name="catatan" rows="3" placeholder="Ada permintaan khusus?"
                            class="w-full bg-white border border-olive-200 rounded-xl px-4 py-3 text-sm text-olive-800 placeholder:text-olive-300 resize-none"></textarea>
                    </div>

                    <p id="dateHint" class="mt-3 text-xs text-red-500 h-4" role="status" aria-live="polite"></p>

                    <div id="priceSummary"
                        class="hidden mt-2 bg-clay-100 border border-clay-300 rounded-xl px-5 py-4 text-sm text-olive-700 flex items-center justify-between flex-wrap gap-2">
                        <span id="priceSummaryDetail"></span>
                        <div class="flex items-baseline gap-2">
                            <span id="priceSummaryOriginalTotal" class="line-through text-xs text-red-500/80 font-sans hidden"></span>
                            <span id="priceSummaryTotal" class="font-display text-lg text-olive-900"></span>
                        </div>
                    </div>

                    <button type="submit"
                        class="mt-7 w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-olive-800 hover:bg-olive-900 text-cream-50 text-sm uppercase tracking-wide px-8 py-4 rounded-full transition-colors">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                        </svg>
                        Kirim via WhatsApp
                    </button>
                    <p id="bookingMsg" class="mt-4 text-sm text-clay-600 h-5" role="status" aria-live="polite"></p>
                </form>
            </div>
        </div>
    </section>

    <!-- ============ KAMAR LAINNYA ============ -->
    {{-- <section class="py-20 md:py-24 bg-cream-100">
        <div class="max-w-7xl mx-auto px-5 sm:px-8">
            <h2 class="font-display font-medium text-2xl sm:text-3xl text-olive-900 reveal">Kamar Lainnya</h2>
            <div id="otherRooms" class="mt-10 grid sm:grid-cols-2 gap-8"></div>
        </div>
    </section> --}}
@endsection
@section('script-frontend')
    <script>
        (function() {
            "use strict";

            var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            /* ---------- Icon library ---------- */
            var ICONS = {
                wifi: '<path d="M5 12.55a11 11 0 0 1 14 0"/><path d="M1.42 9a16 16 0 0 1 21.16 0"/><path d="M8.53 16.11a6 6 0 0 1 6.95 0"/><circle cx="12" cy="20" r="1" fill="currentColor" stroke="none"/>',
                ac: '<path d="M12 2v20M2 12h20M4.9 4.9l14.2 14.2M19.1 4.9 4.9 19.1"/>',
                tv: '<rect x="3" y="4" width="18" height="12" rx="2"/><path d="M8 20h8M12 16v4"/>',
                closet: '<rect x="5" y="3" width="14" height="18" rx="1"/><path d="M12 3v18"/><circle cx="9.5" cy="12" r="0.6" fill="currentColor" stroke="none"/><circle cx="14.5" cy="12" r="0.6" fill="currentColor" stroke="none"/>',
                bath: '<path d="M4 12h16M4 12a4 4 0 0 0 4 4h8a4 4 0 0 0 4-4M7 12V6a2 2 0 0 1 2-2"/><path d="M6 21v-1M18 21v-1"/>',
                water: '<path d="M12 2.5s6 6.7 6 10.8a6 6 0 0 1-12 0c0-4.1 6-10.8 6-10.8Z"/>',
                balcony: '<path d="M4 21V10l8-6 8 6v11"/><path d="M4 21h16"/><path d="M8 21v-5a4 4 0 0 1 8 0v5"/>',
                minibar: '<rect x="6" y="2" width="12" height="20" rx="1.5"/><path d="M6 9.5h12"/><path d="M9 5.5v2M9 12.5v2"/>',
                deskchair: '<path d="M4 20h16M6 20V9h9v11M6 9V5a1 1 0 0 1 1-1h6M17 13h3v7"/>',
                family: '<path d="M17 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2"/><circle cx="10" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
                kitchenette: '<path d="M3 10h18l-1.5 9a2 2 0 0 1-2 1.7H6.5a2 2 0 0 1-2-1.7L3 10Z"/><path d="M7 10V6a5 5 0 0 1 10 0v4"/>',
                playarea: '<circle cx="12" cy="12" r="9"/><path d="M9 9h.01M15 9h.01"/><path d="M8.5 14.5c1.2 1.3 5.8 1.3 7 0"/>',
                diningtable: '<path d="M3 9h18"/><path d="M5 9v9M19 9v9"/><path d="M3 9l2-4h14l2 4"/>',
                bed: '<path d="M3 18v-6a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v6"/><path d="M3 18v2M21 18v2"/><path d="M5 10V7a2 2 0 0 1 2-2h3v5"/>',
                view: '<path d="M12 3c-1 3-4 4-4 8a4 4 0 0 0 8 0c0-4-3-5-4-8Z"/><path d="M12 21v-6"/>',
                desk: '<path d="M3 9h18"/><path d="M5 9v9M19 9v9"/><path d="M3 9l2-4h14l2 4"/>',
                decor: '<path d="M12 3v4M12 17v4M3 12h4M17 12h4M5.6 5.6l2.8 2.8M15.6 15.6l2.8 2.8M18.4 5.6l-2.8 2.8M8.4 15.6l-2.8 2.8"/>',
                ruler: '<path d="M21 3 3 21"/><path d="M8 8l2 2M12 4l2 2M4 12l2 2M14.5 9.5l2 2M17.5 6.5l2 2"/>'
            };

            function iconSvg(key, size) {
                size = size || 22;
                return '<svg width="' + size + '" height="' + size +
                    '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">' +
                    (ICONS[key] || '') + '</svg>';
            }

            /* ---------- Gallery slot definitions (universal across rooms) ---------- */
            var GALLERY_SLOTS = [];

            /* ---------- Room data ---------- */
            var ROOMS = @json($data);
            const baseUrl = "{{ asset('/storage/') }}/";
            console.log(ROOMS);
            ROOMS.attachments.forEach(function(attachment) {
                GALLERY_SLOTS.push({
                    icon: baseUrl + attachment.file_url,
                    label: attachment.file_url
                });
            });

            var ROOM_ORDER = ['standard', 'deluxe', 'suite'];

            function formatRupiah(n) {
                return 'Rp ' + Number(n).toLocaleString('id-ID');
            }

            /* ---------- State ---------- */
            var currentRoomId = 0;
            var currentSlideIndex = 0;
            var lightboxIndex = 0;

            /* ---------- Gallery rendering ---------- */
            function slideMarkup(room, slot, size) {
                var theme = {
                    gradient: 'from-olive-200 via-olive-100 to-cream-100',
                    iconColor: '#545545',
                    dark: false
                };
                var textColor = theme.dark ? 'text-cream-50' : 'text-olive-700';
                var chipClass = theme.dark ?
                    'bg-cream-50/10 border border-cream-50/25 backdrop-blur text-cream-50' :
                    'bg-cream-50/90 border border-olive-100 text-olive-700';
                return '' +
                    '<div class="absolute inset-0 bg-gradient-to-br from-olive-200 via-olive-100 to-cream-100"></div>' +
                    '<img data-src="'+slot.icon+'" alt="" class="gallery-photo absolute inset-0 w-full h-full object-cover opacity-0">' +
                    '<div class="absolute inset-0 flex items-center justify-center ' + textColor + '">' + iconSvg(slot
                        .icon, size) + '</div>' +
                    (size > 30 ?
                        '<span class="absolute bottom-4 left-4 text-[11px] tracking-[0.15em] uppercase px-3 py-1.5 rounded-full ' +
                        chipClass + '">' + slot.label + '</span>' : '');
            }

            function lazyLoadPhoto(el) {
                var img = el.querySelector('.gallery-photo');
                if (!img) return;
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
            }

            function renderGallery(room) {
                var main = document.getElementById('galleryMain');
                var thumbs = document.getElementById('galleryThumbs');
                currentSlideIndex = 0;
                console.log(room);
                
                main.innerHTML = slideMarkup(room, GALLERY_SLOTS[0], 72);
                lazyLoadPhoto(main);

                thumbs.innerHTML = '';
                GALLERY_SLOTS.forEach(function(slot, i) {
                    var btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'thumb-btn relative rounded-xl overflow-hidden aspect-square' + (i === 0 ?
                        ' is-active' : '');
                    btn.setAttribute('aria-label', slot.label);
                    btn.innerHTML = slideMarkup(room, slot, 22);
                    btn.addEventListener('click', function() {
                        setMainSlide(room, i);
                    });
                    thumbs.appendChild(btn);
                    lazyLoadPhoto(btn);
                });
            }

            function setMainSlide(room, index) {
                currentSlideIndex = (index + GALLERY_SLOTS.length) % GALLERY_SLOTS.length;
                var main = document.getElementById('galleryMain');
                main.innerHTML = slideMarkup(room, GALLERY_SLOTS[currentSlideIndex], 72);
                lazyLoadPhoto(main);
                Array.prototype.forEach.call(document.getElementById('galleryThumbs').children, function(btn, i) {
                    btn.classList.toggle('is-active', i === currentSlideIndex);
                });
            }

            document.getElementById('galleryMain').addEventListener('click', function() {
                openLightbox(currentSlideIndex);
            });
            document.getElementById('galleryMain').addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    openLightbox(currentSlideIndex);
                }
            });

            /* ---------- Lightbox ---------- */
            var lightbox = document.getElementById('lightbox');
            var lastFocused = null;

            function renderLightbox() {
                var room = ROOMS[currentRoomId];
                var slot = GALLERY_SLOTS[lightboxIndex];
                var media = document.getElementById('lightboxMedia');
                media.innerHTML = slideMarkup(room, slot, 88);
                lazyLoadPhoto(media);
                document.getElementById('lightboxLabel').textContent = slot.label;
                document.getElementById('lightboxCount').textContent = (lightboxIndex + 1) + ' / ' + GALLERY_SLOTS
                    .length;
            }

            function openLightbox(index) {
                lightboxIndex = index;
                renderLightbox();
                lastFocused = document.activeElement;
                lightbox.classList.remove('hidden');
                lightbox.classList.add('flex');
                document.body.style.overflow = 'hidden';
                document.getElementById('lightboxClose').focus();
            }

            function closeLightbox() {
                lightbox.classList.add('hidden');
                lightbox.classList.remove('flex');
                document.body.style.overflow = '';
                if (lastFocused) lastFocused.focus();
            }

            function lightboxStep(delta) {
                lightboxIndex = (lightboxIndex + delta + GALLERY_SLOTS.length) % GALLERY_SLOTS.length;
                renderLightbox();
            }

            document.getElementById('lightboxClose').addEventListener('click', closeLightbox);
            document.getElementById('lightboxPrev').addEventListener('click', function() {
                lightboxStep(-1);
            });
            document.getElementById('lightboxNext').addEventListener('click', function() {
                lightboxStep(1);
            });
            lightbox.addEventListener('click', function(e) {
                if (e.target === lightbox) closeLightbox();
            });
            document.addEventListener('keydown', function(e) {
                if (lightbox.classList.contains('hidden')) return;
                if (e.key === 'Escape') closeLightbox();
                if (e.key === 'ArrowRight') lightboxStep(1);
                if (e.key === 'ArrowLeft') lightboxStep(-1);
            });

            /* ---------- Facilities ---------- */
            function renderFacilities(room) {
                var wrap = document.getElementById('roomFacilities');
                wrap.innerHTML = '';
                room.facilities.forEach(function(f) {
                    var item = document.createElement('div');
                    item.className =
                        'flex items-center gap-3 bg-cream-50 border border-olive-100 rounded-xl px-4 py-3.5';
                    item.innerHTML = '<span class="text-olive-700 shrink-0">' + iconSvg(f.icon, 20) +
                        '</span><span class="text-sm text-olive-700">' + f.facility.nama_fasilitas + '</span>';
                    wrap.appendChild(item);
                });
            }

            /* ---------- Specs row ---------- */
            function renderSpecs(room) {
                var wrap = document.getElementById('roomSpecs');
                var specs = [{
                        icon: 'family',
                        text: room.kapasitas + ' Tamu'
                    },
                    {
                        icon: 'ruler',
                        text: room.wide + ' m\u00B2'
                    },
                    {
                        icon: 'bed',
                        text: room.bed_type.charAt(0).toUpperCase() + room.bed_type.slice(1)+' Bed'
                    },
                    {
                        icon: 'bath',
                        text: 'Kamar Mandi Dalam + Shower'
                    }
                ];
                wrap.innerHTML = specs.map(function(s) {
                    return '<div class="flex items-center gap-2.5 text-olive-600 text-sm"><span class="text-clay-600">' +
                        iconSvg(s.icon, 20) + '</span>' + s.text + '</div>';
                }).join('');
            }

            /* ---------- Price + booking sync ---------- */
            function renderPricing(room) {
                var sidebarOrigEl = document.getElementById('sidebarOriginalPrice');
                if (sidebarOrigEl) {
                    if (room.original_price && Number(room.original_price) > 0) {
                        sidebarOrigEl.textContent = formatRupiah(room.original_price);
                        sidebarOrigEl.classList.remove('hidden');
                    } else {
                        sidebarOrigEl.classList.add('hidden');
                    }
                }
                document.getElementById('sidebarPrice').textContent = formatRupiah(room.base_price);
                document.getElementById('bookingRoomLabel').textContent = room.type_name;
                updatePriceSummary();
            }

            function updatePriceSummary() {
                var room = ROOMS;
                console.log(room);
                
                var checkin = document.getElementById('bkCheckin').value;
                var checkout = document.getElementById('bkCheckout').value;
                var summary = document.getElementById('priceSummary');
                var hint = document.getElementById('dateHint');

                if (!checkin || !checkout) {
                    summary.classList.add('hidden');
                    hint.textContent = '';
                    return;
                }

                var d1 = new Date(checkin + 'T00:00:00');
                var d2 = new Date(checkout + 'T00:00:00');
                var nights = Math.round((d2 - d1) / (1000 * 60 * 60 * 24));

                if (nights <= 0) {
                    summary.classList.add('hidden');
                    hint.textContent = 'Tanggal check-out harus setelah tanggal check-in.';
                    return;
                }
                hint.textContent = '';

                var rate = room.base_price;
                var origRate = room.original_price;
                var total = rate * nights;

                var detailHtml = nights + ' malam \u00D7 ';
                if (origRate && Number(origRate) > 0) {
                    detailHtml += '<span class="line-through text-xs text-red-500/80 mr-1.5">' + formatRupiah(origRate) + '</span>';
                }
                detailHtml += formatRupiah(rate);

                document.getElementById('priceSummaryDetail').innerHTML = detailHtml;

                var origTotalEl = document.getElementById('priceSummaryOriginalTotal');
                if (origTotalEl) {
                    if (origRate && Number(origRate) > 0) {
                        origTotalEl.textContent = formatRupiah(origRate * nights);
                        origTotalEl.classList.remove('hidden');
                    } else {
                        origTotalEl.classList.add('hidden');
                    }
                }

                document.getElementById('priceSummaryTotal').textContent = formatRupiah(total);
                summary.classList.remove('hidden');
            }

            /* ---------- Room switching ---------- */
            function renderRoom(id) {

                var room = ROOMS;

                document.getElementById('pageTitle').textContent = room.type_name + ' — Ezzy Homestay';
                document.getElementById('pageDescription').setAttribute('content', room.description);
                document.getElementById('breadcrumbRoom').textContent = room.type_name;
                document.getElementById('roomName').textContent = room.type_name;
                document.getElementById('roomTagline').textContent = room.tagline;
                document.getElementById('roomDescription').innerHTML = '<p>' + room.description + '</p>';

                renderGallery(room);
                renderSpecs(room);
                renderFacilities(room);
                renderPricing(room);
            }

            /* ---------- Booking form -> WhatsApp ---------- */
            var bookingForm = document.getElementById('bookingForm');
            var bookingMsg = document.getElementById('bookingMsg');
            var WHATSAPP_NUMBER = '6281234567890'; // TODO: ganti nomor WhatsApp asli

            document.getElementById('bkCheckin').addEventListener('change', function() {
                var checkinVal = this.value;
                var checkoutInput = document.getElementById('bkCheckout');
                if (checkinVal) {
                    var nextDay = new Date(checkinVal + 'T00:00:00');
                    nextDay.setDate(nextDay.getDate() + 1);
                    checkoutInput.min = nextDay.toISOString().slice(0, 10);
                }
                updatePriceSummary();
            });
            document.getElementById('bkCheckout').addEventListener('change', updatePriceSummary);

            (function setMinDates() {
                var today = new Date().toISOString().slice(0, 10);
                document.getElementById('bkCheckin').min = today;
                document.getElementById('bkCheckout').min = today;
            })();

            /* ---------- Init ---------- */
            renderRoom(currentRoomId);

        })();
    </script>
@endsection
