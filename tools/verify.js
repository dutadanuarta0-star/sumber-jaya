/**
 * Verifikasi design system via Chrome DevTools Protocol (headless).
 *
 * Menguji tiap viewport:
 *   1. tidak ada scroll horizontal
 *   2. token warna design system benar-benar terpakai (computed style)
 *   3. tipografi display memakai Anton, body memakai Space Mono
 *   4. navigasi desktop / mobile berganti sesuai breakpoint
 *   5. tidak ada error konsol
 *
 * Jalankan: node tools/verify.js
 */
const { spawn, execFileSync } = require('child_process');
const fs = require('fs');
const path = require('path');
const os = require('os');

const CHROME = 'C:/Program Files/Google/Chrome/Application/chrome.exe';
const BASE = 'http://localhost/Sumber%20jaya';
const PORT = 9333;

const VIEWPORTS = [
	{ name: 'mobile-360', w: 360, h: 780 },
	{ name: 'mobile-390', w: 390, h: 844 },
	{ name: 'tablet-768', w: 768, h: 1024 },
	{ name: 'laptop-1024', w: 1024, h: 800 },
	{ name: 'desktop-1280', w: 1280, h: 900 },
	{ name: 'desktop-1440', w: 1440, h: 900 },
];

const PAGES = [
	{ path: '/', name: 'beranda' },
	{ path: '/products', name: 'katalog' },
	{ path: '/products/detail/1', name: 'detail' },
	{ path: '/cart', name: 'keranjang' },
	{ path: '/about', name: 'tentang' },
];

const profile = fs.mkdtempSync(path.join(os.tmpdir(), 'verify-'));
const chrome = spawn(CHROME, [
	'--headless=new',
	'--disable-gpu',
	'--no-sandbox',
	'--hide-scrollbars',
	`--user-data-dir=${profile}`,
	`--remote-debugging-port=${PORT}`,
	'about:blank',
], { stdio: 'ignore' });

const sleep = (ms) => new Promise((r) => setTimeout(r, ms));

async function main() {
	// Tunggu devtools siap.
	let targets = null;
	for (let i = 0; i < 40; i++) {
		try {
			const res = await fetch(`http://127.0.0.1:${PORT}/json/list`);
			targets = await res.json();
			if (targets.length) break;
		} catch (e) { /* belum siap */ }
		await sleep(250);
	}
	if (!targets || !targets.length) throw new Error('Chrome DevTools tidak merespons');

	const ws = new WebSocket(targets.find((t) => t.type === 'page').webSocketDebuggerUrl);
	let id = 0;
	const pending = new Map();
	const consoleErrors = [];

	ws.addEventListener('message', (ev) => {
		const msg = JSON.parse(ev.data);
		if (msg.id && pending.has(msg.id)) {
			pending.get(msg.id)(msg);
			pending.delete(msg.id);
		}
		if (msg.method === 'Runtime.exceptionThrown') {
			consoleErrors.push(msg.params.exceptionDetails.text);
		}
		if (msg.method === 'Log.entryAdded' && msg.params.entry.level === 'error') {
			consoleErrors.push(msg.params.entry.text);
		}
	});

	await new Promise((r) => ws.addEventListener('open', r));

	const send = (method, params = {}) => new Promise((resolve) => {
		const msgId = ++id;
		pending.set(msgId, resolve);
		ws.send(JSON.stringify({ id: msgId, method, params }));
	});

	await send('Runtime.enable');
	await send('Log.enable');
	await send('Page.enable');

	const evalJs = async (expr) => {
		const res = await send('Runtime.evaluate', { expression: expr, returnByValue: true, awaitPromise: true });
		return res.result && res.result.result ? res.result.result.value : null;
	};

	/**
	 * Navigasi lalu tunggu event `load` (bukan sleep tetap), supaya halaman
	 * yang memuat aset dari CDN (mis. Swiper) tidak diukur sebelum selesai.
	 */
	const navigate = async (url, extraWait = 400) => {
		const loaded = new Promise((resolve) => {
			const onMessage = (ev) => {
				const msg = JSON.parse(ev.data);
				if (msg.method === 'Page.loadEventFired') {
					ws.removeEventListener('message', onMessage);
					resolve(true);
				}
			};
			ws.addEventListener('message', onMessage);
			setTimeout(() => {
				ws.removeEventListener('message', onMessage);
				resolve(false);
			}, 10000);
		});

		await send('Page.navigate', { url });
		await loaded;
		await sleep(extraWait);
	};

	let failures = 0;

	for (const vp of VIEWPORTS) {
		await send('Emulation.setDeviceMetricsOverride', {
			width: vp.w, height: vp.h, deviceScaleFactor: 1, mobile: vp.w < 768,
		});

		for (const pg of PAGES) {
			await navigate(BASE + pg.path);

			const report = await evalJs(`(() => {
				const cs = (el, prop) => el ? getComputedStyle(el).getPropertyValue(prop) : null;
				const body = document.body;
				const h1 = document.querySelector('h1');
				const mainNav = document.querySelector('header nav[aria-label="Navigasi utama"]');
				const burger = document.querySelector('[data-ds-drawer-open]');
				const card = document.querySelector('.ds-product-card');
				const btn = document.querySelector('.ds-btn--primary');
				return {
					overflow: document.documentElement.scrollWidth - document.documentElement.clientWidth,
					bodyBg: cs(body, 'background-color'),
					bodyFont: cs(body, 'font-family'),
					h1Font: cs(h1, 'font-family'),
					h1Size: cs(h1, 'font-size'),
					h1Transform: cs(h1, 'text-transform'),
					navDisplay: cs(mainNav, 'display'),
					burgerDisplay: cs(burger, 'display'),
					cardRadius: cs(card, 'border-radius'),
					cardBg: cs(card, 'background-color'),
					panelRadius: cs(document.querySelector('.ds-panel'), 'border-radius'),
					btnRadius: cs(btn, 'border-radius'),
					btnBg: cs(btn, 'background-color'),
					btnHeight: cs(btn, 'height'),
					logoFont: cs(document.querySelector('.ds-logo'), 'font-family'),
				};
			})()`);

			const problems = [];
			if (report.overflow > 1) problems.push(`overflow-x +${report.overflow}px`);
			if (!/Anton/i.test(report.h1Font || '')) problems.push(`h1 font=${report.h1Font}`);
			if (!/Space Mono/i.test(report.bodyFont || '')) problems.push(`body font=${report.bodyFont}`);
			if (report.h1Transform !== 'uppercase') problems.push(`h1 transform=${report.h1Transform}`);
			if (report.bodyBg !== 'rgb(233, 224, 208)') problems.push(`body bg=${report.bodyBg}`);
			if (!/Anton/i.test(report.logoFont || '')) problems.push(`logo font=${report.logoFont}`);

			const isDesktop = vp.w >= 1024;
			if (isDesktop && report.navDisplay === 'none') problems.push('nav desktop tersembunyi');
			if (!isDesktop && report.burgerDisplay === 'none') problems.push('burger mobile tersembunyi');
			if (!isDesktop && report.navDisplay !== 'none') problems.push('nav desktop tampil di mobile');

			// Kartu produk hanya ada di halaman beranda/katalog/detail.
			if (report.cardRadius && report.cardRadius !== '16px') problems.push(`card radius=${report.cardRadius}`);
			// Panel dipakai di keranjang/tentang/dashboard.
			if (report.panelRadius && report.panelRadius !== '24px') problems.push(`panel radius=${report.panelRadius}`);
			if (report.btnRadius !== '999px') problems.push(`btn radius=${report.btnRadius}`);

			const tag = problems.length ? 'GAGAL' : 'OK   ';
			if (problems.length) failures++;
			console.log(
				`${tag} ${vp.name.padEnd(13)} ${pg.name.padEnd(10)} h1=${report.h1Size} ` +
				`${problems.length ? '-> ' + problems.join('; ') : ''}`
			);
		}
	}

	if (consoleErrors.length) {
		console.log('\nError konsol:');
		[...new Set(consoleErrors)].forEach((e) => console.log('  -', e));
	} else {
		console.log('\nTidak ada error konsol.');
	}

	/* ---- Halaman admin (butuh login) ---- */
	const adminUser = process.env.ADMIN_USER;
	const adminPass = process.env.ADMIN_PASS;

	if (adminUser && adminPass) {
		console.log('\n--- Halaman admin ---');
		await send('Emulation.setDeviceMetricsOverride', { width: 1440, height: 900, deviceScaleFactor: 1, mobile: false });
		await navigate(BASE + '/admin/login', 300);

		const loginOk = await evalJs(`(() => {
			const u = document.querySelector('#username');
			const p = document.querySelector('#password');
			if (!u || !p) return 'form tidak ada';
			u.value = ${JSON.stringify(adminUser)};
			p.value = ${JSON.stringify(adminPass)};
			document.querySelector('form').submit();
			return 'submitted';
		})()`);
		await sleep(2000);

		const ADMIN_PAGES = [
			{ path: '/admin', name: 'dashboard' },
			{ path: '/admin/products', name: 'produk' },
			{ path: '/admin/categories', name: 'kategori' },
			{ path: '/admin/slider', name: 'slider' },
			{ path: '/admin/settings', name: 'pengaturan' },
			{ path: '/admin/account', name: 'akun' },
			{ path: '/admin/products/create', name: 'form-produk' },
		];

		for (const pg of ADMIN_PAGES) {
			await navigate(BASE + pg.path, 300);
			const r = await evalJs(`(() => {
				const cs = (el, p) => el ? getComputedStyle(el).getPropertyValue(p) : null;
				return {
					url: location.pathname,
					overflow: document.documentElement.scrollWidth - document.documentElement.clientWidth,
					h1Font: cs(document.querySelector('h1'), 'font-family'),
					h1Transform: cs(document.querySelector('h1'), 'text-transform'),
					menuActive: !!document.querySelector('.ds-menu-item[aria-current="page"]'),
					tableScroll: cs(document.querySelector('.ds-table-scroll'), 'overflow-x'),
					inputRadius: cs(document.querySelector('.ds-input'), 'border-radius'),
					inputHeight: cs(document.querySelector('.ds-input'), 'height'),
					btnRadius: cs(document.querySelector('.ds-btn--primary'), 'border-radius'),
					bg: cs(document.body, 'background-color'),
				};
			})()`);

			const problems = [];
			if (r.url.includes('login')) problems.push('tidak terautentikasi');
			if (r.overflow > 1) problems.push(`overflow-x +${r.overflow}px`);
			if (!/Anton/i.test(r.h1Font || '')) problems.push(`h1 font=${r.h1Font}`);
			if (r.h1Transform !== 'uppercase') problems.push(`h1 transform=${r.h1Transform}`);
			if (r.bg !== 'rgb(233, 224, 208)') problems.push(`bg=${r.bg}`);
			if (r.btnRadius && r.btnRadius !== '999px') problems.push(`btn radius=${r.btnRadius}`);
			if (r.inputRadius && r.inputRadius !== '999px') problems.push(`input radius=${r.inputRadius}`);
			if (r.inputHeight && parseFloat(r.inputHeight) < 44) problems.push(`input height=${r.inputHeight}`);

			if (problems.length) failures++;
			console.log(`${problems.length ? 'GAGAL' : 'OK   '} admin/${pg.name.padEnd(14)} ${problems.length ? '-> ' + problems.join('; ') : ''}`);
		}
	}

	console.log(failures === 0 ? '\nSEMUA PEMERIKSAAN LULUS' : `\n${failures} PEMERIKSAAN GAGAL`);
	ws.close();
	chrome.kill();
	process.exit(failures === 0 ? 0 : 1);
}

main().catch((e) => { console.error(e); chrome.kill(); process.exit(1); });
