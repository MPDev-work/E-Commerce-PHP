<?php
require_once __DIR__ . '/_layout.php';

if (($user['roles'] ?? '') !== 'admin') {
    http_response_code(403);
    exit('Administrator access is required.');
}

if (empty($_SESSION['order_status_csrf'])) {
    $_SESSION['order_status_csrf'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = filter_input(INPUT_POST, 'order_id', FILTER_VALIDATE_INT);
    $csrf = $_POST['csrf'] ?? '';

    if ($orderId && hash_equals($_SESSION['order_status_csrf'], $csrf)) {
        $statement = $conn->prepare("UPDATE orders SET status = 'completed' WHERE order_id = ? AND status IN ('pending', 'processing')");
        $statement->bind_param('i', $orderId);
        $statement->execute();
    }

    header('Location: orders.php');
    exit;
}

$orders = $conn->query('SELECT o.order_id, o.total_amount, o.status, o.created_at, u.username, u.email FROM orders o LEFT JOIN users u ON u.id = o.user_id ORDER BY o.created_at DESC');
$statusClasses = ['completed' => 'bg-emerald-50 text-emerald-700', 'cancelled' => 'bg-red-50 text-red-700', 'pending' => 'bg-amber-50 text-amber-700', 'processing' => 'bg-blue-50 text-blue-700'];
admin_header('Orders', 'orders.php');
page_title('Sales', 'Orders', 'Track orders and their fulfillment status.');
?>
<div class="overflow-x-auto rounded-[24px] border border-slate-100">
    <table class="w-full min-w-[700px] text-left text-sm">
        <thead class="bg-black text-white">
            <tr>
                <th class="px-5 py-4">Order</th>
                <th class="px-5 py-4">Customer</th>
                <th class="px-5 py-4">Total</th>
                <th class="px-5 py-4">Status</th>
                <th class="px-5 py-4">Date</th>
                <th class="px-5 py-4">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($orders && $orders->num_rows): while ($order = $orders->fetch_assoc()): $status = strtolower($order['status']); ?>
                    <tr class="border-b border-slate-100 last:border-0 hover:bg-[#f2f2f6]">
                        <td class="px-5 py-4 font-medium">#<?= e($order['order_id']) ?></td>
                        <td class="px-5 py-4"><?= e($order['username'] ?: 'Guest') ?><small
                                class="block text-slate-400"><?= e($order['email'] ?: '') ?></small></td>
                        <td class="px-5 py-4">$<?= number_format((float)$order['total_amount'], 2) ?></td>
                        <td class="px-5 py-4"><span
                                class="rounded-full px-3 py-1 <?= $statusClasses[$status] ?? 'bg-slate-100 text-slate-700' ?>"><?= e(ucwords($status)) ?></span>
                        </td>
                        <td class="px-5 py-4 text-slate-500"><?= e(date('M j, Y', strtotime($order['created_at']))) ?></td>
                        <td class="px-5 py-4">
                            <?php if (in_array($status, ['pending', 'processing'], true)): ?>
                                <form method="post">
                                    <input type="hidden" name="order_id" value="<?= e($order['order_id']) ?>">
                                    <input type="hidden" name="csrf" value="<?= e($_SESSION['order_status_csrf']) ?>">
                                    <button type="submit" class="rounded-full bg-emerald-600 px-4 py-2 text-xs font-medium text-white transition hover:bg-emerald-700">Complete</button>
                                </form>
                            <?php else: ?>
                                <span class="text-slate-400">—</span>
                            <?php endif; ?>
                        </td>
                    </tr><?php endwhile;
                    else: ?><tr>
                    <td colspan="6" class="px-5 py-12 text-center text-slate-400">No orders have been placed yet.</td>
                </tr><?php endif; ?>
        </tbody>
    </table>
</div>
<?php admin_footer(); ?>
