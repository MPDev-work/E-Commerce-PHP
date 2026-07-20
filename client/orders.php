<?php
require_once __DIR__ . '/_store.php';

$userId = current_user_id();
if (!$userId) {
    header('Location: ../auth/login.php');
    exit;
}

if (empty($_SESSION['client_order_csrf'])) {
    $_SESSION['client_order_csrf'] = bin2hex(random_bytes(32));
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = filter_input(INPUT_POST, 'order_id', FILTER_VALIDATE_INT);
    $csrf = $_POST['csrf'] ?? '';

    if ($orderId && hash_equals($_SESSION['client_order_csrf'], $csrf)) {
        $stmt = $conn->prepare("UPDATE orders SET status = 'cancelled' WHERE order_id = ? AND user_id = ? AND status = 'pending'");
        $stmt->bind_param('ii', $orderId, $userId);
        $stmt->execute();
        $message = $stmt->affected_rows ? 'Your order has been cancelled.' : 'This order can no longer be cancelled.';
    }
}

$ordersStmt = $conn->prepare('SELECT order_id, total_amount, status, created_at FROM orders WHERE user_id = ? ORDER BY created_at DESC');
$ordersStmt->bind_param('i', $userId);
$ordersStmt->execute();
$orders = $ordersStmt->get_result()->fetch_all(MYSQLI_ASSOC);

$itemsStmt = $conn->prepare('SELECT oi.product_name, oi.unit_price, oi.quantity, p.p_image FROM order_items oi LEFT JOIN products p ON p.p_id = oi.product_id WHERE oi.order_id = ? ORDER BY oi.order_item_id');
foreach ($orders as &$order) {
    $itemsStmt->bind_param('i', $order['order_id']);
    $itemsStmt->execute();
    $order['items'] = $itemsStmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
unset($order);

$statusClasses = [
    'pending' => 'background:#fff7df;color:#9a6700',
    'processing' => 'background:#e8f1ff;color:#1d4ed8',
    'completed' => 'background:#e9f9ef;color:#18743a',
    'cancelled' => 'background:#feecee;color:#b42318',
];

store_header('Order history');
?>
<main class="cart-page">
    <div class="cart-shell">
        <span class="eyebrow">Your account</span>
        <h1>Order history</h1>
        <p style="color:#666; margin:8px 0 24px">View your purchases and cancel orders before they are processed.</p>

        <?php if ($message): ?>
            <div style="margin-bottom:20px;padding:14px 16px;border-radius:10px;background:#eef8f1;color:#18743a"><?= e($message) ?></div>
        <?php endif; ?>

        <?php if (!$orders): ?>
            <div class="empty">
                <i class="bi bi-bag"></i>
                <h2>No orders yet</h2>
                <p>Your completed checkouts will appear here.</p>
                <a class="btn" href="shopAll.php">Start shopping</a>
            </div>
        <?php else: ?>
            <?php foreach ($orders as $order): $status = strtolower($order['status']); ?>
                <section class="summary-card" style="margin-bottom:18px">
                    <div style="display:flex;gap:16px;justify-content:space-between;align-items:flex-start;flex-wrap:wrap">
                        <div>
                            <h2 style="margin:0">Order #<?= e($order['order_id']) ?></h2>
                            <p style="margin:6px 0 0;color:#777">Placed <?= e(date('M j, Y', strtotime($order['created_at']))) ?></p>
                        </div>
                        <span style="padding:6px 12px;border-radius:999px;font-size:13px;font-weight:600;<?= $statusClasses[$status] ?? 'background:#f1f5f9;color:#475569' ?>"><?= e(ucfirst($status)) ?></span>
                    </div>

                    <div style="margin:18px 0;border-top:1px solid #eee">
                        <?php foreach ($order['items'] as $item): ?>
                            <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;padding:12px 0;border-bottom:1px solid #eee">
                                <div style="display:flex;align-items:center;gap:12px;min-width:0">
                                    <?php if ($item['p_image'] && image_for($item)): ?>
                                        <img src="<?= e(image_for($item)) ?>" alt="<?= e($item['product_name']) ?>" style="width:56px;height:56px;flex:0 0 56px;object-fit:cover;border-radius:10px;background:#f2f2f6">
                                    <?php else: ?>
                                        <span style="width:56px;height:56px;flex:0 0 56px;display:grid;place-items:center;border-radius:10px;background:#f2f2f6;color:#999"><i class="bi bi-image"></i></span>
                                    <?php endif; ?>
                                    <span><?= e($item['product_name']) ?> <small style="color:#777">× <?= e($item['quantity']) ?></small></span>
                                </div>
                                <strong><?= money((float)$item['unit_price'] * (int)$item['quantity']) ?></strong>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div style="display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap">
                        <strong>Total: <?= money($order['total_amount']) ?></strong>
                        <?php if ($status === 'pending'): ?>
                            <form method="post" onsubmit="return confirm('Cancel this pending order?');">
                                <input type="hidden" name="order_id" value="<?= e($order['order_id']) ?>">
                                <input type="hidden" name="csrf" value="<?= e($_SESSION['client_order_csrf']) ?>">
                                <button class="cance-btn" type="submit">Cancel order</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </section>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>
<?php store_footer(); ?>