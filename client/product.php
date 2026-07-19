<?php
require_once __DIR__ . '/_store.php';
$id = (int)($_GET['id'] ?? 0);
$product = null;
foreach (catalogue() as $item) if ((int)$item['p_id'] === $id) {
  $product = $item;
  break;
}
if (!$product) {
  header('Location: shopAll.php');
  exit;
}
$skinTypes = product_options($product, 'skin_types');
$sizes = product_options($product, 'sizes');
$productImage = image_for($product);
store_header('Product details');
?>
<main class="product-detail-page">
  <div class="product-layout-split">
    <div class="product-gallery-column">
      <div class="gallery-main-display">
        <?php if ($productImage): ?><img class="object-cover" src="<?= e($productImage) ?>"
            alt="<?= e($product['p_title']) ?>">
        <?php else: ?><span class="image-empty"><i class="bi bi-image"></i><small>Product image will appear
              here</small></span><?php endif; ?>
      </div>
    </div>
    <div class="product-info-column">
      <div class="detail-brand-row"><span class="brand-dot"></span><span
          class="brand-name-text"><?= e(product_label($product)) ?></span></div>
      <h1 class="detail-product-title"><?= e($product['p_title']) ?></h1>
      <div class="detail-price-row"><span class="current-price"><?= money($product['p_price']) ?></span></div>
      <?php if (!empty($product['description'])): ?><div class="detail-description">
          <p><?= e($product['description']) ?></p>
        </div><?php endif; ?>
      <p class="stock-status"><i class="bi bi-check2-circle"></i> <?= e($product['p_qty']) ?> in stock</p>
      <form class="detail-add-form" action="cart.php" method="post">
        <input type="hidden" name="action" value="add"><input type="hidden" name="id"
          value="<?= e($product['p_id']) ?>">
        <?php if ($skinTypes): ?><div class="option-group"><span class="option-label">Skin type</span>
            <div class="chips-flex"><?php foreach ($skinTypes as $index => $st): ?><label
                  class="chip-btn"><input type="radio" name="skin_type" value="<?= e($st) ?>"
                    <?= $index === 0 ? 'checked' : '' ?>><span
                    class="chip-text"><?= e($st) ?></span></label><?php endforeach; ?></div>
          </div><?php endif; ?>
        <?php if ($sizes): ?><div class="option-group"><span class="option-label">Size</span>
            <div class="chips-flex"><?php foreach ($sizes as $index => $sz): ?><label class="chip-btn"><input
                    type="radio" name="size" value="<?= e($sz) ?>"
                    <?= $index === 0 ? 'checked' : '' ?>><span
                    class="chip-text"><?= e($sz) ?></span></label><?php endforeach; ?></div>
          </div><?php endif; ?>
        <div class="detail-actions-row">
          <div class="detail-qty-control"><button type="button" class="qty-btn"
              onclick="changeQty(-1)">−</button><input type="number" id="detail-qty-input" name="qty"
              value="1" min="1" max="<?= e($product['p_qty']) ?>" readonly><button type="button"
              class="qty-btn" onclick="changeQty(1)">+</button></div><button class="detail-add-bag-btn"
            type="submit"><i class="bi bi-bag"></i> Add to bag</button>
        </div>
      </form>
    </div>
  </div>
  <?php $related = array_slice(array_filter(catalogue(), fn($item) => $item['p_id'] != $product['p_id']), 0, 4);
  if ($related): ?>
    <section class="related-products-section">
      <h2 class="section-title-large">You might also like</h2>
      <div class="product-grid">
        <?php foreach ($related as $product): include __DIR__ . '/_product_card.php';
        endforeach; ?></div>
    </section><?php endif; ?>
</main>
<script>
  function changeQty(amount) {
    const input = document.getElementById('detail-qty-input'),
      next = Number(input.value) + amount;
    if (next >= Number(input.min) && next <= Number(input.max)) input.value = next;
  }
</script>
<?php store_footer(); ?>