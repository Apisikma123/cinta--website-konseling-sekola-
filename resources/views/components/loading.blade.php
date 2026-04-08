{{--
    Global Page Loading Overlay
    ===========================
    Dimasukkan SEKALI di setiap layout utama (app, student, teacher, dashboard, auth, guest).
    Secara otomatis tampil pada:
      1. Navigasi antar halaman (beforeunload)
      2. Submit form apa pun (kecuali data-no-loading / data-ajax)
      3. Klik link internal (kecuali data-no-loading / target="_blank")

    API publik:
      window.PageLoading.show(pesan?)   → tampilkan overlay
      window.PageLoading.hide()          → sembunyikan overlay
--}}

{{-- ─── OVERLAY ─────────────────────────────────────────────── --}}
<div id="global-loading"
     role="status"
     aria-label="Memuat halaman"
     aria-live="polite">

    {{-- Book Animation --}}
    <div class="book-loader">
        <div class="book">
            <div class="book__pg-shadow"></div>
            <div class="book__pg"></div>
            <div class="book__pg book__pg--2"></div>
            <div class="book__pg book__pg--3"></div>
            <div class="book__pg book__pg--4"></div>
            <div class="book__pg book__pg--5"></div>
        </div>
    </div>

    {{-- Text --}}
    <div class="gl-text">
        <p class="gl-title">Memuat<span class="gl-dots">...</span></p>
        <p id="global-loading-msg" class="gl-msg"></p>
    </div>
</div>

{{-- ─── CSS ─────────────────────────────────────────────────── --}}
<style>
/* ── Overlay ── */
#global-loading {
    display: none;           /* hidden by default — JS controls visibility */
    position: fixed;
    inset: 0;
    z-index: 9999;
    background: #ffffff;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0;
    /* Fade-in when shown */
    animation: gl-fadein 0.18s ease both;
}
#global-loading.gl-visible {
    display: flex;
}
@keyframes gl-fadein {
    from { opacity: 0; }
    to   { opacity: 1; }
}

/* ── Text ── */
.gl-text  { text-align: center; margin-top: 1.5rem; }
.gl-title {
    font-family: 'Poppins', 'Inter', sans-serif;
    font-size: .875rem;
    font-weight: 700;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: #7c3aed;
    margin: 0;
}
.gl-dots  { display: inline-block; animation: gl-pulse 1s ease-in-out infinite; }
.gl-msg   {
    font-family: 'Poppins', 'Inter', sans-serif;
    font-size: .75rem;
    font-weight: 500;
    color: #a78bfa;
    margin: .5rem 0 0;
    min-height: 1rem;
}
@keyframes gl-pulse {
    0%, 100% { opacity: 1; }
    50%       { opacity: .3; }
}

/* ── Book Animation ── */
.book-loader {
    display: flex;
    align-items: center;
    justify-content: center;
    perspective: 1200px;
    transform: scale(1.4) translateY(-12px);
}
.book,
.book__pg-shadow,
.book__pg { animation: cover 5s ease-in-out infinite; }

.book {
    background: linear-gradient(135deg, #a855f7 0%, #9333ea 100%);
    border-radius: .25rem;
    box-shadow: 0 .25rem .5rem rgba(0,0,0,.3), 0 0 0 .25rem #7e22ce inset;
    padding: .25rem;
    perspective: 37.5rem;
    position: relative;
    width: 8rem;
    height: 6rem;
    transform: translate3d(0,0,0);
    transform-style: preserve-3d;
}
.book__pg-shadow,
.book__pg {
    position: absolute;
    left: .25rem;
    width: calc(50% - .25rem);
}
.book__pg-shadow {
    animation-name: shadow;
    background-image: linear-gradient(-45deg,rgba(0,0,0,0) 50%,rgba(0,0,0,.3) 50%);
    filter: blur(.25rem);
    top: calc(100% - .25rem);
    height: 3.75rem;
    transform: scaleY(0);
    transform-origin: 100% 0%;
}
.book__pg {
    animation-name: pg1;
    background-color: #f5f3ff;
    background-image: linear-gradient(90deg,rgba(230,210,255,0) 87.5%,#e9d5ff);
    height: calc(100% - .5rem);
    transform-origin: 100% 50%;
}
.book__pg--2,.book__pg--3,.book__pg--4 {
    background-image:
        repeating-linear-gradient(#4c1d95 0 .125rem,rgba(76,29,149,0) .125rem .5rem),
        linear-gradient(90deg,rgba(230,210,255,0) 87.5%,#e9d5ff);
    background-repeat: no-repeat;
    background-position: center;
    background-size: 2.5rem 4.125rem, 100% 100%;
}
.book__pg--2 { animation-name: pg2; }
.book__pg--3 { animation-name: pg3; }
.book__pg--4 { animation-name: pg4; }
.book__pg--5 { animation-name: pg5; }

/* ── Keyframes (cover / shadow / pages) ── */
@keyframes cover {
    from,5%,45%,55%,95%,to { animation-timing-function:ease-out; background:linear-gradient(135deg,#a855f7 0%,#9333ea 100%); }
    10%,40%,60%,90%         { animation-timing-function:ease-in;  background:linear-gradient(135deg,#7e22ce 0%,#6b21a8 100%); }
}
@keyframes shadow {
    from,10.01%,20.01%,30.01%,40.01% { animation-timing-function:ease-in;  transform:translate3d(0,0,1px) scaleY(0) rotateY(0); }
    5%,15%,25%,35%,45%,55%,65%,75%,85%,95% { animation-timing-function:ease-out; transform:translate3d(0,0,1px) scaleY(.2) rotateY(90deg); }
    10%,20%,30%,40%,50%,to { animation-timing-function:ease-out; transform:translate3d(0,0,1px) scaleY(0) rotateY(180deg); }
    50.01%,60.01%,70.01%,80.01%,90.01% { animation-timing-function:ease-in; transform:translate3d(0,0,1px) scaleY(0) rotateY(180deg); }
    60%,70%,80%,90%,to { animation-timing-function:ease-out; transform:translate3d(0,0,1px) scaleY(0) rotateY(0); }
}
@keyframes pg1 {
    from,to { animation-timing-function:ease-in-out; background-color:#f5f3ff; transform:translate3d(0,0,1px) rotateY(.4deg); }
    10%,15% { animation-timing-function:ease-out; background-color:#f5f3ff; transform:translate3d(0,0,1px) rotateY(180deg); }
    20%,80% { animation-timing-function:ease-in;  background-color:#6b21a8; transform:translate3d(0,0,1px) rotateY(180deg); }
    85%,90% { animation-timing-function:ease-in-out; background-color:#f5f3ff; transform:translate3d(0,0,1px) rotateY(180deg); }
}
@keyframes pg2 {
    from,to  { animation-timing-function:ease-in; background-color:#6b21a8; transform:translate3d(0,0,1px) rotateY(.3deg); }
    5%,10%   { animation-timing-function:ease-in-out; background-color:#f5f3ff; transform:translate3d(0,0,1px) rotateY(.3deg); }
    20%,25%  { animation-timing-function:ease-out; background-color:#f5f3ff; transform:translate3d(0,0,1px) rotateY(179.9deg); }
    30%,70%  { animation-timing-function:ease-in;  background-color:#6b21a8; transform:translate3d(0,0,1px) rotateY(179.9deg); }
    75%,80%  { animation-timing-function:ease-in-out; background-color:#f5f3ff; transform:translate3d(0,0,1px) rotateY(179.9deg); }
    90%,95%  { animation-timing-function:ease-out; background-color:#f5f3ff; transform:translate3d(0,0,1px) rotateY(.3deg); }
}
@keyframes pg3 {
    from,10%,90%,to { animation-timing-function:ease-in; background-color:#6b21a8; transform:translate3d(0,0,1px) rotateY(.2deg); }
    15%,20% { animation-timing-function:ease-in-out; background-color:#f5f3ff; transform:translate3d(0,0,1px) rotateY(.2deg); }
    30%,35% { animation-timing-function:ease-out; background-color:#f5f3ff; transform:translate3d(0,0,1px) rotateY(179.8deg); }
    40%,60% { animation-timing-function:ease-in;  background-color:#6b21a8; transform:translate3d(0,0,1px) rotateY(179.8deg); }
    65%,70% { animation-timing-function:ease-in-out; background-color:#f5f3ff; transform:translate3d(0,0,1px) rotateY(179.8deg); }
    80%,85% { animation-timing-function:ease-out; background-color:#f5f3ff; transform:translate3d(0,0,1px) rotateY(.2deg); }
}
@keyframes pg4 {
    from,20%,80%,to { animation-timing-function:ease-in; background-color:#6b21a8; transform:translate3d(0,0,1px) rotateY(.1deg); }
    25%,30% { animation-timing-function:ease-in-out; background-color:#f5f3ff; transform:translate3d(0,0,1px) rotateY(.1deg); }
    40%,45% { animation-timing-function:ease-out; background-color:#f5f3ff; transform:translate3d(0,0,1px) rotateY(179.7deg); }
    50%     { animation-timing-function:ease-in;  background-color:#6b21a8; transform:translate3d(0,0,1px) rotateY(179.7deg); }
    55%,60% { animation-timing-function:ease-in-out; background-color:#f5f3ff; transform:translate3d(0,0,1px) rotateY(179.7deg); }
    70%,75% { animation-timing-function:ease-out; background-color:#f5f3ff; transform:translate3d(0,0,1px) rotateY(.1deg); }
}
@keyframes pg5 {
    from,30%,70%,to { animation-timing-function:ease-in; background-color:#6b21a8; transform:translate3d(0,0,1px) rotateY(0); }
    35%,40% { animation-timing-function:ease-in-out; background-color:#f5f3ff; transform:translate3d(0,0,1px) rotateY(0deg); }
    50%     { animation-timing-function:ease-in-out; background-color:#f5f3ff; transform:translate3d(0,0,1px) rotateY(179.6deg); }
    60%,65% { animation-timing-function:ease-out; background-color:#f5f3ff; transform:translate3d(0,0,1px) rotateY(0); }
}

/* ── Mobile tweaks ── */
@media (max-width: 480px) {
    .book-loader { transform: scale(1.1) translateY(-8px); }
    .gl-title    { font-size: .8rem; }
}
</style>

{{-- ─── JAVASCRIPT TRIGGER ──────────────────────────────────── --}}
<script>
(function () {
    'use strict';

    var overlay = document.getElementById('global-loading');
    var msgEl   = document.getElementById('global-loading-msg');
    if (!overlay) return;

    /* ── Public API ── */
    window.PageLoading = {
        show: function (msg) {
            if (msgEl) msgEl.textContent = msg || '';
            overlay.classList.add('gl-visible');
            document.body.style.overflow = 'hidden';
        },
        hide: function () {
            overlay.classList.remove('gl-visible');
            document.body.style.overflow = '';
            // Re-enable any disabled submit buttons
            document.querySelectorAll('[type="submit"][data-gl-disabled]').forEach(function (btn) {
                btn.disabled = false;
                btn.removeAttribute('data-gl-disabled');
            });
        }
    };

    /* ── 1. Hide as soon as page is ready (catches back-forward cache) ── */
    window.addEventListener('pageshow', function (e) {
        // pageshow fires after bfcache restore too — always hide
        PageLoading.hide();
    });

    /* ── 2. Show on unload (real navigation away) ── */
    window.addEventListener('beforeunload', function () {
        PageLoading.show();
    });

    /* ── 3. Form submit ── */
    document.addEventListener('submit', function (e) {
        var form = e.target;
        if (!form || form.tagName !== 'FORM') return;

        // Skip forms explicitly marked as AJAX / no-loading
        if (form.hasAttribute('data-no-loading')) return;
        if (form.hasAttribute('data-ajax')) return;

        // Disable all submit buttons to prevent double-post
        form.querySelectorAll('[type="submit"]:not([disabled])').forEach(function (btn) {
            btn.setAttribute('data-gl-disabled', '1');
            btn.disabled = true;
        });

        PageLoading.show();
    });

    /* ── 4. Internal link clicks ── */
    document.addEventListener('click', function (e) {
        var link = e.target.closest('a[href]');
        if (!link) return;

        var href = link.getAttribute('href') || '';

        // Skip: empty, hash anchors, javascript:, target="_blank", data-no-loading
        if (!href || href === '#' || href.startsWith('#') || href.startsWith('javascript')) return;
        if (link.getAttribute('target') === '_blank') return;
        if (link.hasAttribute('data-no-loading')) return;

        // Skip external links
        try {
            var url = new URL(href, window.location.origin);
            if (url.origin !== window.location.origin) return;
        } catch (_) { return; }

        // Only show for GET-like navigations (links don't submit forms)
        PageLoading.show();
    });

})();
</script>
