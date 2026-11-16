/* El Roi Khadoshi Motel – site scripts */
(function () {
	'use strict';

	/* Backup image if an online photo fails to load */
	var ph = (window.ELROI && window.ELROI.placeholder) || '';
	if (ph) {
		document.querySelectorAll('img').forEach(function (img) {
			var swap = function () { if (img.src !== ph) { img.src = ph; } };
			if (img.complete && img.naturalWidth === 0 && img.getAttribute('src')) { swap(); }
			img.addEventListener('error', swap, { once: true });
		});
	}

	/* Mobile menu */
	var toggle = document.querySelector('.nav-toggle');
	var nav = document.getElementById('primary-nav');
	if (toggle && nav) {
		toggle.addEventListener('click', function () {
			var open = nav.classList.toggle('is-open');
			toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
		});
		nav.addEventListener('click', function (e) {
			if (e.target.tagName === 'A') { nav.classList.remove('is-open'); toggle.setAttribute('aria-expanded', 'false'); }
		});
	}

	/* Header shadow on scroll */
	var header = document.getElementById('site-header');
	window.addEventListener('scroll', function () {
		if (header) { header.classList.toggle('is-scrolled', window.scrollY > 10); }
	}, { passive: true });

	/* Reveal on scroll */
	var revealEls = document.querySelectorAll('.section-head, .room-card, .amenity, .place-card, .split__text, .split__media, .room-detail, .value, .tip, .contact-card, .stats > div');
	if ('IntersectionObserver' in window) {
		var io = new IntersectionObserver(function (entries) {
			entries.forEach(function (en) {
				if (en.isIntersecting) { en.target.classList.add('is-visible'); io.unobserve(en.target); }
			});
		}, { threshold: 0.12 });
		revealEls.forEach(function (el) { el.classList.add('reveal'); io.observe(el); });
	}

	/* Gallery filters */
	var filters = document.querySelectorAll('.filter');
	filters.forEach(function (btn) {
		btn.addEventListener('click', function () {
			filters.forEach(function (b) { b.classList.remove('is-active'); });
			btn.classList.add('is-active');
			var cat = btn.getAttribute('data-filter');
			document.querySelectorAll('.gallery-item').forEach(function (item) {
				item.classList.toggle('is-hidden', cat !== 'all' && item.getAttribute('data-cat') !== cat);
			});
		});
	});

	/* Lightbox */
	var box = document.getElementById('lightbox');
	if (box) {
		var img = box.querySelector('img');
		var cap = box.querySelector('.lightbox__caption');
		var list = [], idx = 0;
		var show = function (i) {
			idx = (i + list.length) % list.length;
			var a = list[idx];
			var fallback = a.querySelector('img');
			img.onerror = function () { img.onerror = null; img.src = fallback ? fallback.src : ''; };
			img.src = a.href;
			img.alt = a.getAttribute('data-caption') || '';
			cap.textContent = a.getAttribute('data-caption') || '';
		};
		document.addEventListener('click', function (e) {
			var a = e.target.closest('.js-lightbox');
			if (!a) { return; }
			e.preventDefault();
			list = Array.prototype.filter.call(document.querySelectorAll('.js-lightbox'), function (el) { return !el.classList.contains('is-hidden'); });
			box.hidden = false;
			document.body.style.overflow = 'hidden';
			show(list.indexOf(a));
		});
		var close = function () { box.hidden = true; document.body.style.overflow = ''; };
		box.querySelector('.lightbox__close').addEventListener('click', close);
		box.querySelector('.lightbox__prev').addEventListener('click', function () { show(idx - 1); });
		box.querySelector('.lightbox__next').addEventListener('click', function () { show(idx + 1); });
		box.addEventListener('click', function (e) { if (e.target === box) { close(); } });
		document.addEventListener('keydown', function (e) {
			if (box.hidden) { return; }
			if (e.key === 'Escape') { close(); }
			if (e.key === 'ArrowLeft') { show(idx - 1); }
			if (e.key === 'ArrowRight') { show(idx + 1); }
		});
	}

	/* Date fields: check-out must be after check-in */
	document.querySelectorAll('form').forEach(function (form) {
		var cin = form.querySelector('[name="checkin"]');
		var cout = form.querySelector('[name="checkout"]');
		if (!cin || !cout) { return; }
		var sync = function () {
			if (!cin.value) { return; }
			var d = new Date(cin.value);
			d.setDate(d.getDate() + 1);
			var next = d.toISOString().slice(0, 10);
			cout.min = next;
			if (!cout.value || cout.value < next) { cout.value = next; }
		};
		cin.addEventListener('change', sync);
	});

	/* Booking total estimate */
	var bform = document.querySelector('.js-booking');
	if (bform && window.ELROI) {
		var out = bform.querySelector('.js-total');
		var calc = function () {
			var cin = bform.checkin.value, cout = bform.checkout.value;
			var price = window.ELROI.rooms[bform.room.value] || 0;
			var qty = parseInt(bform.rooms_qty.value, 10) || 1;
			if (!cin || !cout) { out.textContent = 'Choose your dates'; return; }
			var nights = Math.round((new Date(cout) - new Date(cin)) / 86400000);
			if (nights < 1) { out.textContent = 'Check your dates'; return; }
			out.textContent = 'UGX ' + (nights * price * qty).toLocaleString() + ' (' + nights + ' night' + (nights > 1 ? 's' : '') + ')';
		};
		['change', 'input'].forEach(function (ev) { bform.addEventListener(ev, calc); });
		calc();
	}
})();
