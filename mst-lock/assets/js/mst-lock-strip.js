/*
 * mst-lock-strip.js
 * Client-side cleaner: removes title attributes, strips configured data-* attributes,
 * watches DOM changes and optionally blocks console/F12 keys and detects DevTools (overlay).
 *
 * Author: Telegram @l1ghtsun
 * Author URI: https://t.me/l1ghtsun
 */

(function () {
  'use strict';

  var cfg = (typeof window.mstLockClientConfig !== 'undefined') ? window.mstLockClientConfig : {
    removeTitle: true,
    removePatterns: ['data-mst*','data-latepoint*'],
    blockConsole: false,
    aggressiveDevtools: false
  };

  function patternToRegex(pat) {
    var esc = pat.replace(/[-\/\\^$+?.()|[\]{}]/g, '\\$&');
    esc = esc.replace(/\\\*/g, '.*?');
    return new RegExp('^' + esc + '$', 'i');
  }

  var patterns = (cfg.removePatterns || []).map(function (p) {
    return p.trim();
  }).filter(Boolean).map(patternToRegex);

  function matchesPattern(attrName) {
    for (var i = 0; i < patterns.length; i++) {
      if (patterns[i].test(attrName)) return true;
    }
    return false;
  }

  function stripTitles(el) {
    if (!el || !el.removeAttribute) return;
    if (el.hasAttribute('title')) {
      try {
        var val = el.getAttribute('title');
        if (val !== null) el.setAttribute('data-mst-title', val);
        el.removeAttribute('title');
      } catch (e) {}
    }
  }

  function stripConfiguredAttrs(el) {
    if (!el || !el.attributes) return;
    var attrs = el.attributes;
    var toRemove = [];
    for (var i = 0; i < attrs.length; i++) {
      var name = attrs[i].name;
      if (matchesPattern(name)) toRemove.push(name);
    }
    toRemove.forEach(function (name) {
      try { el.removeAttribute(name); } catch (e) {}
    });
  }

  function processNode(node) {
    if (!node) return;
    if (node.nodeType !== 1) return;
    if (cfg.removeTitle) stripTitles(node);
    stripConfiguredAttrs(node);

    var imgs = node.querySelectorAll && node.querySelectorAll('img[title]');
    if (imgs && imgs.length) {
      for (var i = 0; i < imgs.length; i++) stripTitles(imgs[i]);
    }
    var els = node.querySelectorAll && node.querySelectorAll('*');
    if (els && els.length) {
      for (var j = 0; j < els.length; j++) {
        if (cfg.removeTitle) stripTitles(els[j]);
        stripConfiguredAttrs(els[j]);
      }
    }
  }

  document.addEventListener('DOMContentLoaded', function () {
    try {
      if (cfg.removeTitle) {
        var items = document.querySelectorAll('[title]');
        for (var i = 0; i < items.length; i++) stripTitles(items[i]);
      }
      var all = document.querySelectorAll('*');
      for (var k = 0; k < all.length; k++) stripConfiguredAttrs(all[k]);
    } catch (e) {}
  });

  var mo = new MutationObserver(function (mutations) {
    mutations.forEach(function (m) {
      if (m.addedNodes && m.addedNodes.length) {
        m.addedNodes.forEach(function (node) {
          if (node.nodeType === 1) processNode(node);
        });
      }
      if (m.type === 'attributes' && m.target) {
        processNode(m.target);
      }
    });
  });

  try {
    mo.observe(document.documentElement || document.body, {
      childList: true,
      subtree: true,
      attributes: true,
      attributeFilter: null
    });
  } catch (e) {}

  // Optional: basic console wipe
  if (cfg.blockConsole && window.console) {
    try {
      var noop = function () {};
      ['log','info','warn','error','debug','table','dir'].forEach(function (m) { if (window.console[m]) window.console[m] = noop; });
    } catch (e) {}
  }

  // Aggressive DevTools detection and overlay (opt-in)
  if (cfg.aggressiveDevtools) {
    var overlay = null;
    function showOverlay() {
      if (overlay) return;
      overlay = document.createElement('div');
      overlay.style.position = 'fixed';
      overlay.style.top = 0;
      overlay.style.left = 0;
      overlay.style.right = 0;
      overlay.style.bottom = 0;
      overlay.style.background = 'rgba(0,0,0,0.9)';
      overlay.style.color = '#fff';
      overlay.style.zIndex = 999999999;
      overlay.style.display = 'flex';
      overlay.style.alignItems = 'center';
      overlay.style.justifyContent = 'center';
      overlay.style.fontSize = '20px';
      overlay.innerHTML = '<div style="max-width:800px;text-align:center;padding:20px;">Обнаружен режим разработчика. Для продолжения авторизуйтесь в админке.</div>';
      document.documentElement.appendChild(overlay);
    }
    function hideOverlay() {
      if (!overlay) return;
      overlay.parentNode.removeChild(overlay);
      overlay = null;
    }

    function detectDevTools() {
      try {
        // check size diffs
        var threshold = 160;
        if ((window.outerWidth - window.innerWidth) > threshold || (window.outerHeight - window.innerHeight) > threshold) {
          return true;
        }
        // timing debugger trick (may be unreliable)
        var start = Date.now();
        // eslint-disable-next-line no-debugger
        debugger;
        var took = Date.now() - start;
        if (took > 100) return true;
      } catch (e) {}
      return false;
    }

    setInterval(function () {
      try {
        if (detectDevTools()) showOverlay(); else hideOverlay();
      } catch (e) {}
    }, 1000);

    // block common keys
    window.addEventListener('keydown', function (e) {
      if ( e.keyCode === 123 || (e.ctrlKey && e.shiftKey && (e.keyCode === 73 || e.keyCode === 74)) || (e.ctrlKey && e.keyCode === 85) ) {
        e.preventDefault();
        e.stopImmediatePropagation();
      }
    }, true);

    // contextmenu block
    window.addEventListener('contextmenu', function (e) {
      e.preventDefault();
    }, true);
  }

})();