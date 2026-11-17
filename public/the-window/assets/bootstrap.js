'use strict';

(() => {
  const defaults = {
    theme: {
      defaultMode: 'light',
      rememberChoice: true,
      transitionMs: 440
    },
    slideshow: {
      autoplay: true,
      imagesPerSecond: 0.3,
      followSpeed: 8
    },
    hover: {
      zoom: 1.045,
      zoomMs: 320,
      alignMinMs: 320,
      alignExtraMs: 180
    },
    navigation: {
      slideToImageMs: 420,
      extraMsPerImage: 70,
      maxExtraMs: 420,
      settleMs: 90
    },
    viewer: {
      openMs: 600,
      closeMs: 420,
      switchMs: 300,
      resumeDelayMs: 180
    },
    thumbnails: {
      animate: true,
      durationMs: 240,
      staggerMs: 255,
      edgeOffsetPx: 16
    },
    captions: {
      fadeMs: 180
    }
  };

  const limits = {
    'theme.transitionMs': [0, 5000],
    'slideshow.imagesPerSecond': [0, 10],
    'slideshow.followSpeed': [0.1, 100],
    'hover.zoom': [1, 1.3],
    'hover.zoomMs': [0, 5000],
    'hover.alignMinMs': [1, 5000],
    'hover.alignExtraMs': [0, 5000],
    'navigation.slideToImageMs': [1, 10000],
    'navigation.extraMsPerImage': [0, 5000],
    'navigation.maxExtraMs': [0, 10000],
    'navigation.settleMs': [0, 5000],
    'viewer.openMs': [0, 10000],
    'viewer.closeMs': [0, 10000],
    'viewer.switchMs': [0, 10000],
    'viewer.resumeDelayMs': [0, 5000],
    'thumbnails.durationMs': [0, 10000],
    'thumbnails.staggerMs': [0, 10000],
    'thumbnails.edgeOffsetPx': [0, 200],
    'captions.fadeMs': [0, 5000]
  };
  function validate(input) {
    const result = JSON.parse(JSON.stringify(defaults));
    for (const [group, fields] of Object.entries(defaults)) {
      const source = input?.[group];
      if (!source || typeof source !== 'object' || Array.isArray(source)) continue;
      for (const [name, fallback] of Object.entries(fields)) {
        const candidate = source[name];
        const range = limits[group + '.' + name];
        if (range && typeof candidate === 'number' && Number.isFinite(candidate)) {
          result[group][name] = Math.max(range[0], Math.min(range[1], candidate));
        } else if (typeof fallback === 'boolean' && typeof candidate === 'boolean') {
          result[group][name] = candidate;
        } else if (
          group === 'theme' &&
          name === 'defaultMode' &&
          ['light', 'dark'].includes(candidate)
        ) {
          result[group][name] = candidate;
        }
      }
    }
    return result;
  }
  const settings = validate(window.TheWindowSettings);
  window.TheWindowSettings = settings;
  const style = document.documentElement.style;
  style.setProperty('--theme-time', settings.theme.transitionMs + 'ms');
  style.setProperty('--lens-hover-scale', String(settings.hover.zoom));
  style.setProperty('--lens-hover-time', settings.hover.zoomMs + 'ms');
  style.setProperty('--viewer-open-time', settings.viewer.openMs + 'ms');
  style.setProperty('--viewer-close-time', settings.viewer.closeMs + 'ms');

  const root = document.documentElement;
  const key = 'dl-slide-theme';
  let saved = null;
  try {
    const value = localStorage.getItem(key);
    if (['light', 'dark'].includes(value)) saved = value;
  } catch {}
  let theme = settings.theme.rememberChoice && saved ? saved : settings.theme.defaultMode;
  const icons = { light: 'moon', dark: 'sun' };
  function apply() {
    root.dataset.theme = theme;
    const meta = document.querySelector('meta[name="theme-color"]');
    if (meta) meta.content = theme === 'dark' ? '#151715' : '#f1f0eb';
    const button = document.getElementById('theme-toggle');
    if (button) {
      button.setAttribute('aria-pressed', String(theme === 'dark'));
      button.title = theme === 'dark' ? 'Switch to light mode' : 'Switch to dark mode';
      const icon = icons[theme];
      button.innerHTML = `<img class="icon" src="assets/icons/${icon}.svg" alt="" aria-hidden="true" draggable="false">`;
    }
  }
  // Restore the saved choice before the first paint.
  apply();
  function connect() {
    apply();
    const button = document.getElementById('theme-toggle');
    if (button)
      button.addEventListener('click', () => {
        theme = theme === 'dark' ? 'light' : 'dark';
        apply();
        const icon = button.querySelector?.('.icon');
        if (
          icon?.animate &&
          !globalThis.matchMedia?.('(prefers-reduced-motion: reduce)')?.matches
        ) {
          icon.animate(
            [
              { opacity: 0.4, transform: 'rotate(-25deg) scale(.85)' },
              { opacity: 1, transform: 'rotate(0) scale(1)' }
            ],
            {
              duration: settings.theme.transitionMs,
              easing: 'cubic-bezier(.22,.61,.36,1)'
            }
          );
        }
        if (settings.theme.rememberChoice)
          try {
            localStorage.setItem(key, theme);
          } catch {}
      });
  }
  if (document.readyState === 'loading')
    document.addEventListener('DOMContentLoaded', connect, { once: true });
  else connect();
})();
