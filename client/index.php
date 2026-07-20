<?php require_once __DIR__ . '/_store.php';
$newArrivals = latest_products(4);
$topProducts = top_selling_products(4);
$sliderFiles = glob(__DIR__ . '/../src/slider-images/*.{jpg,jpeg,png,webp,avif}', GLOB_BRACE) ?: [];
sort($sliderFiles, SORT_NATURAL | SORT_FLAG_CASE);
store_header('Home'); ?>
<main class="page">
  <?php if ($sliderFiles): ?>
    <section class="hero-slider" aria-label="Featured collections">
      <div class="hero-slider-track">
        <?php foreach ($sliderFiles as $image): ?>
          <a class="hero-slide" href="shopAll.php">
            <img src="../src/slider-images/<?= e(rawurlencode(basename($image))) ?>" alt="Featured skincare collection">
          </a>
        <?php endforeach; ?>
      </div>
      <?php if (count($sliderFiles) > 1): ?>
        <button class="hero-slider-control hero-slider-prev" type="button" aria-label="Previous slide"><i class="bi bi-chevron-left"></i></button>
        <button class="hero-slider-control hero-slider-next" type="button" aria-label="Next slide"><i class="bi bi-chevron-right"></i></button>
        <div class="hero-slider-dots" aria-label="Choose a slide">
          <?php foreach ($sliderFiles as $index => $image): ?>
            <button class="hero-slider-dot<?= $index === 0 ? ' is-active' : '' ?>" type="button" aria-label="Show slide <?= e($index + 1) ?>"></button>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </section>
  <?php endif; ?>
  <div class="section-title">
    <div>
      <!-- <span class="eyebrow">Just added</span> -->
      <h2>New arrivals 🎉</h2>
    </div>
    <!-- <a href="shopAll.php">View all products</a> -->
  </div>
  <div class="product-grid">
    <?php foreach ($newArrivals as $product): ?><?php include __DIR__ . '/_product_card.php'; ?><?php endforeach; ?>
  </div>
  <div class="section-title" style="margin-top:64px">
    <div>
      <h2>Best seller 🥶</h2>
    </div>
  </div>
  <div class="product-grid">
    <?php foreach ($topProducts as $product): ?><?php include __DIR__ . '/_product_card.php'; ?><?php endforeach; ?>
  </div>
</main>
<?php if (count($sliderFiles) > 1): ?>
  <script>
    (() => {
      const slider = document.querySelector('.hero-slider');
      const track = slider.querySelector('.hero-slider-track');
      const slides = slider.querySelectorAll('.hero-slide');
      const dots = slider.querySelectorAll('.hero-slider-dot');
      let current = 0;
      let timer;

      const showSlide = (next) => {
        current = (next + slides.length) % slides.length;
        track.style.transform = `translateX(-${current * 100}%)`;
        dots.forEach((dot, index) => dot.classList.toggle('is-active', index === current));
      };
      const startAutoplay = () => {
        window.clearInterval(timer);
        timer = window.setInterval(() => showSlide(current + 1), 5000);
      };
      const restartAutoplay = () => {
        startAutoplay();
      };

      slider.querySelector('.hero-slider-prev').addEventListener('click', () => { showSlide(current - 1); restartAutoplay(); });
      slider.querySelector('.hero-slider-next').addEventListener('click', () => { showSlide(current + 1); restartAutoplay(); });
      dots.forEach((dot, index) => dot.addEventListener('click', () => { showSlide(index); restartAutoplay(); }));
      slider.addEventListener('mouseenter', () => window.clearInterval(timer));
      slider.addEventListener('mouseleave', startAutoplay);
      startAutoplay();
    })();
  </script>
<?php endif; ?>
<?php store_footer(); ?>
