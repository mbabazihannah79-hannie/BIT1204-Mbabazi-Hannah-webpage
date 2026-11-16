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

/* ERK Photo Slider */
(function () {
	'use strict';
	function initSlider(el) {
		if (!el || el.dataset.ready) { return; }
		el.dataset.ready = '1';
		var track = el.querySelector('.erk-slider__track');
		var slides = el.querySelectorAll('.erk-slide');
		var dots = el.querySelectorAll('.erk-slider__dot');
		var fade = el.classList.contains('erk-slider--fade');
		var n = slides.length, i = 0, timer = null;
		var auto = el.dataset.autoplay === '1', speed = parseInt(el.dataset.speed, 10) || 4000;
		if (n < 2) { return; }
		function go(k) {
			i = (k + n) % n;
			if (!fade) { track.style.transform = 'translateX(' + (-100 * i) + '%)'; }
			slides.forEach(function (s, x) { s.classList.toggle('is-active', x === i); });
			dots.forEach(function (d, x) { d.classList.toggle('is-active', x === i); });
		}
		function play() { stop(); if (auto) { timer = setInterval(function () { go(i + 1); }, speed); } }
		function stop() { if (timer) { clearInterval(timer); timer = null; } }
		var prev = el.querySelector('.erk-slider__prev'), next = el.querySelector('.erk-slider__next');
		if (prev) { prev.addEventListener('click', function () { go(i - 1); play(); }); }
		if (next) { next.addEventListener('click', function () { go(i + 1); play(); }); }
		dots.forEach(function (d, x) { d.addEventListener('click', function () { go(x); play(); }); });
		el.addEventListener('mouseenter', stop);
		el.addEventListener('mouseleave', play);
		el.addEventListener('keydown', function (e) {
			if (e.key === 'ArrowLeft') { go(i - 1); play(); }
			if (e.key === 'ArrowRight') { go(i + 1); play(); }
		});
		var startX = null;
		el.addEventListener('touchstart', function (e) { startX = e.touches[0].clientX; stop(); }, { passive: true });
		el.addEventListener('touchend', function (e) {
			if (startX === null) { return; }
			var dx = e.changedTouches[0].clientX - startX;
			if (Math.abs(dx) > 40) { go(dx < 0 ? i + 1 : i - 1); }
			startX = null; play();
		});
		go(0); play();
	}
	window.erkInitSliders = function (root) { (root || document).querySelectorAll('.erk-slider').forEach(initSlider); };
	if (document.readyState !== 'loading') { window.erkInitSliders(); } else { document.addEventListener('DOMContentLoaded', function () { window.erkInitSliders(); }); }
	// Live preview inside the Elementor editor.
	if (window.jQuery) {
		window.jQuery(window).on('elementor/frontend/init', function () {
			if (window.elementorFrontend && window.elementorFrontend.hooks) {
				window.elementorFrontend.hooks.addAction('frontend/element_ready/erk-slider.default', function ($scope) { window.erkInitSliders($scope[0]); });
			}
		});
	}
})();
