<?php
require_once __DIR__ . '/_layout.php';
$notice = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') { $storeName = trim($_POST['store_name'] ?? 'Solis Skin'); $currency = $_POST['currency'] ?? 'USD'; $stmt = $conn->prepare("INSERT INTO store_settings (setting_key, setting_value) VALUES ('store_name', ?), ('currency', ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)"); $stmt->bind_param('ss', $storeName, $currency); $stmt->execute(); $notice = 'Store settings saved.'; }
$settings = []; $result = $conn->query('SELECT setting_key, setting_value FROM store_settings'); if ($result) while ($row = $result->fetch_assoc()) $settings[$row['setting_key']] = $row['setting_value'];
admin_header('Settings', 'settting.php'); page_title('Account', 'Store settings', 'Control the basics that appear across your store.');
?>
<?php if ($notice): ?><p class="mb-4 rounded-2xl bg-emerald-50 p-4 text-emerald-700"><?= e($notice) ?></p><?php endif; ?>
<form method="post" class="max-w-2xl rounded-[28px] bg-[#f2f2f6] p-5"><div class="grid gap-4 sm:grid-cols-2"><label class="text-sm font-medium">Store name<input name="store_name" value="<?= e($settings['store_name'] ?? 'Solis Skin') ?>" class="mt-2 w-full rounded-full bg-white px-4 py-3 outline-none"></label><label class="text-sm font-medium">Currency<select name="currency" class="mt-2 w-full rounded-full bg-white px-4 py-3 outline-none"><option <?= ($settings['currency'] ?? 'USD') === 'USD' ? 'selected' : '' ?>>USD</option><option <?= ($settings['currency'] ?? '') === 'KHR' ? 'selected' : '' ?>>THB</option><option <?= ($settings['currency'] ?? '') === 'EUR' ? 'selected' : '' ?>>EUR</option></select></label></div><button class="mt-5 rounded-full bg-black px-6 py-3 text-sm text-white">Save settings</button></form>
<?php admin_footer(); ?>
