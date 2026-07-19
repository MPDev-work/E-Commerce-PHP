<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
require_once __DIR__ . '/../config/connect.php';

function e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
function money($value)
{
    return '$' . number_format((float)$value, 2);
}
function image_for($product)
{
    $image = !empty($product['p_image']) ? basename($product['p_image']) : '';
    if ($image && is_file(__DIR__ . '/../images/' . $image)) return '../images/' . rawurlencode($image);
    return '';
}
function catalogue()
{
    global $conn;
    $sql = "SELECT p.p_id, p.p_title, p.p_price, p.p_qty, p.description, p.p_image, c.category_name,
      GROUP_CONCAT(DISTINCT st.skin_type_name ORDER BY st.skin_type_name SEPARATOR '|') AS skin_types,
      GROUP_CONCAT(DISTINCT s.size_name ORDER BY s.sort_order, s.size_name SEPARATOR '|') AS sizes
      FROM products p LEFT JOIN categories c ON c.category_id = p.category_id
      LEFT JOIN product_skin_types pst ON pst.product_id = p.p_id LEFT JOIN skin_types st ON st.skin_type_id = pst.skin_type_id
      LEFT JOIN product_sizes ps ON ps.product_id = p.p_id LEFT JOIN sizes s ON s.size_id = ps.size_id
      WHERE p.p_qty > 0 GROUP BY p.p_id ORDER BY p.p_id DESC";
    $result = $conn->query($sql);
    if ($result && $result->num_rows) return $result->fetch_all(MYSQLI_ASSOC);
    return [];
}
function product_options(array $product, string $field): array
{
    return array_values(array_filter(explode('|', $product[$field] ?? '')));
}

function current_user_id(): int
{
    return (int)($_SESSION['user_id'] ?? 0);
}

function user_cart_id(bool $create = false): int
{
    global $conn;
    $userId = current_user_id();
    if (!$userId) return 0;
    $stmt = $conn->prepare('SELECT cart_id FROM user_carts WHERE user_id = ?');
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $cart = $stmt->get_result()->fetch_assoc();
    if ($cart) return (int)$cart['cart_id'];
    if (!$create) return 0;
    $stmt = $conn->prepare('INSERT INTO user_carts (user_id) VALUES (?)');
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    return (int)$conn->insert_id;
}

function session_cart_items(): array
{
    $all = [];
    foreach (catalogue() as $p) $all[$p['p_id']] = $p;
    $items = [];
    foreach (($_SESSION['cart'] ?? []) as $id => $entry) if (isset($all[$id])) {
        $qty = is_array($entry) ? (int)($entry['qty'] ?? 0) : (int)$entry;
        if ($qty > 0) {
            $all[$id]['cart_item_id'] = 'guest-' . $id;
            $all[$id]['cart_qty'] = min($qty, (int)$all[$id]['p_qty']);
            $all[$id]['selected_skin_type'] = is_array($entry) ? ($entry['skin_type'] ?? '') : '';
            $all[$id]['selected_size'] = is_array($entry) ? ($entry['size'] ?? '') : '';
            $items[] = $all[$id];
        }
    }
    return $items;
}
function cart_items()
{
    global $conn;
    if (!current_user_id()) return session_cart_items();
    $cartId = user_cart_id();
    if (!$cartId) return [];
    $stmt = $conn->prepare("SELECT ci.cart_item_id, ci.quantity AS cart_qty, ci.selected_skin_type, ci.selected_size, p.p_id, p.p_title, p.p_price, p.p_qty, p.description, p.p_image, c.category_name, GROUP_CONCAT(DISTINCT st.skin_type_name ORDER BY st.skin_type_name SEPARATOR '|') AS skin_types, GROUP_CONCAT(DISTINCT s.size_name ORDER BY s.sort_order, s.size_name SEPARATOR '|') AS sizes FROM user_cart_items ci JOIN products p ON p.p_id = ci.product_id LEFT JOIN categories c ON c.category_id = p.category_id LEFT JOIN product_skin_types pst ON pst.product_id = p.p_id LEFT JOIN skin_types st ON st.skin_type_id = pst.skin_type_id LEFT JOIN product_sizes ps ON ps.product_id = p.p_id LEFT JOIN sizes s ON s.size_id = ps.size_id WHERE ci.cart_id = ? GROUP BY ci.cart_item_id, ci.quantity, ci.selected_skin_type, ci.selected_size, p.p_id, p.p_title, p.p_price, p.p_qty, p.description, p.p_image, c.category_name ORDER BY ci.cart_item_id DESC");
    $stmt->bind_param('i', $cartId);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
function cart_count()
{
    if (!current_user_id()) {
        $count = 0;
        foreach (($_SESSION['cart'] ?? []) as $entry) $count += is_array($entry) ? (int)($entry['qty'] ?? 0) : (int)$entry;
        return $count;
    }
    $cartId = user_cart_id();
    if (!$cartId) return 0;
    global $conn;
    $stmt = $conn->prepare('SELECT COALESCE(SUM(quantity), 0) total FROM user_cart_items WHERE cart_id = ?');
    $stmt->bind_param('i', $cartId);
    $stmt->execute();
    return (int)($stmt->get_result()->fetch_assoc()['total'] ?? 0);
}
function cart_total()
{
    $total = 0;
    foreach (cart_items() as $p) $total += $p['p_price'] * $p['cart_qty'];
    return $total;
}
function cart_add(int $productId, int $qty, string $skinType, string $size): void
{
    global $conn;
    if (!current_user_id()) {
        $current = $_SESSION['cart'][$productId] ?? ['qty' => 0];
        if (!is_array($current)) $current = ['qty' => (int)$current];
        $current['qty'] = min(99, (int)$current['qty'] + max(1, $qty));
        $current['skin_type'] = $skinType;
        $current['size'] = $size;
        $_SESSION['cart'][$productId] = $current;
        return;
    }
    $cartId = user_cart_id(true);
    $stmt = $conn->prepare('SELECT cart_item_id, quantity FROM user_cart_items WHERE cart_id = ? AND product_id = ? AND selected_skin_type = ? AND selected_size = ?');
    $stmt->bind_param('iiss', $cartId, $productId, $skinType, $size);
    $stmt->execute();
    $item = $stmt->get_result()->fetch_assoc();
    if ($item) {
        $newQty = min(99, (int)$item['quantity'] + max(1, $qty));
        $stmt = $conn->prepare('UPDATE user_cart_items SET quantity = ? WHERE cart_item_id = ?');
        $stmt->bind_param('ii', $newQty, $item['cart_item_id']);
        $stmt->execute();
    } else {
        $qty = max(1, min(99, $qty));
        $stmt = $conn->prepare('INSERT INTO user_cart_items (cart_id, product_id, quantity, selected_skin_type, selected_size) VALUES (?, ?, ?, ?, ?)');
        $stmt->bind_param('iiiss', $cartId, $productId, $qty, $skinType, $size);
        $stmt->execute();
    }
}
function cart_change_quantity($itemId, int $qty): void
{
    global $conn;
    if (!current_user_id() || !is_numeric($itemId)) return;
    $userId = current_user_id();
    if ($qty < 1) {
        $stmt = $conn->prepare('DELETE ci FROM user_cart_items ci JOIN user_carts uc ON uc.cart_id = ci.cart_id WHERE ci.cart_item_id = ? AND uc.user_id = ?');
        $stmt->bind_param('ii', $itemId, $userId);
    } else {
        $qty = min(99, $qty);
        $stmt = $conn->prepare('UPDATE user_cart_items ci JOIN user_carts uc ON uc.cart_id = ci.cart_id SET ci.quantity = ? WHERE ci.cart_item_id = ? AND uc.user_id = ?');
        $stmt->bind_param('iii', $qty, $itemId, $userId);
    }
    $stmt->execute();
}
function cart_remove($itemId): void
{
    global $conn;
    if (!current_user_id() || !is_numeric($itemId)) return;
    $userId = current_user_id();
    $stmt = $conn->prepare('DELETE ci FROM user_cart_items ci JOIN user_carts uc ON uc.cart_id = ci.cart_id WHERE ci.cart_item_id = ? AND uc.user_id = ?');
    $stmt->bind_param('ii', $itemId, $userId);
    $stmt->execute();
}
function product_label(array $product): string
{
    return trim((string)($product['category_name'] ?? '')) ?: 'Solis Skin';
}
function store_header($title = 'Shop')
{
    $count = cart_count();
    $loggedIn = current_user_id() > 0;
    $username = trim((string)($_SESSION['username'] ?? ''));
    $initial = strtoupper(substr($username ?: 'U', 0, 1)); ?>
    <!doctype html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= e($title) ?> · Solis Skin</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
            rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="store.css">
    </head>

    <body>
        <header class="site-header">
            <div class="topbar"><a class="logo" href="index.php">SOLIS <span class="logo-skin">SKIN</span></a>
                <form class="search" action="shopAll.php" method="get"><input name="q" value="<?= e($_GET['q'] ?? '') ?>"
                        placeholder="Search products"><button aria-label="Search"><i class="bi bi-search"></i></button>
                </form>
                <div class="header-actions"><?php if ($loggedIn): ?><a href="indexAfter.php" class="account-chip"
                            aria-label="Your profile"><span
                                class="account-initial"><?= e($initial) ?></span><span><?= e($username ?: 'My profile') ?></span></a><a
                            href="../auth/logout.php" class="logout-btn"><i
                                class="bi bi-box-arrow-right"></i><span>Logout</span></a><?php else: ?><a
                            href="../auth/login.php" class="header-link">Login</a><a href="../auth/register.php"
                            class="header-register">Register</a><?php endif; ?><a href="cart.php" class="action-btn badge-btn"
                        aria-label="Cart"><i class="bi bi-bag"></i><?php if ($count): ?><span
                                class="badge"><?= $count ?></span><?php endif; ?></a></div>
            </div>
            <nav class="nav-links"><a href="index.php">Home</a><a href="shopAll.php">Shop</a><a
                    href="category.php">Categories</a></nav>
        </header>
    <?php }
function store_footer()
{ ?>
        <footer class="site-footer">
            <div class="footer-grid">
                <div class="footer-brand"><a class="logo" href="index.php">SOLIS <span class="logo-skin">SKIN</span></a>
                    <p class="brand-desc">Your trusted destination for healthy and glowing skin. 100% authentic skincare
                        products.</p>
                    <ul class="social-links">
                        <li><a href="#"><i class="bi bi-facebook"></i> Facebook</a></li>
                        <li><a href="#"><i class="bi bi-tiktok"></i> TikTok</a></li>
                        <li><a href="#"><i class="bi bi-instagram"></i> Instagram</a></li>
                        <li><a href="#"><i class="bi bi-youtube"></i> YouTube</a></li>
                    </ul>
                </div>
                <div class="footer-links">
                    <h4>Quick Link</h4>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="shopAll.php">Shop</a></li>
                        <li><a href="shopAll.php?filter=new">New Arrivals</a></li>
                        <li><a href="shopAll.php?filter=best">Best Sellers</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">About Us</a></li>
                    </ul>
                </div>
                <div class="footer-links">
                    <h4>Customer Service</h4>
                    <ul>
                        <li><a href="#">My Account</a></li>
                        <li><a href="#">Order Tracking</a></li>
                        <li><a href="#">Shipping Policy</a></li>
                        <li><a href="#">Return Policy</a></li>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="footer-contact">
                    <h4>Contact-Info</h4>
                    <ul>
                        <li><i class="bi bi-geo-alt-fill"></i> Phnom Penh, Cambodia</li>
                        <li><i class="bi bi-telephone-fill"></i> +855 XX XXX XXX</li>
                        <li><i class="bi bi-envelope-fill"></i> support@glowskin.com</li>
                        <li><i class="bi bi-clock-fill"></i> Mon - Sat (8:00AM - 6:00PM)</li>
                    </ul>
                </div>
            </div>
        </footer>
    </body>

    </html>
<?php }
