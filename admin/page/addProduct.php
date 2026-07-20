<?php
require_once __DIR__ . '/_layout.php';

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$editing = $id > 0;
$message = '';
$product = ['p_title' => '', 'p_price' => '', 'p_qty' => 0, 'description' => '', 'p_image' => '', 'category_id' => ''];
$selectedSkinTypes = [];
$selectedSizes = [];

if ($editing) {
    $stmt = $conn->prepare('SELECT p_id, p_title, p_price, p_qty, description, p_image, category_id FROM products WHERE p_id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
    if (!$product) {
        header('Location: products.php');
        exit;
    }
    $result = $conn->query("SELECT skin_type_id FROM product_skin_types WHERE product_id = $id");
    while ($row = $result->fetch_assoc()) $selectedSkinTypes[] = (int)$row['skin_type_id'];
    $result = $conn->query("SELECT size_id FROM product_sizes WHERE product_id = $id");
    while ($row = $result->fetch_assoc()) $selectedSizes[] = (int)$row['size_id'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['p_title'] ?? '');
    $price = (float)($_POST['p_price'] ?? 0);
    $qty = (int)($_POST['p_qty'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $categoryId = (int)($_POST['category_id'] ?? 0);
    $selectedSkinTypes = array_map('intval', $_POST['skin_types'] ?? []);
    $selectedSizes = array_map('intval', $_POST['sizes'] ?? []);
    $product = array_merge($product, ['p_title' => $title, 'p_price' => $price, 'p_qty' => $qty, 'description' => $description, 'category_id' => $categoryId]);
    if ($title === '' || $price < 0 || $qty < 0 || !$categoryId || !$selectedSkinTypes || !$selectedSizes) {
        $message = 'Complete all required fields, including category, skin types, and sizes.';
    } else {
        $image = $product['p_image'] ?? '';
        if (!empty($_FILES['p_image']['name']) && $_FILES['p_image']['error'] === UPLOAD_ERR_OK) {
            $extension = strtolower(pathinfo($_FILES['p_image']['name'], PATHINFO_EXTENSION));
            if (!in_array($extension, ['jpg', 'jpeg', 'png', 'webp'], true)) {
                $message = 'Use a JPG, PNG, or WEBP image.';
            } else {
                $image = uniqid('product_', true) . '.' . $extension;
                move_uploaded_file($_FILES['p_image']['tmp_name'], __DIR__ . '/../../images/' . $image);
            }
        }
        if ($message === '') {
            $conn->begin_transaction();
            try {
                if ($editing) {
                    $stmt = $conn->prepare('UPDATE products SET p_title=?, p_price=?, p_qty=?, description=?, p_image=?, category_id=? WHERE p_id=?');
                    $stmt->bind_param('sdissii', $title, $price, $qty, $description, $image, $categoryId, $id);
                    $stmt->execute();
                } else {
                    $stmt = $conn->prepare('INSERT INTO products (p_title, p_price, p_qty, description, p_image, category_id) VALUES (?, ?, ?, ?, ?, ?)');
                    $stmt->bind_param('sdissi', $title, $price, $qty, $description, $image, $categoryId);
                    $stmt->execute();
                    $id = $conn->insert_id;
                }
                $conn->query("DELETE FROM product_skin_types WHERE product_id = $id");
                $conn->query("DELETE FROM product_sizes WHERE product_id = $id");
                $skinStmt = $conn->prepare('INSERT INTO product_skin_types (product_id, skin_type_id) VALUES (?, ?)');
                foreach ($selectedSkinTypes as $skinTypeId) {
                    $skinStmt->bind_param('ii', $id, $skinTypeId);
                    $skinStmt->execute();
                }
                $sizeStmt = $conn->prepare('INSERT INTO product_sizes (product_id, size_id) VALUES (?, ?)');
                foreach ($selectedSizes as $sizeId) {
                    $sizeStmt->bind_param('ii', $id, $sizeId);
                    $sizeStmt->execute();
                }
                $conn->commit();
                header('Location: products.php?saved=1');
                exit;
            } catch (Throwable $error) {
                $conn->rollback();
                $message = 'The product could not be saved. Import the latest database.sql, then try again.';
            }
        }
    }
}
$categories = $conn->query('SELECT category_id, category_name FROM categories ORDER BY category_name');
$skinTypes = $conn->query('SELECT skin_type_id, skin_type_name FROM skin_types ORDER BY skin_type_name');
$sizes = $conn->query('SELECT size_id, size_name FROM sizes ORDER BY sort_order, size_name');
admin_header($editing ? 'Edit product' : 'Add product', 'addProduct.php');
page_title('Catalogue', $editing ? 'Edit product' : 'Add product', $editing ? 'Update product details and options.' : 'Create a skincare product with customer-selectable options.');
?>
<?php if ($message): ?><p class="mb-4 rounded-2xl bg-red-50 p-4 text-red-600"><?= e($message) ?></p><?php endif; ?>
<form method="post" enctype="multipart/form-data" class="max-w-[calc(100vw-256px)] rounded-[28px] bg-[#f2f2f6] p-5">
    <input type="hidden" name="id" value="<?= e($id) ?>">
    <div class="grid gap-4 md:grid-cols-2"><label class="block text-sm font-medium">Product name *<input required
                name="p_title" value="<?= e($product['p_title']) ?>"
                class="mt-2 w-full rounded-full bg-white px-4 py-3 outline-none"></label><label
            class="block text-sm font-medium">Price (USD) *<input required min="0" step="0.01" type="number"
                name="p_price" value="<?= e($product['p_price']) ?>"
                class="mt-2 w-full rounded-full bg-white px-4 py-3 outline-none"></label><label
            class="block text-sm font-medium">Quantity *<input required min="0" type="number" name="p_qty"
                value="<?= e($product['p_qty']) ?>"
                class="mt-2 w-full rounded-full bg-white px-4 py-3 outline-none"></label><label
            class="block text-sm font-medium">Category *<select required name="category_id"
                class="mt-2 w-full rounded-full bg-white px-4 py-3 outline-none">
                <option value="">Choose a skincare category</option>
                <?php while ($category = $categories->fetch_assoc()): ?><option
                        value="<?= e($category['category_id']) ?>"
                        <?= (int)$product['category_id'] === (int)$category['category_id'] ? 'selected' : '' ?>>
                        <?= e($category['category_name']) ?></option><?php endwhile; ?>
            </select></label><label class="block text-sm font-medium md:col-span-2">Product image
            <?= $editing ? '(leave empty to keep current image)' : '' ?><input accept="image/png,image/jpeg,image/webp"
                type="file" id="image" name="p_image"
                class="mt-2 block w-full rounded-full bg-white px-4 py-3 text-sm"></label>
        <div id="preview-container" class="<?= $product['p_image'] ? '' : 'hidden' ?> md:col-span-2">
            <p class="mb-2 text-sm font-medium text-slate-600">Image preview</p>
            <img
                src="<?= $product['p_image'] ? '../../images/' . e(basename($product['p_image'])) : '' ?>"
                id="preview"
                alt="Product image preview"
                class="h-[240px] w-[240px] rounded-3xl border border-gray-200 object-cover">
        </div>
    </div>
    <div class="mt-5 grid gap-5 md:grid-cols-2">
        <fieldset>
            <legend class="text-sm font-medium">Suitable skin types *</legend>
            <div class="mt-2 grid grid-cols-2 gap-2 rounded-2xl bg-white p-4">
                <?php while ($type = $skinTypes->fetch_assoc()): ?><label class="flex items-center gap-2 text-sm"><input
                            type="checkbox" name="skin_types[]" value="<?= e($type['skin_type_id']) ?>"
                            <?= in_array((int)$type['skin_type_id'], $selectedSkinTypes, true) ? 'checked' : '' ?>><?= e($type['skin_type_name']) ?></label><?php endwhile; ?>
            </div>
        </fieldset>
        <fieldset>
            <legend class="text-sm font-medium">Available sizes *</legend>
            <div class="mt-2 grid grid-cols-2 gap-2 rounded-2xl bg-white p-4">
                <?php while ($size = $sizes->fetch_assoc()): ?><label class="flex items-center gap-2 text-sm"><input
                            type="checkbox" name="sizes[]" value="<?= e($size['size_id']) ?>"
                            <?= in_array((int)$size['size_id'], $selectedSizes, true) ? 'checked' : '' ?>><?= e($size['size_name']) ?></label><?php endwhile; ?>
            </div>
        </fieldset>
    </div><label class="mt-5 block text-sm font-medium">Description<textarea name="description" rows="5"
            class="mt-2 w-full rounded-2xl bg-white p-4 outline-none"><?= e($product['description']) ?></textarea></label>
    <!-- <img
        id="preview"
        src="../../images/<?= e($product['p_image']) ?>" class="w-[240px] aspect-sqaure object-cover rounded-3xl border-1 border-gray-300"
        alt="Current product image"> -->
    <div class="mt-5 flex gap-3"><button
            class="rounded-full bg-black px-6 py-3 text-sm text-white"><?= $editing ? 'Update product' : 'Save product' ?></button><a
            href="products.php" class="rounded-full border border-slate-300 px-6 py-3 text-sm">Cancel</a></div>
</form>
<script>
    const imageInput = document.getElementById('image');
    const preview = document.getElementById('preview');
    const previewContainer = document.getElementById('preview-container');
    let previewUrl = null;

    imageInput.addEventListener('change', function() {
        const file = this.files && this.files[0];
        if (!file) return;

        if (previewUrl) URL.revokeObjectURL(previewUrl);
        previewUrl = URL.createObjectURL(file);
        preview.src = previewUrl;
        previewContainer.classList.remove('hidden');
    });
</script>
<?php admin_footer(); ?>