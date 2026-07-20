<?php
require_once __DIR__ . '/_store.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = (int)($_POST['id'] ?? 0);
  $action = $_POST['action'] ?? '';
  $itemId = $_POST['cart_item_id'] ?? '';
  if ($action === 'add' && $id) cart_add($id, (int)($_POST['qty'] ?? 1), trim($_POST['skin_type'] ?? ''), trim($_POST['size'] ?? ''));
  if ($action === 'update') {
    $qty = (int)($_POST['qty'] ?? 1);
    if (current_user_id()) cart_change_quantity($itemId, $qty);
    elseif (str_starts_with($itemId, 'guest-')) {
      $guestId = (int)substr($itemId, 6);
      if ($qty > 0) $_SESSION['cart'][$guestId]['qty'] = $qty;
      else unset($_SESSION['cart'][$guestId]);
    }
  }
  if ($action === 'remove') {
    if (current_user_id()) cart_remove($itemId);
    elseif (str_starts_with($itemId, 'guest-')) unset($_SESSION['cart'][(int)substr($itemId, 6)]);
  }
  header('Location: cart.php?updated=1');
  exit;
}
$items = cart_items();
$subtotal = cart_total();

// Compute matching values from the mockup
$delivery = $subtotal ? 1.25 : 0;
$discount = $subtotal ? round($subtotal * 0.0467, 2) : 0;
$grandTotal = $subtotal + $delivery - $discount;

// Group items by brand/vendor
$grouped_items = [];
foreach ($items as $product) {
  $brand = store_name();
  if (isset($product['category_name'])) {
    if (strcasecmp($product['category_name'], 'Blush') === 0 || strcasecmp($product['category_name'], 'Other Product') === 0) {
      $brand = 'Miss Sunflower';
    } elseif (strcasecmp($product['category_name'], 'Cleanser') === 0 || strcasecmp($product['category_name'], 'Collection') === 0) {
      $brand = 'Weyoung';
    }
  }
  $grouped_items[$brand][] = $product;
}

store_header('Shopping bag');
?>
<main class="cart-page-wrapper">
  <div class="cart-container">
    <div class="cart-title-row">
      <h1>Shopping Bag</h1>
    </div>

    <?php if (isset($_GET['updated'])): ?>
      <div class="notice"><i class="bi bi-check-circle"></i> Your shopping bag has been updated.</div>
    <?php endif; ?>

    <?php if (!$items): ?>
      <div class="empty">
        <i class="bi bi-bag-heart"></i>
        <h2>Your bag is waiting for something lovely.</h2>
        <p>Explore our skin-loving essentials and add your first favourite.</p>
        <a class="btn" href="shopAll.php">Explore products</a>
      </div>
    <?php else: ?>
      <div class="cart-split-layout">
        <!-- Left Side: Grouped Cart Items -->
        <div class="cart-items-column">
          <?php foreach ($grouped_items as $brand => $brandProducts): ?>
            <div class="brand-group-container">
              <div class="brand-group-header">
                <?php if ($brand === 'Miss Sunflower'): ?>
                  <div class="brand-avatar yellow-avatar">🌻</div>
                <?php else: ?>
                  <div class="brand-avatar black-avatar">WY</div>
                <?php endif; ?>
                <span class="brand-group-name"><?= e($brand) ?></span>
              </div>

              <div class="brand-products-list">
                <?php foreach ($brandProducts as $product): ?>
                  <div class="cart-item-card">
                    <div class="cart-item-media">
                      <img src="<?= e(image_for($product)) ?>" alt="<?= e($product['p_title']) ?>">
                    </div>

                    <div class="cart-item-info">
                      <h3 class="cart-item-title"><?= e($product['p_title']) ?></h3>

                      <div class="cart-item-summary-box">
                        <span class="summary-text">Female ·
                          <?= e($product['selected_skin_type'] ?: 'Dry skin') ?> ·
                          <?= e($product['selected_size'] ?: '100ml') ?></span>
                        <i class="bi bi-chevron-down"></i>
                      </div>

                      <span
                        class="cart-item-price"><?= money($product['p_price'] * $product['cart_qty']) ?></span>
                    </div>

                    <div class="cart-item-actions">
                      <div class="qty-pill-control">
                        <form method="post" class="qty-form">
                          <input type="hidden" name="action" value="update">
                          <input type="hidden" name="cart_item_id"
                            value="<?= e($product['cart_item_id']) ?>">
                          <button type="submit" name="qty" value="<?= max(0, $product['cart_qty'] - 1) ?>"
                            class="qty-btn">−</button>
                          <input class="qty-value-input" aria-label="Quantity"
                            value="<?= e($product['cart_qty']) ?>" readonly>
                          <button type="submit" name="qty" value="<?= $product['cart_qty'] + 1 ?>"
                            class="qty-btn">+</button>
                        </form>
                      </div>

                      <div class="action-buttons-row">
                        <form method="post" class="inline-form">
                          <input type="hidden" name="action" value="remove">
                          <input type="hidden" name="cart_item_id"
                            value="<?= e($product['cart_item_id']) ?>">
                          <button type="submit" class="icon-action-btn delete-btn" title="Remove item"><i
                              class="bi bi-trash3"></i></button>
                        </form>
                        <button type="button" class="icon-action-btn heart-btn" title="Save to wishlist"><i
                            class="bi bi-heart"></i></button>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <!-- Right Side: Order Summary & Checkout -->
        <div class="cart-summary-column">
          <div class="summary-section-card">
            <h2>Order Summary</h2>

            <div class="summary-details-rows">
              <div class="detail-row">
                <span>Total Price :</span>
                <strong><?= money($subtotal) ?></strong>
              </div>
              <div class="detail-row">
                <span>Delivery Fee :</span>
                <strong><?= money($delivery) ?></strong>
              </div>
              <div class="detail-row discount-row">
                <span>Total Discount :</span>
                <strong>-<?= money($discount) ?></strong>
              </div>

              <div class="promote-code-row">
                <input type="text" placeholder="Enter code here" class="promo-input">
                <button type="button" class="promo-apply-btn">Apply</button>
              </div>

              <div class="detail-row subtotal-row">
                <span>Sub Total :</span>
                <strong><?= money($grandTotal) ?></strong>
              </div>
            </div>
          </div>

          <div class="summary-section-card">
            <h2>Payment Method</h2>

            <div class="payment-methods-list">
              <label class="payment-method-option">
                <input type="radio" name="payment_method" value="card" checked>
                <span class="custom-radio-indicator"></span>
                <i class="bi bi-credit-card-2-front-fill method-icon font-icon"></i>
                <span class="method-label">Credit Card \ Debit Card</span>
              </label>

              <!-- <label class="payment-method-option">
                            <input type="radio" name="payment_method" value="acleda">
                            <span class="custom-radio-indicator"></span>
                            <span class="method-icon text-icon acleda-icon">A</span>
                            <span class="method-label">ACLEDA Pay</span>
                        </label> -->

              <!-- <label class="payment-method-option">
                            <input type="radio" name="payment_method" value="khqr">
                            <span class="custom-radio-indicator"></span>
                            <span class="method-icon text-icon khqr-icon">KH</span>
                            <span class="method-label">KHQR</span>
                        </label> -->

              <label class="payment-method-option">
                <input type="radio" name="payment_method" value="apple">
                <span class="custom-radio-indicator"></span>
                <i class="bi bi-apple method-icon font-icon"></i>
                <span class="method-label">Apple Pay</span>
              </label>
            </div>
          </div>

          <div class="checkout-footer-row">
            <div class="total-payment-display">
              <span class="label">Total Payment :</span>
              <span class="value"><?= money($grandTotal) ?></span>
            </div>
            <!-- <a class="black-checkout-btn" href="checkout.php">
              Check Out Now <i class="bi bi-arrow-up-right"></i>
            </a> -->
            <a class="black-checkout-btn" href="checkout.php">
              Check Out Now <i class="bi bi-arrow-up-right"></i>
            </a>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>
</main>
<?php store_footer(); ?>