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
                 navbar.classList.add('shadow-card');
             } else {
                 navbar.classList.remove('is-scrolled');
                 navbar.classList.remove('shadow-card');
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
         var sections = ['tentang', 'kamar', 'fasilitas', 'harga'].map(function(id) {
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


         function formatRupiah(n) {
             return 'Rp ' + Number(n).toLocaleString('id-ID');
         }



         /* ---------- Booking form -> WhatsApp ---------- */
         var bookingForm = document.getElementById('bookingForm');
         var bookingMsg = document.getElementById('bookingMsg');
         
         if (bookingForm) {
             bookingForm.addEventListener('submit', function(e) {
                 e.preventDefault();
                 var data = new FormData(bookingForm);
                 var nama = (data.get('nama') || '').trim();
                 var telepon = (data.get('telepon') || '').trim();
                 var checkin = data.get('checkin') || '';
                 var checkout = data.get('checkout') || '';
                 var tipeKamar = (document.getElementById('bookingRoomLabel').textContent ?? data.get('tipe_kamar')) || '';
                 var voucher = (data.get('voucher') || '').trim();
                 var catatan = (data.get('catatan') || '').trim();

                 if (!nama || !telepon || !checkin || !checkout || !tipeKamar) {
                     bookingMsg.classList.add('text-red-500');
                     bookingMsg.classList.remove('text-clay-600');
                     bookingMsg.textContent =
                         'Mohon lengkapi nama, WhatsApp, tanggal menginap, dan tipe kamar.';
                     return;
                 }

                 bookingMsg.classList.remove('text-red-500');
                 bookingMsg.classList.add('text-clay-600');
                 bookingMsg.textContent = 'Mengarahkan ke WhatsApp...';

                 var rawText = 'Halo Ezzy Homestay, saya ingin membuat reservasi kamar:\n\n' +
                     '📌 *Data Pemesan*\n' +
                     '• Nama: ' + nama + '\n' +
                     '• No. WhatsApp: ' + telepon + '\n\n' +
                     '🏨 *Detail Reservasi*\n' +
                     '• Tipe Kamar: ' + tipeKamar + '\n' +
                     '• Tanggal Check-in: ' + checkin + '\n' +
                     '• Tanggal Check-out: ' + checkout +
                     (voucher ? '\n• Kode Voucher: ' + voucher : '') +
                     (catatan ? '\n\n📝 *Catatan Tambahan:*\n' + catatan : '') +
                     '\n\nMohon informasi ketersediaan dan konfirmasi selengkapnya. Terima kasih!';

                 var waUrl = 'https://wa.me/' + WHATSAPP_NUMBER + '?text=' + encodeURIComponent(rawText);

                 var win = window.open(waUrl, '_blank');
                 if (!win || win.closed || typeof win.closed === 'undefined') {
                     window.location.href = waUrl;
                 }
             });
         }

         /* ---------- Footer year ---------- */
         document.getElementById('year').textContent = new Date().getFullYear();

     })();
 </script>
@yield('script-frontend')