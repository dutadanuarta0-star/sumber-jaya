/* ==========================================================================
   Slider hero (Swiper) — Sumber Jaya.
   Dipakai pada beranda. Memakai Swiper 14 (CDN, dimuat di layout/footer).

   Bila Swiper gagal termuat (CDN diblokir), kelas `ds-hero--ready` tidak
   pernah dipasang; fallback di assets/css/design-system.css tetap menyisakan
   hanya slide pertama yang terlihat.
   ========================================================================== */

(function () {
	'use strict';

	var root = document.querySelector('[data-ds-hero]');

	if (!root || typeof Swiper === 'undefined')
	{
		return;
	}

	var slides = root.querySelectorAll('.swiper-slide');
	var multi = slides.length > 1;
	var reduceMotion = window.matchMedia
		&& window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	/**
	 * Slide aktif dibiarkan bisa difokus; slide lain disembunyikan dari
	 * pembaca layar dan dari alur keyboard (atribut `inert`).
	 *
	 * @param {Object} swiper Instance Swiper.
	 */
	function syncSlideState(swiper)
	{
		var active = swiper.slides[swiper.activeIndex];

		swiper.slides.forEach(function (slide) {
			var isActive = (slide === active);

			slide.setAttribute('aria-hidden', isActive ? 'false' : 'true');

			if (isActive)
			{
				slide.removeAttribute('inert');
			}
			else
			{
				slide.setAttribute('inert', '');
			}
		});
	}

	var options = {
		loop: multi,
		speed: reduceMotion ? 0 : 700,
		effect: 'slide',
		direction: 'horizontal',
		slidesPerView: 1,
		spaceBetween: 0,
		autoHeight: false,
		allowTouchMove: multi,
		simulateTouch: true,
		grabCursor: multi,
		// Geser singkat (±25% lebar slide) sudah cukup untuk pindah slide,
		// supaya navigasi kiri/kanan terasa ringan di mouse maupun sentuhan.
		longSwipesRatio: 0.25,
		watchSlidesProgress: true,
		keyboard: { enabled: multi, onlyInViewport: true },
		a11y: {
			enabled: true,
			prevSlideMessage: 'Slide sebelumnya',
			nextSlideMessage: 'Slide berikutnya',
			firstSlideMessage: 'Ini slide pertama',
			lastSlideMessage: 'Ini slide terakhir',
			paginationBulletMessage: 'Ke slide {{index}}',
			slideLabelMessage: '{{index}} dari {{slidesLength}}',
			itemRoleDescriptionMessage: 'slide'
		},
		autoplay: (multi && ! reduceMotion)
			? { delay: 5000, disableOnInteraction: false, pauseOnMouseEnter: true }
			: false,
		on: {
			init: function (swiper) {
				// Selama fallback aktif, slide selain yang pertama disembunyikan
				// (`display: none`), sehingga saat init Swiper mengukur lebar 0 dan
				// menganggap semua slide muat dalam satu layar → slider terkunci
				// (panah, autoplay, dan drag tidak berfungsi). Lepas kelas fallback
				// lebih dulu, lalu minta Swiper mengukur ulang.
				root.classList.add('ds-hero--ready');
				swiper.update();
				syncSlideState(swiper);
			},
			slideChange: function (swiper) {
				syncSlideState(swiper);
			}
		}
	};

	if (multi)
	{
		options.pagination = {
			el: root.querySelector('.swiper-pagination'),
			clickable: true,
			bulletElement: 'button'
		};
		options.navigation = {
			prevEl: root.querySelector('.swiper-button-prev'),
			nextEl: root.querySelector('.swiper-button-next')
		};
	}

	/* eslint-disable no-new */
	new Swiper(root, options);
})();
