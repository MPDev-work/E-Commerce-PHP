<?php require_once __DIR__ . '/_store.php'; $products = array_slice(catalogue(), 0, 4); store_header('Home'); ?>
<main class="page">
  <section class="hero"><div class="hero-copy"><span class="eyebrow">A slower, kinder skincare ritual</span><h1>Skin comfort, bottled beautifully.</h1><p>Meet everyday formulas selected to calm, hydrate, and make your routine feel like a small act of care.</p><a class="btn" href="shopAll.php">Shop the collection <i class="bi bi-arrow-right"></i></a></div><div class="hero-art" aria-hidden="true"></div></section>
  <div class="section-title"><div><span class="eyebrow">Fresh finds</span><h2>Made for your daily glow</h2></div><a href="shopAll.php">View all products</a></div>
  <div class="product-grid"><?php foreach ($products as $product): ?><?php include __DIR__ . '/_product_card.php'; ?><?php endforeach; ?></div>
</main><?php store_footer(); ?>
