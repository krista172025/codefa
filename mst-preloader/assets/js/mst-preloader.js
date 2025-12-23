// Robust mst-preloader.js — fixed cookie reader (no invalid RegExp),
// keeps cookie/localStorage "shown" logic and safe fallbacks.
// DEFAULT_EXPIRY_DAYS: change to 1 (day) or 7 (week) if you want.

(function () {
    const DEFAULT_EXPIRY_DAYS = 365; // change to 1 for daily showing
    const DURATION = 8000; // ms
    const TEXTS = [
        "Находим лучшие направления",
        "Ищем выгодные предложения",
        "Подбираем идеальные туры",
        "Создаем незабываемые путешествия"
    ];

    // state
    let progress = 0;
    let textIndex = 0;
    let isExiting = false;

    // dom refs
    let preloader = null;
    let progressBar = null;
    let progressPercent = null;
    let dynamicText = null;
    let routePathMobile = null;
    let routePathDesktop = null;
    let movingDotMobile = null;
    let movingDotDesktop = null;

    // timers
    let progressInterval = null;
    let textTimers = [];
    let exitTimer = null;
    let hideTimer = null;
    let animationFrame = null;

    // helpers: cookies (safe parser without RegExp)
    function readCookie(name) {
        try {
            if (!document.cookie) return null;
            const parts = document.cookie.split('; ');
            for (let i = 0; i < parts.length; i++) {
                const idx = parts[i].indexOf('=');
                if (idx === -1) continue;
                const key = decodeURIComponent(parts[i].substring(0, idx));
                if (key === name) {
                    return decodeURIComponent(parts[i].substring(idx + 1));
                }
            }
            return null;
        } catch (e) {
            console.warn('readCookie failed', e);
            return null;
        }
    }
    function setCookie(name, value, days) {
        try {
            const maxAge = days ? days * 24 * 60 * 60 : 0;
            const secure = location.protocol === 'https:' ? '; Secure' : '';
            const s = `; path=/; max-age=${maxAge}; SameSite=Lax${secure}`;
            document.cookie = encodeURIComponent(name) + '=' + encodeURIComponent(value) + s;
        } catch (e) {
            console.warn('setCookie failed', e);
        }
    }

    function mst_setShownCookie(days) {
        const expiry = typeof days === 'number' ? days : DEFAULT_EXPIRY_DAYS;
        setCookie('mst_preloader_shown', '1', expiry);
        try { localStorage.setItem('mst_preloader_shown', '1'); } catch (e) { /* ignore */ }
    }

    // If already shown (cookie or localStorage) — remove immediately and bail out
    function earlyExitIfShown() {
        try {
            const cookie = readCookie('mst_preloader_shown');
            let ls = null;
            try { ls = localStorage.getItem('mst_preloader_shown'); } catch (e) { ls = null; }
            if (cookie === '1' || ls === '1') {
                const el = document.getElementById('mst-preloader');
                if (el && el.parentNode) {
                    el.parentNode.removeChild(el);
                }
                try { document.body.classList.remove('mst-preloader-active'); } catch (e) {}
                console.info('MST preloader: already shown (early exit)');
                return true;
            }
        } catch (e) {
            console.warn('earlyExitIfShown error', e);
        }
        return false;
    }

    // Start everything
    function init() {
        try {
            if (earlyExitIfShown()) return;

            preloader = document.getElementById('mst-preloader');
            progressBar = document.getElementById('progress-bar');
            progressPercent = document.getElementById('progress-percent');
            dynamicText = document.getElementById('dynamic-text');
            routePathMobile = document.getElementById('route-path-mobile');
            routePathDesktop = document.getElementById('route-path-desktop');
            movingDotMobile = document.getElementById('moving-dot-mobile');
            movingDotDesktop = document.getElementById('moving-dot-desktop');

            if (!preloader) {
                console.error('MST preloader: #mst-preloader not found in DOM. Aborting animations.');
                return;
            }

            try { document.body.classList.add('mst-preloader-active'); } catch (e) {}

            safeStartProgressAnimation();
            safeStartTextAnimation();
            safeStartRouteAnimation();
            scheduleExit();
        } catch (e) {
            console.error('MST preloader init error:', e);
        }
    }

    // Safe progress animation
    function safeStartProgressAnimation() {
        if (!progressBar || !progressPercent) {
            // not found — still ensure exit after DURATION
            return;
        }
        if (progressInterval) { clearInterval(progressInterval); progressInterval = null; }
        const stepDelay = Math.max(10, Math.floor(DURATION / 200));
        progressInterval = setInterval(() => {
            try {
                progress = Math.min(progress + 0.5, 100);
                progressBar.style.width = progress + '%';
                progressPercent.textContent = Math.round(progress);
                if (progress >= 100) {
                    clearInterval(progressInterval);
                    progressInterval = null;
                }
            } catch (e) {
                console.warn('MST preloader progress update error:', e);
                clearInterval(progressInterval);
                progressInterval = null;
            }
        }, stepDelay);
    }

    function safeStartTextAnimation() {
        textTimers.forEach(t => clearTimeout(t));
        textTimers = [];
        try {
            textTimers.push(setTimeout(() => {
                textIndex = 1;
                if (dynamicText) dynamicText.textContent = TEXTS[textIndex];
            }, 2000));
            textTimers.push(setTimeout(() => {
                textIndex = 2;
                if (dynamicText) dynamicText.textContent = TEXTS[textIndex];
            }, 4000));
            textTimers.push(setTimeout(() => {
                textIndex = 3;
                if (dynamicText) dynamicText.textContent = TEXTS[textIndex];
            }, 6000));
        } catch (e) {
            console.warn('MST preloader text animation error', e);
        }
    }

    function safeStartRouteAnimation() {
        try {
            const isMobile = window.innerWidth < 640;
            const routePath = isMobile ? routePathMobile : routePathDesktop;
            const movingDot = isMobile ? movingDotMobile : movingDotDesktop;
            if (!routePath) return;
            let pathLength = 0;
            try { pathLength = routePath.getTotalLength(); } catch (e) {
                console.warn('routePath.getTotalLength failed', e);
                return;
            }
            let startTime = null;
            function animate(timestamp) {
                if (!startTime) startTime = timestamp;
                const elapsed = timestamp - startTime;
                const progressRatio = Math.min(elapsed / DURATION, 1);
                try {
                    const offset = pathLength * (1 - progressRatio);
                    routePath.style.strokeDashoffset = offset;
                    const point = routePath.getPointAtLength(pathLength * progressRatio);
                    if (movingDot && point) {
                        movingDot.setAttribute('cx', point.x);
                        movingDot.setAttribute('cy', point.y);
                    }
                } catch (e) {
                    console.warn('route animation frame error', e);
                    return;
                }
                if (progressRatio < 1 && !isExiting) {
                    animationFrame = requestAnimationFrame(animate);
                }
            }
            animationFrame = requestAnimationFrame(animate);
        } catch (e) {
            console.warn('MST preloader startRouteAnimation error', e);
        }
    }

    function scheduleExit() {
        if (exitTimer) clearTimeout(exitTimer);
        exitTimer = setTimeout(() => { exitPreloader(); }, DURATION);
    }

    function exitPreloader() {
        if (isExiting) return;
        isExiting = true;
        try { if (preloader) preloader.classList.add('exiting'); } catch (e) {}
        hideTimer = setTimeout(() => {
            try {
                if (preloader) {
                    preloader.classList.add('hidden');
                    preloader.parentNode && preloader.parentNode.removeChild(preloader);
                }
            } catch (e) {}
            cleanup();
            try { mst_setShownCookie(DEFAULT_EXPIRY_DAYS); } catch (e) {}
            try { document.body.classList.remove('mst-preloader-active'); } catch (e) {}
            if (typeof window.mstPreloaderComplete === 'function') {
                try { window.mstPreloaderComplete(); } catch (e) {}
            }
        }, 500);
    }

    function skipPreloader() { exitPreloader(); }

    function cleanup() {
        try { if (progressInterval) { clearInterval(progressInterval); progressInterval = null; } } catch (e) {}
        try { textTimers.forEach(t => clearTimeout(t)); textTimers = []; } catch (e) {}
        try { if (exitTimer) { clearTimeout(exitTimer); exitTimer = null; } } catch (e) {}
        try { if (hideTimer) { clearTimeout(hideTimer); hideTimer = null; } } catch (e) {}
        try { if (animationFrame) { cancelAnimationFrame(animationFrame); animationFrame = null; } } catch (e) {}
    }

    // Expose skip
    window.skipPreloader = skipPreloader;
    window.mst_setShownCookie = mst_setShownCookie;

    // Start when DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init, { once: true });
    } else {
        setTimeout(init, 0);
    }
})();