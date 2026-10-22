/* ==========================================================================
   Interaksi UI design system — Sumber Jaya.
   Saat ini hanya drawer menu mobile (navigation §7.4).

   Tanpa dependensi; vanilla JS.
   ========================================================================== */

(function () {
	'use strict';

	/* ------------------------------------------------------------------
	   Drawer menu mobile
	   ------------------------------------------------------------------ */

	var drawer = document.querySelector('[data-ds-drawer]');

	if (drawer)
	{
		var openBtn = document.querySelector('[data-ds-drawer-open]');
		var closeBtn = document.querySelector('[data-ds-drawer-close]');
		var backdrop = document.querySelector('[data-ds-drawer-backdrop]');
		var lastFocus = null;

		function openDrawer()
		{
			lastFocus = document.activeElement;

			drawer.hidden = false;
			drawer.setAttribute('aria-hidden', 'false');
			document.body.classList.add('ds-scroll-lock');

			if (openBtn)
			{
				openBtn.setAttribute('aria-expanded', 'true');
			}

			if (closeBtn)
			{
				closeBtn.focus();
			}

			document.addEventListener('keydown', onKeydown);
		}

		function closeDrawer()
		{
			drawer.hidden = true;
			drawer.setAttribute('aria-hidden', 'true');
			document.body.classList.remove('ds-scroll-lock');

			if (openBtn)
			{
				openBtn.setAttribute('aria-expanded', 'false');
			}

			document.removeEventListener('keydown', onKeydown);

			if (lastFocus && typeof lastFocus.focus === 'function')
			{
				lastFocus.focus();
			}
		}

		function onKeydown(event)
		{
			if (event.key === 'Escape')
			{
				closeDrawer();
			}
		}

		if (openBtn)
		{
			openBtn.addEventListener('click', openDrawer);
		}

		if (closeBtn)
		{
			closeBtn.addEventListener('click', closeDrawer);
		}

		if (backdrop)
		{
			backdrop.addEventListener('click', closeDrawer);
		}

		/* Tutup bila menaut ke halaman lain di dalam drawer. */
		drawer.querySelectorAll('a[href]').forEach(function (link)
		{
			link.addEventListener('click', closeDrawer);
		});

		/* Keadaan awal: sembunyi + aria-hidden (bukan hanya display:none). */
		drawer.hidden = true;
		drawer.setAttribute('aria-hidden', 'true');

		if (openBtn)
		{
			openBtn.setAttribute('aria-expanded', 'false');
		}
	}
})();
