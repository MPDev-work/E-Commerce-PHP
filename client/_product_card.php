<?php $productImage = image_for($product); ?>
<article class="product-card">
  <a class="product-card-media product-image-link" href="product.php?id=<?= e($product['p_id']) ?>">
    <?php if ($productImage): ?>
      <img class="object-cover" src="<?= e($productImage) ?>" alt="<?= e($product['p_title']) ?>">
    <?php else: ?>
      <span class="image-empty"><i class="bi bi-image"></i><small>Image coming soon</small></span>
    <?php endif; ?>
  </a>
  <div class="product-card-details">
    <span class="product-brand"><?= e(product_label($product)) ?></span>
    <h3 class="product-title"><a href="product.php?id=<?= e($product['p_id']) ?>"><?= e($product['p_title']) ?></a>
    </h3>
    <div class="product-footer-row">
      <span class="current-price"><?= money($product['p_price']) ?></span>
      <a class="add-to-bag-btn" href="product.php?id=<?= e($product['p_id']) ?>">View product <i
          class="bi bi-arrow-up-right"></i></a>
    </div>
  </div>
</article>