/* Folio3 Design Studio: interactions ported from the reference site (GSAP + ScrollTrigger + Lenis). */
(function () {
  'use strict';
  var d = document, w = window;
  if (!w.gsap || !w.ScrollTrigger) return;
  if (d.body.classList.contains('elementor-editor-active') || /[?&]elementor-preview=/.test(location.search)) return;

  var gsap = w.gsap, ST = w.ScrollTrigger;
  gsap.registerPlugin(ST);
  var reduce = w.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var fine = w.matchMedia('(pointer: fine)').matches;
  var EASE = 'expo.out';
  var $ = function (s, c) { return (c || d).querySelector(s); };
  var $$ = function (s, c) { return Array.prototype.slice.call((c || d).querySelectorAll(s)); };
  var title = function (el) { return $('.elementor-heading-title', el) || el; };
  d.documentElement.classList.add('f3-js');

  /* ---------- helpers ---------- */
  function splitWords(el, masked) {
    var words = [];
    (function walk(node) {
      Array.prototype.slice.call(node.childNodes).forEach(function (n) {
        if (n.nodeType === 3) {
          var frag = d.createDocumentFragment();
          n.textContent.split(/(\s+)/).forEach(function (part) {
            if (!part) return;
            if (/^\s+$/.test(part)) { frag.appendChild(d.createTextNode(' ')); return; }
            var inner = d.createElement('span'); inner.className = 'f3-w__i'; inner.textContent = part;
            if (masked) { var outer = d.createElement('span'); outer.className = 'f3-w'; outer.appendChild(inner); frag.appendChild(outer); }
            else { inner.className = 'f3-word'; frag.appendChild(inner); }
            words.push(inner);
          });
          n.parentNode.replaceChild(frag, n);
        } else if (n.nodeType === 1 && n.tagName !== 'BR') { walk(n); }
      });
    })(el);
    return words;
  }
  function roll(el) {
    if (!el || el.dataset.f3Roll) return;
    el.dataset.f3Roll = '1';
    var text = el.textContent; el.textContent = '';
    var wrap = d.createElement('span'); wrap.className = 'f3-roll'; wrap.setAttribute('aria-hidden', 'true');
    text.split('').forEach(function (ch, i) {
      var s = d.createElement('span'); s.className = 'f3-roll__ch'; s.textContent = ch; s.dataset.ch = ch; s.style.setProperty('--i', i);
      wrap.appendChild(s);
    });
    var sr = d.createElement('span'); sr.className = 'f3-sr'; sr.textContent = text;
    el.appendChild(wrap); el.appendChild(sr);
  }
  /* Play a paused timeline each time its trigger scrolls into view; rewind once it has fully left. */
  function onView(tl, trigger, start) {
    tl.pause(0);
    var play = function () { if (tl.progress() === 0) tl.play(); };
    ST.create({ trigger: trigger, start: start || 'top 88%', end: 'bottom top', onEnter: play, onEnterBack: play });
    ST.create({ trigger: trigger, start: 'top bottom', end: 'bottom top', onLeave: function () { tl.pause(0); }, onLeaveBack: function () { tl.pause(0); } });
  }
  function off(el, root) { var x = 0, y = 0; while (el && el !== root) { x += el.offsetLeft; y += el.offsetTop; el = el.offsetParent; } return { x: x, y: y }; }

  /* ---------- hover micro-interactions (run for everyone) ---------- */
  $$('.f3-link a').forEach(roll);
  $$('.f3-btn .elementor-button').forEach(function (b) {
    roll($('.elementor-button-text', b));
    var icon = $('.elementor-button-icon', b);
    if (icon && icon.firstElementChild && icon.children.length === 1) icon.appendChild(icon.firstElementChild.cloneNode(true));
  });

  /* Header: hide on scroll down, show on scroll up */
  var header = $('.f3-header');
  if (header) {
    var last = w.scrollY;
    w.addEventListener('scroll', function () {
      var y = w.scrollY;
      if (y < 24) { header.classList.remove('is-hidden', 'is-shown'); }
      else if (y > last + 4) { header.classList.add('is-hidden'); header.classList.remove('is-shown'); }
      else if (y < last - 4) { header.classList.remove('is-hidden'); header.classList.add('is-shown'); }
      last = y;
    }, { passive: true });
  }

  /* Back to top */
  var top = d.createElement('button');
  top.className = 'f3-totop'; top.type = 'button'; top.setAttribute('aria-label', 'Back to top'); top.innerHTML = '<span aria-hidden="true">↑</span>';
  d.body.appendChild(top);
  w.addEventListener('scroll', function () { top.classList.toggle('is-visible', w.scrollY > w.innerHeight * 1.2); }, { passive: true });

  /* Focus lists: one card is "active" (dark), driven by scroll and hover */
  function focusList(listSel, itemSel) {
    $$(listSel).forEach(function (list) {
      var items = $$(itemSel, list); if (items.length < 2) return;
      var cur = 0, hovering = false;
      var paint = function (i) { items.forEach(function (it, k) { it.classList.toggle('is-active', k === i); }); };
      paint(0);
      ST.create({ trigger: list, start: 'top 75%', end: 'bottom 35%', onUpdate: function (s) {
        var i = Math.min(items.length - 1, Math.floor(s.progress * items.length)); if (i !== cur) { cur = i; if (!hovering) paint(i); } } });
      if (fine) {
        items.forEach(function (it, i) { it.addEventListener('mouseenter', function () { hovering = true; paint(i); }); });
        list.addEventListener('mouseleave', function () { hovering = false; paint(cur); });
      }
    });
  }
  focusList('.f3-picks', '.f3-stage');
  focusList('.f3-models', '.f3-price');
  focusList('.f3-tiers', '.f3-price');

  /* Testimonials: drag to scroll + arrows */
  $$('.f3-quotes').forEach(function (row) {
    var nav = d.createElement('div'); nav.className = 'f3-quotes__nav';
    [-1, 1].forEach(function (dir) {
      var b = d.createElement('button'); b.type = 'button'; b.setAttribute('aria-label', dir < 0 ? 'Previous testimonials' : 'Next testimonials'); b.textContent = dir < 0 ? '←' : '→';
      b.addEventListener('click', function () { row.scrollBy({ left: dir * Math.min(row.clientWidth * 0.8, 520), behavior: 'smooth' }); });
      nav.appendChild(b);
    });
    row.parentNode.insertBefore(nav, row);
    var down = false, sx = 0, sl = 0, moved = false;
    row.addEventListener('pointerdown', function (e) { if (e.pointerType !== 'mouse') return; down = true; moved = false; sx = e.clientX; sl = row.scrollLeft; });
    w.addEventListener('pointermove', function (e) { if (!down) return; var dx = e.clientX - sx; if (Math.abs(dx) > 4) { moved = true; row.classList.add('is-dragging'); } row.scrollLeft = sl - dx; });
    w.addEventListener('pointerup', function () { down = false; row.classList.remove('is-dragging'); });
    row.addEventListener('click', function (e) { if (moved) { e.preventDefault(); e.stopPropagation(); } }, true);
  });

  if (reduce) return;

  /* ---------- smooth scroll ---------- */
  var lenis = null;
  if (w.Lenis && fine) {
    lenis = new w.Lenis({ lerp: 0.1, wheelMultiplier: 1 });
    lenis.on('scroll', ST.update);
    gsap.ticker.add(function (t) { lenis.raf(t * 1000); });
    gsap.ticker.lagSmoothing(0);
  }
  var scrollTo = function (target) { if (lenis) lenis.scrollTo(target, { offset: -80 }); else if (target.scrollIntoView) target.scrollIntoView({ behavior: 'smooth' }); else w.scrollTo({ top: 0, behavior: 'smooth' }); };
  top.addEventListener('click', function () { if (lenis) lenis.scrollTo(0); else w.scrollTo({ top: 0, behavior: 'smooth' }); });
  d.addEventListener('click', function (e) {
    var a = e.target.closest('a[href*="#"]'); if (!a) return;
    var u; try { u = new URL(a.href); } catch (err) { return; }
    if (u.pathname !== location.pathname || u.hash.length < 2) return;
    var t = d.getElementById(u.hash.slice(1)); if (!t) return;
    e.preventDefault(); scrollTo(t);
  });

  var mm = gsap.matchMedia();
  var handled = new Set();
  var mark = function (els) { els.forEach(function (e) { handled.add(e); }); return els; };

  /* Header drop-in */
  if (header) gsap.from(header.children, { y: -24, opacity: 0, duration: 1.1, ease: EASE });

  /* ---------- headings: masked word reveal ---------- */
  $$('.f3-split').forEach(function (h) {
    if (h.closest('.f3-hero') || h.closest('.f3-cta')) return;
    var words = splitWords(title(h), true); if (!words.length) return;
    onView(gsap.from(words, { yPercent: 110, duration: 0.85, ease: EASE, stagger: 0.022, paused: true }), h, 'top bottom');
  });

  /* ---------- hero: pinned, fanned deck that deals out into a row ---------- */
  var hero = $('.f3-hero'), deck = $('.f3-hero-deck'), htitle = $('.f3-hero-title');
  if (hero && deck && htitle) {
    var hwords = splitWords(title(htitle), true);
    gsap.from(hwords, { yPercent: 110, duration: 1.3, ease: EASE, stagger: 0.06, delay: 0.25 });
    var cards = $$('.f3-hero-card', deck);
    d.documentElement.classList.add('f3-hero-intro');
    mm.add('(min-width: 1024px)', function () {
      var FAN = [{ x: -0.342, y: -0.008, r: -9 }, { x: -0.112, y: -0.078, r: -3 }, { x: 0.119, y: -0.058, r: 4 }, { x: 0.349, y: 0.012, r: 10 }];
      var pos = [], lift = 0;
      var measure = function () {
        var t = off(htitle, hero), dk = off(deck, hero);
        var cx = dk.x + deck.offsetWidth / 2, cy = t.y + htitle.offsetHeight * 0.36;
        pos = cards.map(function (c, i) {
          var o = off(c, hero), f = FAN[i % 4];
          return { x: cx + f.x * c.offsetWidth * 1.15 - (o.x + c.offsetWidth / 2), y: cy + f.y * c.offsetHeight - (o.y + c.offsetHeight / 2), r: f.r };
        });
        lift = Math.max(0, hero.offsetHeight - w.innerHeight + 8);
      };
      measure();
      var tl = gsap.timeline({ defaults: { ease: 'power2.inOut', duration: 1 }, scrollTrigger: {
        trigger: hero, start: 'top top', end: '+=110%', pin: true, pinSpacing: true, scrub: 0.6, invalidateOnRefresh: true, onRefreshInit: measure } });
      cards.forEach(function (c, i) {
        c.style.zIndex = String(10 - i);
        tl.fromTo(c, { x: function () { return pos[i].x; }, y: function () { return pos[i].y; }, rotation: function () { return pos[i].r; }, scale: 1.18 },
          { x: 0, y: 0, rotation: 0, scale: 1 }, 0.15);
      });
      tl.fromTo([htitle, deck], { y: 0 }, { y: function () { return -lift; }, ease: 'none', duration: 1.15 }, 0);
      return function () { gsap.set(cards.concat([htitle, deck]), { clearProps: 'transform,zIndex' }); };
    });
  }

  /* ---------- statement: words fly in from a scatter as you scroll ---------- */
  var st = $('.f3-statement');
  if (st) {
    var sw = splitWords(title(st), false), seed = 117;
    var rnd = function () { seed = (seed * 16807) % 2147483647; return (seed - 1) / 2147483646; };
    var vec = sw.map(function () { var a = rnd() * Math.PI * 2, m = 0.35 + rnd() * 0.65; return { x: Math.cos(a) * m, y: Math.sin(a) * m, r: (rnd() - 0.5) * 24 }; });
    var spread = 0, n = sw.length, lag = 0.32;
    var draw = function (p) {
      sw.forEach(function (el, i) {
        var s = i / Math.max(1, n - 1) * lag, k = Math.min(1, Math.max(0, (p - s) / (1 - lag))), inv = 1 - k;
        gsap.set(el, { x: vec[i].x * spread * inv, y: vec[i].y * spread * 0.6 * inv, rotation: vec[i].r * inv, opacity: 0.12 + 0.88 * k });
      });
    };
    var size = function () { spread = Math.min(1000, w.innerWidth * 0.75); };
    size();
    ST.create({ trigger: st, start: 'top bottom', end: 'top 35%', scrub: 0.6, invalidateOnRefresh: true,
      onRefresh: function (s) { size(); draw(s.progress); }, onUpdate: function (s) { draw(s.progress); } });
    draw(0);
  }

  /* ---------- client logos: rows drift in from opposite sides ---------- */
  var logos = $('.f3-logos');
  if (logos) {
    var cells = mark($$('.f3-logo-cell', logos));
    onView(gsap.from(cells, { y: 28, opacity: 0, scale: 0.9, duration: 0.8, ease: EASE, stagger: 0.02, paused: true }), logos, 'top bottom');
    mm.add('(min-width: 1025px)', function () {
      var rows = []; cells.forEach(function (c, i) { (rows[Math.floor(i / 7)] = rows[Math.floor(i / 7)] || []).push($('.elementor-widget-image', c) || c.firstElementChild); });
      rows.forEach(function (row, p) {
        var dir = p % 2 ? 1 : -1;
        gsap.timeline({ scrollTrigger: { trigger: logos, start: 'top bottom', end: 'bottom top', scrub: 0.9 } })
          .fromTo(row, { x: function () { return dir * w.innerWidth * (0.16 + p * 0.04); } }, { x: 0, ease: 'power2.out', duration: 0.5 })
          .to(row, { x: function () { return -dir * w.innerWidth * 0.06; }, ease: 'power1.in', duration: 0.5 });
      });
    });
  }

  /* ---------- capabilities: pinned stack of cards ---------- */
  var capsDeck = $('.f3-capsdeck');
  if (capsDeck) {
    var caps = mark($$('.f3-capcard', capsDeck));
    caps[0] && caps[0].classList.add('is-active');
    mm.add('(min-width: 1024px)', function () {
      var STEP = 14;
      var h = Math.max.apply(null, caps.map(function (c) { return c.offsetHeight; }));
      if (h + STEP * (caps.length - 1) > w.innerHeight - 120) return;
      capsDeck.classList.add('is-stack');
      capsDeck.style.height = (h + STEP * (caps.length - 1)) + 'px';
      caps.forEach(function (c) { c.style.height = h + 'px'; });
      var active = function (i) { caps.forEach(function (c, k) { c.classList.toggle('is-active', k === i); }); };
      gsap.set(caps, { y: function (i) { return i === 0 ? 0 : w.innerHeight; }, scale: 1, zIndex: function (i) { return i + 1; } });
      var tl = gsap.timeline({ defaults: { ease: 'none', duration: 1 }, scrollTrigger: {
        trigger: capsDeck, start: 'top 96px', end: function () { return '+=' + (caps.length - 1) * w.innerHeight * 0.8; }, pin: true, pinSpacing: true, scrub: 0.6, invalidateOnRefresh: true,
        onUpdate: function (s) { active(Math.min(caps.length - 1, Math.floor(s.progress * (caps.length - 1) + 0.5))); } } });
      for (var u = 1; u < caps.length; u++) {
        (function (u) {
          tl.fromTo(caps[u], { y: function () { return w.innerHeight; } }, { y: u * STEP, ease: 'power2.out' }, u - 1);
          tl.to(caps.slice(0, u), { scale: function (i) { return 1 - (u - i) * 0.035; } }, u - 1);
        })(u);
      }
      return function () { capsDeck.classList.remove('is-stack'); capsDeck.style.height = ''; caps.forEach(function (c) { c.style.height = ''; }); gsap.set(caps, { clearProps: 'all' }); active(0); };
    });
  }

  /* ---------- offers: timeline rail that fills as you scroll ---------- */
  var flow = $('.f3-flow');
  if (flow) {
    var steps = $$('.f3-flow__step', flow), reached = new Set();
    var rail = d.createElement('div'); rail.className = 'f3-flow__rail'; rail.innerHTML = '<i></i>'; flow.appendChild(rail);
    var paint = function () { var cur = reached.size ? Math.max.apply(null, Array.from(reached)) : -1;
      steps.forEach(function (s, i) { s.classList.toggle('is-reached', reached.has(i)); s.classList.toggle('is-current', i === cur); }); };
    steps.forEach(function (s, i) {
      var node = d.createElement('span'); node.className = 'f3-flow__node'; node.textContent = ('0' + (i + 1)).slice(-2); s.appendChild(node);
      ST.create({ trigger: node, start: 'center 60%', onEnter: function () { reached.add(i); paint(); }, onLeaveBack: function () { reached.delete(i); paint(); } });
      var card = $('.f3-offer', s), num = $('.f3-flow__num', s);
      mark([card]);
      var tl = gsap.timeline({ paused: true, defaults: { ease: EASE } });
      tl.from(card, { x: w.innerWidth >= 1024 ? (i % 2 ? 56 : -56) : 32, opacity: 0, duration: 0.75, clearProps: 'transform,opacity' }, 0);
      if (num) tl.from(num, { y: 40, opacity: 0, duration: 0.9, clearProps: 'transform,opacity' }, 0.08);
      onView(tl, s, 'top 92%');
    });
    gsap.fromTo(rail.firstChild, { scaleY: 0 }, { scaleY: 1, ease: 'none', scrollTrigger: { trigger: rail, start: 'top 60%', end: 'bottom 60%', scrub: 0.3 } });
  }

  /* ---------- stage cards ---------- */
  var picks = $('.f3-picks');
  if (picks) onView(gsap.from(mark($$('.f3-stage', picks)), { y: 36, opacity: 0, duration: 0.7, ease: EASE, stagger: 0.06, paused: true, clearProps: 'transform,opacity' }), picks, 'top bottom+=80');

  /* ---------- oversized line that slides sideways with the scroll ---------- */
  var mega = $('.f3-ticker__text .elementor-heading-title');
  if (mega) gsap.fromTo(mega, { x: function () { return w.innerWidth * 0.08; } }, { x: function () { return -Math.max(0, mega.scrollWidth - w.innerWidth + 48); }, ease: 'none',
    scrollTrigger: { trigger: mega.closest('.f3-ticker'), start: 'top bottom', end: 'bottom 50%', scrub: 0.4, invalidateOnRefresh: true } });

  /* ---------- case cards: wipe-up reveal, image settles, slow parallax ---------- */
  $$('.f3-workcard').forEach(function (card) {
    mark([card]);
    var media = $('.f3-workcard__media', card), img = media && $('img', media); if (!media || !img) return;
    var tl = gsap.timeline({ paused: true });
    tl.fromTo(media, { clipPath: 'inset(100% 0% 0% 0%)' }, { clipPath: 'inset(0% 0% 0% 0%)', duration: 1, ease: 'expo.inOut' }, 0)
      .fromTo(img, { scale: 1.3 }, { scale: 1, duration: 1.3, ease: EASE }, 0);
    var meta = media.nextElementSibling; if (meta) tl.from(meta, { y: 20, opacity: 0, duration: 0.7, ease: EASE }, 0.15);
    onView(tl, card, 'top bottom');
    gsap.fromTo(img, { yPercent: -8 }, { yPercent: 0, ease: 'none', scrollTrigger: { trigger: card, start: 'top bottom', end: 'bottom top', scrub: true } });
  });

  /* ---------- industries: tiles tip up into place ---------- */
  var ind = $('.f3-indgrid');
  if (ind) onView(gsap.from(mark($$('.f3-ind', ind)), { y: 50, opacity: 0, rotationX: -25, transformOrigin: '50% 100%', transformPerspective: 800, duration: 0.8, ease: EASE,
    stagger: { each: 0.04, grid: [2, 4], from: 'start' }, paused: true }), ind, 'top bottom');

  /* ---------- process: gantt bars draw in one after another ---------- */
  var gantt = $('.f3-gantt');
  if (gantt) {
    var bars = $$('.f3-gantt__bar', gantt).reverse(), stepsEl = $$('.f3-step');
    mark(stepsEl);
    var gt = gsap.timeline({ paused: true, defaults: { ease: EASE } });
    bars.forEach(function (b, i) {
      var at = 0.05 + i * 0.2;
      gt.fromTo(b, { scaleX: 0, transformOrigin: '0 50%' }, { scaleX: 1, duration: 0.8, ease: 'expo.inOut', clearProps: 'transform' }, at)
        .fromTo(b.firstElementChild, { opacity: 0, x: -12 }, { opacity: 1, x: 0, duration: 0.5, clearProps: 'all' }, at + 0.3);
      if (stepsEl[i]) gt.fromTo(stepsEl[i], { opacity: 0, y: 20 }, { opacity: 1, y: 0, duration: 0.7, clearProps: 'all' }, at + 0.15);
    });
    onView(gt, gantt, 'top bottom');
  } else {
    $$('.f3-step').forEach(function () {});
  }

  /* ---------- partners: wipe-up reveal + alternating parallax ---------- */
  var partners = $('.f3-partners');
  if (partners) {
    var ps = mark($$('.f3-partner', partners));
    var pt = gsap.timeline({ paused: true, defaults: { ease: EASE } });
    pt.fromTo(ps, { clipPath: 'inset(100% 0% 0% 0% round 16px)', y: 60 }, { clipPath: 'inset(0% 0% 0% 0% round 16px)', y: 0, duration: 1, ease: 'expo.inOut', stagger: 0.06, clearProps: 'clipPath' }, 0.08);
    onView(pt, partners, 'top bottom');
    ps.forEach(function (p, i) { gsap.fromTo(p, { yPercent: i % 2 ? 6 : 0 }, { yPercent: i % 2 ? -6 : 0, ease: 'none', scrollTrigger: { trigger: partners, start: 'top bottom', end: 'bottom top', scrub: true } }); });
  }

  /* ---------- CTA band: grows into place, card fan flies in ---------- */
  $$('.f3-cta').forEach(function (cta) {
    mark([cta]);
    var fan = d.createElement('div'); fan.className = 'f3-cta__fan'; fan.setAttribute('aria-hidden', 'true'); fan.innerHTML = '<i></i><i></i><i></i><i></i>'; cta.appendChild(fan);
    gsap.fromTo(cta, { scale: 0.92, borderRadius: 48 }, { scale: 1, borderRadius: 24, ease: 'none', scrollTrigger: { trigger: cta, start: 'top bottom', end: 'top 40%', scrub: true } });
    var words = []; $$('.f3-split', cta).forEach(function (h) { words = words.concat(splitWords(title(h), true)); });
    var tl = gsap.timeline({ paused: true });
    tl.from(fan, { y: 320, x: 160, rotation: 35, opacity: 0, duration: 1.2, ease: EASE }, 0)
      .from(words, { yPercent: 110, duration: 0.85, ease: EASE, stagger: 0.025 }, 0.08)
      .from($$('.elementor-widget-text-editor, .elementor-widget-button', cta), { y: 20, opacity: 0, duration: 0.7, ease: EASE, stagger: 0.06 }, 0.25);
    onView(tl, cta, 'top 92%');
  });

  /* ---------- footer wordmark: blinds open as you reach the end ---------- */
  var fm = $('.f3-mega .elementor-heading-title');
  if (fm) {
    var blinds = d.createElement('span'); blinds.className = 'f3-blinds'; blinds.setAttribute('aria-hidden', 'true');
    for (var b = 0; b < 6; b++) blinds.appendChild(d.createElement('i'));
    fm.appendChild(blinds);
    var bars2 = $$('i', blinds), gap = 0.416 / (bars2.length - 1);
    var paintB = function (p) { bars2.forEach(function (el, i) { gsap.set(el, { scaleY: Math.min(1, Math.max(0, 1 - (p - i * gap) / 0.584)) }); }); };
    var topOf = function () { return fm.getBoundingClientRect().top + w.scrollY; };
    ST.create({ trigger: fm, start: function () { return Math.min(topOf() - w.innerHeight * 0.75, ST.maxScroll(w) - 1); }, end: function () { return Math.min(topOf() - w.innerHeight * 0.25, ST.maxScroll(w)); },
      invalidateOnRefresh: true, onUpdate: function (s) { paintB(s.progress); }, onRefresh: function (s) { paintB(s.progress); } });
    paintB(0);
  }

  /* ---------- inner pages ---------- */
  $$('.f3-svc').forEach(function (blk, i) {
    mark([blk]);
    var img = $('.f3-cover img', blk), cols = Array.prototype.slice.call(blk.children).filter(function (c) { return c.classList.contains('e-con'); });
    var tl = gsap.timeline({ paused: true, defaults: { ease: EASE } });
    tl.from(blk, { y: 60, opacity: 0, duration: 0.9, clearProps: 'transform,opacity' }, 0);
    if (cols[0]) tl.from($$('.elementor-widget', cols[0]), { y: 24, opacity: 0, duration: 0.7, stagger: 0.05, clearProps: 'transform,opacity' }, 0.1);
    if (img) { tl.fromTo(img, { scale: 1.25 }, { scale: 1, duration: 1.3 }, 0);
      gsap.fromTo(img, { yPercent: -6 }, { yPercent: 6, ease: 'none', scrollTrigger: { trigger: blk, start: 'top bottom', end: 'bottom top', scrub: true } }); }
    onView(tl, blk, 'top bottom');
  });
  $$('.f3-process-img, .f3-reveal-img').forEach(function (el) {
    var img = $('img', el); if (!img) return;
    var tl = gsap.timeline({ paused: true });
    tl.fromTo(img, { clipPath: 'inset(100% 0% 0% 0%)' }, { clipPath: 'inset(0% 0% 0% 0%)', duration: 1.1, ease: 'expo.inOut' }, 0).fromTo(img, { scale: 1.15 }, { scale: 1, duration: 1.4, ease: EASE }, 0);
    var cap = $('figcaption', el); if (cap) tl.from(cap, { y: 12, opacity: 0, duration: 0.6, ease: EASE }, 0.5);
    onView(tl, el, 'top bottom');
  });
  $$('.f3-chapter .elementor-heading-title').forEach(function (el) {
    gsap.fromTo(el, { yPercent: 40 }, { yPercent: -20, ease: 'none', scrollTrigger: { trigger: el, start: 'top bottom', end: 'bottom top', scrub: true } });
  });

  /* ---------- everything else: grouped fade-up ---------- */
  var groups = new Map();
  $$('.f3-card, .f3-quote, .f3-fade').forEach(function (el) {
    if (handled.has(el) || el.closest('.is-stack')) return;
    var p = el.parentNode; if (!groups.has(p)) groups.set(p, []); groups.get(p).push(el);
  });
  groups.forEach(function (els, parent) {
    onView(gsap.from(els, { y: 40, opacity: 0, duration: 0.8, ease: EASE, stagger: 0.07, paused: true, clearProps: 'transform,opacity' }), parent, 'top bottom');
  });

  if (d.fonts && d.fonts.ready) d.fonts.ready.then(function () { ST.refresh(); });
  w.addEventListener('load', function () { ST.refresh(); });
})();
