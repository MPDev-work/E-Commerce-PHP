<?php
require_once __DIR__ . '/_store.php';

$all_products = catalogue();
$groups = [];
foreach ($all_products as $product) {
  $cat = $product['category_name'] ?: 'Skincare';
  $groups[$cat][] = $product;
}

$desired_order = ['Blush', 'Cleanser', 'Toner', 'Sunscreen', 'Serum', 'Collection', 'Other Product'];
// Sort groups by desired order
uksort($groups, function ($a, $b) use ($desired_order) {
  $pos_a = array_search($a, $desired_order);
  $pos_b = array_search($b, $desired_order);
  if ($pos_a === false) return 1;
  if ($pos_b === false) return -1;
  return $pos_a - $pos_b;
});

store_header('Skincare categories');
?>
<main class="category-page">
  <?php foreach ($groups as $category => $items): ?>
    <section class="category-section">
      <div class="category-section-header">
        <h2 class="category-title"><?= e(strtoupper($category)) ?> 📌</h2>
        <a class="show-more-link" href="shopAll.php?category=<?= urlencode($category) ?>">Show more</a>
      </div>
      <div class="product-grid">
        <?php foreach (array_slice($items, 0, 4) as $product): ?>
          <?php include __DIR__ . '/_product_card.php'; ?>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endforeach; ?>
</main>
<?php store_footer(); ?>