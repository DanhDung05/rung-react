'use strict';

document.addEventListener(
  'pointerdown',
  () => {
    document.documentElement.dataset.focusInput = 'pointer';
  },
  true
);
document.addEventListener(
  'keydown',
  (event) => {
    if (event.key === 'Tab') document.documentElement.dataset.focusInput = 'keyboard';
  },
  true
);

const galleryFonts = document.getElementById('gallery-fonts');
if (galleryFonts) {
  if (galleryFonts.sheet) galleryFonts.media = 'all';
  else
    galleryFonts.addEventListener(
      'load',
      () => {
        galleryFonts.media = 'all';
      },
      { once: true }
    );
}
const settings = window.TheWindowSettings;
const DRAG_CLICK_DELAY = 140;
const DRAG_RELEASE_DELAY = 180;

const works = window.DLSlideData.works;
const imageStories = window.DLSlideData.stories;
const gallery = document.getElementById('gallery');
const timeline = document.getElementById('timeline');
const modal = document.getElementById('modal');
const lens = document.getElementById('focus-lens');
const intro = document.getElementById('intro-story');
let introFade = null;
let introOutgoing = null;
let introOutgoingFade = null;
const reduceMotion = matchMedia('(prefers-reduced-motion: reduce)');
let cards = [];
let lensSlides = [];
let ticks = [];
let position = 0;
let target = 0;
let playing = settings.slideshow.autoplay && !reduceMotion.matches;
let drag = null;
let lastTime = 0;
let lastActive = -1;
let galleryReady = false;
let lensHovered = false;

let hoverTween = null;
let lastIntroPosition = null;
let lastIntroIndex = -1;
const mod = (n, m) => ((n % m) + m) % m;

const photoCache = new Map();
// Decode first so a slow image never replaces a usable preview.
function decodedPhoto(src, priority = 'low') {
  if (!photoCache.has(src)) {
    const photo = new Image();
    photo.decoding = 'async';
    photo.fetchPriority = priority;
    photo.src = src;
    photoCache.set(
      src,
      photo
        .decode()
        .then(() => src)
        .catch(() => null)
    );
  }
  return photoCache.get(src);
}
function mount() {
  gallery.classList.add('is-loading');
  gallery.setAttribute('aria-busy', 'true');
  const previews = [];
  function photoElement(work, isLens) {
    const img = document.createElement('img');
    if (isLens) {
      img.className = 'lens-slide';
      img.alt = '';
      img.setAttribute('aria-hidden', 'true');
    } else img.alt = work[0] + ' — ' + work[1];
    img.width = 660;
    img.height = 900;
    img.draggable = false;
    img.decoding = 'async';
    img.src = work[3] || work[2];
    previews.push(img);
    return img;
  }
  lensSlides = works.map((work) => {
    const img = photoElement(work, true);
    lens.append(img);
    return img;
  });
  cards = works.map((work, i) => {
    const card = document.createElement('button');
    card.className = 'art-card';
    card.setAttribute('aria-label', `View ${work[0]}`);
    card.append(photoElement(work, false));
    gallery.append(card);
    card.addEventListener('click', () => {
      if (performance.now() - (drag?.ended || 0) < DRAG_CLICK_DELAY) return;
      moveImageToLens(i);
    });
    return card;
  });
  ticks = works.map((work, i) => {
    const b = document.createElement('button');
    b.className = 'tick';
    b.setAttribute('aria-label', `Go to ${work[0]}`);
    b.onclick = () => {
      cancelPendingImageOpen();

      target = viewerImagePosition(i);

      hoverTween = null;
      setPlaying(false);
    };
    timeline.append(b);
    return b;
  });
  draw();

  Promise.allSettled(previews.map((img) => img.decode())).then(() => {
    galleryReady = true;
    gallery.classList.remove('is-loading');
    gallery.setAttribute('aria-busy', 'false');
    const order = works
      .map((_, i) => i)
      .sort((a, b) => Math.min(a, works.length - a) - Math.min(b, works.length - b));
    order.forEach((i) =>
      decodedPhoto(works[i][2], i === 0 ? 'high' : 'low').then((src) => {
        if (!src) return;
        lensSlides[i].src = src;
        cards[i].firstElementChild.src = src;
      })
    );
  });
}

// Captions change only when a whole image crosses the frame.
function updateImageInfo() {
  let step = null;
  const epsilon = 1e-7;
  if (lastIntroPosition === null) step = Math.round(position);
  else if (position > lastIntroPosition) {
    const boundary = Math.floor(position + epsilon);
    if (boundary > lastIntroPosition + epsilon) step = boundary;
  } else if (position < lastIntroPosition) {
    const boundary = Math.ceil(position - epsilon);
    if (boundary < lastIntroPosition - epsilon) step = boundary;
  }
  lastIntroPosition = position;
  if (step === null) return;
  const index = mod(step, works.length);
  if (index === lastIntroIndex) return;
  const firstStory = lastIntroIndex === -1;
  lastIntroIndex = index;
  introFade?.cancel();
  introOutgoingFade?.cancel();
  introOutgoing?.remove();
  introFade = null;
  introOutgoingFade = null;
  introOutgoing = null;
  const shouldFade =
    !firstStory &&
    settings.captions.fadeMs > 0 &&
    !reduceMotion.matches &&
    typeof intro.animate === 'function';
  if (shouldFade) {
    introOutgoing = intro.cloneNode(true);
    introOutgoing.removeAttribute('id');
    introOutgoing.removeAttribute('aria-label');
    introOutgoing.querySelectorAll('[id]').forEach((node) => node.removeAttribute('id'));
    introOutgoing.classList.add('intro-outgoing');
    introOutgoing.setAttribute('aria-hidden', 'true');
    intro.after(introOutgoing);
  }

  const work = works[index];
  const story = imageStories[work[4]];
  document.getElementById('intro-kicker').textContent = work[1];
  document.getElementById('intro-title').textContent =
    work[0] + (/[.!?]$/.test(work[0]) ? '' : '.');
  document.getElementById('intro-accent').textContent = story ? story.accent : 'Your perspective.';
  document.getElementById('intro-description').textContent = story
    ? story.description
    : 'A photograph from your collection. Take a moment to enjoy the details.';
  if (shouldFade) {
    const options = { duration: settings.captions.fadeMs, easing: 'ease-out' };
    introFade = intro.animate([{ opacity: 0 }, { opacity: 1 }], options);
    const outgoing = introOutgoing;
    introOutgoingFade = outgoing.animate([{ opacity: 1 }, { opacity: 0 }], options);
    introOutgoingFade.onfinish = () => {
      outgoing.remove();
      if (introOutgoing === outgoing) introOutgoing = null;
    };
  }
}

function draw() {
  updateImageInfo();
  const n = works.length;
  const w = gallery.clientWidth;
  const fit = w / 1440;
  const mobile = w <= 700 || (w <= 1000 && gallery.clientHeight <= 500);

  const dock = document.getElementById('home-controls');
  const sceneTop = gallery.getBoundingClientRect?.().top || 0;
  const centerY = sceneTop + gallery.clientHeight / 2;
  const captionBottom = document
    .getElementById('intro-description')
    .getBoundingClientRect?.().bottom;
  const controlsTop = dock.getBoundingClientRect?.().top;
  let lensHeightLimit = gallery.clientHeight - 75;
  if (Number.isFinite(captionBottom)) {
    const above = centerY - captionBottom - (mobile ? 22 : 18);
    const below =
      (Number.isFinite(controlsTop) ? controlsTop : sceneTop + gallery.clientHeight - 100) -
      centerY -
      (mobile ? 26 : 36);
    lensHeightLimit = Math.max(80, 2 * Math.min(above, below));
  }
  const preferredLensWidth = mobile
    ? Math.min(286, w * 0.64)
    : Math.min(286, Math.max(195, w * 0.312));
  const lensWidth =
    Math.min(preferredLensWidth, (Math.max(80, lensHeightLimit) * 220) / 300) * (mobile ? 0.6 : 1);
  const lensHeight = (lensWidth * 300) / 220;
  lens.style.width = lensWidth + 'px';
  lens.style.height = lensHeight + 'px';

  const wallRadius = 650 * fit;
  const perspective = 900 * fit;
  const wallRecess = 260 * fit;
  const wallScale = mobile
    ? 0.87 * (lensHeight / 1.2 / 390)
    : 0.74 * Math.min(1.2, Math.max(0.8, fit));
  gallery.style.perspective = perspective + 'px';
  cards.forEach((card, i) => {
    const d = mod(i - position + n / 2, n) - n / 2;
    const a = Math.abs(d);

    const theta = mobile ? Math.sign(d) * Math.min(a * 0.14, 1.26) : d * 0.17;
    const outward = mobile ? Math.sign(d) * Math.max(0, a - 9) * w * 0.18 : 0;
    const x = wallRadius * Math.sin(theta) + outward;
    const z = wallRadius * (1 - Math.cos(theta)) - wallRecess;
    const projectedX = (x * perspective) / (perspective - z);

    const edgeProgress = Math.min(1, Math.abs(projectedX) / (w * 0.5));
    const scale = wallScale * (1 + (mobile ? 0.65 : 0.9) * Math.pow(edgeProgress, 3));
    const angle = mobile
      ? -Math.sign(d) * Math.min(62, (a * 0.14 * 180) / Math.PI)
      : (-theta * 180) / Math.PI;
    card.style.transform = `translate(-50%,-50%) translateX(${x}px) translateZ(${z}px) rotateY(${angle}deg) scale(${scale})`;
    card.style.zIndex = String(Math.round(mobile ? a * 100 : (1 - Math.cos(theta)) * 1000));
    const offscreen = mobile
      ? a > 9.65 || Math.abs(projectedX) > w / 2 + (120 * scale * perspective) / (perspective - z)
      : Math.abs(projectedX) > w / 2 + 260;

    card.style.opacity = offscreen ? '0' : '1';
    card.tabIndex = offscreen || Math.abs(projectedX) > w / 2 + 100 ? -1 : 0;

    const slide = lensSlides[i];
    slide.style.transform = `translateX(${n === 1 ? 0 : d * 100}%)`;
    slide.style.visibility = n === 1 || a < 1.1 ? 'visible' : 'hidden';
  });
  const active = mod(Math.round(position), n);
  if (active !== lastActive) {
    document.getElementById('number').textContent =
      `${String(active + 1).padStart(2, '0')} / ${String(n).padStart(2, '0')}`;
    document.getElementById('title').textContent = works[active][0];
    document.getElementById('category').textContent = works[active][1];
    lens.setAttribute('aria-label', 'View centered image: ' + works[active][0]);
    ticks.forEach((b, i) => {
      b.classList.toggle('active', i === active);
      b.setAttribute('aria-current', i === active ? 'true' : 'false');
    });
    lastActive = active;
  }
}

function alignHoverStop() {
  const direction = target < position - 0.0001 ? -1 : 1;
  const stop = direction < 0 ? Math.floor(position + 0.001) : Math.ceil(position - 0.001);
  const distance = Math.abs(stop - position);
  const duration =
    (settings.hover.alignMinMs + Math.min(1, distance) * settings.hover.alignExtraMs) / 1000;

  const speed = Math.min(
    Math.max(0, (target - position) * settings.slideshow.followSpeed * direction),
    distance / duration
  );
  hoverTween = { from: position, to: stop, elapsed: 0, duration, velocity: direction * speed };
  target = position;
  if (reduceMotion.matches || distance < 0.0001) {
    position = target = stop;
    hoverTween = null;
    draw();
  }
}
function onLensEnter(event) {
  if (event.pointerType === 'touch' || modal.open || pendingImageOpen) return;
  lensHovered = true;
  lens.classList.add('is-hovered');
  if (performance.now() >= slideResumeAt && (!drag || drag.ended)) alignHoverStop();
}

function onLensLeave() {
  if (modal.open) return;
  const wasSettling = hoverTween !== null;
  lensHovered = false;

  hoverTween = null;
  if (wasSettling) target = position;
  lens.classList.remove('is-hovered');
}

function frame(time) {
  const dt = Math.min((time - lastTime) / 1000 || 0, 0.05);
  lastTime = time;
  const previous = position;
  const canMove = galleryReady && !drag && !modal.open;
  if (modal.open || time < slideResumeAt) {
    requestAnimationFrame(frame);
    return;
  }
  if (pendingImageOpen) {
    if (canMove) {
      const tween = pendingImageOpen;
      if (!tween.arrived) {
        const t = Math.min(1, (tween.elapsed += dt) / tween.duration);
        const ease = t * t * (3 - 2 * t);
        position = tween.from + (tween.to - tween.from) * ease;
        if (t === 1) {
          position = target = tween.to;
          tween.arrived = true;
        }
        draw();
      } else if ((tween.hold += dt) >= settings.navigation.settleMs / 1000) {
        pendingImageOpen = null;
        position = target = tween.to;
        draw();
        openImage(tween.index);
      }
    }
    requestAnimationFrame(frame);
    return;
  }
  if (lensHovered && hoverTween) {
    if (canMove) {
      const tween = hoverTween;
      const t = Math.min(1, (tween.elapsed += dt) / tween.duration);
      const ease = t * t * (3 - 2 * t);
      const momentum = t * (1 - t) * (1 - t);
      position =
        tween.from + (tween.to - tween.from) * ease + tween.velocity * tween.duration * momentum;
      target = position;
      if (t === 1) {
        position = target = tween.to;
        hoverTween = null;
      }
    }
  } else {
    if (canMove && playing && !lensHovered) target += dt * settings.slideshow.imagesPerSecond;
    position += (target - position) * Math.min(1, dt * settings.slideshow.followSpeed);
    if (Math.abs(target - position) < 0.0001) position = target;
  }
  if (previous !== position || (playing && !lensHovered)) draw();
  requestAnimationFrame(frame);
}
function setPlaying(value) {
  playing = value;
  const button = document.getElementById('play');
  button.setAttribute(
    'aria-label',
    playing ? 'Pause automatic slideshow' : 'Play automatic slideshow'
  );
  button.setAttribute('aria-pressed', String(playing));
  const icon = playing ? 'pause' : 'play';
  button.innerHTML = `<img class="icon" src="assets/icons/${icon}.svg" alt="" aria-hidden="true" draggable="false">`;
}
function step(direction) {
  cancelPendingImageOpen();
  target = Math.round(target) + direction;

  hoverTween = null;
  setPlaying(false);
}

function onPlayClick() {
  cancelPendingImageOpen();
  setPlaying(!playing);
}

function onGalleryPointerDown(event) {
  if (event.button !== 0 || modal.open) return;
  cancelPendingImageOpen();

  hoverTween = null;
  drag = { x: event.clientX, y: event.clientY, start: target, moved: false };
  gallery.classList.add('dragging');
}

function onPointerMove(event) {
  if (!drag || drag.ended) return;
  const distance = event.clientX - drag.x;
  if (Math.abs(distance) <= 5) return;
  drag.moved = true;
  setPlaying(false);
  target = drag.start - distance / (gallery.clientWidth < 700 ? 45 : 90);
}

function onPointerUp() {
  if (modal.open || !drag || drag.ended) return;
  gallery.classList.remove('dragging');
  if (drag.moved) {
    target = Math.round(target);
    drag.ended = performance.now();
    setTimeout(() => {
      drag = null;
    }, DRAG_RELEASE_DELAY);
  } else {
    drag = null;
  }
  if (lensHovered) alignHoverStop();
}

function onPointerCancel() {
  if (modal.open) return;
  drag = null;
  gallery.classList.remove('dragging');
  if (lensHovered) alignHoverStop();
}

function onKeyDown(event) {
  if (modal.open || ['INPUT', 'TEXTAREA'].includes(document.activeElement.tagName)) return;
  if (event.key === 'Escape' && pendingImageOpen) {
    cancelPendingImageOpen();
    return;
  }
  if (event.key === 'ArrowLeft') {
    event.preventDefault();
    step(-1);
  }
  if (event.key === 'ArrowRight') {
    event.preventDefault();
    step(1);
  }
  if (event.code === 'Space' && document.activeElement === document.body) {
    event.preventDefault();
    setPlaying(!playing);
  }
}

const originalImageSizes = window.DLSlideData.originalImageSizes;
let previewVersion = 0;
let viewerAnimation = null;
let viewerIndex = 0;
let viewerClosing = false;
let viewerThumbs = [];
let viewerReturnPosition = null;
let slideResumeAt = 0;
let viewerReturnSurface = null;
let viewerReturnFade = null;
let pendingImageOpen = null;
let thumbnailEntranceStarted = false;
const thumbnailAnimations = new Set();
function sizeViewer() {
  const work = works[viewerIndex];
  const size = originalImageSizes[work[4]] || [220, 300];
  const ratio = size[0] / size[1];
  const vw = window.innerWidth || 1440;
  const vh = window.innerHeight || 900;
  const rim = 26;
  const maxWidth = Math.min(1400, vw - 32) - rim;
  const maxHeight = Math.max(80, vh - 188) - rim;
  const imageWidth = Math.min(maxWidth, maxHeight * ratio);
  const imageHeight = imageWidth / ratio;
  modal.style.width = imageWidth + rim + 'px';
  modal.style.height = imageHeight + rim + 'px';
  modal.style.left = vw / 2 + 'px';
  modal.style.top = (vh - 76) / 2 + 'px';
}
function viewerRect(rect) {
  return {
    left: rect.left + rect.width / 2 + 'px',
    top: rect.top + rect.height / 2 + 'px',
    width: rect.width + 'px',
    height: rect.height + 'px'
  };
}
function updateViewerThumbnails(scroll = false) {
  viewerThumbs.forEach((button, i) =>
    button.setAttribute('aria-pressed', String(i === viewerIndex))
  );
  if (scroll)
    viewerThumbs[viewerIndex]?.scrollIntoView?.({
      block: 'nearest',
      inline: 'center',
      behavior: reduceMotion.matches ? 'instant' : 'smooth'
    });
}
function finishThumbnailEntrance() {
  thumbnailAnimations.forEach((animation) => animation.cancel());
  thumbnailAnimations.clear();
}
function playThumbnailEntrance() {
  if (thumbnailEntranceStarted || !modal.open || viewerClosing) return;
  thumbnailEntranceStarted = true;
  if (reduceMotion.matches || !settings.thumbnails.animate || settings.thumbnails.durationMs === 0)
    return;
  const strip = document.getElementById('viewer-thumbnails');
  const bounds = strip.getBoundingClientRect?.();
  if (!bounds?.width) return;
  const left = Math.max(0, bounds.left);
  const right = Math.min(window.innerWidth || 1440, bounds.left + bounds.width);
  const middle = (left + right) / 2;
  const streams = [[], []];
  viewerThumbs.forEach((button) => {
    if (typeof button.animate !== 'function') return;
    const rect = button.getBoundingClientRect();
    const edge = rect.left + rect.width;
    if (edge <= left || rect.left >= right) return;
    const center = rect.left + rect.width / 2;
    const fromLeft = center < middle;
    streams[fromLeft ? 0 : 1].push({
      button,
      center,
      travel: fromLeft
        ? left - edge - settings.thumbnails.edgeOffsetPx
        : right - rect.left + settings.thumbnails.edgeOffsetPx
    });
  });
  streams[0].sort((a, b) => b.center - a.center);
  streams[1].sort((a, b) => a.center - b.center);

  streams.forEach((stream) =>
    stream.forEach(({ button, travel }, rank) => {
      const opacity = button === viewerThumbs[viewerIndex] ? 1 : 0.55;
      const animation = button.animate(
        [
          { transform: `translateX(${travel}px)`, opacity: 0 },
          { transform: 'translateX(0px)', opacity }
        ],
        {
          duration: settings.thumbnails.durationMs,
          delay: rank * settings.thumbnails.staggerMs,
          easing: 'cubic-bezier(.25,.46,.45,.94)',
          fill: 'backwards'
        }
      );
      thumbnailAnimations.add(animation);
      animation.onfinish = () => thumbnailAnimations.delete(animation);
    })
  );
}
function buildViewerThumbnails() {
  finishThumbnailEntrance();
  thumbnailEntranceStarted = false;
  const strip = document.getElementById('viewer-thumbnails');
  strip.replaceChildren();
  viewerThumbs = works.map((work, i) => {
    const button = document.createElement('button');
    button.type = 'button';
    button.className = 'viewer-thumb';
    button.setAttribute('aria-label', 'View ' + work[0]);
    button.title = work[0];
    const image = document.createElement('img');
    image.src = lensSlides[i]?.src || work[3] || work[2];
    image.alt = '';
    image.draggable = false;
    button.append(image);
    button.addEventListener('click', () => selectViewerImage(i));
    strip.append(button);
    return button;
  });
  updateViewerThumbnails();
}
function loadViewerPhoto(i) {
  const version = ++previewVersion;
  const work = works[i];
  const img = document.getElementById('preview');
  img.src = lensSlides[i]?.src || work[3] || work[2];
  img.alt = work[0];
  document.getElementById('preview-title').textContent = work[0] + ' / ' + work[1];
  decodedPhoto(work[4] || work[2], 'high').then((src) => {
    if (src && version === previewVersion && modal.open && !viewerClosing) img.src = src;
  });
}
function selectViewerImage(i) {
  if (!modal.open || viewerClosing) return;
  finishThumbnailEntrance();
  if (i === viewerIndex) return;
  const from = modal.getBoundingClientRect?.();
  viewerAnimation?.cancel();
  viewerAnimation = null;
  modal.classList.remove('is-opening');
  viewerIndex = i;

  position = target = viewerReturnPosition = viewerImagePosition(i);
  hoverTween = null;

  lastIntroPosition = null;
  draw();
  loadViewerPhoto(i);
  sizeViewer();
  updateViewerThumbnails(true);
  playThumbnailEntrance();
  if (!reduceMotion.matches && from?.width && typeof modal.animate === 'function') {
    viewerAnimation = modal.animate([viewerRect(from), viewerRect(modal.getBoundingClientRect())], {
      duration: settings.viewer.switchMs,
      easing: 'cubic-bezier(.22,1,.36,1)'
    });
    viewerAnimation.onfinish = () => {
      viewerAnimation = null;
    };
  }
}
function viewerImagePosition(i) {
  const base = Math.round(position);
  const current = mod(base, works.length);
  return base + mod(i - current + works.length / 2, works.length) - works.length / 2;
}
function cancelPendingImageOpen() {
  if (!pendingImageOpen) return;
  pendingImageOpen = null;
  target = position;
}
function resetInteraction() {
  hoverTween = null;

  lensHovered = false;
  drag = null;
  gallery.classList.remove('dragging');
  lens.classList.remove('is-hovered');
}

function moveImageToLens(i) {
  if (modal.open) return;
  cancelPendingImageOpen();
  const to = viewerImagePosition(i);
  const distance = Math.abs(to - position);
  resetInteraction();
  slideResumeAt = 0;
  if (reduceMotion.matches || distance < 0.0001) {
    position = target = to;
    draw();
    openImage(i);
    return;
  }
  target = to;
  pendingImageOpen = {
    index: i,
    from: position,
    to,
    elapsed: 0,
    duration:
      (settings.navigation.slideToImageMs +
        Math.min(settings.navigation.maxExtraMs, distance * settings.navigation.extraMsPerImage)) /
      1000,
    arrived: false,
    hold: 0
  };
}
function openImage(i) {
  if (modal.open) return;
  cancelPendingImageOpen();
  viewerIndex = i;
  viewerClosing = false;
  const from = lens.getBoundingClientRect?.();

  viewerReturnPosition = viewerImagePosition(i);
  target = position;
  resetInteraction();
  buildViewerThumbnails();
  loadViewerPhoto(i);
  sizeViewer();
  modal.showModal();
  updateViewerThumbnails(true);
  if (!reduceMotion.matches && from?.width && typeof modal.animate === 'function') {
    const to = modal.getBoundingClientRect();
    modal.classList.add('is-opening');
    viewerAnimation = modal.animate([viewerRect(from), viewerRect(to)], {
      duration: settings.viewer.openMs,
      easing: 'cubic-bezier(.22,1,.36,1)'
    });
    viewerAnimation.onfinish = () => {
      viewerAnimation = null;
      modal.classList.remove('is-opening');
      restoreViewerSlide();
      playThumbnailEntrance();
    };
  } else {
    restoreViewerSlide();
    playThumbnailEntrance();
  }
}
function restoreViewerSlide() {
  if (viewerReturnPosition !== null) position = target = viewerReturnPosition;
  resetInteraction();
  draw();
}
function viewerReturnRect() {
  const rect = lens.getBoundingClientRect?.();
  if (!rect) return rect;

  const width = parseFloat(lens.style.width) || rect.width;
  const height = parseFloat(lens.style.height) || rect.height;
  return {
    left: rect.left + (rect.width - width) / 2,
    top: rect.top + (rect.height - height) / 2,
    width,
    height
  };
}
function clearViewerReturnSurface() {
  viewerReturnFade?.cancel();
  viewerReturnFade = null;
  viewerReturnSurface?.remove();
  viewerReturnSurface = null;
}
// Match the lens crop before the closing dialog leaves the top layer.
function prepareViewerReturnSurface() {
  clearViewerReturnSurface();
  const replica = lens.cloneNode(true);
  replica.removeAttribute('id');
  replica.querySelectorAll('[id]').forEach((node) => node.removeAttribute('id'));
  replica.setAttribute('tabindex', '-1');
  replica.setAttribute('aria-hidden', 'true');
  replica.classList.remove('is-hovered');
  viewerReturnSurface = document.createElement('div');
  viewerReturnSurface.className = 'viewer-return-surface';
  viewerReturnSurface.setAttribute('aria-hidden', 'true');
  viewerReturnSurface.append(replica);
  modal.append(viewerReturnSurface);

  viewerReturnFade = viewerReturnSurface.animate(
    [
      { opacity: 0, offset: 0 },
      { opacity: 0, offset: 0.4 },
      { opacity: 1, offset: 0.85 },
      { opacity: 1, offset: 1 }
    ],
    { duration: settings.viewer.closeMs, easing: 'linear', fill: 'forwards' }
  );
}
function closeImage() {
  if (!modal.open || viewerClosing) return;
  viewerClosing = true;
  ++previewVersion;
  restoreViewerSlide();
  const from = modal.getBoundingClientRect?.();
  const to = viewerReturnRect();
  viewerAnimation?.cancel();
  viewerAnimation = null;
  modal.classList.remove('is-opening');
  if (reduceMotion.matches || !from?.width || !to?.width || typeof modal.animate !== 'function') {
    modal.close();
    return;
  }
  modal.classList.add('is-closing');
  prepareViewerReturnSurface();
  viewerAnimation = modal.animate([viewerRect(from), viewerRect(to)], {
    duration: settings.viewer.closeMs,
    easing: 'cubic-bezier(.4,0,.2,1)',
    fill: 'forwards'
  });
  viewerAnimation.onfinish = () => {
    Object.assign(modal.style, viewerRect(to));
    modal.close();
  };
}
function onLensClick() {
  if (performance.now() - (drag?.ended || 0) < DRAG_CLICK_DELAY) return;
  openImage(mod(Math.round(position), works.length));
}

function onViewerCancel(event) {
  event.preventDefault();
  closeImage();
}

function onViewerKeyDown(event) {
  if (viewerClosing || !['ArrowLeft', 'ArrowRight'].includes(event.key)) return;
  event.preventDefault();
  const direction = event.key === 'ArrowRight' ? 1 : -1;
  selectViewerImage(mod(viewerIndex + direction, works.length));
}

function onViewerClose() {
  finishThumbnailEntrance();
  clearViewerReturnSurface();
  viewerAnimation?.cancel();
  viewerAnimation = null;
  viewerClosing = false;
  modal.classList.remove('is-opening');
  modal.classList.remove('is-closing');
  restoreViewerSlide();
  lastTime = performance.now();
  slideResumeAt = lastTime + settings.viewer.resumeDelayMs;
}

function onBackdropClick(event) {
  if (event.target !== modal) return;
  const rect = modal.getBoundingClientRect();
  const outside =
    event.clientX < rect.left ||
    event.clientX > rect.right ||
    event.clientY < rect.top ||
    event.clientY > rect.bottom;
  if (outside) closeImage();
}

function onResize() {
  if (modal.open && !viewerClosing) {
    finishThumbnailEntrance();
    viewerAnimation?.cancel();
    viewerAnimation = null;
    modal.classList.remove('is-opening');
    sizeViewer();
    playThumbnailEntrance();
  }
  draw();
}

function bindEvents() {
  const thumbnails = document.getElementById('viewer-thumbnails');
  document.getElementById('prev').onclick = () => step(-1);
  document.getElementById('next').onclick = () => step(1);
  document.getElementById('play').onclick = onPlayClick;
  document.getElementById('viewer-close').addEventListener('click', closeImage);

  gallery.addEventListener('pointerdown', onGalleryPointerDown);
  lens.addEventListener('pointerenter', onLensEnter);
  lens.addEventListener('pointerleave', onLensLeave);
  lens.addEventListener('click', onLensClick);

  thumbnails.addEventListener('pointerdown', finishThumbnailEntrance);
  thumbnails.addEventListener('wheel', finishThumbnailEntrance, { passive: true });
  modal.addEventListener('cancel', onViewerCancel);
  modal.addEventListener('keydown', onViewerKeyDown);
  modal.addEventListener('close', onViewerClose);
  modal.addEventListener('click', onBackdropClick);

  window.addEventListener('pointermove', onPointerMove);
  window.addEventListener('pointerup', onPointerUp);
  window.addEventListener('pointercancel', onPointerCancel);
  window.addEventListener('keydown', onKeyDown);
  window.addEventListener('resize', onResize);
  reduceMotion.addEventListener('change', (event) => {
    if (event.matches) setPlaying(false);
  });
}

function startGallery() {
  playing = settings.slideshow.autoplay && !reduceMotion.matches;
  lastTime = performance.now();
  mount();
  setPlaying(playing);
  requestAnimationFrame(frame);
  document.fonts?.ready.then(draw);
}

bindEvents();
startGallery();
