<!DOCTYPE html>
<html lang="id">

@include('template.head-frontend')

<body class="font-sans text-olive-700 antialiased">
    @include('template.nav-frontend')
    <main class="konten">

        @yield('main-frontend')
    </main>

    <!-- ============ FOOTER ============ -->
    <footer class="bg-olive-900 text-olive-200">
        <div class="max-w-7xl mx-auto px-5 sm:px-8 py-16 grid sm:grid-cols-2 lg:grid-cols-4 gap-10">
            <div>
                <div class="inline-block bg-cream-50 rounded-2xl px-4 py-3">
                    <img src="{{ asset('assets') }}/img/brand/logo-landscape.png" alt="Ezzy Homestay" class="h-8 w-auto">
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
                     
                    <li>Jl. Teratai No.51, Kec. Kaliwates,<br>Kabupaten Jember, Jawa Timur 68133</li>
                    <li>+62 823-7454-7179</li>
                    <li>[EMAIL_ADDRESS]</li>
                </ul>
            </div>

            <div>
                <h4 class="text-cream-100 text-sm tracking-[0.15em] uppercase mb-5">Ikuti Kami</h4>
                <div class="flex items-center gap-3">
                    <a href="https://www.instagram.com/ezzyhomestay_jbr?igsi=MWRjYmQ1MGIxOTRsdg==" target="_blank"
                        aria-label="Instagram"
                        class="h-10 w-10 rounded-full border border-olive-600 flex items-center justify-center hover:bg-clay-600 hover:border-clay-600 transition-colors">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.6">
                            <rect x="3" y="3" width="18" height="18" rx="5" />
                            <circle cx="12" cy="12" r="4" />
                            <circle cx="17.5" cy="6.5" r="1" />
                        </svg>
                    </a>
                    <a href="https://www.tiktok.com/@ezzy.homestayjember?_r=1&_t=ZS-995XAW8Qa3R" target="_blank"
                        aria-label="TikTok"
                        class="h-10 w-10 rounded-full border border-olive-600 flex items-center justify-center hover:bg-clay-600 hover:border-clay-600 transition-colors">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5" />
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

    <div id="lightbox"
        class="hidden fixed inset-0 z-[100] bg-olive-900/95 backdrop-blur-sm items-center justify-center p-4 sm:p-10"
        role="dialog" aria-modal="true" aria-label="Perbesar foto kamar">
        <button id="lightboxClose" type="button" aria-label="Tutup"
            class="absolute top-5 right-5 sm:top-8 sm:right-8 h-11 w-11 rounded-full bg-cream-50/10 hover:bg-cream-50/20 border border-cream-50/25 text-cream-50 flex items-center justify-center transition-colors">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="1.8" stroke-linecap="round">
                <path d="M6 6l12 12M18 6L6 18" />
            </svg>
        </button>
        <button id="lightboxPrev" type="button" aria-label="Foto sebelumnya"
            class="absolute left-3 sm:left-8 top-1/2 -translate-y-1/2 h-11 w-11 rounded-full bg-cream-50/10 hover:bg-cream-50/20 border border-cream-50/25 text-cream-50 flex items-center justify-center transition-colors">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M15 18l-6-6 6-6" />
            </svg>
        </button>
        <button id="lightboxNext" type="button" aria-label="Foto berikutnya"
            class="absolute right-3 sm:right-8 top-1/2 -translate-y-1/2 h-11 w-11 rounded-full bg-cream-50/10 hover:bg-cream-50/20 border border-cream-50/25 text-cream-50 flex items-center justify-center transition-colors">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 18l6-6-6-6" />
            </svg>
        </button>

        <div class="w-full max-w-4xl">
            <div id="lightboxMedia" class="relative w-full aspect-[16/10] rounded-2xl overflow-hidden"></div>
            <p class="text-center text-cream-100/80 text-xs uppercase tracking-[0.2em] mt-4">
                <span id="lightboxLabel"></span> — <span id="lightboxCount"></span>
            </p>
        </div>
    </div>

    @include('template.script-frontend')
</body>

</html>
