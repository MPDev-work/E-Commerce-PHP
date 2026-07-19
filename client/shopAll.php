<?php require_once __DIR__ . '/_store.php';
$products = catalogue();
$q = trim($_GET['q'] ?? '');
$category = trim($_GET['category'] ?? '');
if ($q) $products = array_filter($products, fn($p) => stripos($p['p_title'], $q) !== false || stripos($p['description'], $q) !== false);
if ($category) $products = array_filter($products, fn($p) => strcasecmp($p['category_name'] ?? '', $category) === 0);
store_header('Shop'); ?>
<main class="page shop-all-page"><?php if (!$products): ?><div class="empty"><i class="bi bi-search"></i>
            <h2>No products found</h2>
            <p>Try another search or browse the full collection.</p><a class="btn" href="shopAll.php">View all products</a>
        </div><?php else: ?><div class="product-grid">
            <?php foreach ($products as $product): ?><?php include __DIR__ . '/_product_card.php'; ?><?php endforeach; ?>
        </div><?php endif; ?></main><?php store_footer(); ?>