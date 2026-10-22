/* ==========================================================================
   Konfigurasi Tailwind (Play CDN) — token design system Sumber Jaya.
   Sumber nilai: design-system.md (root project).

   File ini harus dimuat SESUDAH https://cdn.tailwindcss.com
   (polanya sama dengan tailwind.config = {...} inline).
   ========================================================================== */

tailwind.config = {
	theme: {
		/* Breakpoint sesuai design system (mobile-first, min-width). */
		screens: {
			sm: '600px',
			md: '768px',
			lg: '1024px',
			xl: '1200px',
			'2xl': '1536px',
		},

		extend: {
			colors: {
				'ds-page': '#E9E0D0',
				'ds-surface': '#F7F2E8',
				'ds-surface-alt': '#D9CBB2',
				'ds-tan': '#B9A685',
				'ds-forest': '#2F6A52',
				'ds-frame': '#3A2B25',
				'ds-ink': '#1C1714',
				'ds-ink-soft': '#5B4F45',
				'ds-on-forest': '#F2EBDD',
				'ds-mint': '#8ED8B6',
				'ds-line': '#CFC2AA',
				'ds-danger': '#B3261E',
			},

			fontFamily: {
				display: ['Anton', 'Impact', "'Arial Narrow'", 'sans-serif'],
				mono: ['"Space Mono"', 'ui-monospace', 'SFMono-Regular', 'Menlo', 'monospace'],
			},

			fontSize: {
				'display-xl': ['clamp(2.75rem, 1.6rem + 5.5vw, 6.5rem)', { lineHeight: '0.95', letterSpacing: '-0.01em' }],
				'display-lg': ['clamp(2rem, 1.4rem + 2.5vw, 3.5rem)', { lineHeight: '1', letterSpacing: '0' }],
				'display-md': ['clamp(1.75rem, 1.4rem + 1.4vw, 2.5rem)', { lineHeight: '1', letterSpacing: '0' }],
				'title-sm': ['15px', { lineHeight: '1.2', letterSpacing: '0' }],
				body: ['clamp(.875rem, .82rem + .25vw, 1rem)', { lineHeight: '1.6', letterSpacing: '0' }],
				caption: ['12px', { lineHeight: '1.5', letterSpacing: '0.02em' }],
				label: ['12px', { lineHeight: '1.2', letterSpacing: '0.06em' }],
			},

			/* Skala radius dibuat monotonik mengikuti design system. */
			borderRadius: {
				sm: '8px',
				md: '16px',
				lg: '24px',
				xl: '32px',
				'2xl': '40px',
				'3xl': '48px',
				pill: '9999px',
			},

			maxWidth: {
				container: '1200px',
			},

			boxShadow: {
				'ds-sm': '0 1px 2px rgba(28,23,20,.08)',
				'ds-md': '0 8px 24px rgba(28,23,20,.12)',
				'ds-lg': '0 16px 48px rgba(28,23,20,.20)',
			},

			transitionTimingFunction: {
				ds: 'cubic-bezier(.2,.8,.2,1)',
			},

			transitionDuration: {
				micro: '150ms',
				card: '200ms',
				menu: '260ms',
			},
		},
	},
};
