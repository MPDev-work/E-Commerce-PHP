<?php
require_once __DIR__ . '/_store.php';
$items = cart_items();
if (!$items) {
  header('Location: cart.php');
  exit;
}
$subtotal = cart_total();
$delivery = $subtotal >= 35 ? 0 : 2.50;
$total = $subtotal + $delivery;
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  if (!$name || !filter_var($email, FILTER_VALIDATE_EMAIL)) $error = 'Please provide your name and a valid email address.';
  else {
    $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
    $stmt = $conn->prepare('INSERT INTO orders (user_id, total_amount, status) VALUES (?, ?, "pending")');
    $stmt->bind_param('id', $userId, $total);
    if ($stmt->execute()) {
      $orderId = $conn->insert_id;
      $line = $conn->prepare('INSERT INTO order_items (order_id, product_id, product_name, unit_price, quantity) VALUES (?, ?, ?, ?, ?)');
      foreach ($items as $p) {
        $pid = !empty($p['_demo']) ? null : (int)$p['p_id'];
        $title = $p['p_title'];
        $price = (float)$p['p_price'];
        $qty = (int)$p['cart_qty'];
        $line->bind_param('iisdi', $orderId, $pid, $title, $price, $qty);
        $line->execute();
      }
      $_SESSION['cart'] = [];
      header('Location: checkout.php?success=' . $orderId);
      exit;
    }
    $error = 'We could not place your order. Please try again.';
  }
}
store_header('Checkout');
?>
<main class="cart-page">
  <div class="cart-shell"><?php if (isset($_GET['success'])): ?><div class="empty"><i
          class="bi bi-check-circle-fill"></i>
        <h1>Order confirmed</h1>
        <p>Thank you for your order. We’ll send a confirmation and delivery updates shortly.</p>
        <p style="font-weight:700">Order #<?= e($_GET['success']) ?></p><a class="btn" href="shopAll.php">Continue
          shopping</a>
      </div><?php else: ?><span class="eyebrow">Almost yours</span>
      <h1>Checkout</h1>
      <div class="cart-grid">
        <section class="summary-card">
          <h2>Delivery details</h2><?php if ($error): ?><div style="color:#bf2638;margin-top:15px">
              <?= e($error) ?></div><?php endif; ?><form class="checkout-form" method="post"
            style="margin-top:20px"><input name="name" required placeholder="Full name"
              value="<?= e($_POST['name'] ?? '') ?>"><input name="email" type="email" required
              placeholder="Email address" value="<?= e($_POST['email'] ?? '') ?>"><input name="phone"
              placeholder="Phone number"><textarea name="address" required
              placeholder="Delivery address"></textarea>
            <h3 style="margin:8px 0 0;font:700 20px Outfit">Payment method</h3><label class="payment"><span><i
                  class="bi bi-credit-card" style="color:#df3448"></i> Credit or debit card</span><input
                type="radio" checked name="payment"></label><label class="payment"><span><i
                  class="bi bi-wallet2" style="color:#df3448"></i> Pay on delivery</span><input
                type="radio" name="payment"></label><button class="btn" type="submit">Place order ·
              <?= money($total) ?></button>
          </form>
        </section>
        <aside class="summary-card">
          <h2>Your order</h2><?php foreach ($items as $p): ?><div
              style="display:flex;gap:12px;align-items:center;margin:16px 0"><img
                style="width:54px;height:54px;border-radius:10px;object-fit:cover" src="<?= e(image_for($p)) ?>"
                alt=""><span style="flex:1;font-size:13px"><?= e($p['p_title']) ?><small
                  style="display:block;color:#777">Qty
                  <?= e($p['cart_qty']) ?></small></span><strong><?= money($p['p_price'] * $p['cart_qty']) ?></strong>
            </div><?php endforeach; ?><div class="summary-line">
            <span>Subtotal</span><strong><?= money($subtotal) ?></strong>
          </div>
          <div class="summary-line">
            <span>Delivery</span><strong><?= $delivery ? money($delivery) : 'Free' ?></strong>
          </div>
          <div class="summary-line summary-total"><span>Total</span><strong><?= money($total) ?></strong></div>
        </aside>
      </div><?php endif; ?>
  </div>
</main><?php store_footer(); ?>