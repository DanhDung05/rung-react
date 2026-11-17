<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
    <base href="/the-window/" />
    <meta name="robots" content="noindex" />
    <meta name="theme-color" content="#f1f0eb" />
    <meta name="description" content="The Window - 3D Photo Carousel. Twenty fashion portraits, studio studies, and street-style moments in an immersive 3D gallery." />
    
    <title>The Window - 3D Photo Carousel</title>

    <!-- Styles & Fonts -->
    <link rel="stylesheet" href="assets/site.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link id="gallery-fonts" rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;450;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" media="print" />

    <!-- Scripts -->
    <script src="config.js"></script>
    <script src="assets/bootstrap.js"></script>
    <script>
      window.DLSlideData = {!! json_encode($data, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_HEX_TAG|JSON_HEX_AMP) !!};
    </script>
    <script src="assets/site.js" defer></script>
  </head>
  
  <body>
    <a class="skip-link" href="#main-content">Skip to content</a>

    <div class="page" id="main-content">
      <!-- Intro Section -->
      <section class="intro" id="intro-story" aria-label="About the photograph">
        <div class="eyebrow" id="intro-kicker">Women / Studio portrait</div>
        <h1>
          <span id="intro-title">Soft Focus.</span>
          <em id="intro-accent">Quiet confidence.</em>
        </h1>
        <p id="intro-description">
          Soft studio light, flowing hair, and a composed gaze against a charcoal backdrop.
        </p>
      </section>

      <!-- Gallery Viewport -->
      <section class="gallery is-loading" id="gallery" aria-label="Interactive 3D image carousel" aria-roledescription="carousel">
        <div class="horizon"></div>
        <button class="focus-lens" id="focus-lens" aria-label="View centered image"></button>
      </section>

      <!-- Controls & Footer -->
      <section class="bottom gallery-controls footer-controls" id="home-controls" aria-label="Gallery controls">
        <div class="bottom-row">
          <div class="project">
            <div class="project-number" id="number">01 / 20</div>
            <div>
              <div class="project-title" id="title">Soft Focus</div>
              <div class="project-category" id="category">Women / Studio portrait / Fashion collection</div>
            </div>
          </div>

          <div class="controls">
            <button class="round" id="prev" aria-label="Previous image">
              <img class="icon" src="assets/icons/arrow-left.svg" alt="" aria-hidden="true" draggable="false" />
            </button>
            <button class="round" id="next" aria-label="Next image">
              <img class="icon" src="assets/icons/arrow-right.svg" alt="" aria-hidden="true" draggable="false" />
            </button>
            <button class="round play" id="play" aria-label="Pause automatic slideshow" aria-pressed="true">
              <img class="icon" src="assets/icons/pause.svg" alt="" aria-hidden="true" draggable="false" />
            </button>
            <button class="round theme-toggle" id="theme-toggle" type="button" aria-label="Dark mode" aria-pressed="false" title="Switch to dark mode">
              <img class="icon" src="assets/icons/moon.svg" alt="" aria-hidden="true" draggable="false" />
            </button>
          </div>
        </div>

        <div class="dock-timeline">
          <div class="timeline" id="timeline" aria-label="Choose an image"></div>
        </div>

        <div class="gallery-hint">
          <span aria-hidden="true">↔</span>
          <span class="hint-label hint-desktop">DRAG TO EXPLORE · CLICK TO VIEW</span>
          <span class="hint-label hint-touch">SWIPE TO EXPLORE · TAP TO VIEW</span>
        </div>
      </section>
    </div>

    <!-- Modal Preview -->
    <dialog class="modal" id="modal" aria-label="Image preview">
      <img id="preview" alt="" />
      <button class="viewer-close" id="viewer-close" type="button" aria-label="Close image preview" title="Close">
        <img class="icon" src="assets/icons/close.svg" alt="" aria-hidden="true" draggable="false" />
      </button>
      <div class="modal-bottom">
        <p id="preview-title"></p>
        <div class="viewer-thumbnails" id="viewer-thumbnails" role="group" aria-label="Choose a photograph"></div>
      </div>
    </dialog>
  </body>
</html>